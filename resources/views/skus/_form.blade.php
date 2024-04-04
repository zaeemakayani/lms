<div class="row">
    <div class="col-lg-3 col-md-4 col-sm-6 col-12 mb-2">
        <label for="seller_id">
            Select Seller *
        </label>
        <select class="form-control" name="seller_id" id="seller_id" placeholder="Select Seller" required>
            <option value="" disabled selected>Select Seller</option>
            @if(!empty($sellers))
                @foreach($sellers as $seller)
                    <option value="{{$seller->id}}" @isset($product) @if($seller->id == $product->seller_id) selected @endif @endisset>{{$seller->name}}</option>
                @endforeach
            @endif
        </select>
    </div>
    <div class="col-lg-3 col-md-4 col-sm-6 col-12 mb-2">
        <label for="brand_id">
            Select Brand *
        </label>
        <select class="form-control" name="brand_id" id="brand_id" placeholder="Select Brand" required>
            <option value="" disabled selected>Select Brand</option>
            @if(!empty($brands))
                @foreach($brands as $brand)
                    <option value="{{$brand->id}}" @isset($product) @if($brand->id == $product->brand_id) selected @endif @endisset>{{$brand->name}}</option>
                @endforeach
            @endif
        </select>
    </div>
    <div class="col-lg-3 col-md-4 col-sm-6 col-12 mb-2">
        <label for="name">
            SKU Name ID *
        </label>
        <input type="text" class="form-control" name="name" id="name" placeholder="Enter Product Name" value="@isset($product){{$product->name}}@endisset" required>
    </div>
    <div class="col-lg-3 col-md-4 col-sm-6 col-12 mb-2">
        <label for="name">
            Name *
        </label>
        <input type="text" class="form-control" name="name" id="name" placeholder="Enter Product Name" value="@isset($product){{$product->name}}@endisset" required>
    </div>
</div>
<hr>
<div class="row">
    <h4 class="text-decoration-underline">Products</h4>
    <div class="col-12">
        <table class="table table-bordered">
            <thead>
                <th>Product Name</th>
                <th>Product QTY</th>
                <th>Product Weight</th>
                <th>Product Selling Cost</th>
                <th class="text-center">Action</th>
            </thead>
            <tbody>
                <!-- <tr>
                    <td colspan="5" class="text-center">No data available in table</td>
                </tr> -->
                <tr>
                    <td>
                        <select class="form-control" name="" id="">
                            <option value="">Select Product</option>
                        </select>
                    </td>
                    <td>0</td>
                    <td>0 gms</td>
                    <td>0 AED</td>
                    <td class="text-center">
                        <a href="#"><i class="align-middle text-center" data-feather="plus-circle"></i></a>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
<div class="row">
    <div class="col-3">
        <button type="submit" class="d-flex btn btn-primary save-button" id="save-button">Save <div class="loader d-none"></div></button>
    </div>
</div>