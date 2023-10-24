@extends('layouts.layoutAdminSocial')

@section('content')

@php
    $social_posts = App\Models\Api::where('creator_id', Auth::user()->id)->count();
    $data = App\Models\Api::all()->where('creator_id', Auth::user()->id);
@endphp

<div class="content-wrapper">
    <div class="content-header">
        <div class="container">
            <section class="content">

                <p class="my-4" style="font-size: 20px">
                    Update New Posts from Your App <br>
                    wirte time by <span class="text-danger" style="font-weight: bold">minutes</span> 
                </p>
                    
                <form class="form-horizontal" action="{{route('updateInterval')}}" method="post">
                    @csrf
                    @method('PUT')
                    <div class="card card-info">
                        <div class="card-header">
                        <h3 class="card-title">Update Time Posts</h3>
                        </div>

                        <div class="card-body">
                            <div class="form-group row mb-0">
                                <label for="timeInput" class="col-sm-2 col-form-label">Time by minute</label>
                                <div class="col-sm-10">
                                    <div class="row">
                                        <div class="col-12">
                                            <input type="number" class="form-control" id="timeInput" name="update_interval" 
                                            @if ($social_posts != 0) value="{{ $data->last()['update_interval'] }}" @endif>
                                        </div>
                                        <div class="col-12 mt-3">
                                            <b><span id="hours"></span></b>
                                        </div>
                                    </div>
                                </div>
                                
                            </div>
                        </div>

                        <div class="card-footer">
                            @if ($social_posts != 0)
                                <button type="submit" class="btn btn-info">save</button>
                            @else
                                <a href="{{route('socialAccounts')}}" class="text-decoration-underline">Add social acount</a>
                            @endif 
                            <a href="{{route('adminSocail')}}" class="btn btn-secondary float-right">Cancel</a>
                        </div>
                    </div>
                </form>

            </section>
        </div>
    </div>
</div>

<script>
    const hourSpan = document.getElementById('hours');
    const inputField = document.getElementById('timeInput');

    function updateTime() {
        const value = inputField.value;
        if (value >= 60) {
            const hours = Math.floor(value / 60);
            hourSpan.innerHTML = `It's about ${hours} hours`;
        } else {
            hourSpan.innerHTML = `It's about ${value} Minutes`;
        }
    }

    inputField.addEventListener('input', updateTime);
    if(inputField.value != ''){
        updateTime(); 
    }
</script>

@endsection


