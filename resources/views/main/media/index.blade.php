@extends('layouts.layout')

@section('content')

 <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <section class="content py-4">

                <div class="d-flex justify-content-between align-items-center px-4">
                    <h4 class="my-4"><i class="bx bx-image mr-3"></i> Media Library</h4>
                </div>

                <div class="card p-4">
                    <div class="row">
                        @foreach($mediaImages as $image)
                        <div class="col-lg-3 col-md-4 col-sm-6 justify-content-center">
                            <img src="{{ $image->getUrl() }}" class="w-50 rounded" alt="">
                        </div>
                        @endforeach
                    </div>
                </div>
                
            </section>
        </div>
  </div>

@endsection
