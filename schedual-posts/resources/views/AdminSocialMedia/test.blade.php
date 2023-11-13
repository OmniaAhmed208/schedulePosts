
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>AdminLTE 3 | Calendar</title>

  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="tools/plugins/fontawesome-free/css/all.min.css">
  <!-- fullCalendar -->
  <link rel="stylesheet" href="tools/plugins/fullcalendar/main.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="tools/dist/css/adminlte.min.css">
</head>
<body class="hold-transition sidebar-mini">
<div class="wrapper">

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">

    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
        
        @php
            // 1 to 1
            $time = App\Models\User::find(1)->time_think; 
            // 1 to 1 relation row in timethink table which user has id 1 in timethink class

            // 1 to many
            $posts = App\Models\User::find(1)->Publish_Posts;
            foreach ($posts as $post) {
                // ...
            }

            // 1 to many by constraints
            $posts = App\Models\User::find(1)->PublishPosts()
                    ->where('status', 'published')
                    ->first();

            // many to  many
            $user = App\Models\User::find(1);
            foreach ($user->roles as $role) {
                // ... $user->roles ==> ->roles it means class inside user model
            }   
            $roles = App\Models\User::find(1)->roles()->orderBy('name')->get();

            // many to many ==> we may access the intermediate table using the pivot attribute on the models:
            $user = App\Models\User::find(1);
            foreach ($user->roles as $role) {
                echo $role->pivot->created_at;
            }




            // Access post_images for a specific publish_post
            // $publishPost = App\Models\PublishPost::find(1)->postImages;

            // Access the parent publish_post for a specific post_image
            // $postImage = App\Models\PostImage::find(1)->publishPost;


        @endphp

      </div>
    </section>
    
  </div>


  @include('layouts.sidebarSocial')
</div>
<!-- ./wrapper -->

<!-- jQuery -->
<script src="tools/plugins/jquery/jquery.min.js"></script>
<!-- Bootstrap -->
<script src="tools/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- jQuery UI -->
<script src="tools/plugins/jquery-ui/jquery-ui.min.js"></script>
<script src="tools/plugins/moment/moment.min.js"></script>
<script src="tools/plugins/fullcalendar/main.js"></script>


</body>
</html>
