{{-- @extends('layouts.layout')

@section('content') --}}

 <div class="content-wrapper">
  <div class="content-header">
    <div class="container">
      <section class="content">

        {{-- @php
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


        @endphp --}}

        @php
        $mediaImages = App\Models\User::find(Auth::user()->id)->getMedia('profile_images');
            
        @endphp
        @foreach($mediaImages as $image)
        <img src="{{ $image->getUrl() }}" alt=""> 
        @endforeach

      </section>
    </div>
</div>

<!-- Include moment.js -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>
<!-- Include moment-timezone with data -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment-timezone/0.5.33/moment-timezone-with-data.min.js"></script>

<script>
  var timezone = moment.tz.guess();
  console.log(timezone);
</script>


{{-- @endsection --}}
