<form class="row g-3 update-food-form" data-action="{{ route("update_item_bought") }}">
    {{--<div class="col-md-12" hidden>--}}
        {{--<input class="form-control" name="food-item" value="{{ $data['item']->name }} {{ $data['item']->weight }}g RM{{ $data['item']->price }} {{ $data['item']->frequency }}">--}}
    {{--</div>--}}
    <div class="col-md-6">
        <div class="mb-3">
            <label class="form-label">Name</label>
            <input class="form-control" name="food-item" value="{{ $data['item']->name }}">
        </div>
        <div class="mb-3">
            <label class="form-label">Weight</label>
            <input class="form-control" name="food-item" value="{{ $data['item']->weight }}g">
        </div>
    </div>
    <div class="col-md-6">
        <div class="mb-3">
            <label class="form-label">RM</label>
            <input class="form-control" name="food-item" value=" RM{{ $data['item']->price }}">
        </div>
        <div class="mb-3">
            <label class="form-label">Frequency</label>
            <input class="form-control" name="food-item" value=" {{ $data['item']->frequency }}">
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
