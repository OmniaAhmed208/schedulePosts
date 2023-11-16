@extends('layouts.layout')

@section('content')

 <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">
                <section class="content py-4">

                    <div class="d-flex justify-content-between align-items-center px-4">
                        <h4 class="my-4"><i class="fas fa-shield-alt"></i><span class="mx-2">Roles and permissions</span></h4>
                    </div>
        
                    {{-- roles --}}
                    <div class="row">
                        <div class="col-lg">

                            <div class="card mb-5">
                                <div class="card-header d-flex justify-content-between align-items-center">
                                    <h5 class="mb-0"></h5>
                                    <small class="text-muted float-end">
                                        <button class="btn text-white" style="background: #06283d"
                                        type="button" data-bs-toggle="modal" data-bs-target="#addRoleModal">
                                            Add Role
                                        </button>
                                    </small>
                                </div>
                                <div class="card-body">
                                    <table id="example2" class="table table-hover">
                                        <thead>
                                            <tr>
                                                <th><b>Roles</b></th>
                                                <th><b>Permissions</b></th>
                                                <th><b>Action</b></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($roles as $role)
                                                <tr>
                                                    <td>
                                                        <span class="badge bg-label-{{ $role->color }} me-1">{{ $role->name }}</span>
                                                    </td>
                                                    <td>
                                                        <div class="row">
                                                            @foreach ($role_has_permissions as $item)
                                                                @if ($role->id == $item->role_id)
                                                                    @php
                                                                        $permission = $permissions->find($item->permission_id)->name;
                                                                    @endphp
                                                        
                                                                    <div class="col-md-4 p-2">{{ $permission }}</div>
                                                        
                                                                    @if (($loop->index + 1) % 4 == 0 || $loop->last)
                                                                        </div>
                                                                        @if (!$loop->last)
                                                                            <div class="row">
                                                                        @endif
                                                                    @endif
                                                                @endif
                                                            @endforeach
                                                        </div>                                                    
                                                    </td>
                                                    <td>
                                                        <div class="dropdown">
                                                            <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                                                            <i class="bx bx-dots-vertical-rounded"></i>
                                                            </button>
                                                            <div class="dropdown-menu">
                                                                <a class="dropdown-item" href="javascript:void(0);" 
                                                                data-bs-toggle="modal" data-bs-target="#editRoleModal" data-role="{{$role}}">
                                                                    <i class="bx bx-edit-alt me-1"></i> Edit role name
                                                                </a>

                                                                <a class="dropdown-item" href="javascript:void(0);" 
                                                                data-bs-toggle="modal" data-bs-target="#roleToPermissionsModal" data-role="{{$role}}">
                                                                    <i class="bx bx-edit me-1"></i> Assign role to permissions
                                                                </a>
                                                            </div>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endforeach

                                        </tbody>
                                    </table>
                                </div>
                            </div>

                        </div>
                    </div>

                    {{-- permissions --}}
                    <div class="row">
                        <div class="col-lg">

                            <div class="card">
                                <div class="card-header d-flex justify-content-between align-items-center">
                                    <h5 class="mb-0"></h5>
                                    <small class="text-muted float-end">
                                        <button class="btn text-white" style="background: #06283d"
                                        type="button" data-bs-toggle="modal" data-bs-target="#addPermissionModal">
                                            Add Permission
                                        </button>
                                    </small>
                                </div>
                                <div class="card-body">
                                    <table id="permissionTable" class="table table-hover">
                                        <thead>
                                            <tr>
                                                <th><b>Pages</b></th>
                                                <th><b>Permissions</b></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($pages as $page)
                                                <tr>
                                                    <td>{{ $page }}</td>
                                                    <td>
                                                        @php $counter = 0; @endphp

                                                        @foreach($permissions as $permission)
                                                            @if (strpos($permission->name, $page) === 0)
                                                                @if ($counter % 3 == 0)
                                                                    <div class="row">
                                                                @endif
                                                                <div class="col-md-3 px-4">
                                                                    {{ $permission->name }}
                                                                </div>
                                                                <div class="col-1">
                                                                    <button class="btn" data-bs-toggle="modal" data-bs-target="#editPermissionModal" 
                                                                    data-permission="{{ $permission }}">
                                                                        <i class="bx bx-edit"></i>
                                                                    </button>
                                                                </div>
                                                                @php $counter++; @endphp
                                                                @if ($counter % 3 == 0)
                                                                    </div>
                                                                @endif
                                                            @endif
                                                        @endforeach

                                                        @if ($counter % 3 != 0)
                                                            </div>
                                                        @endif
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                        </div>
                    </div>

                    {{-- add role --}}
                    <div class="modal fade" id="addRoleModal" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                <h5 class="modal-title">Add new role</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>

                                <form id="addRoleForm" action="{{ route('roles.store') }}" method="post" enctype="multipart/form-data">
                                    @csrf
                                    <div class="modal-body">
                                        <div class="row">
                                            <div class="col mb-3">
                                                <label class="form-label" for="modalRoleName">Role Name</label>
                                                <input type="text" id="newRole" name="role" required class="form-control" tabindex="-1" />
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col mb-3">
                                                <label class="form-label" for="modalRoleColor">Role Color</label>
                                                <div class="colors d-flex flex-wrap mb-3"></div>
                                                <input type="hidden" id="roleColor" name="color"/>
                                            </div>
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

                    {{-- edit role --}}
                    <div class="modal fade" id="editRoleModal" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                <h5 class="modal-title">Edit Role</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>

                                <form id="editRoleForm" action="" method="post" enctype="multipart/form-data">
                                    @csrf
                                    @method('put')
                                    <div class="modal-body">
                                        <div class="row">
                                            <div class="col mb-3">
                                                <label class="form-label" for="modalRoleName">Role Name</label>
                                                <input type="text" id="roleName" name="role" required class="form-control" tabindex="-1" />
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col mb-3">
                                                <label class="form-label" for="modalRoleColor">Role Color</label>
                                                <div class="colors d-flex flex-wrap mb-3"></div>
                                                <input type="hidden" id="roleColor" name="color"/>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="reset" class="btn btn-outline-secondary" data-bs-dismiss="modal"> Cancel </button>
                                        <button type="submit" class="btn btn text-white" style="background: #79DAE8">Update</button>
                                    </div>
                                </form>

                            </div>
                        </div>
                    </div>


                    {{-- add permission --}}
                    <div class="modal fade" id="addPermissionModal" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                <h5 class="modal-title">Add New Permission</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>

                                <form id="addPermissionForm" action="{{ route('permissions.store') }}" method="post" enctype="multipart/form-data">
                                    @csrf
                                    <div class="modal-body">
                                        <div class="row">
                                            <div class="col mb-3">
                                                <label class="form-label" for="modalPermissionName">Permission Name</label>
                                                <input type="text" id="newPermission" name="permission" required class="form-control" placeholder="Enter permission name" tabindex="-1" />
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="reset" class="btn btn-outline-secondary"> Cancel </button>
                                        <button type="submit" class="btn btn text-white" style="background: #79DAE8">Save</button>
                                    </div>
                                </form>

                            </div>
                        </div>
                    </div>   
                    
                    {{-- edit permission --}}
                    <div class="modal fade" id="editPermissionModal" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                <h5 class="modal-title">Edit Permission</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>

                                <form id="editPermissionForm" action="" method="post" enctype="multipart/form-data">
                                    @csrf
                                    @method('put')
                                    <div class="modal-body">
                                        <div class="row">
                                            <div class="col mb-3">
                                                <label class="form-label" for="modalPermissionName">Permission Name</label>
                                                <input type="text" id="permissionName" name="permission" required class="form-control" tabindex="-1" />
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="reset" class="btn btn-outline-secondary"> Cancel </button>
                                        <button type="submit" class="btn btn text-white" style="background: #79DAE8">Update</button>
                                    </div>
                                </form>

                            </div>
                        </div>
                    </div>

                    {{-- add roleToPermissions --}}
                    <div class="modal fade" id="roleToPermissionsModal" tabindex="-1" aria-hidden="true" style="background: aliceblue;">
                        <div class="modal-dialog modal-fullscreen" role="document">
                            <div class="modal-content p-5" style="background: transparent;box-shadow: 0 0 0 0;">
                                <form id="roleToPermissionsForm" action="" method="post" enctype="multipart/form-data">
                                    @csrf
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="modalFullTitle">Assign Role to Permissions for 
                                            <span id="user" style="text-dark">Ali</span>
                                        </h5>
                                        <button type="submit" class="btn btn-primary mx-4">Save changes</button>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" ></button>
                                    </div>
                                
                                    <div class="modal-body">
                                        <div class="row">
                                            <div class="col-12 mb-3">
                                                <input class="form-check-input" type="checkbox" id="allPermissions"/>
                                                <label class="form-check-label mx-2 text-dark" for="allPermissions"> Provide all permissions </label>
                                            </div>

                                            @foreach($pages as $page)
                                                <div class="col-sm-6 col-md-4 col-lg-3">
                                                    <div class="p-4 border rounded bg-white my-2">
                                                        <h5 class="text-dark">{{ $page }}</h5>
                                                        <p>
                                                            <input class="form-check-input allBox" type="checkbox" data-group="{{ $page }}" 
                                                            id="allBox_{{ $page }}"/>
                                                            <label class="form-check-label mx-2" for="allBox_{{ $page }}">All</label>
                                                        </p>
                                                        @foreach($permissions as $permission)
                                                            @if (strpos($permission->name, $page) === 0)
                                                                <p class="permissionsCheckBox">
                                                                    <input class="form-check-input permissionCheckbox" type="checkbox" 
                                                                    name="permission[]" value="{{$permission->id}}" data-group="{{ $page }}" 
                                                                    id="checkPermission{{ $permission->id }}"/>
                                                                    <label class="form-check-label mx-2" for="checkPermission{{ $permission->id }}">
                                                                        {{ $permission->name }}
                                                                    </label>
                                                                </p>
                                                            @endif
                                                        @endforeach
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </form>    
                            </div>
                        </div>
                    </div> 

                </section>
            </div>
        </div>   
    </div>


  
@endsection

<script>
    document.addEventListener('DOMContentLoaded', function () {

        // checkboxes
        const role_has_permissions = <?php echo $role_has_permissions;?>;
        const roleToPermissionsModal = document.getElementById('roleToPermissionsModal');
        const checkboxes = roleToPermissionsModal.querySelectorAll('.permissionsCheckBox input[type="checkbox"]');
        
        roleToPermissionsModal.addEventListener('show.bs.modal', function (event) {
            const button = event.relatedTarget; // Get the button that triggered the modal
            const roleData = JSON.parse(button.getAttribute('data-role'));
            console.log(roleData);
            checkboxes.forEach(function (checkbox) {
                checkbox.value = checkbox.value + ',' + roleData.id; // value="{{$permission->id}},{{ $role->id }}"

                role_has_permissions.forEach(row => {
                    const [permissionId, roleId] = checkbox.value.split(',');                 
                    if (permissionId == row['permission_id'] && roleId == row['role_id']) {
                        checkbox.checked = true;
                    }
                });
            });
        });

        roleToPermissionsModal.addEventListener('hide.bs.modal', function (event) {
            checkboxes.forEach(function (checkbox) {
                checkbox.value = checkbox.value.split(',')[0]; // save first number ==> permission_id ==> it will remove role_id
                checkbox.checked = false;
            });
        });


        const allBoxes = document.querySelectorAll('.allBox'); // that have (all) input checkbox
        
        allBoxes.forEach(function (allBox) {
            allBox.addEventListener('click', function () {
                const group = allBox.getAttribute('data-group');
                const checkboxes = document.querySelectorAll(`.permissionCheckbox[data-group=${group}]`);

                checkboxes.forEach(function (checkbox) {
                    checkbox.checked = allBox.checked;
                });
            });
        });

        const allPermissionsCheckbox =  document.getElementById('allPermissions');

        allPermissionsCheckbox.addEventListener('change', function () {
            const checkboxesModal = roleToPermissionsModal.querySelectorAll('#roleToPermissionsModal input[type="checkbox"]');
            checkboxesModal.forEach(function (checkbox) {
                checkbox.checked = allPermissionsCheckbox.checked;
            });
        });


        // colors in roles
        var colors = ['success', 'warning', 'danger', 'primary', 'info','dark'];
        var colorContainer = document.querySelector('#addRoleModal .colors');
        var colorContainerEdit = document.querySelector('#editRoleModal .colors');

        var html = '';
        colors.forEach((color,index) => 
        {
            html += `<span class="mx-2 my-1 badge bg-label-${color}" style="font-size: 90%;cursor:pointer">Role</span>`;
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

        // edit role name and color
        const roleForm = document.getElementById('editRoleForm');
        const editRoleButtons = document.querySelectorAll(' [data-bs-target="#editRoleModal"] ');

        editRoleButtons.forEach(function(button) {
            button.addEventListener('click', function() {
                const roleData = JSON.parse(button.getAttribute('data-role'));
                var url = "{{ route('roles.update', ['role' => '__id__']) }}";
                var finalUrl = url.replace('__id__', roleData.id || '');
                roleForm.action = finalUrl;

                document.querySelector('#editRoleForm #roleName').value = roleData.name;

                const colorSpan = document.querySelectorAll('#editRoleForm .colors span');
                const roleColorInput = document.querySelector('#editRoleForm #roleColor');
                const roleColor = roleData.color;

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

        
        // edit permission name
        const permissionForm = document.getElementById('editPermissionForm');
        const editPermissionButtons = document.querySelectorAll(' [data-bs-target="#editPermissionModal"] ');

        editPermissionButtons.forEach(function(button) {
            button.addEventListener('click', function() {
                const permissionData = JSON.parse(button.getAttribute('data-permission'));
                var url = "{{ route('permissions.update', ['permission' => '__id__']) }}";
                var finalUrl = url.replace('__id__', permissionData.id || '');
                permissionForm.action = finalUrl;

                document.querySelector('#editPermissionForm #permissionName').value = permissionData.name;
            });
        }); 


        // roleToPermissionsForm
        const roleToPermissionsForm = document.getElementById('roleToPermissionsForm');
        const roleToPermissionsButtons = document.querySelectorAll(' [data-bs-target="#roleToPermissionsModal"] ');
        roleToPermissionsButtons.forEach(function(button) {
            button.addEventListener('click', function() {
                const roleData = JSON.parse(button.getAttribute('data-role'));
                var url = "{{ url('assignRoleToPermissions', ['role_id' => '__id__']) }}";
                var finalUrl = url.replace('__id__', roleData.id || '');
                roleToPermissionsForm.action = finalUrl;
            });
        }); 
    });
</script>
