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
                <h1 class="h3 mb-3">All Products</h1>
            </div>
            <div class="col-6 d-flex justify-content-end">
                <a href="{{route('products.create')}}" class="btn btn-primary mx-2 py-2">
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
                                <th>Image</th>
                                <th>Name</th>
                                <th>Category</th>
                                <th>Weight</th>
                                <th>Unit Cost</th>
                                <th>Shipping Cost</th>
                                <th>Total Cost</th>
                                <th>Selling Cost</th>
                                <th>Qty</th>
                                <th>Active Status</th>
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
                url: "{{route('products')}}",
            },
            columns: [{
                    name: 'sr_no',
                    data: 'sr_no'
                },
                {
                    className: 'd-flex justify-content-center',
                    name: 'image',
                    data: 'image'
                },
                {
                    name: 'name',
                    data: 'name'
                },
                {
                    name: 'category',
                    data: 'category'
                },
                {
                    name: 'weight',
                    data: 'weight'
                },
                {
                    name: 'unit_cost',
                    data: 'unit_cost'
                },
                {
                    name: 'shipping_cost',
                    data: 'shipping_cost'
                },
                {
                    name: 'total_cost',
                    data: 'total_cost'
                },
                {
                    name: 'selling_cost',
                    data: 'selling_cost'
                },
                {
                    name: 'qty',
                    data: 'qty'
                },
                {
                    name: 'active_status',
                    data: 'active_status'
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