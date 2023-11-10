@extends('layouts.layout')
@section('content')

<!-- Content wrapper -->
<div class="content-wrapper">
    <!-- Content -->

    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="row">
            <div class="col-lg-12 mb-4 order-0">
                <div class="card">
                    <div class="d-flex align-items-end row">
                        <div class="col-sm-7">
                            <div class="card-body">
                                <h5 class="card-title">Welcome {{Auth::user()->name}}! 🎉</h5>
                                <p class="mb-4">We're excited to have you on our social media platform! <br>Explore, connect, and share your experiences with the community.</p>

                                <a href="javascript:;" class="btn btn-sm btn-outline-primary">View our newsletter</a>
                            </div>
                        </div>

                        <div class="col-sm-5 text-center text-sm-left">
                            <div class="card-body pb-0 px-0 px-md-4">
                                <img src="tools/assets/img/illustrations/man-with-laptop-light.png"
                                height="140" alt="View Badge User"
                                data-app-dark-img="illustrations/man-with-laptop-dark.png"
                                data-app-light-img="illustrations/man-with-laptop-light.png" />
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-12 order-1">
                <div class="row">
                    <div class="col-lg-4 col-md-12 col-6 mb-4">
                        <div class="card" style="background: #06283d">
                            <div class="card-body text-white">
                                <div class="card-title d-flex align-items-start justify-content-between">
                                    <div class="avatar flex-shrink-0 p-2 rounded" style="background-color: #e0f7fc;">
                                        <img src="tools/assets/img/icons/unicons/service.png" alt="chart success" class="rounded" />
                                    </div>
                                </div>
                                <div class="d-flex justify-content-between">
                                    <h3 class="fw-medium d-block mb-1 text-white">Services</h3>
                                    <h4 class="card-title mb-2 text-white">{{ $appCount }} / {{ $servicesCount }}</h4>
                                </div>
                                <p>Accounts you are connected in</p>
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
                                        <img src="tools/assets/img/icons/unicons/post.png" alt="post" class="rounded" />
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
                                        <img src="tools/assets/img/icons/unicons/post.png" alt="post" />
                                    </div>
                                </div>
                                <div class="d-flex justify-content-between">
                                    <h3 class="fw-medium d-block mb-1">posts</h3>
                                    <h4 class="card-title mb-2">{{ $allPosts }}</h4>
                                </div>
                                <p>All posts you have</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        <div class="row">

            <!-- Expense Overview -->
            <div class="col-md-6 col-lg-12 order-1 mb-4">
                <div id="calendar"></div>

                {{-- <div class="card h-100">
                    <div class="card-header">
                        <ul class="nav nav-pills" role="tablist">
                            <li class="nav-item">
                                <button
                                type="button"
                                class="nav-link active"
                                role="tab"
                                data-bs-toggle="tab"
                                data-bs-target="#navs-tabs-line-card-income"
                                aria-controls="navs-tabs-line-card-income"
                                aria-selected="true">
                                Filter applications <i class="bx bx-down-arrow-alt"></i>
                                </button>
                            </li>
                        </ul>
                    </div>
                    <div class="card-body px-0">
                        <div class="tab-content p-0">
                        <div class="tab-pane fade show active" id="navs-tabs-line-card-income" role="tabpanel">
                            <div id="incomeChart"></div>
                            <div class="d-flex justify-content-center pt-4 gap-2">
                            <div class="flex-shrink-0">
                                <div id="expensesOfWeek"></div>
                            </div>
                            <div>
                                <p class="mb-n1 mt-1">Expenses This Week</p>
                                <small class="text-muted">$39 less than last week</small>
                            </div>
                            </div>
                        </div>
                        </div>
                    </div>
                </div> --}}
            </div>

        </div>
    </div>
    <!-- / Content -->

    <div class="content-backdrop fade"></div>
</div>
<!-- Content wrapper -->

@endsection
