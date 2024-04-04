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
                <h1 class="h3 mb-3">
                    @isset($brand) {{$brand->name}}'s Products @endisset
                </h1>
            </div>
            <div class="col-6 d-flex justify-content-end">
                <a href="#" class="btn btn-danger py-2">
                    Trashed
                </a>
            </div>
        </div>
        <div class="card">
            <div class="card-header">
                <div class="row">
                    <div class="col-4">
                        <label for="products">Select Product to Add</label>
                        <select name="products" id="product_id" class="form-control">
                            <option value="">Select Product to Add</option>
                            @if (!empty($allProducts))
                            @foreach ($allProducts as $product)
                            <option value="{{$product->id}}">{{$product->name}}</option>
                            @endforeach
                            @endif
                        </select>
                    </div>
                </div>
            </div>
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
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    $(document).ready(function() {
        var brandId = '{{ $id }}';
        var brandProductsURL = "{{ route('brand.products', ':brandId') }}".replace(':brandId', brandId);
        var table = $('table').DataTable({
            processing: true,
            serverSide: true,
            scrollX: true,
            ajax: {
                url: brandProductsURL,
            },
            columns: [{
                    name: 'sr_no',
                    data: 'sr_no'
                },
                {
                    className: 'text-center',
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
        $(document).on('change', '#product_id', function() {
            var _this = $(this);
            if (_this.val() != '') {
                Swal.fire({
                    title: 'Are you sure you want to add?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#dc3545',
                    confirmButtonText: 'Yes, add this product!',
                    customClass: {
                        confirmButton: 'btn btn-primary me-1',
                        cancelButton: 'btn btn-danger'
                    },
                    buttonsStyling: false
                }).then(function(result) {
                    if (result.value) {
                        var createBrandProductsURL = "{{ route('brand.products.create', ':brandId') }}".replace(':brandId', brandId);
                        $.ajax({
                            url: createBrandProductsURL,
                            method: "POST",
                            data: {
                                _token: "{{csrf_token()}}",
                                product_id: _this.val()
                            },
                            success:function(response) {
                                if (response.status == true) {
                                    Swal.fire({
                                        icon: 'success',
                                        title: 'Added!',
                                        text: response.message,
                                        customClass: {
                                            confirmButton: 'btn btn-success'
                                        }
                                    });
                                    setTimeout(function() {
                                        window.location.reload();
                                    }, 2000);
                                } else {
                                    Swal.fire({
                                        icon: 'danger',
                                        title: 'Alert!',
                                        text: response.message,
                                        customClass: {
                                            confirmButton: 'btn btn-danger'
                                        }
                                    });
                                }
                            }, error:function(response) {
                                    Swal.fire({
                                        icon: 'danger',
                                        title: 'Alert!',
                                        text: 'Something went wrong.',
                                        customClass: {
                                            confirmButton: 'btn btn-danger'
                                        }
                                    });
                            }
                        });
                    }
                });
            }
        });
        $(document).on('click', '.delete-brand-product', function() {
            var _this = $(this);
            var productId = _this.attr('data-product-id');
            if (productId != '') {
                Swal.fire({
                    title: 'Are you sure you want to delete?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#dc3545',
                    confirmButtonText: 'Yes, delete this product!',
                    customClass: {
                        confirmButton: 'btn btn-primary me-1',
                        cancelButton: 'btn btn-danger'
                    },
                    buttonsStyling: false
                }).then(function(result) {
                    if (result.value) {
                        var createBrandProductsURL = "{{ route('brand.products.delete', ':brandId') }}".replace(':brandId', brandId);
                        $.ajax({
                            url: createBrandProductsURL,
                            method: "POST",
                            data: {
                                _token: "{{csrf_token()}}",
                                product_id: productId
                            },
                            success:function(response) {
                                if (response.status == true) {
                                    Swal.fire({
                                        icon: 'success',
                                        title: 'Added!',
                                        text: response.message,
                                        customClass: {
                                            confirmButton: 'btn btn-success'
                                        }
                                    });
                                    setTimeout(function() {
                                        table.draw(false);
                                    }, 2000);
                                } else {
                                    Swal.fire({
                                        icon: 'danger',
                                        title: 'Alert!',
                                        text: response.message,
                                        customClass: {
                                            confirmButton: 'btn btn-danger'
                                        }
                                    });
                                }
                            }, error:function(response) {
                                Swal.fire({
                                    icon: 'danger',
                                    title: 'Alert!',
                                    text: 'Something went wrong.',
                                    customClass: {
                                        confirmButton: 'btn btn-danger'
                                    }
                                });
                            }
                        });
                    }
                });
            }
        });
    });
</script>
@endsection