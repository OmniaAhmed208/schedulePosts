@extends('layouts.layout')

@section('content')

 <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <section class="content py-4">

                <div class="d-flex justify-content-between align-items-center px-4">
                    <h4 class="my-4"><i class="bx bx-user mr-3"></i> All Users</h4>
                </div>
    
                <div class="row p-4">
                    <div class="col-12">

                        <div class="card">
                            <div class="card-body">
                                <table id="example2" class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>NAME</th>
                                            <th>EMAIL</th>
                                            <th>ASSIGNED TO</th>
                                            <th>ACTION</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($allUsers as $index=>$user)
                                            <tr>
                                                <td>{{ ++$index }}</td>
                                                <td><a href="{{ route('dashboard.show',$user->id) }}">{{ $user['name'] }}</a></td>
                                                <td>{{ $user['email'] }}</td>
                                                <td>
                                                    @foreach ($user_roles as $user_role)
                                                        @if ($user_role->user_id == $user->id)
                                                            @foreach ($roles as $role)
                                                                @if ($role->id == $user_role->role_id)
                                                                    <span class="text-uppercase badge bg-label-{{ $role->color }}">{{ $role->name }}</span>
                                                                @endif
                                                            @endforeach
                                                        @endif
                                                    @endforeach
                                                </td>
                                                <td>
                                                    <span data-bs-toggle="modal" data-bs-target="#userToRoles" data-user={{ $user }} style="color: #06283D;cursor:pointer">
                                                        <i class="bx bx-edit"></i>
                                                    </span>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        
                    </div>
                </div>
    
    
                {{-- add userToRoles --}}
                <div class="modal fade" id="userToRoles" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Add role</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <p class="ms-3">Role you may use and assign to your users.</p>


                            <form id="userToRolesForm" action="" method="post" enctype="multipart/form-data">
                                @csrf
                                <div class="modal-body">
                                    <div class="row">
                                        @foreach ($roles as $role)
                                            <div class="col-sm-6 col-md-4">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" name="roles[]" value="{{ $role->id }}" 
                                                    id="role-{{ $role->id }}">
                                                    <label class="form-check-label" for="role-{{ $role->id }}">
                                                        {{ $role->name }}
                                                    </label>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="reset" class="btn btn-outline-secondary" data-bs-dismiss="modal"> Cancel </button>
                                    <button type="submit" class="btn btn text-white" style="background: #79DAE8">Save</button>
                                </div>
                            </form>

                        </div>
                    </div>
                </div>
                
            </section>
        </div>
  </div>

@endsection


<script>
    document.addEventListener('DOMContentLoaded', function () {
        let roleBtns = document.querySelectorAll('[data-bs-target="#userToRoles"]');
        var form = document.getElementById('userToRolesForm');

        roleBtns.forEach(btn => {
            btn.addEventListener('click', () => {
                var user = JSON.parse(btn.getAttribute('data-user'));
                var url = "{{ url('assignUserToRoles', ['userId' => '__id__']) }}";
                var finalUrl = url.replace('__id__', user.id || '');
                form.action = finalUrl;

                // Clear all checkboxes in the modal
                const checkboxes = document.querySelectorAll('#userToRoles input[type="checkbox"]');
                checkboxes.forEach(checkbox => {
                    checkbox.checked = false;
                });

                // Populate checkboxes based on userRoles
                const userRoles = <?php echo json_encode($user_roles); ?>;
                const checkboxesForUser = document.querySelectorAll(`#userToRoles input[type="checkbox"][name="roles[]"]`);
                checkboxesForUser.forEach(checkbox => {
                    const roleId = checkbox.value;
                    const userHasRole = userRoles.find(userRole => userRole.user_id == user.id && userRole.role_id == roleId);
                    if (userHasRole) {
                        checkbox.checked = true;
                    }
                });
            });
        });
    });
</script>
