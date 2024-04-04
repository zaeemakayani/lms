@extends('layout.app')

@section('styles')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
<style>
    .badge {
        padding: 5px 10px;
        border-radius: 20px;
        font-size: 14px;
        font-weight: 600;
    }

    .bg-red {
        background-color: red;
        color: white;
    }

    .bg-green {
        background-color: green;
        color: white;
    }

    .bg-yellow {
        background-color: orange;
        color: white;
    }

    .bg-blue {
        background-color: blue;
        color: white;
    }
</style>
@endsection

@section('content')

<main class="content">
    <div class="container-fluid p-0">
        <div class="head-section row my-2">
            <div class="col-6">
                <h1 class="h3 mb-3">Permission Modules</h1>
            </div>
            <div class="col-6 d-flex justify-content-end">
                <a href="{{route('roles.create')}}" class="btn btn-primary mx-2 py-2">
                    Create New Module
                </a>
            </div>
        </div>
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-12">
                        <table class="table">
                            <thead>
                                <th>SR#</th>
                                <th>Name</th>
                                <th>Actions</th>
                            </thead>
                        </table>
                        <tbody>
                            @php $srno = 0; @endphp
                            @foreach ($roles as $role)
                            <tr>
                                <td>{{$srno++}}</td>
                                <td>{{$role->name}}</td>
                                <td>
                                    <a class="nav-link d-none d-sm-inline-block" href="#" data-bs-toggle="dropdown">
                                        <i class="align-middle" data-feather="more-vertical"></i>
                                    </a>
                                    <div class="dropdown-menu dropdown-menu-end">
                                        <a class="dropdown-item" href="roles/{{$row->id}}/edit">
                                            <i class="align-middle me-1 text-primary" data-feather="edit">
                                            </i>
                                            Edit Role
                                        </a>
                                        <a class="dropdown-item" href="roles/{{$row->id}}/delete">
                                            <i class="align-middle me-1 text-danger" data-feather="trash-2">
                                            </i>
                                            Delete Role
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

@endsection

@section('scripts')
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/feather-icons/dist/feather.min.js"></script>
<script>
    $(document).ready(function() {
        var table = $('table').DataTable();
    });
</script>
@endsection