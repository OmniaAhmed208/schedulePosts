@extends('layouts.layoutAdminSocial')

@section('content')

  @php
    $allUsers = App\Models\User::all();
    $roles = Spatie\Permission\Models\Role::all();
    $user_roles = Illuminate\Support\Facades\DB::table('user_has_roles')->get();
  @endphp

 <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <section class="content allUsers">

            <div class="d-flex justify-content-between align-items-center px-4">
                <h4 class="my-4" style="font-weight: bold;"><i class="nav-icon fas fa-users mr-3"></i> All Users</h4>
                <a href="{{ route('rolePermission') }}" class="btn text-white" style="background-color: #79DAE8;font-size:18px">
                    <i class="fas fa-plus-circle mr-2"></i>
                    Add permissions
                </a>
              </div>

            <div class="row p-4">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <table id="example1" class="table table-bordered table-striped">
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
                                            <td><a href="{{ route('userDashboard',$user->id) }}">{{ $user['name'] }}</a></td>
                                            <td>{{ $user['email'] }}</td>
                                            <td>
                                                @foreach ($user_roles as $user_role)
                                                    @if ($user_role->user_id == $user->id)
                                                        @foreach ($roles as $role)
                                                            @if ($role->id == $user_role->role_id)
                                                                <span class="text-uppercase badge bg-label-{{ $role->role_color }}">{{ $role->name }}</span>
                                                            @endif
                                                        @endforeach
                                                    @endif
                                                @endforeach
                                            </td>
                                            <td>
                                                <span data-target="#addRolePermission" data-toggle="modal" data-userId={{ $user->id }} style="color: #06283D"><i class="far fa-edit"></i></span>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th>#</th>
                                        <th>NAME</th>
                                        <th>EMAIL</th>
                                        <th>ASSIGNED TO</th>
                                        <th>ACTION</th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>


            <!-- Add addRolePermission Modal -->
            <div class="modal fade" id="addRolePermission" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-lg modal-simple modal-dialog-centered modal-add-RolePermission">
                    <div class="modal-content p-3 p-md-3">
                        <div class="modal-body">
                            <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button>
                            <div class="text-center mb-4">
                                <h3 class="title">Add Role</h3>
                                <p>Role you may use and assign to your users.</p>
                            </div>
                            <!-- Add addRolePermission form -->
                            <form id="addRolePermissionUserForm" class="row g-3" action="" method="post">
                                @csrf                                
                                @foreach ($roles as $role)
                                    <div class="col-sm-6 col-md-4">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="roles[]" value="{{ $role->id }}" id="{{ $role->id }}">
                                            <label class="form-check-label" for="{{ $role->id }}">
                                                {{ $role->name }}
                                            </label>
                                        </div>
                                    </div>
                                @endforeach

                                <div class="col-12 text-center">
                                    <button type="submit" class="btn btn-info me-sm-3 me-1">Submit</button>
                                    <button type="reset" class="btn text-white" style="background-color: #BEBEBE">Reset</button>
                                </div>
                            </form>
                            <!--/ Add addRolePermission form -->
                        </div>
                    </div>
                </div>
            </div>
            <!--/ Add addRolePermission Modal -->
            
            
        </section>
      </div>
  </div>

@endsection



<script>
    document.addEventListener('DOMContentLoaded', function () {
        let roleBtns = document.querySelectorAll('[data-toggle="modal"]');
        var form = document.getElementById('addRolePermissionUserForm');

        roleBtns.forEach(btn => {
            btn.addEventListener('click', () => {
                var userId = btn.getAttribute('data-userId');
                var url = "{{ route('rolePermissionUser', ['userId' => '__id__']) }}";
                var finalUrl = url.replace('__id__', userId);
                form.action = finalUrl;

                // Clear all checkboxes in the modal
                const checkboxes = document.querySelectorAll('#addRolePermission input[type="checkbox"]');
                checkboxes.forEach(checkbox => {
                    checkbox.checked = false;
                });

                // Populate checkboxes based on userRoles
                const userRoles = <?php echo json_encode($user_roles); ?>;
                const checkboxesForUser = document.querySelectorAll(`#addRolePermission input[type="checkbox"][name="roles[]"]`);
                checkboxesForUser.forEach(checkbox => {
                    const roleId = checkbox.value;
                    const userHasRole = userRoles.find(userRole => userRole.user_id == userId && userRole.role_id == roleId);
                    if (userHasRole) {
                        checkbox.checked = true;
                    }
                });
            });
        });
    });
</script>
