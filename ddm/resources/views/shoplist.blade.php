   <div class="d-flex mb-3">
       <input type="text" class="data-input-box form-control me-2" placeholder="Sunrise Mart-Malaysia,Bromley Food Store-Malaysia"
           aria-label="egg 2, beef 15g" aria-describedby="button-add-shop" id="add-shop-input-box">

       <button data-href="{{ route('post_shop_list') }}" data-input="add-shop-input-box"
           class="btn btn-success d-flex align-items-center" type="button" id="button-add-shop">
           <img src="{{ asset('images/right-arrow.png') }}" width="10" class="img-fluid" /></button>
   </div>
   <div class="row row-cols-5 recent-meals" id="recent-meals">

       @foreach ($data as $shop)

           <div class="col-6 col-md-4 col-lg-3 col-xl-3 col-xxl-3 mb-3">
               <div class="product-block border rounded-2 overflow-hidden bg-white position-relative">
                   <div class="product-image d-flex flex-wrap">
                       @php
                           if ($shop->image != null) {
                               echo '<div class="position-absolute product-title d-flex align-items-start flex-column">';
                               echo '<h6 class="mb-auto bg-white p-2 opacity-75 rounded">' . $shop->location  . '</h6>';
                               echo '<h2 class="mt-0 mb-1"><a href="javascript:void(0)" data-item="shop_id" data-href="' . route('buying_detail',$shop->id) . '" class="text-decoration-none text-white buying-details">' . $shop->name . '</a></h2>';
                           } else {
                               echo '<div class="position-absolute product-title">';
                               echo '<h2 class="mt-0 mb-0"><a href="javascript:void(0)" data-item="shop_id" data-href="' . route('buying_detail',$shop->id) . '" class="text-decoration-none text-dark buying-details">' . $shop->name . '</a></h2>';
                               echo '<h6  class="mb-auto">' . $shop->location . '</h6>';
                           }
                       @endphp
                   </div>
                   <figure id="image-container-{{ $shop->id }}"
                       class="mb-0 flex-fill product-img-gradiant position-absolute d-flex align-items-center justify-content-center flex-wrap">

                       @if ($shop->image != null)
                           @php
                               $class = '';
                               if (file_exists(public_path() . $shop->image)) {
                                   $image = getimagesize(public_path() . $shop->image);
                                   $width = $image[0];
                                   $height = $image[1];
                                   $class = $width / $height < 1.2 ? 'img-full-width' : 'img-full-height';
                               }
                           @endphp
                           <img src="{{ $shop->image }}" id="image{{ $shop->id }}" alt="Food"
                               class="{{ $class }}" />
                       @endif
                   </figure>
               </div>
               <form action="{{ route("upload_image","shop") }}" method="post" class="upload-image-form"
                   enctype="multipart/form-data">
                   <div class="product-actions d-flex justify-content-between px-2 py-1">
                       <a href="javascript:void(0)" class="d-inline-block camera-btn">
                           <input type="hidden" name="id" value="{{ $shop->id }}">
                           <input type="hidden" name="table" value="prdshp">
                           <input type="file" name="image" style="display: none" class="upload-image-input">
                           <img src="{{ asset('images/camera-icon.png') }}" height="16" class="upload-image"
                               alt="Photos" /></a>
                               <a type="submit" data-input="add-shop-input-box" data-text="{{$shop->name}}-{{$shop->location}}" data-id="{{ $shop->id }}" class="text-decoration-none shop-edit-btn">Edit</a>
                               <a type="submit" data-href="{{route("delete_shop",$shop->id )}}" data-id="{{ $shop->id }}" class="text-decoration-none shop-delete-btn">Delete</a>
                            </div>
               </form>

           </div>
   </div>
   @endforeach

</div>
<div class="col" id="load_more" >
    @php
    $link = $data->nextPageUrl();
    if( $link!=""){
    $add_more = '<a class="text-center col-2 m-auto page-link ajax-page-load-more" href="" data-href="'.$link.'">Load More</a>';        
    echo $add_more;
    }
   @endphp      
    </div>
