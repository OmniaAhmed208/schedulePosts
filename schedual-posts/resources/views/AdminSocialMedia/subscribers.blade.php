@extends('layouts.layoutAdminSocial')

@section('content')

 <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <section class="content postsPage">

                <div class="d-flex justify-content-between align-items-center px-4">
                    <h4 class="my-4" style="font-weight: bold">Subscribers</h4>
                </div>

                <form action="{{ route('subscribers.store') }}" method="post" class="p-2">
                    @csrf
                    <div class="row">
                        <div class="col-6 mb-2">
                            <label class="form-label" for="modalEmail">Email</label>
                            <input type="text" id="email" name="email" required class="form-control" tabindex="-1" />
                        </div>
                        <div class="col-6 mb-2">
                            <label class="form-label" for="modalServiceName">Service name</label>
                            <input type="text" id="serviceName" name="service_name" class="form-control" tabindex="-1" />
                        </div>
                        <div class="col-12 mb-2">
                            <label class="form-label" for="modalReason">Reason</label>
                            <textarea type="text" id="reason" name="reason" class="form-control"></textarea>
                        </div>
                        <div class="col-12 mb-2">
                            <label class="form-label" for="modalNotes">Notes</label>
                            <textarea type="text" id="notes" name="notes" class="form-control"></textarea>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-info">Submit</button>
                </form>


                <form action="{{ route('subscribers.store') }}" method="post" class="p-2">
                    @csrf
                    <div class="row">
                        <div class="col-6 mb-2">
                            <label class="form-label" for="modalEmail">Email</label>
                            <input type="text" id="email" name="email" required class="form-control" tabindex="-1" />
                        </div>
                    </div>
                    <button type="submit" class="btn btn-info">Submit</button>
                </form>
                
            </section>
        </div>
  </div>


@endsection