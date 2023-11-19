@extends('layouts.layoutAdminSocial')

@section('content')

@php
$timeThink = App\Models\time_think::where('creator_id', Auth::user()->id)->count();
$data = App\Models\time_think::where('creator_id', Auth::user()->id)->first();
$timeServer = Carbon\Carbon::now()->format('Y-m-d H:i');
@endphp

<div class="content-wrapper">
    <div class="content-header">
        <div class="container">
            <section class="content">

                <p class="my-4" style="font-size: 20px">
                    the time on serve is <span class="text-danger" style="font-weight: bold">{{ $timeServer }}</span> <br>
                    update the time by increase the difference:
                </p>
                    
                <form class="form-horizontal" action="{{route('timeThinkStore')}}" method="post">
                    @csrf
                    @method('PUT')
                    <div class="card card-info">
                        <div class="card-header">
                        <h3 class="card-title">Update Time Server</h3>
                        </div>

                        <div class="card-body">
                            <div class="form-group row">
                                <label for="inputEmail3" class="col-sm-2 col-form-label">Time</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" id="inputEmail3" name="time"
                                    @if ($timeThink != 0) value="{{ $data->first()['time'] }}" @endif required>
                                </div>
                            </div>
                        </div>

                        <div class="card-footer">
                            <button type="submit" class="btn btn-info">save</button>
                            <a href="{{route('adminSocail')}}" class="btn btn-secondary float-right">Cancel</a>
                        </div>
                    </div>
                </form>

            </section>
        </div>
    </div>
</div>
@endsection


