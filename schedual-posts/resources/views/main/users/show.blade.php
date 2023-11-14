@extends('layouts.layout')

@section('content')
<link rel="stylesheet" href="{{asset('tools/css/sneat.css')}}" />

 <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <section class="content socialAccounts py-4">

               @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach

              <form action="{{ route('users.update', $user->id) }}" method="post" enctype="multipart/form-data">
                @csrf
                @method('put')
                <div class="row mb-5">
                  <div class="col-sm-4 col-lg-3">
                    <div class="card h-100">
                      <div class="d-flex justify-content-center align-items-center h-100 previewSec p-5 py-3">
                        @if ($user->image != null)
                          <img class="card-img-top rounded-circle" src="{{ asset($user->image) }}" alt="profile image" />                            
                        @else
                          <img class="card-img-top rounded-circle" src="{{ asset('tools/dist/img/user.png') }}" alt="profile image" />                            
                        @endif
                      </div>
                      <div class="card-body py-0">
                        <p class="card-text">
                          <div class="button-wrapper">
                            <label for="profile_image" class="btn btn-dark me-2 mb-4" tabindex="0">
                              <span class="d-none d-sm-block">Upload photo</span>
                              <i class="bx bx-upload d-block d-sm-none"></i>
                              <input type="file" id="profile_image" class="account-file-input" hidden name="image" onchange="getImagePreview(event)" accept="image/png, image/jpeg, image/jpg" />
                            </label>
                            <button type="button" class="btn btn-outline-secondary account-image-reset mb-4" onclick="closeFile()">
                              <i class="bx bx-reset d-block d-sm-none"></i>
                              <span class="d-none d-sm-block">Reset</span>
                            </button>
                            <input type="hidden" name="reset_image" id="reset_image" value="0">
                            <p class="text-muted mb-0">Allowed JPG, JPEG or PNG.</p>
                          </div>
                          {{-- <div class="d-flex justify-content-center">
                            <button type="button" class="btn btn-danger mt-4">Deactivate Account </button>
                          </div> --}}
                        </p>
                      </div>
                    </div>
                  </div> 

                  <div class="col-sm-8 col-lg-9">
                    <div class="card shadow-none h-100" style="background: transparent;">
                      <div class="card">
                        <h5 class="card-header">Profile Details</h5>
                        <div class="card-body">
                          <div class="row">
                            <div class="mb-3 col-md-6">
                              <label for="name" class="form-label">Name</label>
                              <input class="form-control" type="text" id="name" name="name" value="{{ $user->name }}" autofocus/>
                            </div>
                            <div class="mb-3 col-md-6">
                              <label for="email" class="form-label">E-mail</label>
                              <input class="form-control" type="text" id="email" name="email" value="{{ $user->email }}" placeholder="john.doe@example.com"/>
                            </div>
                            <div class="mb-3 col-md-6">
                              <label for="oldPassword" class="form-label">Old password</label>
                              <input class="form-control" type="password" id="oldPassword" name="old_password"/>
                            </div>
                            <div class="mb-3 col-md-6">
                              <label for="newPassword" class="form-label">New password</label>
                              <input class="form-control" type="password" id="newPassword" name="new_password"/>
                            </div>
                            <p class="text-primary">Optionally to change password</p>

                          </div>
                          <div class="mt-2">
                            <button type="submit" class="btn btn-dark me-2">Save changes</button>
                            <button type="reset" class="btn btn-outline-secondary">Cancel</button>
                          </div>
                        </div>
                      </div>

                      <div class="card mt-4 p-3 h-100">
                        <div class="w-100 h-100 p-2 pb-0 d-flex justify-content-center align-items-center" style="border: 2px dashed #ccc;">
                          <button class="btn" type="button" data-bs-toggle="modal" data-bs-target="#addAccount">
                            <i class="bx bx-plus-circle fs-large" style="color: #333;"></i>
                          </button>
                        </div>
                      </div>  
                    </div>
                  </div>
                
              </form>

              <div class="row my-5">
                <h4 class="my-4"><i class="bx bx-laptop mr-3"></i> Accounts you have</h4>

                @foreach ($apiAccounts as $account)
                  <div class="col-sm-6 col-md-4 col-lg-3">
                      <div class="card info-box d-flex flex-row user-info py-3">
                        <div class="info-box-icon w-100 d-flex flex-column align-items-center position-relative">

                          @if ($account['account_pic'])
                            <img src="{{ asset($account['account_pic']) }}" class="rounded-circle p-1 {{ $account['account_type'] }}App-border" alt="User Image">
                          @else
                            <img src="{{ asset('tools/dist/img/user.png') }}" class="rounded-circle p-1 {{ $account['account_type'] }}App-border" alt="User Image">          
                          @endif
                          <p class="mt-3">{{ $account['account_name'] }}</p>
                          <span class="position-absolute mt-5 {{ $account['account_type'] }}App" style="background: transparent">
                            <i class="bx bxl-{{ $account['account_type'] }} rounded-circle shadow-sm p-1 ms-5 position-relative"></i>
                          </span>

                        </div>

                        <div class="btn-group mx-2">
                          <svg class="dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-three-dots" viewBox="0 0 16 16">
                            <path d="M3 9.5a1.5 1.5 0 1 1 0-3 1.5 1.5 0 0 1 0 3zm5 0a1.5 1.5 0 1 1 0-3 1.5 1.5 0 0 1 0 3zm5 0a1.5 1.5 0 1 1 0-3 1.5 1.5 0 0 1 0 3z"/>
                          </svg>  
                          <div class="dropdown-menu dropdown-menu-right px-2">
                            <ul class="list-unstyled m-0">
                              <li class="my-2 py-1 rounded" style="background-color: #E6F8FE"> 
                                <form action="{{ route('accounts.destroy',$account['account_id']) }}" method="post">
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

              <div class="modal fade" id="addAccount" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered" role="document">
                  <div class="modal-content" style="background: aliceblue">
                      <div class="modal-header">
                      <h5 class="modal-title">Choose app would you like to login?</h5>
                      <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                      </div>
                      <div class="modal-body">
                        @if ($userApps->isNotEmpty())
                          @foreach ($userApps as $app)
                            <div class="mb-2">
                              <div class="d-flex justify-content-between align-items-center">
                                <h5 class="{{ $app['appType'] }}App" style="text-transform: capitalize;"> 
                                  <i class="bx bxl-{{ $app['appType'] }} appIcon me-2 p-1 rounded fs-large"></i>
                                  <span>{{ $app['appType'] }} </span>
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
                  </div>
                </div>
              </div>

            </section>
        </div>
  </div>

  
  <script>
    // document.getElementById('connectAccount').addEventListener('click',()=>{
    //     document.getElementById('appAccount').style.display = 'flex';
    // });

    // document.querySelector('#appAccount .cancel').addEventListener('click',()=>{
    //     document.getElementById('appAccount').style.display = 'none';
    // });
    
    // upload photo
    function getImagePreview(event){
      for(let i = 0; i<event.target.files.length; i++)
      {
        var img = URL.createObjectURL(event.target.files[i]);
        var container = document.querySelector('.previewSec');
        container.innerHTML = '';
        var html = `<img src="${img}" class="card-img-top rounded-circle" alt="profile image">`;
        container.innerHTML += html;
      }  
    }

    function closeFile() {
      var container = document.querySelector('.previewSec');
      container.innerHTML = '';
      var html = `<img src="{{ asset('tools/dist/img/user.png') }}" class="card-img-top rounded-circle" alt="profile image">`;
      container.innerHTML += html;
      document.getElementById('reset_image').value = 1;
    }
  </script>


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
                        <img src="{{ asset('tools/dist/img/avatar.png') }}" class="rounded-circle border-primary p-1" alt="User Image">
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
  

@endsection
