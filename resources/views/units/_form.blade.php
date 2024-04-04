<div class="row">
    <div class="col-lg-3 col-md-4 col-sm-6 col-12 mb-2">
        <label for="name">
            Unit Name *
        </label>
        <input type="text" class="form-control" name="name" id="name" placeholder="Enter Unit Name" value="@isset($unit){{$unit->name}}@endisset" required>
    </div>
    <div class="col-lg-3 col-md-4 col-sm-6 col-12 mb-2">
        <label for="weight">
            Weight (gms)
        </label>
        <br>
        <input type="number" class="form-control" name="weight" id="weight" placeholder="Enter weight in grams" value="@isset($unit){{$unit->weight}}@endisset" required>
    </div>
    <div class="col-lg-3 col-md-4 col-sm-6 col-12 mb-2">
        <label for="active_status">
            Weight (gms)
        </label>
        <br>
        <input type="checkbox" class="form-check-input border-secondary" name="active_status" id="active_status" @isset($unit) @if($unit->active_status == 'active') checked @endif @endisset>
    </div>
</div>
<hr>
<div class="row">
    <div class="col-3">
        <button type="submit" class="d-flex btn btn-primary save-button" id="save-button">Save <div class="loader d-none"></div></button>
    </div>
</div>