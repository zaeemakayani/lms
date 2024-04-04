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

    /* CSS for zoom effect on hover */
    .zoomable-img {
        transition: transform 0.3s;
        /* Add a smooth transition */
    }

    .zoomable-img:hover {
        transform: scale(3);
        /* Zoom in by 20% on hover */
    }
</style>
@endsection

@section('content')

<main class="content">
    <div class="container-fluid p-0">
        <div class="head-section row my-2">
            <div class="col-6">
                <h1 class="h3 mb-3">All Brands</h1>
            </div>
            <div class="col-6 d-flex justify-content-end">
                <a href="{{route('brands.create')}}" class="btn btn-primary mx-2 py-2">
                    Create New
                </a>
                <a href="#" class="btn btn-danger py-2">
                    Trashed
                </a>
            </div>
        </div>
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-12">
                        <table class="table hover cell-border display nowrap" style="width: 100%;">
                            <thead>
                                <th>SR#</th>
                                <th>Name</th>
                                <th>Seller</th>
                                <th>Active Status</th>
                                <th>Created By</th>
                                <th>Actions</th>
                            </thead>
                        </table>
                        <tbody>

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
        var table = $('table').DataTable({
            processing: true,
            serverSide: true,
            scrollX: true,
            ajax: {
                url: "{{route('brands')}}",
            },
            columns: [{
                    name: 'sr_no',
                    data: 'sr_no'
                },
                {
                    name: 'name',
                    data: 'name'
                },
                {
                    name: 'seller',
                    data: 'seller'
                },
                {
                    className: 'text-center',
                    name: 'active_status',
                    data: 'active_status'
                },
                {
                    className: 'text-center',
                    name: 'created_by',
                    data: 'created_by'
                },
                {
                    className: 'text-center',
                    name: 'actions',
                    data: 'actions'
                },
            ],
            createdRow: function(row, data, dataIndex) {
                var index = dataIndex + 1; // Start from 1
                $('td', row).eq(0).text(index); // Update the first cell of the row
            }
        });
        // After initializing DataTables, call feather.replace()
        table.on('draw', function() {
            feather.replace();
        });
    });
</script>
@endsection