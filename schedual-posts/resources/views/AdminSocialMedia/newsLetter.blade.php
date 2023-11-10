@extends('layouts.layoutAdminSocial')

@section('content')

 <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <section class="content postsPage">

                <div class="d-flex justify-content-between align-items-center px-4">
                    <h4 class="my-4" style="font-weight: bold">NewsLetter</h4>
                </div>

                <form action="{{ route('newsLetter.store') }}" method="post" class="p-2">
                    @csrf
                    <div class="row">
                        <div class="col-6 mb-2">
                            <label class="form-label" for="title">title</label>
                            <input type="text" name="title" required class="form-control" tabindex="-1" />
                        </div>
                        <div class="col-12 mb-2">
                            <label class="form-label" for="content">Content</label>
                            <textarea type="text" name="content" class="form-control"></textarea>
                        </div>
                        <div class="col-12 mb-2">
                            <input type="file" name="image" class="form-control" id="image">
                        </div>
                    </div>
                    <button type="submit" class="btn btn-info">Submit</button>
                </form>

                <div class="row">
                    <div class="col-12">
                        <div class="row">
                            @foreach ($newsLetter as $post)
                            <div class="col-6">
                                <div class="card mb-4 p-4">
                                    <div class="row">
                                        @if ($post->image != null)
                                            <div class="col-3">
                                                <img src="{{ url($post->image) }}" alt="post image">
                                            </div>
                                        @endif
                                        <div class="@if($post->image != null) col-9 @else col-12 @endif">
                                            <div class="content">
                                                <h4>{{ $post->title }}</h4>
                                                <p>{{ $post->content }}</p>
                                            </div>
    
                                            <div class="action d-flex justify-content-end">
                                                <a href="#" class="mr-2" data-target="#editPost" data-toggle="modal" data-postId={{ $post->id }}>Edit</a>
                                                <a href="#">Delete</a>
                                            </div>
                                        </div>
                                    </div>
                                </div> 
                            </div>
                            @endforeach
                        </div>
                               
                    </div>
                </div>

                <!-- editPost Modal -->
                <div class="modal fade" id="editPost" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog modal-lg modal-simple modal-dialog-centered modal-add-new-role">
                        <div class="modal-content p-3 p-md-3">
                            <div class="modal-body">
                                <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button>
                                <div class="text-center mb-4">
                                    <h3 class="role-title">Edit Post</h3>
                                </div>

                                <form id="editPostForm" class="row g-3" action="{{route('newsLetter.update', 1)}}" method="post">
                                    @csrf
                                    @method('put')
                                    <input type="hidden" id="roleId" name="roleId"/>
                                    <div class="col-12 mb-4">
                                        <label class="form-label" for="modalTitle">Title</label>
                                        <input type="text" id="modalTitle" name="title" required class="form-control"/>
                                    </div>
                                    <div class="col-12 mb-4">
                                        <label class="form-label" for="modalContent">Content</label>
                                        <input type="text" id="modalContent" name="content" class="form-control" />
                                    </div>
                                    <div class="col-12 mb-4">
                                        <label class="form-label" for="modalImage">Image</label>
                                        <input type="file" id="modalImage" name="image" class="form-control" />
                                    </div>
                                    <div class="col-12 text-center">
                                        <button type="submit" class="btn btn-info me-sm-3 me-1">Update</button>
                                        <button type="reset" class="btn text-white" style="background-color: #BEBEBE">Reset</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                
            </section>
        </div>
  </div>


@endsection