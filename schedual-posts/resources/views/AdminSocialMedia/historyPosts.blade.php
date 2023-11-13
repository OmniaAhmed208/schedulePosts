@extends('layouts.layoutAdminSocial')

@section('content')


<div class="content-wrapper history_posts">
  <div class="content-header">
      <div class="container">
        <section class="content">

          <div class="d-flex justify-content-between align-items-center px-4">
            <h4 class="my-4" style="font-weight: bold">History</h4>
            <a href="{{ route('accountPages') }}" class="btn text-white" style="background-color: #79DAE8;font-size:18px">
                <i class="fas fa-plus-circle mr-2"></i>
                Add New Post
            </a>
          </div>
            
            @if (session('postDeleted'))
              <div class="alert alert-success mt-4" role="alert">
                {{ session('postDeleted') }}
              </div>
            @endif

            @if ($postsCount == 0)
              <p class="alert text-white my-4" style="font-size: 20px;background-color: #d8dddd">
                No posts have been published or schedualed form your accounts
              </p>
            @endif

            <div class="calender-tools my-4">
              <span class="mx-2 border-0" style="width: 30px;height: 11px;border: 1px solid;background:#01b954;display: inline-block;"></span> Publishing posts
              <span class="mx-2 border-0" style="width: 30px;height: 11px;border: 1px solid;background: #f39c12;display: inline-block;"></span> Pending Posts
            </div>

            <div class="row">
              <div class="col-12">
                <div class="card card-primary">
                  <div class="card-body p-0">
                    <!-- THE CALENDAR -->
                    <div id="calendar"></div>
                  </div>
                  <!-- /.card-body -->
                </div>
                <!-- /.card -->
              </div>
              <!-- /.col -->
            </div>

          </section>
      </div>
  </div>
</div>

@endsection
