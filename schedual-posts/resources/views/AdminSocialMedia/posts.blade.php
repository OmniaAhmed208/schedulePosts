@extends('layouts.layoutAdminSocial')

@section('content')

 <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <section class="content postsPage">

            <div class="d-flex justify-content-between align-items-center px-4">
                <h4 class="my-4" style="font-weight: bold">Posts</h4>
                <div class="btn-group">
                    <button type="button" class="btn text-white dropdown-toggle" style="background-color: #79DAE8;font-size:18px" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="fas fa-filter mr-2"></i>
                        Filters
                    </button>
                    <div class="dropdown-menu dropdown-menu-right" style="background: #f5f5f5">
                        <button class="dropdown-item filter-button" data-filter="all" type="button">Clear Filter</button>
                        <div class="dropdown-divider"></div>
                        <h5 class="pl-3">Accounts</h5>

                        @foreach ($allApps as $app)
                          <button class="dropdown-item filter-button my-1" data-filter="{{ $app['appType'] }}" type="button" style="text-transform: capitalize;">
                            <span class="info-box-icon py-1 px-2 mr-2 rounded {{ $app['appType'] }}App"><i class="fab fa-{{ $app['appType'] }}"></i></span>
                            {{ $app['appType'] }}
                          </button>
                        @endforeach
                    </div>
                </div>
            </div>

            <div class="p-4 config">
              <nav>
                <div class="nav nav-tabs" id="nav-tab" role="tablist">
                  <a class="nav-item nav-link active" id="nav-all-tab" data-toggle="tab" href="#nav-all" role="tab" aria-controls="nav-all" aria-selected="true">All</a>
                  <a class="nav-item nav-link" id="nav-draft-tab" data-toggle="tab" href="#nav-draft" role="tab" aria-controls="nav-draft" aria-selected="false">Draft</a>
                  <a class="nav-item nav-link" id="nav-scheduled-tab" data-toggle="tab" href="#nav-scheduled" role="tab" aria-controls="nav-scheduled" aria-selected="false">Scheduled</a>
                  <a class="nav-item nav-link" id="nav-published-tab" data-toggle="tab" href="#nav-published" role="tab" aria-controls="nav-published" aria-selected="false">Published</a>
                </div>
              </nav>

              <div class="tab-content" id="nav-tabContent">
                <div class="tab-pane mt-4 fade show active" id="nav-all" role="tabpanel" aria-labelledby="nav-all-tab">
                    <div class="row">
                        <div class="col-12">
                          <div class="card">
                            <div class="card-header">
                              <h3 class="card-title"> All posts for <span class="typeApp" style="text-transform: capitalize;">All applications</span> </h3>
                            </div>
                            <div class="card-body">
                              <table id="example2" class="table table-bordered table-hover">
                                <thead>
                                  <tr>
                                    <th>#</th>
                                    <th>id</th>
                                    <th>Status</th>
                                    <th>Content</th>
                                    <th>Media</th>
                                    <th>Schedule time</th>
                                    <th>Accounts</th>
                                    <th>Actions</th>
                                  </tr>
                                </thead>
                                <tbody>
                                  @foreach ($allPosts as $post)
                                  <tr class="post-row">
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $post['id'] }}</td>
                                    <td>
                                      <span class="badge @if($post['status'] === 'published') bg-label-success @elseif($post['status'] === 'pending') bg-label-warning @else bg-label-danger @endif">{{ $post['status'] }}</span>
                                    </td>
                                    <td>{{ $post['content'] }}</td>

                                    <td>
                                      @if ($post->postImages->isNotEmpty())
                                          <img src="{{ url($post->postImages[0]->image) }}" class="rounded" style="width: 70px" alt="">                                              
                                      @endif
                                      @if ($post->postVideos->isNotEmpty())
                                          <video src="{{ url($post->postVideos[0]->video) }}" class="rounded" style="width: 70px" alt=""></video>                                              
                                      @endif
                                    </td>
                                  
                                    <td>{{ $post['scheduledTime'] }}</td>
                                    <td>
                                      <span class="info-box-icon py-1 px-2 rounded-circle {{ $post['account_type'] }} {{ $post['account_type'] }}App"><i class="fab fa-{{ $post['account_type'] }}"></i></span>
                                    </td>
                                    <td >
                                      @if ($post->status == 'pending')
                                        <div class="d-flex">
                                          <a href="{{ route('posts.edit', $post->id) }}" style="background:transparent;border:none">
                                            <i class="fa fa-edit text-success"></i>
                                          </a>
                                          <form action="{{ route('posts.destroy',$post->id) }}" method="post">
                                            @csrf
                                            @method('delete')
                                              <button type="submit" onclick="return confirm('are you sure?')" style="background:transparent;border:none">
                                                <i class="fa fa-trash text-danger"></i>
                                              </button>
                                          </form>
                                        </div>
                                        {{-- @else  <a class="btn btn border"> share again </a> --}}
                                      @endif
                                    </td>
                                  </tr>
                                  @endforeach
                                </tbody>
                                <tfoot>
                                <tr>
                                  <th>#</th>
                                  <th>id</th>
                                  <th>Status</th>
                                  <th>Content</th>
                                  <th>Media</th>
                                  <th>Schedule time</th>
                                  <th>Accounts</th>
                                  <th>Actions</th>
                                </tr>
                                </tfoot>
                              </table>
                            </div>
                            <!-- /.card-body -->
                          </div>
                          <!-- /.card -->
                        </div>
                    </div>
                </div>


                <div class="tab-pane mt-4 bg-white fade" id="nav-draft" role="tabpanel" aria-labelledby="nav-draft-tab">
                  
                </div>

                <div class="tab-pane mt-4 bg-white fade" id="nav-scheduled" role="tabpanel" aria-labelledby="nav-scheduled-tab">
                  <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                              <h3 class="card-title"> Scheduled posts for <span class="typeApp" style="text-transform: capitalize;">All applications</span> </h3>
                            </div>
                            <div class="card-body">
                              <table id="example2" class="table table-bordered table-hover">
                                <thead>
                                <tr>
                                  <th>#</th>
                                  <th>id</th>
                                  <th>Status</th>
                                  <th>Content</th>
                                  <th>Media</th>
                                  <th>Schedule time</th>
                                  <th>Accounts</th>
                                  <th>Actions</th>
                                </tr>
                                </thead>
                                <tbody>
                                  @foreach ($allPosts as $index=>$post)
                                    @if($post->status === "pending")
                                      <tr class="post-row">
                                        {{-- <td>{{ $index+1 }}</td> --}}
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $post['id'] }}</td>
                                        <td>
                                          <span class="badge @if($post['status'] === 'published') bg-label-success @elseif($post['status'] === 'pending') bg-label-warning @else bg-label-danger @endif">{{ $post['status'] }}</span>
                                        </td>
                                        <td>{{ $post['content'] }}</td>

                                        <td>
                                          @if ($post->postImages->isNotEmpty())
                                            <img src="{{ url($post->postImages[0]->image) }}" class="rounded" style="width: 70px" alt="">                                                  
                                          @endif
                                          @if ($post->postVideos->isNotEmpty())
                                              <video src="{{ url($post->postVideos[0]->video) }}" class="rounded" style="width: 70px" alt=""></video>                                              
                                          @endif
                                        </td>
                                      
                                        <td>{{ $post['scheduledTime'] }}</td>
                                        <td>
                                          <span class="info-box-icon py-1 px-2 rounded-circle {{ $post['account_type'] }} {{ $post['account_type'] }}App"><i class="fab fa-{{ $post['account_type'] }}"></i></span>
                                        </td>
                                        <td >
                                          @if ($post->status == 'pending')
                                            <div class="d-flex">
                                              <a href="{{ route('posts.edit', $post->id) }}" style="background:transparent;border:none">
                                                <i class="fa fa-edit text-success"></i>
                                              </a>
                                              <form action="{{ route('posts.destroy',$post->id) }}" method="post">
                                                @csrf
                                                @method('delete')
                                                  <button type="submit" onclick="return confirm('are you sure?')" style="background:transparent;border:none">
                                                    <i class="fa fa-trash text-danger"></i>
                                                  </button>
                                              </form>
                                            </div>
                                          @endif
                                        </td>
                                      </tr>
                                    @endif
                                  @endforeach
                                </tbody>
                                <tfoot>
                                <tr>
                                  <th>#</th>
                                  <th>id</th>
                                  <th>Status</th>
                                  <th>Content</th>
                                  <th>Media</th>
                                  <th>Schedule time</th>
                                  <th>Accounts</th>
                                  <th>Actions</th>
                                </tr>
                                </tfoot>
                              </table>
                            </div>
                            <!-- /.card-body -->
                          </div>
                          <!-- /.card -->
                    </div>
                  </div>
                </div>

                <div class="tab-pane mt-4 bg-white fade" id="nav-published" role="tabpanel" aria-labelledby="nav-published-tab">
                  <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                              <h3 class="card-title"> Published posts for <span class="typeApp" style="text-transform: capitalize;">All applications</span> </h3>
                            </div>
                            <div class="card-body">
                              <table id="example2" class="table table-bordered table-hover">
                                <thead>
                                <tr>
                                  <<th>#</th>
                                  <th>id</th>
                                  <th>Status</th>
                                  <th>Content</th>
                                  <th>Media</th>
                                  <th>Schedule time</th>
                                  <th>Accounts</th>
                                  <th>Actions</th>
                                </tr>
                                </thead>
                                <tbody>
                                  @foreach ($allPosts as $index=>$post)
                                    @if($post->status === "published")
                                      <tr class="post-row">
                                        {{-- <td>{{ $index+1 }}</td> --}}
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $post['id'] }}</td>
                                        <td>
                                          <span class="badge @if($post['status'] === 'published') bg-label-success @elseif($post['status'] === 'pending') bg-label-warning @else bg-label-danger @endif">{{ $post['status'] }}</span>
                                        </td>
                                        <td>{{ $post['content'] }}</td>

                                        <td>
                                          @if ($post->postImages->isNotEmpty())
                                              <img src="{{ url($post->postImages[0]->image) }}" class="rounded" style="width: 70px" alt="">      
                                          @endif
                                          @if ($post->postVideos->isNotEmpty())
                                              <video src="{{ url($post->postVideos[0]->video) }}" class="rounded" style="width: 70px" alt=""></video>                                              
                                          @endif
                                        </td>
                                      
                                        <td>{{ $post['scheduledTime'] }}</td>
                                        <td>
                                          <span class="info-box-icon py-1 px-2 rounded-circle {{ $post['account_type'] }} {{ $post['account_type'] }}App"><i class="fab fa-{{ $post['account_type'] }}"></i></span>
                                        </td>
                                        <td >
                                          @if ($post->status == 'pending')
                                            <div class="d-flex">
                                              <a href="{{ route('posts.edit', $post->id) }}" style="background:transparent;border:none">
                                                <i class="fa fa-edit text-success"></i>
                                              </a>
                                              <form action="{{ route('posts.destroy',$post->id) }}" method="post">
                                                @csrf
                                                @method('delete')
                                                  <button type="submit" onclick="return confirm('are you sure?')" style="background:transparent;border:none">
                                                    <i class="fa fa-trash text-danger"></i>
                                                  </button>
                                              </form>
                                            </div>
                                          @endif
                                        </td>
                                      </tr>
                                    @endif
                                  @endforeach
                                </tbody>
                                <tfoot>
                                <tr>
                                  <th>#</th>
                                  <th>id</th>
                                  <th>Status</th>
                                  <th>Content</th>
                                  <th>Media</th>
                                  <th>Schedule time</th>
                                  <th>Accounts</th>
                                  <th>Actions</th>
                                </tr>
                                </tfoot>
                              </table>
                            </div>
                            <!-- /.card-body -->
                          </div>
                          <!-- /.card -->
                    </div>
                  </div>
                </div>
              </div>
            </div>

        </section>
      </div><!-- /.container-fluid -->
    <!-- /.content -->
  </div>

  <script src="{{ asset('tools/plugins/jquery/jquery.min.js') }}"></script>

  <script>
    $(document).ready(function () {

      const filter_button = document.querySelectorAll('.filter-button');
      const post_row = document.querySelectorAll('.post-row');
      const typeApp = document.querySelectorAll('.typeApp');

      // Filter button click event
      filter_button.forEach(btn => {
        btn.addEventListener('click',function(){
          var filterValue = $(this).data('filter');

          post_row.forEach(row => {
            
            var spanElement = row.querySelector('span.' + filterValue);

            if (filterValue === 'all' || spanElement !== null) {
              row.style.display = 'table-row'; // Show the row
            } else {
              row.style.display = 'none'; // Hide the row
            }
            
          });

          typeApp.forEach(app => {
            app.innerHTML = `<strong> ${filterValue} </strong>` + ' application';
          });
          
        });
      });
      
    });
</script>

@endsection