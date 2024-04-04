<div class="row">
    <div class="col-lg-3 col-md-4 col-sm-6 col-12 mb-2">
        <label for="name">
            Name *
        </label>
        <input type="text" class="form-control" name="name" id="name" placeholder="Enter Product Name" value="@isset($brand){{$brand->name}}@endisset" required>
    </div>
    <div class="col-lg-3 col-md-4 col-sm-6 col-12 mb-2">
        <label for="seller_id">
            Select Seller *
        </label>
        <select type="seller_id" class="form-control" name="seller_id" id="seller_id" placeholder="Select Seller" required>
            <option value="" disabled selected>Select Seller</option>
            @if(!empty($sellers))
            @foreach($sellers as $seller)
            <option value="{{$seller->id}}" @isset($brand) @if($seller->id == $brand->seller_id) selected @endif @endisset>{{$seller->name}}</option>
            @endforeach
            @endif
        </select>
    </div>
    <div class="col-lg-3 col-md-4 col-sm-6 col-12 mb-2">
        <label for="active_status">
            Active / Inactive
        </label>
        <br>
        <input type="checkbox" class="form-check-input border-secondary" name="active_status" id="active_status" @isset($brand) @if($brand->active_status == 'active') checked @endif @endisset>
    </div>
</div>
<hr>
<div class="row">
    <div class="col-3">
        <button type="submit" class="d-flex btn btn-primary save-button" id="save-button">Save <div class="loader d-none"></div></button>
    </div>
</div>