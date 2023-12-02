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

                {{-- <div class="d-flex justify-content-center">
                    <button type="button" class="btn btn-danger mt-4">Deactivate Account </button>
                </div> --}}

                {{-- Profile Details --}}
                <div class="row mb-5">
                    <div class="col-sm-6 mb-2">
                        <form action="{{ route('users.update', $user->id) }}" method="post" enctype="multipart/form-data">
                            @csrf
                            @method('put')
                            <div class="card shadow-none h-100" style="background: transparent;">
                                <div class="card">
                                    <h5 class="card-header text-dark">Profile Details</h5>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="mb-3 col-md-6">
                                                <div class="previewSec">
                                                    @if ($user->image != null)
                                                        {{-- @dd(Storage::url($user->image), url($user->image), asset('storage/app/public/'.$user->image)) --}}
                                                        <img class="card-img-top rounded-circle w-50" src="{{ asset($user->image) }}" alt="profile image" />
                                                    @else
                                                        <img class="card-img-top rounded-circle w-25" src="{{ asset('tools/dist/img/user.png') }}" alt="profile image" />
                                                    @endif
                                                </div>
                                            </div>

                                            <div class="col-md-6 d-flex align-items-center">
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
                                            </div>

                                            <div class="mb-3">
                                                <label for="name" class="form-label">Name</label>
                                                <input class="form-control" type="text" id="name" name="name" value="{{ $user->name }}" autofocus required/>
                                            </div>


                                        </div>
                                        <div class="mt-2">
                                            <button type="submit" class="btn btn-dark me-2">Save changes</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>

                    {{-- Change Password --}}
                    <div class="col-sm-6 mb-2">
                        <div class="card h-100 d-flex justify-content-center">
                        <h5 class="card-header text-dark">Change Password</h5>

                        <form action="{{ url('updatePassword', $user->id) }}" method="post">
                            @csrf
                            @method('put')
                            <div class="card-body">
                                <div class="row">
                                    <div class="mb-3">
                                        <label for="oldPassword" class="form-label">Old password</label>
                                        <input class="form-control" type="password" id="oldPassword" name="old_password" required value="{{ old('old_password') }}"/>
                                    </div>
                                    <div class="mb-3">
                                        <label for="newPassword" class="form-label">New password</label>
                                        <input class="form-control" type="password" id="newPassword" name="new_password" required/>
                                    </div>
                                </div>
                                <div class="mt-2">
                                    <button type="submit" class="btn btn-dark me-2 mb-1">Save changes</button>

                                </div>
                            </div>
                        </form>
                        </div>
                    </div>
                </div>

                {{-- Accounts you have --}}
                <div class="row my-5">
                    <h4 class="my-4 text-dark"><i class="bx bx-laptop mr-3"></i> Accounts you have</h4>

                    <div class="col-sm-6 col-md-4 col-lg-3 mb-3">
                        <div class="w-100 h-100 p-2 d-flex justify-content-center align-items-center bg-white" style="border: 2px dashed #ccc;">
                            <button class="btn" type="button" data-bs-toggle="modal" data-bs-target="#addAccountModal">
                                <i class="bx bx-plus-circle fs-large" style="color: #333;"></i>
                            </button>
                        </div>
                    </div>

                    @foreach ($apiAccounts as $account)
                    <div class="col-sm-6 col-md-4 col-lg-3">
                        <div class="card info-box d-flex flex-row user-info py-3 mb-3">
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
                                    <button type="btn" style="border: none;background: transparent;" class="text-dark px-2">
                                    <a href="{{ $account['account_link'] }}">View Channel</a>
                                    </button>
                                </li>
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

                <div class="modal fade" id="addAccountModal" tabindex="-1" aria-hidden="true">
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

                <div class="modal fade" id="fbPagesModal" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered" role="document">
                        <div class="modal-content" style="background: aliceblue">
                            <div class="modal-header">
                                <h5 class="modal-title">Which channels would you like to add?</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <form action="{{ route('facebook.store') }}" method="post">
                                @csrf
                                <div class="modal-body pb-0">
                                    <div class="row">
                                        <div id="getFbPage"></div>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="submit" class="btn btn-dark">Add to E-Vovle</button>
                                </div> 
                            </form>                          
                        </div>
                    </div>
                </div>

            </section>
        </div>
  </div>

    {{-- @php
        $api = App\Models\Api::where('account_type','youtube')->where('creator_id', Auth::user()->id)->get();
        echo '<pre>';
        echo $api;
    @endphp --}}
  
  <script>
    // upload photo
    function getImagePreview(event){
      for(let i = 0; i<event.target.files.length; i++)
      {
        var img = URL.createObjectURL(event.target.files[i]);
        var container = document.querySelector('.previewSec');
        container.innerHTML = '';
        var html = `<img src="${img}" class="card-img-top rounded-circle w-50" alt="profile image">`;
        container.innerHTML += html;
      }
    }

    function closeFile() {
      var container = document.querySelector('.previewSec');
      container.innerHTML = '';
      var html = `<img src="{{ asset('tools/dist/img/user.png') }}" class="card-img-top rounded-circle w-50" alt="profile image">`;
      container.innerHTML += html;
      document.getElementById('reset_image').value = 1;
    }
  </script>


    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @if ($userApps)
        @foreach ($userApps as $app)
            @if ($app->appType == 'facebook')
                <input type="hidden" value="{{ $app->appID }}" id="facebookID">
            @endif
        @endforeach
    @endif

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
                // else {
                //     document.getElementById('status').innerHTML = 'User cancelled login'
                // }
            }, {scope: 'email,public_profile,pages_manage_posts'});
        }
        // ,pages_show_list,manage_pages,pages_manage_ads,pages_manage_metadata,pages_read_engagement,pages_read_user_content,instagram_basic

        window.fbAsyncInit = function()
        {
            var facebookID = document.getElementById('facebookID').value;
            FB.init({
                appId            : facebookID,
                cookie           : true,
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
                console.log('getFbUserData'.response);
                // document.getElementById('fbLink').setAttribute('onclick', 'fbLogout()');
                // document.getElementById('userData').style.display = 'flex';
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

        // function saveUserData(responseData) {
        //     console.log('getPages', responseData);
            
        //     // Assuming responseData is the JSON structure from the server
        //     var userData = responseData.userData;
        //     var pagesData = responseData.pagesData;
        
        //     // Handle user data
        //     console.log('User ID:', userData.id);
        //     console.log('User Name:', userData.name);
        //     console.log('User Email:', userData.email);
        
        //     // Assuming you have an image element for the user profile picture
        //     var userImageElement = document.getElementById('userProfilePicture');
        //     userImageElement.src = userData.picture.data.url;
        //     for (var i = 0; i < pagesData.length; i++) {
        //         var page = pagesData[i];
        //         console.log('Page ID:', page.id);
        //         console.log('Page Access Token:', page.access_token);
        //         console.log('Page Image:', page.image);
        //         console.log('Page Type:', page.type);
        
        //         // Assuming you have a container element for displaying pages
        //         var pagesContainer = document.getElementById('pagesContainer');
        
        //         // Create a new element for each page and append it to the container
        //         var pageElement = document.createElement('div');
        //         pageElement.innerHTML = `
        //             <div class="d-flex justify-content-between align-items-center mb-2">
        //                 <div class="position-relative">
        //                     <img src="${page.image}" class="rounded-circle border-primary p-1" alt="Page Image">
        //                     <i class="fab fa-facebook position-absolute rounded-circle text-primary icon"></i>
        //                     ${page.name} <!-- Assuming there is a name property in your pagesData -->
        //                 </div>
        //                 <div><input type="checkbox"></div>
        //             </div>
        //         `;
        //         pagesContainer.appendChild(pageElement);
        //     }
        // }

        function saveUserData(responseData) {
            
            console.log('getLoginInfo', responseData);
            
            var requestData = {
                userID: responseData.authResponse.userID,
                access_token: responseData.authResponse.accessToken,
                appType: responseData.authResponse.graphDomain,
                status: responseData.status
            };
            console.log(requestData);

            var csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

            var xhr = new XMLHttpRequest();
            xhr.open('POST', '{{ route('getPages') }}', true);

            xhr.setRequestHeader('X-CSRF-TOKEN', csrfToken);
            xhr.setRequestHeader('Content-Type', 'application/json');

            xhr.onload = function() {
                
                console.log('Response:', xhr.responseText);

                if (xhr.status === 200)
                {
                    var response = xhr.responseText;
                    console.log('response 200: '.response);

                    var responseObject = JSON.parse(response);

                    console.log('getPages2', responseObject);

                    var userData = responseObject.userData;
                    var pagesData = responseObject.pagesData;

                    var user_email = 'example@gmail.com'; 
                    if (userData.length > 0) { 
                        user_email = userData[0].email; 
                    }

                    var userDataHTML = '';
                    for (var i = 0; i < pagesData.data.length; i++) {
                        var page = pagesData.data[i];

                        console.log('namePage:', page.name);
                        console.log('id:', page.id);
                        console.log('Access Token:', page.access_token);

                        userDataHTML += `
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <div class="position-relative">
                                <img src="${page.picture.data.url}" class="rounded-circle border-primary p-1" alt="User Image">
                                <i class="fab fa-facebook position-absolute rounded-circle text-primary icon"></i>
                                ${page.name}
                            </div>
                            <div> 
                                <input type="checkbox" name="pageId" value="${page.id}">
                                <input type="hidden" name="pageName" value="${page.name}">
                                <input type="hidden" name="pageImage" value="${page.picture.data.url}">
                                <input type="hidden" name="page_access_token" value="${page.access_token}">
                                <input type="hidden" name="account_token" value="${requestData.access_token}">
                                <input type="hidden" name="email" value="${user_email}">
                            </div>
                        </div>
                        `;
                    } 

                    var addAccountModal = document.getElementById('addAccountModal');
                    addAccountModal.style.display = 'none';
                    addAccountModal.style.opacity = '0';
                    var fbPagesModal = document.getElementById('fbPagesModal');
                    fbPagesModal.style.display = 'block';
                    fbPagesModal.style.opacity = '1';

                    document.getElementById('getFbPage').innerHTML = userDataHTML;
                } else {
                    console.log('Error: ' + xhr.status);
                }
            };

            xhr.send(JSON.stringify(requestData));
        }

    </script>
    <script async defer crossorigin="anonymous" src="https://connect.facebook.net/en_US/sdk.js"></script>

@endsection
