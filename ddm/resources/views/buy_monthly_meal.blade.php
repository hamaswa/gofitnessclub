
    @php
    $css_colors = ['#FF6633', '#FFB399', '#FF33FF', '#FFFF99', '#00B3E6', 
		  '#E6B333', '#3366E6', '#999966', '#99FF99', '#B34D4D',
		  '#80B300', '#809900', '#E6B3B3', '#6680B3', '#66991A', 
		  '#FF99E6', '#CCFF1A', '#FF1A66', '#E6331A', '#33FFCC',
		  '#66994D', '#B366CC', '#4D8000', '#B33300', '#CC80CC', 
		  '#66664D', '#991AFF', '#E666FF', '#4DB3FF', '#1AB399',
		  '#E666B3', '#33991A', '#CC9999', '#B3B31A', '#00E680', 
		  '#4D8066', '#809980', '#E6FF80', '#1AFF33', '#999933',
		  '#FF3380', '#CCCC00', '#66E64D', '#4D80CC', '#9900B3', 
		  '#E64D66', '#4DB380', '#FF4D4D', '#99E6E6', '#6666FF'];
    @endphp
    {{-- <div class="d-flex mb-3" id='meal-edit-box'>
        <input type="hidden" id="created_at">
        <input type="text" class="form-control me-2" placeholder="egg 2, beef 15g" aria-label="egg 2, beef 15g" 
        id="meal-edit-input-box">
        <button class="btn btn-success d-flex align-items-center" type="button" id="button-edit-meal">
            <img src="{{ asset("images/right-arrow.png") }}" width="10" class="img-fluid" /></button>
      </div> --}}
    <ul class="list-unstyled mb-4">
        @foreach ($data as $key=>$item)
        <div class="d-flex flex-wrap">
            <button type="button" style="background-color:{{ $css_colors[(int)date("D", strtotime($key))]}}" 
            class="btn btn-success me-2 mb-2">
                {{ date("d", strtotime($key)) }}
            </button>
            @foreach ($item as $k=>$shop)
            <button type="button" class="btn btn-outline-primary position-relative me-2 mb-2">
                @php
                    echo $k;
                @endphp
            </button>
            @foreach ($shop as $sub_item)       
            <button type="button" class="btn btn-outline-primary position-relative me-2 mb-2">
                {{$sub_item->name}} 
                <div class="history-btn-weight bg-primary position-absolute text-white">
                    @php 
                    if(isset($sub_item->qty) and $sub_item->qty!="")
                     echo str_replace("pcs","",$sub_item->qty)."pcs";
                     else 
                     echo str_replace("g","",$sub_item->weight)."g"
                    @endphp
                </div>
                <span class="text-muted font-11">
                    RM{{ str_replace("RM","", $sub_item->price) }} 
                </span>
               
            </button>             
        @endforeach
                
            @endforeach
          
            {{-- <a href="javascript:void(0)" data-href="{{route("edit_dite")}}" data-created_at="{{$key}}" class="py-0 px-2 btn-edit-food">Edit</a>
            <a href="javascript:void(0)" data-href="{{route("delete_dite")}}" data-created_at="{{$key}}" class="py-0 px-2 btn-delete-food">Delete</a>  --}}
         </div>                      
        @endforeach    
    </ul>
  