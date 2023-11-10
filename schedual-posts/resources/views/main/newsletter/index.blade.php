@extends('layouts.layout')

@section('content')

 <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <section class="content postsPage py-4">

                <div class="d-flex justify-content-between align-items-center px-4">
                    <h4 class="my-4 text-dark" style="font-weight: bold">Newsletter</h4>
                    @if (Auth::user()->user_type == 'admin')
                        <button class="btn btn text-white" style="background: #06283d"
                        type="button" data-bs-toggle="modal" data-bs-target="#addNewsletter">
                            Add newsletter
                        </button>
                    @endif
                </div>

                <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4"  data-masonry='{"percentPosition": true,  "itemSelector": ".col" }'>
                    @foreach ($newsLetter as $index => $data)
                        <div class="col">
                            <div class="card">
                                @if ($data['image'] != null)
                                    <img class="card-img-top" src="{{url($data['image'])}}" alt="Card image cap" />
                                @endif
                                <div class="card-body">
                                    <h5 class="card-title">{{ $data['title'] }}</h5>

                                    @if ($data['content'] != null)
                                        <p class="card-text"> {{ $data['content'] }} </p>
                                    @endif
                                </div>

                                @if (Auth::user()->user_type == 'admin')
                                    <div class="d-flex justify-content-end">
                                        <button type="button" class="btn p-0 mt-2" data-newsletter="{{$data}}" data-bs-toggle="modal" data-bs-target="#editNewsletter">
                                            <i class="bx bx-edit text-primary"></i>
                                        </button>
                                        <form action="{{ route('newsLetter.destroy',$data->id) }}" method="post">
                                            @csrf
                                            @method('delete')
                                            <button type="submit" class="m-1" onclick="return confirm('Are you sure?')"
                                            style="background:transparent;border:none">
                                                <i class="bx bx-trash text-danger"></i>
                                            </button>
                                        </form>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>


                {{-- add newsletter --}}
                <div class="modal fade" id="addNewsletter" tabindex="-1" aria-hidden="true">
                    <form id="addNewsletterForm" action="{{ route('newsLetter.store') }}" method="post" enctype="multipart/form-data">
                        @csrf
                        <div class="modal-dialog modal-dialog-centered" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                            <h5 class="modal-title">Add Newsletter</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <div class="row">
                                    <div class="col mb-3">
                                        <label for="newsletterTitle" class="form-label">Title</label>
                                        <input type="text" id="newsletterTitle" required class="form-control" name="title" placeholder="Enter title" />
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col mb-3">
                                        <label for="newsletterContent" class="form-label">Content</label>
                                        <textarea id="newsletterContent" class="form-control" required name="content"></textarea>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col mb-3">
                                        <div class="w-50 p-2 pb-0" style="border: 2px dashed #ccc;">
                                            <div id="imageContainer"></div>
                                            <label for="newsletterImage" class="form-label position-relative" style="cursor: pointer;">
                                                <input type="file" id="newsletterImage" class="form-control position-absolute" name="image" style="opacity:0;" accept="image/*" />
                                                <i class="bx bx-image-add" style="font-size: 24px; top:8px ;color: #333;"></i> Add Image
                                            </label>
                                        </div>
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

                {{-- edit newsletter --}}
                <div class="modal fade" id="editNewsletter" tabindex="-1" aria-hidden="true">
                    <form id="editNewsletterForm" action="" method="post" enctype="multipart/form-data">
                        @csrf
                        @method('put')
                        <div class="modal-dialog modal-dialog-centered" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                            <h5 class="modal-title">Add Newsletter</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <div class="row">
                                    <div class="col mb-3">
                                        <label for="newsletterTitle" class="form-label">Title</label>
                                        <input type="text" id="newsletterTitle" class="form-control" required name="title" placeholder="Enter title" />
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col mb-3">
                                        <label for="newsletterContent" class="form-label">Content</label>
                                        <textarea id="newsletterContent" class="form-control" required name="content"></textarea>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col mb-0">
                                        <div class="w-50 p-2 pb-0" style="border: 2px dashed #ccc;">
                                            <div id="imageContainer"></div>
                                            <label for="newsletterImage" class="form-label position-relative" style="cursor: pointer;">
                                                <input type="file" id="newsletterImage" class="form-control position-absolute" name="image" style="opacity:0;" accept="image/*" />
                                                <i class="bx bx-image-add" style="font-size: 24px; top:8px ;color: #333;"></i> Add Image
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                            <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal"> Close </button>
                            <button type="submit" class="btn btn text-white" style="background: #79DAE8">Update</button>
                            </div>
                        </div>
                        </div>
                    </form>
                </div>
            </section>
        </div>
  </div>

    <script src="tools/assets/vendor/libs/masonry/masonry.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const addButton = document.querySelector('.btn[data-bs-target="#addNewsletter"]');
            const editButtons = document.querySelectorAll('.btn[data-bs-target="#editNewsletter"]');
            var form = document.getElementById('editNewsletterForm');

            editButtons.forEach(function(button) {
                button.addEventListener('click', function() {
                    const newsletterData = JSON.parse(button.getAttribute('data-newsletter'));
                    const imageContainer = document.querySelector('#editNewsletterForm #imageContainer');
                    imageContainer.innerHTML = '';

                    var url = "{{ route('newsLetter.update', ['newsLetter' => '__id__']) }}";
                    var finalUrl = url.replace('__id__', newsletterData.id || '');
                    form.action = finalUrl;

                    document.querySelector('#editNewsletter #newsletterTitle').value = newsletterData.title;
                    document.querySelector('#editNewsletter #newsletterContent').value = newsletterData.content;

                    if (newsletterData.image != null) {
                        const existingImage = document.createElement('img');
                        existingImage.src = '{{url('')}}' + newsletterData.image;
                        existingImage.className = 'rounded p-1 w-100';
                        imageContainer.appendChild(existingImage);
                        document.querySelector('#editNewsletter #newsletterContent').removeAttribute('required');
                    }

                    const imageInput = document.querySelector('#editNewsletter #newsletterImage');
                    imageInput.addEventListener('change', function() {
                        document.querySelector('#editNewsletter #newsletterContent').removeAttribute('required');
                        const selectedImage = document.createElement('img');
                        const selectedFile = this.files[0];
                        const imageURL = URL.createObjectURL(selectedFile);
                        selectedImage.src = imageURL;
                        selectedImage.className = 'rounded p-1 w-100'; // Adjust the class as needed
                        imageContainer.innerHTML = ''; // Clear previous content
                        imageContainer.appendChild(selectedImage);
                    });
                });
            });


            addButton.addEventListener('click', function() {
                const imageContainer = document.querySelector('#addNewsletterForm #imageContainer');
                imageContainer.innerHTML = ''; // Clear previous content

                const imageInput = document.querySelector('#addNewsletter #newsletterImage');
                imageInput.addEventListener('change', function() {
                    console.log('fdgdg');
                    document.querySelector('#addNewsletter #newsletterContent').removeAttribute('required');
                    const selectedImage = document.createElement('img');
                    const selectedFile = this.files[0];
                    const imageURL = URL.createObjectURL(selectedFile);
                    selectedImage.src = imageURL;
                    selectedImage.className = 'rounded p-1 w-100'; // Adjust the class as needed
                    imageContainer.innerHTML = ''; // Clear previous content
                    imageContainer.appendChild(selectedImage);
                });

                // const removeImageButton = document.querySelector('#addNewsletterForm #removeImage');
                // removeImageButton.addEventListener('click', function() {
                //     imageContainer.innerHTML = ''; // Clear the image container
                //     imageInput.value = ''; // Clear the file input
                // });
            });
        });
    </script>
@endsection
