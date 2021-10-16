
    <h2 class="mt-0 h5 mb-2"> Food Calories</h2>
    <ul class="list-unstyled mb-4">
        @foreach ($data as $item)
            <li class="library-input border bg-white rounded-top d-flex justify-content-between align-items-center mb-2 p-2">
                <input type=text class="form-control border-0 me-2" 
                value="{{ $item->name }} ={{ str_replace("g","",$item->weight) }}g, {{ str_replace("cal/100g","",isset($item->energy)?strtolower($item->energy):0)}} Cal/100g" />
                <a href="javascript:void(0)" data-value="{{ $item->name }} = {{ $item->weight }}, {{ $item->energy}}" 
                    id={{ $item->id }}  class="d-none py-0 px-2 btn-edit-item">Save
                </a>
                <a href="javascript:void(0)" data-value="{{ $item->name }} = {{ $item->weight }}, {{ $item->energy}}" 
                    id={{ $item->id }}  class="d-none py-0 px-2 btn-delete-item">Delete
                </a>
            </li>                      
        @endforeach    
    </ul>
   @php
       $links = $data->links();        
       $links = str_replace('<a class="page-link"', '<a class="page-link ajax-page-link" ', $links);
       $links = str_replace('href', 'href="" data-href', $links);
        echo $links;
   @endphp 
     <h2 class="mt-0 h5 mb-2">Add New food</h2>
     <ul class="list-unstyled">
        <li class="library-input border bg-white rounded-top d-flex justify-content-between align-items-center mb-2 p-2"> 
            <input type=text class="form-control border-0 me-2" placeholder="egg = 60g, 15Cal" />
           <a href="javascript:void(0)" class="d-none py-0 px-2" id='btn-add-item'>Add</a>
        </li>
     </ul>   
    
   
   