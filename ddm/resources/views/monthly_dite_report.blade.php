<style>
   .ui-datepicker-calendar {
       display: none;
   }
   </style>
<input type="text" value="{{ isset($data['month'])?$data['month']:""}}" data-url="{{ route("monthly_dite_report")}}" id="monthpicker" >
<h2 class="mt-0 h5 mb-2">Account</h2>
        <div class="d-flex flex-wrap">
           <button type="button" class="btn btn-dark me-2 mb-2">{{ $data['meals']->count('created_at')}}</button>
           <button type="button" class="btn btn-dark me-2 mb-2">
              {{$data['data']->sum('weight')/1000}}kg
            </button>
           <button data-month="{{ isset($data['month'])?$data['month']:""}}" data-href={{route("user_weight_report")}} type="button" class="person-weight btn btn-dark me-2 mb-2">
              {{isset($data['weight']->weight)?$data['weight']->weight:0}}
            </button>
        </div>     
        
        <div class="table-responsive">
           <table class="table">
              <thead>
                 <tr>
                    <th scope="col">Items</th>
                    <th scope="col">g/pcs</th>
                    <th scope="col">Counts</th>
                    <th scope="col">Cal</th>
                    <th scope="col">Price</th>
                 </tr>
              </thead>
              <tbody>
                @foreach ($data['data'] as $key=>$item)
                    <tr>
                        <th><a href="javascript:void(0)" data-month="{{ isset($data['month'])?$data['month']:""}}" data-item="{{$item->name}}" data-href="{{ route("food_item_report")}}" 
                           class="text-decoration-none meal_item_history">{{$item->name}}</a></th>
                        <td>@php
                           echo  (isset($item->qty) and $item->qty!="")? $item->qty . " pcs": $item->weight."g";
                        @endphp</td>
                        <td>{{$item->count}}</td>
                        <td>{{ (int)str_replace("g","",$item->weight) * (int)str_replace("cal","",$item->cal)/100}}</td>
                        <td>
                           @php
                               $obj = $data['price'];
                               $price = "N/A";
                               if(isset($obj[$item->name]['pergram'])){
                               $price = $obj[$item->name]['pergram'] * $item->weight ;
                               }
                               else if(isset($obj[$item->name]['perpiece'])){
                                 $price = $obj[$item->name]['perpiece'] * $item->qty;
                               }
                               
                               echo round($price,2);
                           @endphp

                        </td>
                     </tr>
                 @endforeach 
              </tbody>
           </table>
        </div>                     
           
   