@extends('layouts.header')
@section('content')

<div class="main-content">
    <div class="page-content">
        <div class="container-fluid">

            <div class="card shadow-sm">
                <div class="card-header bg-light">
                    <h4 class="card-title mb-0">User with Role Permissions</h4>
                </div>
                <div class="card-body">
                    @if(session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                    @endif

                    <form method="POST" action="{{ route('Store') }}">
                        @csrf
                        <div class="row g-3">
                            <div class="col-md-4">
                                <label>Role</label>
                                <select name="role_id" id="role_id" class="form-select" required>
                                    <option value="">Select Role</option>

                                    @foreach ($roles as $role)
                                    @if ($role->id != 1) {{-- SuperAdmin hide --}}
                                    <option value="{{ $role->id }}"
                                        {{ $role->id == 2 ? 'selected' : '' }}>
                                        {{ $role->name }}
                                    </option>
                                    @endif
                                    @endforeach
                                </select>
                            </div>
                        </div>


                        <h5 class="mt-4">Role Permissions</h5>
                        <table class="table table-bordered text-center mt-3">
                            <thead class="table-light">
                                <tr>
                                    <th>Module</th>
                                    <th>Add</th>
                                    <th>View</th>
                                    <th>Edit</th>
                                    <th>Delete</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach(['dashboard','Operator','Machine',
                                'Setting','Hsncode','MaterialType',
                                'FinancialYear','UserAdmin','Customer',
                                'Vendors','Projects','WorkOrders','SetupSheet',
                                'MachineRecord','MaterialReq','MaterialOrder',
                                'Invoice','Subscription'] as $module)
                                <tr>
                                    <td>{{ $module }}</td>
                                    <td><input type="checkbox" class="perm" name="permissions[{{ $module }}][]" value="view" checked></td>
                                    <td><input type="checkbox" class="perm" name="permissions[{{ $module }}][]" value="add" checked></td>
                                    <td><input type="checkbox" class="perm" name="permissions[{{ $module }}][]" value="edit" checked></td>
                                    <td><input type="checkbox" class="perm" name="permissions[{{ $module }}][]" value="delete" checked></td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>

                        <div class="mt-3">
                            <button type="submit" class="btn btn-primary">Save Permissions</button>
                        </div>
                    </form>

                </div>
            </div>

        </div>
    </div>
</div>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
    $(document).ready(function() {

        function loadPermissions() {
            let role_id = $("#role_id").val();

            $(".perm").prop("checked", false);

            if (!role_id) return;

            $.ajax({
                url: "/get-role-permissions/" + role_id,
                type: "GET",
                success: function(response) {

                    if (response.status) {
                        let permissions = response.permissions;

                        Object.keys(permissions).forEach(function(module) {
                            permissions[module].forEach(function(action) {
                                $(`input[name="permissions[${module}][]"][value="${action}"]`)
                                    .prop("checked", true);
                            });
                        });
                    }
                }
            });
        }
        loadPermissions();

        $("#role_id").change(function() {
            loadPermissions();
        });

    });
</script>

@endsection