<div id="item-{{ $item->id }}" class="col-6 col-md-4 col-lg-3 col-xl-3 col-xxl-3 mb-3">
    <div class="product-block border rounded-2 overflow-hidden bg-white position-relative">
        <div class="product-image d-flex flex-wrap">
            @php
                $val = str_replace('g', '', $item->weight) . 'g';
                if ($item->image != null) {
                    echo '<div class="position-absolute product-title d-flex align-items-start flex-column">';
                    echo '<h6 class="mb-auto bg-white p-2 opacity-75 rounded">RM' . $item->price . '/' . $val . '</h6>';
                    echo '<h2 class="mt-0 mb-1"><a href="javascript:void(0)" data-item="' . $item->name . '" data-href="' . route('buying_detail') . '" class="text-decoration-none text-white buying-details">' . $item->name . '</a></h2>';
                } else {
                    echo '<div class="position-absolute product-title">';
                    echo '<h2 class="mt-0 mb-0"><a href="javascript:void(0)" data-item="' . $item->name . '" data-href="' . route('buying_detail') . '" class="text-decoration-none text-dark buying-details">' . $item->name . '</a></h2>';
                    echo '<h6  class="mb-auto">RM' . $item->price . '/' . $val . '</h6>';
                }
            @endphp

            @if ((int) $item->weight > 0)
                <h5 class="mb-auto bg-white p-2 opacity-50 rounded right">

                    RM{{ round(((int) $item->price / (int) $item->weight) * 1000, 2) }}/kg</h5>
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
                <img src="{{ $item->image }}" id="image{{ $item->id }}" alt="Food" class="{{ $class }}" />
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
            <a type="submit" data-id="{{ $item->id }}" data-href="{{ route('edit_item_bought') }}"
                class="text-decoration-none edit-item-btn">Edit</a>
            <a type="submit" data-input="buy-meal-input-box"
                data-text="{{ $item->name }} {{ $item->weight }}g RM{{ $item->price }} {{ $item->frequency }}"
                class="text-decoration-none text-add-btn">add</a>
        </div>
    </form>
</div>

</div>
</div>
