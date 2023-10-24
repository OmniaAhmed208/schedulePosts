@extends('layouts.layoutAdminSocial')

@section('content')


<div class="content-wrapper">
  <div class="content-header">
      <div class="container">
        <section class="content">
                <!-- Horizontal Form -->
                <div class="card card-info">
                    <div class="card-header">
                    <h3 class="card-title">Social Media</h3>
                    </div>
                    <!-- /.card-header -->
                    <!-- form start -->
                    

                    <form class="form-horizontal" action="{{route('store_instaApi')}}" method="post">
                        @csrf
                        <div class="card-body">
                        <div class="form-group row">
                            <label for="inputEmail3" class="col-sm-2 col-form-label">Instagram Name</label>
                            <div class="col-sm-10">
                                <!-- /.form-group -->
                                <div class="form-group">
                                <input type="text" class="form-control form-control-border" name="insta_name">
                                </div>
                            </div>
                        </div>  
                        <div class="form-group row">
                                <label for="inputEmail3" class="col-sm-2 col-form-label">Instagram Token</label>
                                <div class="col-sm-10">
                                    <!-- /.form-group -->
                                    <div class="form-group">
                                    <input type="text" class="form-control form-control-border" name="insta_token">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- /.card-body -->
                        <div class="card-footer">
                                <button type="submit" class="btn btn-info">Save</button>
                                <a href="{{route('adminSocail')}}" class="btn btn-default float-right">Cancel</a>
                        </div>

                    <!-- /.card-footer -->
                    </form>
                </div>
                <!-- /.card -->


                <h3 class="m-3">Steps for get token of instagram</h3>

                <ol>
                    <li>Step 1
                    <ul>
                        <li>You need to make account on <a href="https://developers.facebook.com/">Facebook developer</a></li>
                        <li>Add a New App <br>
                        <img src="{{ asset('/tools/instaApp/app1.png') }}" class="my-3" style="width:500px; height:360px" alt="">
                        </li>
                        <li>First select More Options. <br>
                        <img src="{{ asset('/tools/instaApp/app2.png') }}" class="my-3" style="width:500px;height:360px" alt="">
                        </li>
                        <li>Then choose Something Else in order to register an app with a custom permissions set. <br>
                        <img src="{{ asset('/tools/instaApp/app3.png') }}" class="my-3" style="width:500px;height:360px" alt="">
                        </li>
                    </ul>
                    </li>

                    <li>Step 2
                    <ul>
                        <li>Adding an app will open a pop-up window where you need to insert:
                        <ul>
                            <li>a name for your app,</li>
                            <li>contact email address,</li>
                            <li>connect your Business Manager Account in case you have one and wish to connect it.</li>
                        </ul>
                        </li>
                        <li>Note: You may be prompted to go through a "I'm not a robot" and reCaptcha security check.<br>
                        <img src="{{ asset('/tools/instaApp/app4.png') }}" class="my-3" style="width:500px;height:360px" alt="">
                        </li>
                    </ul>
                    </li>

                </ol>

        </section>
      </div>
  </div>
</div>

@endsection
