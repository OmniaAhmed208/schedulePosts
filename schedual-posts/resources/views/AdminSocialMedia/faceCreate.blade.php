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
                    <form class="form-horizontal" action="{{route('store_facebookApi')}}" method="post">
                        @csrf
                        @method('PUT')
                        <div class="card-body">
                            <div class="form-group row">
                                <label for="inputEmail3" class="col-sm-2 col-form-label">Facebook</label>
                                <div class="col-sm-10">
                                    @if($showLink)
                                        <a href="{{ url('/auth/redirect') }}" class="btn btn-primary">Facebook</a>
                                    @else
                                        <!-- /.form-group -->
                                        <div class="form-group">
                                            <label>Choose page</label>
                                            <select class="form-control select2" style="width: 100%;" name="page">
                                                @foreach($pages as $page)
                                                    <option value="{{$page['name']}}">{{$page['name']}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <!-- /.card-body -->
                        <div class="card-footer">
                            @if($showLink)
                                <a href="{{route('adminSocail')}}" class="btn btn-secondary float-right">Cancel</a>
                            @else
                                <button type="submit" class="btn btn-info">Save</button>
                                <a href="{{route('adminSocail')}}" class="btn btn-secondary float-right">Cancel</a>
                            @endif
                        </div>
                
                    <!-- /.card-footer -->
                    </form>

                </div>
            
            </section>
        </div>
    </div>
</div>

@endsection
