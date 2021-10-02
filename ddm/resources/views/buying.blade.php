   <div class="d-flex mb-3">
       <input type="text" class="form-control me-2" placeholder="egg 2, beef 15g" aria-label="egg 2, beef 15g"
           aria-describedby="button-buy-meal" id="buy-meal-input-box">
       <button class="btn btn-success d-flex align-items-center" type="button" id="button-buy-meal">
           <img src="{{ asset('images/right-arrow.png') }}" width="10" class="img-fluid" /></button>
   </div>
   <div class="row row-cols-5 recent-meals" id="recent-meals">

       @foreach ($data as $item)
           {{-- <div class="col-6 col-md-4 col-lg-3 col-xl-3 col-xxl-3 mb-3">
               <div class="product-block border rounded-2 overflow-hidden bg-white position-relative">
                   <div class="product-image d-flex flex-wrap">
                       <div class="position-absolute product-title">
                           <h2 class="mt-0 mb-0"><a href="#" class="text-decoration-none text-dark">japanese pressed
                                   tofu</a></h2>

                           <h5 class="mb-auto bg-white p-2 opacity-75 rounded right">RM14.2/500g</h5>
                           <h6 class="mb-auto">180g</h6>
                       </div>
                       <figure id="image-container-42"
                           class="mb-0 flex-fill product-img-gradiant position-absolute d-flex align-items-center justify-content-center flex-wrap">
                       </figure>
                   </div>
                   <form action="http://127.0.0.1:8000/api/dailydite/upload_image" method="post" class="dite-image-form"
                       enctype="multipart/form-data">
                       <div class="product-actions d-flex justify-content-between px-2 py-1">
                           <a href="#" class="d-inline-block camera-btn">
                               <input type="hidden" name="id" value="42">
                               <input type="file" name="image" style="display: none" class="dite-image-input">
                               <img src="http://127.0.0.1:8000/images/camera-icon.png" height="16"
                                   class="dite-image" alt="Photos"></a>
                           <a type="submit" data-text="japanese pressed tofu 180g"
                               class="text-decoration-none text-add-btn">Add</a>
                       </div>
                   </form>

               </div>
           </div> --}}
           <div class="col-6 col-md-4 col-lg-3 col-xl-3 col-xxl-3 mb-3">
               <div class="product-block border rounded-2 overflow-hidden bg-white position-relative">
                   <div class="product-image d-flex flex-wrap">
                       @php
                           $val = str_replace('g', '', $item->weight) . 'g';                           
                           if ($item->image != null) {
                               echo '<div class="position-absolute product-title d-flex align-items-start flex-column">';
                               echo '<h6 class="mb-auto bg-white p-2 opacity-75 rounded">RM'.$item->price.'/'.$val.'</h6>';
                               echo '<h2 class="mt-0 mb-1"><a href="javascript:void(0)" data-href="'. route("buying_detail") .'" class="text-decoration-none text-white buying-details">' . $item->name . '</a></h2>';
                           } else {
                               echo '<div class="position-absolute product-title">';
                               echo '<h2 class="mt-0 mb-0"><a href="javascript:void(0)" data-href="'. route("buying_detail") .'" class="text-decoration-none text-dark buying-details">' . $item->name . '</a></h2>';
                               echo '<h6  class="mb-auto">RM'.$item->price.'/'.$val.'</h6>';
                           }
                       @endphp

                       <h5 class="mb-auto bg-white p-2 opacity-50 rounded right">RM
                           {{ round(((int)$item->price / (int)$item->weight) * 1000,2) }}/kg</h5>


                   </div>
                   <figure id="image-container-{{$item->id}}" 
                       class="mb-0 flex-fill product-img-gradiant position-absolute d-flex align-items-center justify-content-center flex-wrap">

                       @if($item->image!=null)
                        @php
                           $class="";
                           if(file_exists(public_path().$item->image)){
                              $image = getimagesize(public_path().$item->image);
                              $width = $image[0];
                              $height = $image[1];
                              $class = ($width/$height)<1.2?"img-full-width":"img-full-height";                      
                           }                          
                        @endphp 
                     <img src="{{ $item->image }}" id="image{{$item->id}}" alt="Food" class="{{$class}}" />                   
                     @endif
                   </figure>
               </div>
               <form action="{{ route("upload_image") }}" method="post" class="dite-image-form" enctype="multipart/form-data">
                  <div class="product-actions d-flex justify-content-between px-2 py-1">
                        <a href="#" class="d-inline-block camera-btn">
                           <input type="hidden" name="id" value="{{ $item->id }}" >
                           <input type="hidden" name="table" value="dailybuyings" >
                           <input type="file" name="image" style="display: none" class="dite-image-input">
                           <img src="{{ asset("images/camera-icon.png") }}" height="16" class="dite-image" alt="Photos" /></a>
                        <a type="submit" data-text="{{$item->name}} {{ $val }}" class="text-decoration-none text-add-btn">Edit</a>
                  </div>
               </form>  

           </div>
   </div>
   @endforeach




   </div>
   {{-- <div class="col" id="load_more" >
      @php
      $link = $data->nextPageUrl();
      if( $link!=""){
      $add_more = '<a class="text-center col-2 m-auto page-link ajax-page-load-more" href="" data-href="'.$link.'">Load More</a>';        
      echo $add_more;
      }
     @endphp      
      </div> --}}
