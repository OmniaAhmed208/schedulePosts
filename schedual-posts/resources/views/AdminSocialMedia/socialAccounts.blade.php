@extends('layouts.layoutAdminSocial')

@section('content')



 <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container">
        <section class="content socialAccounts">

            <h4 class="mt-4" style="font-weight: bold">Social Media Accounts</h4>
            <p>Connect a social account you'd like to manage</p>

            <div class="row mt-5">
                <div class="col-md-3 col-sm-6 col-12">
                    <div class="info-box py-3" style="border: 2px solid #06283d96" id="connectAccount">
                        <div class="info-box-icon w-100 flex-column" style="height:120px">
                            <div><i class="fas fa-plus-circle fa-sm"></i></div>
                            <h5>Add account</h5>
                        </div>
                    </div>
                </div>

                @foreach ($apiAccounts as $account)
                  <div class="col-md-3 col-sm-6 col-12">
                      <div class="info-box user-info py-3">
                        <div class="info-box-icon w-100 flex-column position-relative">

                          @if ($account['account_pic'])
                            <img src="{{ asset($account['account_pic']) }}" class="img-circle p-1 {{ $account['account_type'] }}App-border" alt="User Image">
                          @else
                            <img src="{{ asset('tools/dist/img/user.png') }}" class="img-circle p-1 {{ $account['account_type'] }}App-border" alt="User Image">          
                          @endif
                          <p class="mt-3">{{ $account['account_name'] }}</p>
                          <span class="position-absolute ml-5 {{ $account['account_type'] }}App" style="background: transparent">
                            <i class="fab fa-{{ $account['account_type'] }} rounded-circle shadow-sm p-1 position-relative"></i>
                          </span>

                        </div>

                        <div class="btn-group">
                          <svg class="dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-three-dots" viewBox="0 0 16 16">
                            <path d="M3 9.5a1.5 1.5 0 1 1 0-3 1.5 1.5 0 0 1 0 3zm5 0a1.5 1.5 0 1 1 0-3 1.5 1.5 0 0 1 0 3zm5 0a1.5 1.5 0 1 1 0-3 1.5 1.5 0 0 1 0 3z"/>
                          </svg>  
                          <div class="dropdown-menu dropdown-menu-right px-2">
                            <ul class="list-unstyled m-0">
                              {{-- <li class="my-2 py-1 rounded" style="background-color: #E6F8FE"> 
                                @if ($account['account_type'] === 'youtube' || 'twitter')
                                  <a href = "{{ route($account['account_type'].'.show',$account['account_id']) }}" class="text-dark px-2"> View posts </a> 
                                @else                                    
                                  <a href = "{{ url($account['account_type']) }}" class="text-dark px-2"> View posts </a> 
                                @endif
                              </li> --}}
                              <li class="my-2 py-1 rounded" style="background-color: #E6F8FE"> 
                                <form action="{{ route('removeAccount',$account['account_id']) }}" method="post">
                                  @csrf
                                  @method('delete')
                                  <button type="submit" style="border: none;background: transparent;" class="text-dark px-2"
                                  onclick="return confirm('Are you sure')">Disconnected</button>
                                </form>
                              </li> 
                            </ul>
                          </div>
                        </div>

                      </div>
                  </div>
                @endforeach
            </div>

            {{-- role="link" aria-disabled="true" --}}
            
            <div id="appAccount">
                <div class="box">
                  <div class="bg-white px-3 py-2 pt-3"><h5>Choose app would you like to login?</h5></div>
                  <div class="p-4" style="background-color:#ededed;">

                    {{-- <div class="mb-2">
                      <div class="d-flex justify-content-between align-items-center">
                        <h5 class="text-primary"> <i class="fab fa-facebook appIcon mr-2"></i> Facebook </h5>
                        <a href="javascript:void(0)" id="fbLink" onclick="fbLogin()" class="text-gray mr-3" style="font-weight: bold"> Connect </a>
                      </div>
                      <div class="ac-data" id="userData"></div>
                    </div> --}}
                    @if ($userApps->isNotEmpty())
                      @foreach ($userApps as $app)
                        <div class="mb-2">
                          <div class="d-flex justify-content-between align-items-center">
                            <h5 class="{{ $app['appType'] }}App" style="background: transparent;text-transform: capitalize;"> 
                              <i class="fab fa-{{ $app['appType'] }} appIcon mr-2"></i>
                              {{ $app['appType'] }} 
                            </h5>
                            <a 
                              @if ($app['appType'] == 'facebook')
                                href="javascript:void(0)" id="fbLink" onclick="fbLogin()"
                              @else
                                href="{{ url('/auth/'.$app['appType']) }}"
                              @endif
                              class="text-gray mr-3" style="font-weight: bold"> Connect 
                            </a>
                          </div>
                          @if ($app['appType'] == 'facebook')
                            <div class="ac-data" id="userData"></div>
                          @endif
                        </div>
                      @endforeach    
                    @else
                      <a href="{{ route('services') }}">Fill out the form for Api !</a>
                    @endif
                    
                  
                  </div>
                  <div class="bg-white px-3 py-2 d-flex justify-content-end">
                      <a href="#" class="btn cancel mr-3">Cancel</a>
                  </div>
                </div>
            </div>
        </section>
      </div><!-- /.container-fluid -->
    <!-- /.content -->
  </div>

  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <script>

    function fbLogin()
    {
      FB.login(function(response) {
        // console.log(response);
        if (response.authResponse) 
        {
          getFbUserData(response);
          saveUserData(response);
        } 
        else {
          document.getElementById('status').innerHTML = 'User cancelled login'
        }
      }, {scope: 'email,public_profile,pages_manage_posts'});
    }
    // ,pages_show_list,manage_pages,pages_manage_ads,pages_manage_metadata,pages_read_engagement,pages_read_user_content
     
     
    window.fbAsyncInit = function()
    {
      FB.init({
        appId            : '690179252628964',
        cookie: true,
        autoLogAppEvents : true,
        xfbml            : true,
        version          : 'v17.0'
      });

      FB.getLoginStatus(function(response)
      {
        console.log('getLoginStatus',response);
        if(response.status === 'connected')
        {
          getFbUserData();
        }
        saveUserData(response);
      });
    };

  
    (function(d, s, id)
      {
        var js, fjs = d.getElementByTagName(s)[0];
        if(d.getElementById(id)) return;
        js = d.createElement(s); js.id = id;
        js.src = "https://connect.facebook.net/en_US/sdk.js";
        fjs.parentNode.insertBefore(js, fjs);
      }(document, 'script', 'facebook-jssdk')
    );
    
  
    
    function getFbUserData(response)
    {
      FB.api('/me', {locale: 'en_US', fields: 'id,first_name,last_name,email,picture'},
      function(response){
        // console.log(response);
        document.getElementById('fbLink').setAttribute('onclick', 'fbLogout()');
        document.getElementById('fbLink').innerHTML = ' Logout from facebook ';
        // document.getElementById('status').innerHTML = '<p>Thanks for logged in </p>';
        //  document.getElementById('userData').innerHTML = `<p><b>Name: </b> ${response.first_name} ${response.last_name}</p>`;
        document.getElementById('userData').style.display = 'flex';
      });
    }

    function fbLogout()
    {
      FB.logout(function(){
        document.getElementById('fbLink').setAttribute('onclick', 'fbLogin()');
        document.getElementById('fbLink').innerHTML = ' Facebook ';
        // document.getElementById('status').innerHTML = '<p>You have successfully logout</p>';
        document.getElementById('userData').innerHTML = ``;
        document.getElementById('userData').style.display = 'none';
      })
    }
 
    function saveUserData(responseData) {
        console.log('getPages', responseData);
        var requestData = {
            userID: responseData.authResponse.userID,
            access_token: responseData.authResponse.accessToken,
            appType: responseData.authResponse.graphDomain,
            status: responseData.status
        };

        var csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        var xhr = new XMLHttpRequest();
        xhr.open('POST', '{{ route('getPages') }}', true);

        xhr.setRequestHeader('X-CSRF-TOKEN', csrfToken);
        xhr.setRequestHeader('Content-Type', 'application/json');

        xhr.onload = function() {
            if (xhr.status === 200) 
            {
            var response = xhr.responseText;
            // console.log(response);
            
            var responseObject = JSON.parse(response);

            var userData = responseObject.userData;
            var pagesData = responseObject.pagesData;

            for (var i = 0; i < pagesData.data.length; i++) 
            {
                var id = pagesData.data[i].id;
                var access_token = pagesData.data[i].access_token;
                var namePage = pagesData.data[i].name;

                console.log('namePage:', namePage);
                console.log('id:', id);
                console.log('Access Token:', access_token);
            }

            console.log('getPages2', responseObject);

            var userDataHTML = `
            <div style="width:450px"> 
                <div class="bg-white p-2 pt-3"> 
                    <h5>Which channels would you like to add?</h5> 
                </div>
                <div class="p-4" style="background-color:#ededed;">
            `;

            for (var i = 0; i < pagesData.data.length; i++) {
                var id = pagesData.data[i].id;
                var access_token = pagesData.data[i].access_token;
                var namePage = pagesData.data[i].name;
                userDataHTML += `
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <div class="position-relative">
                        <img src="{{ asset('tools/dist/img/avatar.png') }}" class="img-circle border-primary p-1" alt="User Image">
                        <i class="fab fa-facebook position-absolute rounded-circle text-primary icon"></i>
                        ${namePage}
                    </div>
                    <div><input type="checkbox"></div>
                </div>
                `;
            }

            userDataHTML += `
                </div>
                <div class="bg-white p-2 d-flex justify-content-end"> 
                    <a href="#" class="btn cancel">Cancel</a> 
                    <a href="#" class="btn btn-info" style="color:#fff !important;">Add to E-Vovle</a> 
                </div>
            </div>`;

            document.getElementById('userData').innerHTML = userDataHTML;
            } else {
            console.log('Error: ' + xhr.status);
            }

            document.querySelector('#userData .cancel').addEventListener('click',()=>{
            document.getElementById('userData').style.display = 'none';
            fbLogout();
            });
        };

        xhr.send(JSON.stringify(requestData));

    }
     
  </script>
  <script async defer crossorigin="anonymous" src="https://connect.facebook.net/en_US/sdk.js"></script>


  <script>
    document.getElementById('connectAccount').addEventListener('click',()=>{
        document.getElementById('appAccount').style.display = 'flex';
    });

    document.querySelector('#appAccount .cancel').addEventListener('click',()=>{
        document.getElementById('appAccount').style.display = 'none';
    });
  </script>

@endsection

