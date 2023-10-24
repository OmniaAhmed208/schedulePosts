@extends('layouts.layoutAdminSocial')

@section('content')

<div class="content-wrapper publishPostContainer">
    <div class="content-header">
        <div class="container">
            <section class="content">
                    
                <div class="card card-info">
                    <div class="card-header">
                        <h3 class="card-title">Repost</h3>
                        <div class="card-tools socialIcons">
                            @if ($post->faceToken && $post->instaToken)
                                <span class="rounded-circle px-2 py-1 mx-1 bg-primary"><i class="fab fa-facebook-f"></i></span>
                                <span class="rounded-circle px-2 py-1 mx-1" style="background-color: #d63384"><i class="fab fa-instagram"></i></span>
                            @elseif ($post->faceToken)
                                <span class="rounded-circle px-2 py-1 mx-1 bg-primary"><i class="fab fa-facebook-f"></i></span>
                            @elseif ($post->instaToken)
                                <span class="rounded-circle px-2 py-1 mx-1" style="background-color: #d63384"><i class="fab fa-instagram"></i></span>
                            @endif
                        </div>
                    </div>

                    <form class="form-horizontal" action="{{ url('repostUpdate',$post->id)}}" method="post" enctype="multipart/form-data">
                        @csrf
                        @method('put')

                        <div class="card-body">
                            <input type="hidden" name="faceToken" value="{{ $post->faceToken }}">
                            <input type="hidden" name="instaToken" value="{{ $post->instaToken }}">

                            <div class="card-body">
                                <div class="form-group row mb-3 flex-column">

                                    <div class="form-group border border-gray rounded">
                                        <textarea cols="30" rows="4" class="form-control border-0" name="postData" required 
                                        placeholder="Whta's on your mind?">{{ $post->postData }}</textarea>

                                        <div class="container">
                                            <div class="photoSec pb-4">
                                                @if ($post->image)
                                                    <img src="{{ $post->image }}" alt="">
                                                @endif
                                            </div>
                                            
                                            <div class="card py-2 px-4 mt-2 d-flex flex-row justify-content-between align-items-center">
                                                <p class="m-0">Add to your post</p>
                                                <div class="d-flex position-relative">
                                                    <div class="file position-absolute">
                                                        <input type="file" class="form-control position-absolute" name="image" onchange="getImagePreview(event)"
                                                         @if ($post->instaToken != null) required @endif>
                                                        <i class="fas fa-photo-video text-success px-2"></i>
                                                    </div>
                                                    <i class="fas fa-link text-info mx-1 postLink" data-toggle="modal" data-target="#modal-default"></i>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="d-flex align-items-center mb-2">
                                        <i class="far fa-calendar-times text-info mr-2"></i>
                                        <label class="pt-1">schedule your post</label> 
                                        <i class="far fa-hand-point-right text-info mx-2"></i>
                                        <input type="checkbox" id="checkDate" data-bootstrap-switch onchange="statusChange()">
                                    </div>

                                    <div class="form-group w-25 schedule" style="display: none">
                                        <div class="row">
                                            <input type="datetime-local" id="scheduledTime" class="form-control mb-0" name="scheduledTime">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="card-footer rounded">
                                <button type="submit" class="btn btn-info">save</button>
                                <a href="{{ route('adminSocail') }}" class="btn btn-secondary float-right">Cancel</a>
                            </div>
                        </div>

                          <div class="modal fade" id="modal-default">
                            <div class="modal-dialog">
                              <div class="modal-content">
                                <div class="modal-header bg-info">
                                  <h4 class="modal-title">Add Link</h4>
                                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                  </button>
                                </div>
                                <div class="modal-body">
                                    <input type="text" class="form-control" name="link" placeholder="link"
                                    @if ($post->link)
                                        value="{{ $post->link }}"
                                    @endif>
                                    <br>
                                    <p>
                                        This link for facebook only.... <br>
                                        if you have an iamge, the link not allowed and recommended to put it in your text.
                                    </p>
                                </div>
                                <div class="modal-footer justify-content-end">
                                    <button type="button" class="btn btn-info" data-dismiss="modal" aria-label="Close">Ok</button>
                                </div>
                              </div>
                            </div>
                        </div>
                        <!-- /.modal -->
                    </form>
                  </div>

            </section>
        </div>
    </div>
</div>


@endsection




