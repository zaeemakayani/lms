<div class="row">
    <div class="col-lg-3 col-md-4 col-sm-6 col-12 mb-2">
        <label for="name">
            Name *
        </label>
        <input type="text" class="form-control" name="name" id="name" placeholder="Enter Product Name" value="@isset($product){{$product->name}}@endisset" required>
    </div>
    <div class="col-lg-3 col-md-4 col-sm-6 col-12 mb-2">
        <label for="category_id">
            Select Category *
        </label>
        <select type="category_id" class="form-control" name="category_id" id="category_id" placeholder="Select Category" required>
            <option value="" disabled selected>Select Category</option>
            @if(!empty($categories))
                @foreach($categories as $category)
                    <option value="{{$category->id}}" @isset($product) @if($category->id == $product->category_id) selected @endif @endisset>{{$category->name}}</option>
                @endforeach
            @endif
        </select>
    </div>
    <div class="col-lg-3 col-md-4 col-sm-6 col-12 mb-2">
        <label for="unit_id">
            Select weight unit *
        </label>
        <select type="unit_id" class="form-control" name="unit_id" id="unit_id" placeholder="Select Weight Unit" required>
            <option value="" disabled selected>Select Unit Weight</option>
            @if(!empty($units))
                @foreach($units as $unit)
                    <option value="{{$unit->id}}" @isset($product) @if($unit->id == $product->unit_id) selected @endif @endisset>{{$unit->name}}</option>
                @endforeach
            @endif
        </select>
    </div>
    <div class="col-lg-3 col-md-4 col-sm-6 col-12 mb-2">
        <label for="unit_cost">
            Unit Cost *
        </label>
        <input type="number" step="any" class="form-control" name="unit_cost" id="unit_cost" placeholder="Enter Unit Cost" value="@isset($product){{$product->unit_cost}}@endisset" required>
    </div>
    <div class="col-lg-3 col-md-4 col-sm-6 col-12 mb-2">
        <label for="shipping_cost">
            Shipping Cost
        </label>
        <input type="number" step="any" class="form-control" name="shipping_cost" id="shipping_cost" placeholder="Enter Shipping Cost" value="@isset($product){{$product->shipping_cost}}@endisset">
    </div>
    <div class="col-lg-3 col-md-4 col-sm-6 col-12 mb-2">
        <label for="total_cost">
            Total Cost
        </label>
        <input type="number" step="any" class="form-control" name="total_cost" id="total_cost" placeholder="Enter Total Cost" value="@isset($product){{$product->total_cost}}@endisset">
    </div>
    <div class="col-lg-3 col-md-4 col-sm-6 col-12 mb-2">
        <label for="selling_cost">
            Selling Cost
        </label>
        <input type="number" step="any" class="form-control" name="selling_cost" id="selling_cost" placeholder="Enter Selling Cost" value="@isset($product){{$product->selling_cost}}@endisset">
    </div>
    <div class="col-lg-3 col-md-4 col-sm-6 col-12 mb-2">
        <label for="qty">
            Total Qty
        </label>
        <input type="number" step="any" class="form-control" name="qty" id="qty" placeholder="Enter Total Qty" value="@isset($product){{$product->qty}}@endisset">
    </div>
    <div class="col-lg-3 col-md-4 col-sm-6 col-12 mb-2">
        <label for="image">
            Product Image
        </label>
        <input type="file" class="form-control" name="image" id="image" placeholder="Upload User Photo" value="@isset($product){{$product->image}}@endisset">
        <div class="image-preview">
            <div class="color-box">
                <img id="previewImage" src="
                @isset($product)
                    @if($product->image != '')
                        {{asset('public/img/products/'.$product->image)}}
                    @else {{asset('public/img/products/product-001.jpg')}}
                    @endif
                @else
                    {{asset('public/img/products/product-001.jpg')}}
                @endisset" alt="Product Image">
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-4 col-sm-6 col-12 mb-2">
        <label for="active_status">
            Active / Inactive
        </label>
        <br>
        <input type="checkbox" class="form-check-input border-secondary" name="active_status" id="active_status" @isset($product) @if($product->active_status == 'active') checked @endif @endisset>
    </div>
</div>
<hr>
<div class="row">
    <div class="col-3">
        <button type="submit" class="d-flex btn btn-primary save-button" id="save-button">Save <div class="loader d-none"></div></button>
    </div>
</div>