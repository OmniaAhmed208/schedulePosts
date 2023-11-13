@extends('layouts.layoutAdminSocial')

@section('content')

<div class="content-wrapper">
  <div class="content-header">
      <div class="container-fluid">
        <section class="content adminSocail">
          <div class="row">

            <div class="col-12">
              <div class="small-box bg-white">
                <div class="inner pl-4 d-flex flex-column align-items-start">
                  <h3>Services</h3>
                  <h4>{{ $appCount }} / {{ $servicesCount }}</h4> 
                  
                  <a href="{{ route('services.index') }}" class="small-box-footer text-left text-dark">More <i class="fas fa-arrow-circle-right ml-2"></i></a>
                </div>
                <div class="icon">
                  <i class="ion ion-bag"></i>
                </div>

              </div>
            </div>

            <div class="col-sm-6">
              <div class="small-box bg-white">
                <div class="inner pl-4 d-flex flex-column align-items-start">
                  <h3>Publish Post</h3>
                  <div class="d-flex">
                    <p>Number of posts published for last week</p> <h4 class="ml-4">{{ $lastPosts }}</h4>
                  </div>
                  <a href="{{ route('accountPages') }}" class="small-box-footer text-left text-dark">More <i class="fas fa-arrow-circle-right ml-2"></i></a>
                </div>
                <div class="icon">
                  <i class="ion ion-stats-bars"></i>
                </div>
              </div>
            </div>

            <div class="col-sm-6">
              <div class="small-box bg-white">
                <div class="inner pl-4 d-flex flex-column align-items-start">
                  <h3>History</h3>
                  <div class="d-flex">
                    <p>Number of all posts</p> <h4 class="ml-4"> {{ $allPosts }} </h4>
                  </div>
                  <a href="{{ route('historyPosts') }}" class="small-box-footer text-left text-dark">More <i class="fas fa-arrow-circle-right ml-2"></i></a>
                </div>
                <div class="icon">
                  <i class="ion ion-pie-graph"></i>
                </div>
              </div>
            </div>

            <div class="col-sm-6">
              <div class="small-box bg-white">
                <div class="inner pl-4 d-flex flex-column align-items-start">
                  <h3>Settings</h3>
                  <ul class="nav flex-column align-items-start">
                    <li class="nav-item">
                      <a href="{{ route('timeThink') }}" class="nav-link text-dark px-0">
                        Think Time
                        <i class="fas fa-arrow-circle-right ml-2"></i>
                      </a>
                    </li>
                    <li class="nav-item">
                      <a href="{{ route('updatePostsTime') }}" class="nav-link text-dark px-0">
                        Time to update posts
                        <i class="fas fa-arrow-circle-right ml-2"></i>
                      </a>
                    </li>
                  </ul>
                </div>
                <div class="icon">
                  <i class="ion ion-settings"></i>
                </div>
              </div>
            </div>

            <div class="col-sm-6">
              <div class="small-box bg-white">
                <div class="inner pl-4 d-flex flex-column align-items-start">
                  <h3>Tools</h3>
                  <ul class="nav flex-column align-items-start">
                    <li class="nav-item">
                      <a href="{{ route('schedulePosts') }}" class="nav-link text-dark px-0">
                        Time of schedule Posts
                        <i class="fas fa-arrow-circle-right ml-2"></i>
                      </a>
                    </li>
                    <li class="nav-item">
                      <a href="{{ route('updatePostsNow') }}" class="nav-link text-dark px-0">
                        Get New posts
                        <i class="fas fa-arrow-circle-right ml-2"></i>
                      </a>
                    </li>
                  </ul>
                </div>
                <div class="icon">
                  <i class="ion ion-wrench"></i>
                </div>
              </div>
            </div>

            <div class="col-12">
              <section class="content">
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
                                  <button class="dropdown-item filter-button my-1" data-filter="{{ $app['appType'] }}" type="button" style="text-transform: capitalize;">
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
              </section>

            </div>

          </div>
        </section>
      </div>
  </div>
</div>


<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<meta name="csrf-token" content="{{ csrf_token() }}">

{{-- @php
  $userId = Auth::user()->id;
  $startDate = now()->subDays(9);
  // Fetch posts from the last 10 days
  $publishPost = App\Models\publishPost::where('status', 'published')->where('creator_id', Auth::user()->id)
    ->where('scheduledTime', '>=', $startDate)->get();
@endphp --}}

<script>
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
      var posts = <?php echo $publishPost; ?>;
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
</script>

@endsection


