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
                    <div class="col-sm-8 col-lg-9 mb-2">
                        <form action="{{ route('users.update', $user->id) }}" method="post" enctype="multipart/form-data">
                            @csrf
                            @method('put')
                            <div class="card shadow-none h-100" style="background: transparent;">
                                <div class="card">
                                    <h5 class="card-header text-dark">Profile Details</h5>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="mb-3 col-md-4">
                                            <div class="previewSec">
                                            @if ($user->image != null)
                                                {{-- @dd(Storage::url($user->image), url($user->image), asset('storage/app/public/'.$user->image)) --}}
                                                <img class="card-img-top rounded-circle w-50" src="{{ asset($user->image) }}" alt="profile image" />
                                            @else
                                                <img class="card-img-top rounded-circle w-25" src="{{ asset('tools/dist/img/user.png') }}" alt="profile image" />
                                            @endif
                                            </div>
                                            </div>

                                            <div class="col-md-8 d-flex align-items-center">
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

                                            <div class="mb-3 col-md-6">
                                            <label for="name" class="form-label">Name</label>
                                            <input class="form-control" type="text" id="name" name="name" value="{{ $user->name }}" autofocus required/>
                                            </div>
                                            <div class="mb-3 col-md-6">
                                            <label for="email" class="form-label">E-mail</label>
                                            <input class="form-control" type="text" id="email" name="email" value="{{ $user->email }}"  required/>
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
                    <div class="col-sm-4 col-lg-3 mb-2">
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

                {{-- <button class="btn" type="button" data-bs-toggle="modal" data-bs-target="#forgetPassword">
                    Forget Password
                </button> --}}

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
                
                console.log('Response:', xhr.responseText);

                if (xhr.status === 200)
                {
                    var response = xhr.responseText;
                    // console.log(response);

                    var responseObject = JSON.parse(response);

                    console.log('getPages2', responseObject);

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

                    // var userDataHTML = `
                    // <div style="width:450px">
                    //     <div class="bg-white p-2 pt-3">
                    //         <h5>Which channels would you like to add?</h5>
                    //     </div>
                    //     <div class="p-4" style="background-color:#ededed;">
                    // `;

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

                    var fbPagesModal = document.getElementById('fbPagesModal');
                    var addAccountModal = document.getElementById('addAccountModal');
                    addAccountModal.style.cssText = 'display:none;opacity:0';
                    fbPagesModal.style.cssText = 'display:block;opacity:1';

                    // userDataHTML += `
                    //     </div>
                    //     <div class="bg-white p-2 d-flex justify-content-end">
                    //         <a href="#" class="btn cancel">Cancel</a>
                    //         <a href="#" class="btn btn-info" style="color:#fff !important;">Add to E-Vovle</a>
                    //     </div>
                    // </div>`;

                    // document.getElementById('userData').innerHTML = userDataHTML;

                    document.getElementById('getFbPage').innerHTML = userDataHTML;
                } else {
                    console.log('Error: ' + xhr.status);
                }

                // document.querySelector('#userData .cancel').addEventListener('click',()=>{
                // document.getElementById('userData').style.display = 'none';
                // fbLogout();
                // });
            };

            xhr.send(JSON.stringify(requestData));
        }

    </script>
    <script async defer crossorigin="anonymous" src="https://connect.facebook.net/en_US/sdk.js"></script>

@endsection
