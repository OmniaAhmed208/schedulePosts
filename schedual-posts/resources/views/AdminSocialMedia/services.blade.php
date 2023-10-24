@extends('layouts.layoutAdminSocial')

@section('content')

  @php
    $allApps = ['facebook', 'instagram','twitter','youtube'];
    $settingsApiType = App\Models\settingsApi::all();
  @endphp

 <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container">
        <section class="content servicesPage">

            <h4 class="my-4" style="font-weight: bold">Third Party Services</h4>

            <div class="bg-white p-4 mt-4 config">
              <nav>
                <div class="nav nav-tabs" id="nav-tab" role="tablist">
                 @foreach ($allApps as $app)
                  <a class="nav-item nav-link" id="nav-{{ $app }}-tab" data-toggle="tab" href="#nav-{{ $app }}" role="tab" aria-controls="nav-{{ $app }}" aria-selected="true">{{ $app }}</a>                     
                 @endforeach
                </div>
              </nav>

              <div class="tab-content" id="nav-tabContent">
                @foreach ($allApps as $app)
                  <div class="tab-pane fade" id="nav-{{ $app }}" role="tabpanel" aria-labelledby="nav-{{ $app }}-tab">
                    <form class="form-horizontal bg-white rounded p-4" action="{{route('settingsApi')}}" method="POST" id="setting-form">
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
                            <input type="text" class="form-control" id="appKey" name="appKey">  
                          </div>    
                        @endif
                        
                      </div>
                      <button type="submit" class="btn btn-info" id="saveChangesBtn">
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

        </section>
      </div><!-- /.container-fluid -->
    <!-- /.content -->
  </div>

  <script>
    let allTabs = document.querySelectorAll('#nav-tab a');
    allTabs[0].classList.add('active');
    let allTabContent =  document.querySelectorAll('#nav-tabContent .tab-pane');
    allTabContent[0].classList.add('show','active');
  </script>

@endsection

