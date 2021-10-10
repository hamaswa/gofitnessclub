<form class="row g-3 update-food-form" data-action="{{ route("update_item_bought") }}">
    <div class="col-md-12">
        <input class="form-control" name="food-item" value="{{ $data['item']->name }} {{ $data['item']->weight }}g RM{{ $data['item']->price }} {{ $data['item']->frequency }}">
    </div>
    <input type="hidden" name="id" value="{{$data['id']}}">
    <div class="col-md-6">
        <label  class="form-label">Shop
            <select name="shop" class="form-select">
                <option selected>Choose...</option>
                @foreach ($data['shops'] as $shop)
                    <option {{($data['item']['shop_id']==$shop->id?"selected":"")}} value="{{ $shop->id }}">{{ $shop->name }}</option>
                @endforeach
            </select>
        </label>
       
    </div>

    <div class="col-md-6">
        <label class="form-label">Brand
            <select name="brand" class="form-select">
                <option selected>Choose...</option>
                @foreach ($data['brands'] as $brand)
                    <option {{($data['item']['brand_id']==$brand->id?"selected":"")}} value="{{ $brand->id }}">{{ $brand->name }}</option>
                @endforeach
            </select>
        </label>
        
    </div>

    <div class="col-12">
        <button type="submit"  class="btn btn-primary">Update</button>
    </div>
</form>
