@extends('layouts.layout')

@section('content')

 <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <section class="content servicesPage py-4">

                <div class="d-flex justify-content-between align-items-center px-4">
                    <h4 class="my-4 text-dark" style="font-weight: bold">Third Party Services</h4>
                </div>



                <!-- Tabs -->
                <div class="row">
                    <div class="col">
                        <div class="nav-align-top mb-4 config">
                            <ul class="nav nav-tabs" id="nav-tab" role="tablist">
                                @foreach ($allApps as $app)
                                    <li class="nav-item">
                                        <button type="button" class="nav-link" role="tab" data-bs-toggle="tab" id="nav-{{ $app }}-tab"
                                        data-bs-target="#navs-top-{{ $app }}" aria-controls="navs-top-{{ $app }}" aria-selected="true">
                                        {{ $app }}
                                        </button>
                                    </li>
                                @endforeach
                            </ul>

                            <div class="tab-content" id="nav-tabContent">
                                @foreach ($allApps as $app)
                                    <div class="tab-pane fade show" id="navs-top-{{ $app }}" role="tabpanel">
                                        <form class="form-horizontal bg-white rounded p-4" action="{{route('services.store')}}" method="POST" id="setting-form">
                                            @csrf
                                            <div class="form-group row">
                        
                                                <input type="hidden" class="form-control mb-3" name="appType" value="{{ $app }}">   
                            
                                                <label for="appId" class="col-md-3 col-form-label">App ID</label>
                                                <div class="col-md-9 mb-3">
                                                    <input type="text" class="form-control" id="appId" name="appId" required
                                                    value="@foreach($settingsApiType as $api)@if($app == $api['appType']){{$api['appID']}}@endif @endforeach">
                                                </div>
                        
                                                <label for="appSecret" class="col-md-3 col-form-label">App Secret</label>
                                                <div class="col-md-9 mb-3">
                                                    <input type="text" class="form-control" id="appSecret" name="appSecret" required
                                                    value="@foreach($settingsApiType as $api)@if($app == $api['appType']){{$api['appSecret']}}@endif @endforeach">  
                                                </div>
                        
                                                @if ($app === 'youtube')
                                                    <label for="appKey" class="col-md-3 col-form-label">Api key</label>
                                                    <div class="col-md-9 mb-3">
                                                    <input type="text" class="form-control" id="appKey" name="apiKey" required
                                                    value="@foreach($settingsApiType as $api)@if($app == $api['appType']){{$api['apiKey']}}@endif @endforeach">  
                                                    </div>
                                                @endif
                                            </div>
                                            
                                            @if ($app === 'youtube')
                                                <a href="#" class="btn btn-info" data-target="#youtubeCategory" data-toggle="modal">Youtube categories</a>    
                                            @endif
                                            
                                            <button type="submit" class="btn btn-dark" id="saveChangesBtn">
                                                @php
                                                    $updateChanges = false;
                                                    foreach($settingsApiType as $api) {
                                                    if($app === $api['appType']) { $updateChanges = true; break; }
                                                    }
                                                @endphp
                            
                                                @if($updateChanges) Update changes @else Save @endif
                                            </button>
                                        </form>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Tabs -->
        
        
                <!-- Add category Modal -->
                <div class="modal fade" id="youtubeCategory" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog modal-lg modal-simple modal-dialog-centered modal-add-new-category">
                        <div class="modal-content p-3 p-md-3">
                            <div class="modal-body">
                                <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button>
                                <div class="text-center mb-4">
                                    <h3 class="category-title">Add New Category</h3>
                                    <p>Categories you may use and assign to youtube video.</p>
                                </div>
                                <!-- Add category form -->
                                <form id="addCategoryForm" class="row g-3" action="{{route('youtubeCategories.store')}}" method="post">
                                    @csrf
                                    <div class="col-6">
                                    <label class="form-label" for="modalCategoryId">Category Id</label>
                                    <input type="number" id="categoryID" name="categoryID" required class="form-control" placeholder="Enter a category id" tabindex="-1" />
                                    </div>
                                    <div class="col-6">
                                        <label class="form-label" for="modalCategoryName">Category Name</label>
                                        <input type="text" id="categoryName" name="categoryName" required class="form-control" placeholder="Enter a category name" tabindex="-1" />
                                    </div>
                                    <div class="col-12 text-center">
                                        <button type="submit" class="btn btn-info me-sm-3 me-1">Create Category</button>
                                        <button type="reset" class="btn text-white" style="background-color: #BEBEBE">Reset</button>
                                    </div>
    
                                    <div class="categories">
                                    <div class="row p-4">
                                        <div class="col-12">
                                            <div class="card">
                                                <div class="card-body">
                                                    <table id="" class="table table-bordered table-striped">
                                                        <thead>
                                                            <tr>
                                                                <th>Category ID</th>
                                                                <th>Category Name</th>
                                                                {{-- <th></th> --}}
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                        @foreach ($youtubeCategories as $category)
                                                        <tr>
                                                            <td>{{ $category->category_id }}</td>
                                                            <td>{{ $category->category_name }}</td>
                                                            {{-- <td><span data-target="#" data-toggle="modal" data-userId='' style="color: #06283D"><i class="far fa-edit"></i></span></td> --}}
                                                        </tr>  
                                                        @endforeach
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    </div>
                                </form>
                                <!--/ Add category form -->
                            </div>
                        </div>
                    </div>
                </div>
                <!--/ Add permission Modal -->

            </section>
        </div>
  </div>


  <script>
    let allTabs = document.querySelectorAll('#nav-tab li button');
    allTabs[0].classList.add('active');
    let allTabContent =  document.querySelectorAll('#nav-tabContent .tab-pane');
    allTabContent[0].classList.add('show','active');
  </script>

@endsection
