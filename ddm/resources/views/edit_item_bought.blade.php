<form class="row g-3 update-food-form" data-action="{{ route("update_item_bought") }}">

    <div class="col-md-6">
        <div class="mb-3">
            <label class="form-label">Name</label>
            <input class="form-control" name="food-item" value="{{ $data['item']->name }}">
        </div>
        @php
         if(isset($data['item']->qty) and $data['item']->qty !=""){
         $weight = $data['item']->qty;
         }
        else {
                 $weight = $data['item']->weight;
        }
        @endphp
        <div class="mb-3">
            <label class="form-label">Weight</label>
            <input class="form-control" name="food-item" value="{{ $weight }}">
        </div>
        <div class="mb-3">
            <label class="form-label">Quantity</label>
            <input class="form-control" name="food-item" value=" {{ $data['item']->frequency }}">
        </div>
    </div>
    <div class="col-md-6">
        <div class="mb-3">
            <label class="form-label">RM</label>
            <input class="form-control" name="food-item" value="{{ $data['item']->price }}">
        </div>
        <div class="mb-3">
            <?php
                if(isset($data['item']->qty) and $data['item']->qty !=""){
                    $weight = $data['item']->qty;
                    ?>
                    <label class="form-label">Unit</label>
                    <select id="unit" name="unit[]" class="form-select">
                        <option value="pcs" selected>Pcs</option>
                        <option value="g">g</option>
                    </select>
                <?php } else {
                    $weight = $data['item']->weight;
                    ?>
                    <label class="form-label">Unit</label>
                    <select id="unit" name="unit[]" class="form-select">
                        <option value="pcs">Pcs</option>
                        <option value="g" selected>g</option>
                    </select>
                <?php } ?>
        </div>
    </div>
    <input type="hidden" name="id" value="{{$data['id']}}">

    <div class="col-md-6">
        <label  class="form-label">Shop</label>
        <select name="shop" class="form-select">
            <option selected>Choose...</option>
            @foreach ($data['shops'] as $shop)
                <option {{($data['item']['shop_id']==$shop->id?"selected":"")}} value="{{ $shop->id }}">{{ $shop->name }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-md-6">
        <label class="form-label">Brand</label>
        <select name="brand" class="form-select">
            <option selected>Choose...</option>
            @foreach ($data['brands'] as $brand)
                <option {{($data['item']['brand_id']==$brand->id?"selected":"")}} value="{{ $brand->id }}">{{ $brand->name }}</option>
            @endforeach
        </select>
    </div>

    <div class="col-12">
        <button type="submit"  class="btn btn-primary">Update</button>
    </div>
</form>
