@extends('layouts.layout')

@section('content')

 <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <section class="content py-4">

                <div class="row">
                    <h5> 
                      @foreach ($user as $user)
                      Dashboard:  {{ $user->name }}
                      @endforeach
                    </h5>


                    <div class="col-lg-12 order-1">
                        <div class="row">
                            <div class="col-lg-4 col-md-12 col-6 mb-4">
                                <div class="card" style="background: #06283d">
                                    <div class="card-body text-white">
                                        <div class="card-title d-flex align-items-start justify-content-between">
                                            <div class="avatar flex-shrink-0 p-2 rounded" style="background-color: #e0f7fc;">
                                                <img src="{{ asset('tools/assets/img/icons/unicons/service.png') }}" alt="chart success" class="rounded" />
                                            </div>
                                        </div>
                                        <div class="d-flex justify-content-between">
                                            <h3 class="fw-medium d-block mb-1 text-white">Services</h3>
                                            <h4 class="card-title mb-2 text-white">{{ $appCount }} / {{ $servicesCount }}</h4>
                                        </div>
                                        <p>Accounts connected in</p>
                                        <small class="fw-medium">
                                            <a href="{{ route('services.index') }}" class="text-white">
                                                <i class="bx bx-right-arrow-alt"></i> View more
                                            </a>
                                        </small>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-12 col-6 mb-4">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="card-title d-flex align-items-start justify-content-between">
                                            <div class="avatar flex-shrink-0 p-2 rounded" style="background-color: #e0f7fc;">
                                                <img src="{{ asset('tools/assets/img/icons/unicons/post.png') }}" alt="post" class="rounded" />
                                            </div>
                                        </div>
                                        <div class="d-flex justify-content-between">
                                            <h3 class="fw-medium d-block mb-1">Published</h3>
                                            <h4 class="card-title mb-2">{{ $lastPosts }}</h4>
                                        </div>
                                        <p>Posts published at last week</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-12 col-6 mb-4">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="card-title d-flex align-items-start justify-content-between">
                                            <div class="avatar flex-shrink-0 p-2 rounded" style="background-color: #e0f7fc;">
                                                <img src="{{ asset('tools/assets/img/icons/unicons/post.png') }}" alt="post" />
                                            </div>
                                        </div>
                                        <div class="d-flex justify-content-between">
                                            <h3 class="fw-medium d-block mb-1">posts</h3>
                                            <h4 class="card-title mb-2">{{ $allPosts }}</h4>
                                        </div>
                                        <p>All posts have</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
        
                    <div class="col-12">
        
                      {{-- <section class="content">
                        <div class="card card-info">
                            <div class="card-header">
                              <h3 class="card-title my-2 d-flex align-items-center">
                                <span>Count of posts published from </span>
                                <span class="form-group m-0 mx-3">
                                  <input type="date" id="datePosts" class="form-control mb-0" style="width: 150px" onchange="changeDate();">
                                </span>
                                <span id="numDays">10 days ago</span>
                              </h3>
                              <div class="card-tools">
                                <div class="btn-group">
                                    <button type="button" class="btn text-white dropdown-toggle" style="background-color: #06283D" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <i class="fas fa-desktop mr-2"></i>
                                        choose App
                                    </button>
                                    <div class="dropdown-menu dropdown-menu-right" style="background-color: #f5f5f5">
                                      <button class="dropdown-item filter-button" data-filter="all" type="button">All applications</button>
                                      <div class="dropdown-divider"></div>
                                      <h5 class="pl-3">Accounts</h5>
        
                                      @foreach ($allApps as $app)
                                        <button class="dropdown-item filter-button my-1" data-filter="{{ $app['appType'] }}" type="button">
                                          <span class="info-box-icon py-1 px-2 mr-2 rounded {{ $app['appType'] }}App"><i class="fab fa-{{ $app['appType'] }}"></i></span>
                                          {{ $app['appType'] }}
                                        </button>
                                      @endforeach
                                      
                                  </div>
                                </div>
                              </div>
                            </div>
                            <div class="card-body">
                                <canvas id="myChart"></canvas>
                            </div>
                        </div>
                      </section> --}}
        
                    </div>
        
                  </div>

            </section>
        </div>
  </div>



<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<meta name="csrf-token" content="{{ csrf_token() }}">


{{-- <script>
  var currentDate = new Date(); // Get the current date
  currentDate.setDate(currentDate.getDate() - 9);  // Subtract 10 days from the current date
  var formattedDate = currentDate.toISOString().substr(0, 10); // Format the date as yyyy-mm-dd (required by input type="date")
  var datePosts = document.getElementById('datePosts');
  datePosts.value = formattedDate;
  var today = new Date().toISOString().split('T')[0];
  datePosts.setAttribute("max", today);


  function changeDate()
  {
    document.getElementById('numDays').textContent = '';

    var csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');    
    var selectedDate = datePosts.value;

    var userId = <?php echo $userId; ?>;
    var url = "{{ route('chartJS', ['userId' => '__id__']) }}"; 
    var finalUrl = url.replace('__id__', userId);

    var xhr = new XMLHttpRequest();
    xhr.open('POST', finalUrl, true);

    xhr.setRequestHeader('X-CSRF-TOKEN', csrfToken);
    xhr.setRequestHeader('Content-Type', 'application/json');

    xhr.onload = function() {
      if (xhr.status === 200) {
        var response = xhr.responseText;
        // console.log(response);

        try {
          var responseObject = JSON.parse(response);
          chartJsPosts(responseObject);
        } catch (error) {
          console.error('Error parsing JSON response:', error);
        }
      } else {
        console.log('Error: ' + xhr.status);
      }
    };

    xhr.send(JSON.stringify({ selectedDate: selectedDate }));
  }
</script>

<script>
  chartJsPosts();
  function chartJsPosts(responseObject){
    // console.log(responseObject)
    var selectedApp = 'All';

    document.addEventListener('DOMContentLoaded', () => {
      const rawData = fetchAllData();
      const formattedData = prepareChartData(rawData);
      createLineChart(formattedData);
    });

    if(responseObject){
      var posts = responseObject;
      const rawData = fetchAllData();
      const formattedData = prepareChartData(rawData);
      createLineChart(formattedData);
      // console.log(posts);
    }
    else{
      var posts = <?php echo $Publish_Post; ?>;
    }

    function fetchAllData() {
      
      var postCountsByDate = {};

      posts.forEach(element => {
        var postDate = element.scheduledTime.split(' ')[0]; // to get date without time

        if (postCountsByDate[postDate]) {
          postCountsByDate[postDate]++;
        } else {
          postCountsByDate[postDate] = 1;
        }
      });

      var result = Object.keys(postCountsByDate).map(postDate => ({
          date: postDate,
          postCount: postCountsByDate[postDate],
      }));

      return result;
    }

    // Event listeners for app selection buttons
    document.querySelector('.filter-button[data-filter="all"]').addEventListener('click', function () {
      selectApp('All');
    });

    var allApps = <?php echo $allApps; ?>;
    allApps.forEach(userApp => {
      const selector = `.filter-button[data-filter="${userApp['appType']}"]`;
      document.querySelector(selector).addEventListener('click', function () {
        selectApp(userApp['appType']);
      });
    });

    function selectApp(app) {
      if (app === 'All') {
        // If 'All' is selected, reload the chart with all data
        selectedApp = 'All';
        const rawData = fetchAllData();
        const formattedData = prepareChartData(rawData);
        createLineChart(formattedData);
      } else {
        selectedApp = app;
        // Refetch and recreate the chart with the selected app data
        const rawData = fetchSpecialData();
        const formattedData = prepareChartData(rawData);
        createLineChart(formattedData);
      }
    }

    function fetchSpecialData() {
      if (selectedApp) 
      {
        var postCountsByDate = {};
        posts.forEach(element => {
          // Check if the post type matches the selected app
          if (element.type === selectedApp) {
            var postDate = element.scheduledTime.split(' ')[0];

            if (postCountsByDate[postDate]) {
              postCountsByDate[postDate]++;
            } else {
              postCountsByDate[postDate] = 1;
            }
          }
        });

        var result = Object.keys(postCountsByDate).map(postDate => ({
          date: postDate,
          postCount: postCountsByDate[postDate],
        }));

        return result;
      }
    }

    // Function to format and prepare data for Chart.js
    function prepareChartData(data) {
      const labels = data.map(entry => entry.date);
      const postCounts = data.map(entry => entry.postCount);

      return { labels, postCounts };
    }

    // Create the line chart
    function createLineChart(data) {
      const ctx = document.getElementById('myChart');

      new Chart(ctx, {
        type: 'line',
        data: {
          labels: data.labels,
          datasets: [{
            label: selectedApp,
            data: data.postCounts,
            borderWidth: 1,
            borderColor: 'blue',
            backgroundColor: 'rgba(0, 0, 255, 0.2)'
          }]
        },
        options: {
          scales: {
            y: {
              beginAtZero: true,
              stepSize: 1, // Adjust this based on your data
            }
          }
        }
      });

    }
  }  
</script> --}}

@endsection


