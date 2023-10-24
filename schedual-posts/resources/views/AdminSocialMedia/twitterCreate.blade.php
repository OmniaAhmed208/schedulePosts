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
                    <form class="form-horizontal" action="{{route('store_twitter')}}" method="post">
                        @csrf
                        @method('PUT')
                        <div class="card-body">
                            <div class="form-group row">
                                <label for="inputEmail3" class="col-sm-2 col-form-label">Twitter</label>
                                <div class="col-sm-10">
                                    <a href="{{ url('/auth/redirect') }}" class="btn btn-primary">Twitter</a>
                                </div>
                            </div>
                        </div>
                        <!-- /.card-body -->
                        <div class="card-footer">
                                <a href="{{route('adminSocail')}}" class="btn btn-secondary float-right">Cancel</a>
                        </div>
                
                    <!-- /.card-footer -->
                    </form>
                </div>

            </section>
        </div>
    </div>
</div>

@endsection
