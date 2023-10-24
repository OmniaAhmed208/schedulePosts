@extends('layouts.layoutAdminSocial')

@section('content')

  @php
    $roles = Spatie\Permission\Models\Role::all();
    $permissions = Spatie\Permission\Models\Permission::all();
    $role_has_permissions = Illuminate\Support\Facades\DB::table('role_has_permissions')->get();
  @endphp


 <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <section class="content allUsers">

            <div class="d-flex justify-content-between align-items-center px-4">
                <h4 class="mt-4" style="font-weight: bold;"><i class="nav-icon mr-3 fas fa-shield-alt"></i>Roles and permissions</h4>
            </div>

            <div class="d-flex justify-content-end">
                <button data-target="#addRoleModal" data-toggle="modal" class="btn btn-info mb-3 mr-3 text-nowrap add-new-role">Add New Role</button>
                <button data-target="#addPermissionModal" data-toggle="modal" class="btn btn-info mb-3 text-nowrap add-new-permission">Add New Permission</button>
            </div>

            <form  action="{{route('role.permission.store')}}" method="post" id="addRoleForm" class="row g-3 p-3 mt-3 rounded shadow-sm bg-white">
                @csrf
                <div class="col-12">
                    <!-- Permission table -->
                    <div class="table-responsive">
                        <table class="table table-flush-spacing">
                            <tbody>
                                <tr>
                                    <td class="border-0"><b>Roles</b></td>
                                    <td class="border-0"><b>Permissions</b></td>
                                </tr>
                                @foreach ($roles as $role)
                                    <tr>
                                        <td>
                                            <span text-capitalize>{{ $role['name'] }}</span>
                                        </td>
                                        @foreach ($permissions as $permission)
                                            <td>
                                                <div class="form-check me-3 me-lg-5">
                                                    <input class="form-check-input" type="checkbox" name="permission[]" value="{{$permission->id}},{{ $role->id }}" id="checkPermission{{ $permission['id'] }}"/>
                                                    <label class="form-check-label" for="checkPermission{{ $permission['id'] }}">
                                                    {{ $permission['name'] }}
                                                    </label>
                                                </div>
                                            </td>
                                        @endforeach
                                        <td>
                                            <span data-target="#editRole" data-toggle="modal" data-roleId={{ $role->id }} data-roleName={{ $role->name }} data-roleColor={{ $role->role_color }} style="color: #06283D;cursor:pointer"><i class="far fa-edit"></i></span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <!-- Permission table -->
                </div>
                <div class="col-12 text-center">
                  <button type="submit" class="btn btn-info me-sm-3 me-1">Submit</button>
                  <button type="reset" class="btn text-white" style="background-color: #BEBEBE" data-dismiss="modal" aria-label="Close">Reset</button>
                </div>
            </form>
       
            <!-- Add Role Modal -->
            <div class="modal fade" id="addRoleModal" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-lg modal-simple modal-dialog-centered modal-add-new-role">
                    <div class="modal-content p-3 p-md-3">
                        <div class="modal-body">
                            <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button>
                            <div class="text-center mb-4">
                                <h3 class="role-title">Add New Role</h3>
                                <p>Set role permissions</p>
                            </div>
                            <!-- Add role form -->
                            <form id="addRoleForm" class="row g-3" action="{{route('role.store')}}" method="post">
                                @csrf
                                <div class="col-12 mb-4">
                                    <label class="form-label" for="modalRoleName">Role Name</label>
                                    <input type="text" id="newRole" name="newRole" required class="form-control" placeholder="Enter a role name" tabindex="-1" />
                                </div>

                                <div class="col-12 mb-4">
                                    <label class="form-label" for="modalRoleColor">Role Color</label>
                                    <div class="colors d-flex flex-wrap mb-3"></div>
                                    <input type="hidden" id="roleColor" name="roleColor"/>
                                </div>
                                <div class="col-12 text-center">
                                    <button type="submit" class="btn btn-info me-sm-3 me-1">Submit</button>
                                    <button type="reset" class="btn text-white" style="background-color: #BEBEBE">Reset</button>
                                </div>
                            </form>
                            <!--/ Add role form -->
                        </div>
                    </div>
                </div>
            </div>
            <!--/ Add Role Modal -->


            <!-- Add permission Modal -->
            <div class="modal fade" id="addPermissionModal" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-lg modal-simple modal-dialog-centered modal-add-new-permission">
                    <div class="modal-content p-3 p-md-3">
                        <div class="modal-body">
                            <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button>
                            <div class="text-center mb-4">
                                <h3 class="permission-title">Add New Permission</h3>
                                <p>Permissions you may use and assign to your users.</p>
                            </div>
                            <!-- Add permission form -->
                            <form id="addPermissionForm" class="row g-3" action="{{route('permission.store')}}" method="post">
                                @csrf
                                <div class="col-12 mb-4">
                                    <label class="form-label" for="modalPermissionName">Permission Name</label>
                                    <input type="text" id="newPermission" name="newPermission" required class="form-control" placeholder="Enter a permission name" tabindex="-1" />
                                </div>
                                <div class="col-12 text-center">
                                    <button type="submit" class="btn btn-info me-sm-3 me-1">Create Permission</button>
                                    <button type="reset" class="btn text-white" style="background-color: #BEBEBE">Reset</button>
                                </div>
                            </form>
                            <!--/ Add permission form -->
                        </div>
                    </div>
                </div>
            </div>
            <!--/ Add permission Modal -->

             <!-- Edit Role Modal -->
             <div class="modal fade" id="editRole" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-lg modal-simple modal-dialog-centered modal-add-new-role">
                    <div class="modal-content p-3 p-md-3">
                        <div class="modal-body">
                            <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button>
                            <div class="text-center mb-4">
                                <h3 class="role-title">Edit Role</h3>
                            </div>
                            <!-- Add role form -->
                            <form id="addRoleForm" class="row g-3" action="{{route('role.update')}}" method="post">
                                @csrf
                                @method('put')
                                <input type="hidden" id="roleId" name="roleId"/>
                                <div class="col-12 mb-4">
                                    <label class="form-label" for="modalRoleName">Role Name</label>
                                    <input type="text" id="roleName" name="roleName" required class="form-control" placeholder="Enter a role name" tabindex="-1" />
                                </div>

                                <div class="col-12 mb-4">
                                    <label class="form-label" for="modalRoleColor">Role Color</label>
                                    <div class="colors d-flex flex-wrap mb-3"></div>
                                    <input type="hidden" id="roleColor" name="roleColor"/>
                                </div>
                                <div class="col-12 text-center">
                                    <button type="submit" class="btn btn-info me-sm-3 me-1">Update</button>
                                    <button type="reset" class="btn text-white" style="background-color: #BEBEBE">Reset</button>
                                </div>
                            </form>
                            <!--/ Add role form -->
                        </div>
                    </div>
                </div>
            </div>
            <!--/ Edit Role Modal -->

        </section>
      </div>
  </div>

@endsection


<script>
    document.addEventListener('DOMContentLoaded', function () {
        const checkboxes = document.querySelectorAll('input[type="checkbox"]');
        const role_has_permissions = <?php echo $role_has_permissions;?>;

        checkboxes.forEach(function (checkbox) {
            role_has_permissions.forEach(row => {
                const [permissionId, roleId] = checkbox.value.split(',');
                if (permissionId == row['permission_id'] && roleId == row['role_id']) {
                    checkbox.checked = true;
                }
            });
        });

        // colors
        var colors = ['success', 'warning', 'danger', 'primary', 'info','dark'];
        var colorContainer = document.querySelector('#addRoleModal .colors');
        var colorContainerEdit = document.querySelector('#editRole .colors');

        var html = '';
        colors.forEach((color,index) => 
        {
            html += `<span class="mr-2 badge bg-label-${color}" style="font-size: 90%;cursor:pointer">Role</span>`;
        });
        colorContainer.innerHTML += html;
        colorContainerEdit.innerHTML += html;

        const colorSapn = colorContainer.querySelectorAll('span');
        colorSapn.forEach(span=>{
            var str = span.getAttribute('class');
            var match = str.match(/bg-label-(\w+)/);
            var colorName = match[1];

            span.addEventListener('click', ()=>{
                colorSapn.forEach(function(item) {
                    item.classList.remove('border', `border-${colorName}`);
                });
                span.classList.add('border', `border-${colorName}`);
                const roleColor = document.getElementById('roleColor');
                roleColor.value = colorName;
            });
        });


        let editRoleBtn = document.querySelectorAll('[data-target="#editRole"]');
        editRoleBtn.forEach(btn=>{
            btn.addEventListener('click', ()=>{
                var roleId = btn.getAttribute('data-roleId');
                var roleName = btn.getAttribute('data-roleName');
                var roleColor = btn.getAttribute('data-roleColor');

                var roleIdInput = document.getElementById('roleId');
                roleIdInput.value = roleId;

                var roleNameInput = document.getElementById('roleName');
                roleNameInput.value = roleName;

                colorSpan = document.querySelectorAll('#editRole .colors span');
                const roleColorInput = document.querySelector('#editRole #roleColor');
                colorSpan.forEach(span=>{
                    var str = span.getAttribute('class');
                    var match = str.match(/bg-label-(\w+)/);
                    var colorName = match[1];
                    span.classList.remove('border', `border-${colorName}`);

                    if(roleColor == colorName)
                    {
                        span.classList.add('border', `border-${colorName}`);
                        roleColorInput.value = colorName;
                    }


                    span.addEventListener('click', ()=>{
                        colorSpan.forEach(function(item) {
                            item.classList.remove('border', `border-${colorName}`);
                        });
                        span.classList.add('border', `border-${colorName}`);
                        roleColorInput.value = colorName;
                    });
                });
            });
        });


    });
</script>