<form id="buy_meal_form" class="mb-3">
    <div class="form-row mb-3 row">
        <div class="form-col col-sm-6">
            <select name="shop_id" class="form-select select-shop">
                <option selected>Select Shop</option>
                @foreach ($data['shops'] as $shop)
                    <option {{ $shop->id==$data['shop_id']?"selected":""}} value="{{ $shop->id }}">
                        {{ $shop->name }}</option>
                @endforeach
            </select>
        </div>
    </div>
    <div class="form-row pb-3 pb-md-0 mb-3 buying-form-row">
        <div class="row">
            <div class="form-col col-12 col-sm mb-2 mb-md-0">
                <input id="qName" name="name[]" type="text" class="form-control" placeholder="Name">
                <p>Please enter Name!</p>
            </div>
            <div class="form-col col-12 col-sm mb-2 mb-md-0">
                <div class="d-flex b-input-buttons">
                    <input name="weight[]" type="number" step=".01" class="form-control" placeholder="weight/pcs">
                    <div class="btn-group mb-0" role="group" aria-label="Basic radio toggle button group">
                    
                        <input type="radio" class="btn-check unit radio_pcs" name="radio" id="radio_pcs" 
                        autocomplete="off"  value="pcs">
                        <label class="btn btn-outline-primary first-btn" id="radio_pcs_for" for="radio_pcs">pcs</label>
                        <select name="unit[]" class="d-none">
                            <option value="pcs"></option>
                            <option selected value="g"></option>
                        </select>
                        <input type="radio" class="btn-check unit" name="radio" id="radio_g" autocomplete="off" checked value="g">
                        <label class="btn btn-outline-primary" id="radio_g_for" for="radio_g">g</label>
                        
                    </div>
                </div>
                <p>Please enter weight!</p>
                <span>Zero value not excepted</span>
            </div>   
            
            <div class="form-col col">
                <input name="price[]" type="number" step=".01" class="form-control" placeholder="Price">
                <p>Please enter RM!</p>
                <span>Zero value not excepted</span>
            </div>
            <div class="form-col col">
                <input name="count" type="number" class="form-control quantity" placeholder="Quantity">
                <p>Please enter Quantity!</p>
            </div>
            <div class="form-col col text-right b-actions-btns">
                <div class="button-group">
                    <a href="#" class="btn btn-success add_buy_meal_form_row"><i class="fas fa-plus"></i></a>
                    <a href="#" class="btn btn-danger d-none remove_buy_meal_form_row"><i class="fas fa-minus"></i></a>
                </div>
            </div>
        </div>        
    </div>
    <div class="text-end mt-3 px-1">
        <button id="submitForm" class="btn btn-success" type="submit">Save <img
                src="{{ asset('images/right-arrow.png') }}" width="10" class="img-fluid" /></button>
    </div>
</form>
<div class="d-flex mb-3 d-none">
    <input type="text" class="data-input-box form-control me-2" placeholder="chicken 300g RM14.52 2x"
        aria-label="egg 2, beef 15g" aria-describedby="button-buy-meal" id="buy-meal-input-box">
    <button class="btn btn-success d-flex align-items-center" type="button" id="button-buy-meal">
        <img src="{{ asset('images/right-arrow.png') }}" width="10" class="img-fluid" /></button>
</div>
<div class="row mb-3">
    <div class="col-md-2">
        <select name="month" class="form-select">
            <option value="">Select Month</option>
            <?php for($i=1; $i<=12; $i++) {
                echo "<option value=".$i.">".strftime('%B', mktime(0, 0, 0, $i))."</option>";
            } 
            
            ?>
        </select>
    </div>
    <div class="col-md-2">
        @php 
        $currently_selected = date('Y');
        $earliest_year = 2021;
        $latest_year = date('Y');
        echo '<select name="year" class="form-select">';
        echo '<option value="">Select Year</option>';
        foreach ( range( $latest_year, $earliest_year ) as $i ) {
            echo '<option value="'.$i.'"'.($i === $currently_selected ? ' selected="selected"' : '').'>'.$i.'</option>';
        }
        echo '</select>';
        @endphp
    </div>
    <div class="col-md-2">
        <button class="btn btn-primary" data-url="{{ route("buying-index")}}" id="monthpicker" type="submit">Submit</button>
    </div>
 </div>
<div class="row row-cols-5 recent-meals" id="recent-meals">

    @foreach ($data['data'] as $item)

        <div id="item-{{ $item->id }}" class="col-6 col-md-4 col-lg-3 col-xl-3 col-xxl-3 mb-3">
            <div class="product-block border rounded-2 overflow-hidden bg-white position-relative">
                <a data-id="{{$item->id}}" data-href="{{ route('delete_item_bought', $item->id) }}" 
                    class="position-absolute delete-card">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                        class="bi bi-trash" viewBox="0 0 16 16">
                        <path
                            d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0V6z" />
                        <path fill-rule="evenodd"
                            d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1v1zM4.118 4 4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4H4.118zM2.5 3V2h11v1h-11z" />
                    </svg>
                </a>
                <div class="product-image d-flex flex-wrap">
                    @php
                        if ($item->weight != null) {
                            $val = str_replace('g', '', $item->weight) . 'g';
                            if ($item->image != null) {
                                echo '<div class="position-absolute product-title d-flex align-items-start flex-column">';
                                echo '<h6 class="mb-auto bg-white p-2 opacity-75 rounded">RM' . $item->price . '/' . $val . '</h6>';
                                echo '<h2 class="mt-0 mb-1"><a href="javascript:void(0)" data-item="id" data-href="' . route('buying_detail',$item->id) . '" class="text-decoration-none text-white buying-details">' . $item->name . '</a></h2>';
                            } else {
                                echo '<div class="position-absolute product-title">';
                                echo '<h2 class="mt-0 mb-0"><a href="javascript:void(0)" data-item="id" data-href="' . route('buying_detail',$item->id) . '" class="text-decoration-none text-dark buying-details">' . $item->name . '</a></h2>';
                                echo '<h6  class="mb-auto">RM' . $item->price . '/' . $val . '</h6>';
                            }
                        } else {
                            $val = str_replace('pcs', '', $item->qty) . 'pcs';
                            if ($item->image != null) {
                                echo '<div class="position-absolute product-title d-flex align-items-start flex-column">';
                                echo '<h6 class="mb-auto bg-white p-2 opacity-75 rounded">RM' . $item->price . '/' . $val . '</h6>';
                                echo '<h2 class="mt-0 mb-1"><a href="javascript:void(0)" data-item="id" data-href="' . route('buying_detail',$item->id) . '" class="text-decoration-none text-white buying-details">' . $item->name . '</a></h2>';
                            } else {
                                echo '<div class="position-absolute product-title">';
                                echo '<h2 class="mt-0 mb-0"><a href="javascript:void(0)" data-item="id" data-href="' . route('buying_detail',$item->id) . '" class="text-decoration-none text-dark buying-details">' . $item->name . '</a></h2>';
                                echo '<h6  class="mb-auto">RM' . $item->price . '/' . $val . '</h6>';
                            }
                        }
                    @endphp

                    @if ((int) $item->weight > 0)
                        <h5 class="mb-auto bg-white p-2 opacity-50 price">
                            RM{{ round(((int) $item->price / (int) $item->weight) * 12, 2) }}/kg</h5>
                    @else
                    <h5 class="mb-auto bg-white p-2 opacity-50 price">
                        RM{{ round(((int) $item->price / (int) $item->qty), 2) }}/pcs</h5>
                    @endif
                    @if ($item->brand_name!=null)
                    <h5 class="mb-auto bg-white p-2 opacity-50 brand">{{$item->brand_name}}</h5>
                    @endif


                </div>
                <figure id="image-container-{{ $item->id }}"
                    class="mb-0 flex-fill product-img-gradiant position-absolute d-flex align-items-center justify-content-center flex-wrap">

                    @if ($item->image != null)
                        @php
                            $class = '';
                            if (file_exists(public_path() . $item->image)) {
                                $image = getimagesize(public_path() . $item->image);
                                $width = $image[0];
                                $height = $image[1];
                                $class = $width / $height < 1.2 ? 'img-full-width' : 'img-full-height';
                            }
                        @endphp
                        <img src="{{ $item->image }}" id="image{{ $item->id }}" alt="Food"
                            class="{{ $class }}" />
                    @endif
                </figure>
            </div>
            <form action="{{ route('upload_image', 'meal buy') }}" method="post" class="upload-image-form"
                enctype="multipart/form-data">
                <div class="product-actions d-flex justify-content-between px-2 py-1">
                    <a href="javascript:void(0)" class="d-inline-block camera-btn">
                        <input type="hidden" name="id" value="{{ $item->id }}">
                        <input type="hidden" name="table" value="buy">
                        <input type="file" name="image" style="display: none" class="upload-image-input">
                        <img src="{{ asset('images/camera-icon.png') }}" height="16" class="upload-image"
                            alt="Photos" /></a>
                    <a type="submit" data-id="{{ $item->id }}"
                       data-href="{{ route('edit_item_bought') }}"
                        class="text-decoration-none edit-item-btn">Edit</a>
                    <a type="submit" data-input="buy-meal-input-box"
                       data-name="{{ $item->name }}" data-weight="{{ $item->weight }}"
                       data-qty="{{$item->qty}}" data-price="{{ $item->price }}"
                       data-quantity="{{ $item->frequency }}"
                       class="text-decoration-none text-add-btn">add</a>
                </div>
            </form>
            @php
                echo '</div>';
            @endphp
        </div>
    @endforeach
</div>
<div class="col" id="load_more">
    @php
        $link = $data['data']->nextPageUrl();
        if ($link != '') {
            $add_more = '<a class="text-center col-2 m-auto page-link ajax-page-load-more" href="" data-href="' . $link . '">Load More</a>';
            echo $add_more;
        }
    @endphp
</div>
