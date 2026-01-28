@extends('layouts.header')
@section('content')
<div class="main-content">
    <div class="page-content">
        <div class="container-fluid">
            <h4>Assign Role Permissions</h4>
            <form method="POST" action="{{ route('permissions.store') }}">
                @csrf
                <div class="mb-3">
                    <label>Select Role:</label>
                    <select name="role_id" class="form-control" required>
                        <option value="">-- Select Role --</option>
                        @foreach($roles as $role)
                        <option value="{{ $role->id }}">{{ $role->name }}</option>
                        @endforeach
                    </select>
                </div>

                <table class="table table-bordered text-center align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Module</th>
                            <th>View</th>
                            <th>Add</th>
                            <th>Edit</th>
                            <th>Delete</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach(['Invoice','WorkOrder','MachineRecord','Customer','MaterialOrder'] as $module)
                        <tr>
                            <td>{{ $module }}</td>
                            <td><input type="checkbox" name="permissions[{{ $module }}][]" value="view"></td>
                            <td><input type="checkbox" name="permissions[{{ $module }}][]" value="add"></td>
                            <td><input type="checkbox" name="permissions[{{ $module }}][]" value="edit"></td>
                            <td><input type="checkbox" name="permissions[{{ $module }}][]" value="delete"></td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>

                <button class="btn btn-success">Save Permissions</button>
            </form>
        </div>
    </div>
</div>
@endsection