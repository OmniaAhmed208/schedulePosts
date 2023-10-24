@extends('layouts.layoutAdminSocial')

@section('content')

@php
    $checkExistAccount = App\Models\Api::where('creator_id', Auth::user()->id)->count();
    $timeServer = Carbon\Carbon::now()->format('Y-m-d H:i');
@endphp

<div class="content-wrapper">
    <div class="content-header">
        <div class="container">
            <section class="content">

                <h4 class="my-4" style="font-weight: bold">Applications</h4>

                <p class="my-4" style="font-size: 20px">
                    Choose app to publish post
                </p>

                <div class="publish-posts section_data mt-4 container" id="publish_posts">
        
        
                    @if ($checkExistAccount == 0)
                        <p class="my-4" style="font-size: 20px">
                            You Should login first to your accounts to publish posts
                            <i class="far fa-hand-point-right"></i>
                            <a href="{{ route('services') }}" class="btn btn-primary">Login</a>
                        </p>
                    @else
                        <div class="row">
                            <form action="{{ url('chooseApp') }}" method="post">
                                @csrf
                                @method('post')
                                <div class="mb-2">
                                    <input type="checkbox" name="facebook"> 
                                    <label class="mx-2">Facebook</label> 
                                    <span class="rounded-circle px-2 py-1 mx-1 bg-primary"><i class="fab fa-facebook-f"></i></span>
                                </div>
                                <div class="mb-2">
                                    <input type="checkbox" name="instagram"> 
                                    <label class="mx-2">Instagram</label> 
                                    <span class="rounded-circle px-2 py-1 mx-1 text-white" style="background-color: #d63384"><i class="fab fa-instagram"></i></span>
                                </div>   
                                <button type="submit" class="btn btn-primary">Submit</button>
                            </form>
                        </div>
                    @endif
                </div>
            </section>
        </div>
    </div>
</div>
@endsection


