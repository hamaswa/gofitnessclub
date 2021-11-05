<form class="row g-3 update-food-form" data-action="{{ route('update_item_bought') }}">

    <div class="col-md-6">
        <div class="mb-3">
            <label class="form-label">Name</label>
            <input class="form-control" name="name" value="{{ $data['item']->name }}">
        </div>
        @php
            if (isset($data['item']->qty) and $data['item']->qty != '') {
                $weight = $data['item']->qty;
            } else {
                $weight = $data['item']->weight;
            }
        @endphp
        <div class="mb-3">
            <label class="form-label">Weight</label>
            <input class="form-control" name="weight" value="{{ $weight }}">
        </div>
        <div class="mb-3">
            <label class="form-label">Quantity</label>
            <input class="form-control" name="count" value=" {{ $data['item']->frequency }}">
        </div>
    </div>
    <div class="col-md-6">
        <div class="mb-3">
            <label class="form-label">RM</label>
            <input class="form-control" name="price" value="{{ $data['item']->price }}">
        </div>
        <label class="form-label">Unit</label><br>
        <div class="btn-group" role="group" aria-label="Basic radio toggle button group">          
            <input type="radio" class="btn-check unit" name="radio_edit" id="radio_pcs_edit" autocomplete="off" {{(isset($data['item']->qty) and $data['item']->qty !="")? "checked":""}} value="pcs">
            <label class="btn btn-outline-primary" id="radio_pcs_for" for="radio_pcs_edit">pcs</label>
            <select name="unit[]" class="d-none">
                <option  value="pcs" {{(isset($data['item']->qty) and $data['item']->qty !="")? "selected":""}}></option>
                <option  value="g" {{(isset($data['item']->qty) and $data['item']->qty !="")? "":"selected"}}></option>
            </select>
            <input type="radio" class="btn-check unit" name="radio_edit" id="radio_g_edit" autocomplete="off" 
            {{(isset($data['item']->qty) and $data['item']->qty !="")? "":"checked"}}  value="g">
            <label class="btn btn-outline-primary" id="radio_g_for" for="radio_g_edit">g</label> 
            
            
        </div>   
    </div>
    <input type="hidden" name="id" value="{{ $data['id'] }}">

    <div class="col-md-6">
        <label class="form-label">Shop</label>
        <select name="shop" class="form-select">
            <option selected>Choose...</option>
            @foreach ($data['shops'] as $shop)
                <option {{ $data['item']['shop_id'] == $shop->id ? 'selected' : '' }} value="{{ $shop->id }}">
                    {{ $shop->name }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-md-6">
        <label class="form-label">Brand</label>
        <select name="brand" class="form-select">
            <option selected>Choose...</option>
            @foreach ($data['brands'] as $brand)
                <option {{ $data['item']['brand_id'] == $brand->id ? 'selected' : '' }} value="{{ $brand->id }}">
                    {{ $brand->name }}</option>
            @endforeach
        </select>
    </div>

    <div class="col-12">
        <button type="submit" class="btn btn-primary">Update</button>
    </div>
</form>
