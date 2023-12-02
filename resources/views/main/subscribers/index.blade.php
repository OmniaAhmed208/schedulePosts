@extends('layouts.layout')

@section('content')

 <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <section class="content py-4">

                <div class="d-flex justify-content-between align-items-center px-4">
                    <h4 class="my-4 text-dark" style="font-weight: bold">Subscribers</h4>
                    <div>
                        <button class="btn btn text-white" style="background: #06283d"
                        type="button" data-bs-toggle="modal" data-bs-target="#addSubscribe">
                            Subscribe
                        </button>
                        <button class="btn btn text-white" style="background: #06283d"
                        type="button" data-bs-toggle="modal" data-bs-target="#addService">
                            Add service
                        </button>
                    </div>
                </div>

                <div class="row row-cols-1 row-cols-md-2 row-cols-lg-2 g-4"  data-masonry='{"percentPosition": true,  "itemSelector": ".col" }'>
                    @foreach ($subscribers as $subscriber)
                    <div class="col">
                        <div class="card mb-2">
                            <div class="card-body">
                                <h5 class="card-title">{{ $subscriber->email }}</h5>
                                <div class="card-subtitle text-muted mb-3">Subscriber</div>
                                @if ($subscriber->subscriberRequests->isNotEmpty())
                                    @foreach ($subscriber->subscriberRequests as $request)
                                        <h5>- {{ $request->service_name }}</h5>
                                        <p class="card-text">
                                            {{ $request->reason }}
                                        </p>
                                        <p class="card-text">
                                            {{ $request->note }}
                                        </p>    
                                    @endforeach
                                @endif
                                
                            </div>
                        </div>
                    </div>        
                    @endforeach
                </div>    

                {{-- add subscribe --}}
                <div class="modal fade" id="addSubscribe" tabindex="-1" aria-hidden="true">
                    <form id="addSubscribeForm" action="{{ route('subscribers.store') }}" method="post" enctype="multipart/form-data">
                        @csrf
                        <div class="modal-dialog modal-dialog-centered" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                            <h5 class="modal-title">Add Subscribe</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <div class="row">
                                    <div class="col mb-3">
                                        <label for="subscriberEmail" class="form-label">Email</label>
                                        <input type="email" id="subscriberEmail" required class="form-control" name="email"/>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal"> Close </button>
                                <button type="submit" class="btn btn text-white" style="background: #79DAE8">Save</button>
                            </div>
                        </div>
                        </div>
                    </form>
                </div>

                {{-- add service --}}
                <div class="modal fade" id="addService" tabindex="-1" aria-hidden="true">
                    <form id="addServiceForm" action="{{ route('subscribers.store') }}" method="post" enctype="multipart/form-data">
                        @csrf
                        <div class="modal-dialog modal-dialog-centered" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Add new service</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <div class="row">
                                    <div class="col mb-3">
                                        <label class="form-label" for="modalEmail">Email</label>
                                        <input type="text" id="email" name="email" required class="form-control" tabindex="-1" />
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col mb-3">
                                        <label class="form-label" for="modalServiceName">Service name</label>
                                        <input type="text" id="serviceName" name="service_name" class="form-control" tabindex="-1" />
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col mb-3">
                                        <label class="form-label" for="modalReason">Reason</label>
                                        <textarea type="text" id="reason" name="reason" class="form-control"></textarea>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col mb-3">
                                        <label class="form-label" for="modalNotes">Notes</label>
                                        <textarea type="text" id="notes" name="notes" class="form-control"></textarea>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                            <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal"> Close </button>
                            <button type="submit" class="btn btn text-white" style="background: #79DAE8">Save</button>
                            </div>
                        </div>
                        </div>
                    </form>
                </div>

            </section>
        </div>
  </div>

  <script src="{{ asset('tools/assets/vendor/libs/masonry/masonry.js') }}"></script>

@endsection

