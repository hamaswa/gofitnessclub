@php
$css_colors = ['#FF6633', '#FFB399', '#FF33FF', '#FFFF99', '#00B3E6', '#E6B333', '#3366E6', '#999966', '#99FF99', '#B34D4D', '#80B300', '#809900', '#E6B3B3', '#6680B3', '#66991A', '#FF99E6', '#CCFF1A', '#FF1A66', '#E6331A', '#33FFCC', '#66994D', '#B366CC', '#4D8000', '#B33300', '#CC80CC', '#66664D', '#991AFF', '#E666FF', '#4DB3FF', '#1AB399', '#E666B3', '#33991A', '#CC9999', '#B3B31A', '#00E680', '#4D8066', '#809980', '#E6FF80', '#1AFF33', '#999933', '#FF3380', '#CCCC00', '#66E64D', '#4D80CC', '#9900B3', '#E64D66', '#4DB380', '#FF4D4D', '#99E6E6', '#6666FF'];
@endphp
<style>
    .ui-datepicker-calendar {
        display: none;
    }
    </style>
<div class="row mb-3">
    <div class="col-md-2">
        <select name="selectMonth" class="form-select">
            <option value="">Select Month</option>
            <?php for($i=1; $i<=12; $i++) {
                echo "<option value=".$i.">".$i."</option>";
            } ?>
        </select>
    </div>
    <div class="col-md-2">
        <select name="selectYear" class="form-select">
            <option value="">Select Year</option>
            <?php
            for($i=1; $i<=12; $i++)
            {
                echo "<option value=".$i.">".$i."</option>";
            }
            ?>
        </select>
    </div>
    <div class="col-md-2">
        <button class="btn btn-primary" type="submit">Submit</button>
    </div>
</div>
<input hidden type="text"  value="{{ isset($data['month'])?$data['month']:""}}" placeholder="Select Month" data-url="{{ route("monthly_dite")}}" id="monthpicker" >
<ul class="list-unstyled mb-4">
    @foreach ($data['data'] as $key => $item)
        <div class="d-flex flex-wrap">
            <button type="button" style="background-color:{{ $css_colors[(int) date('D', strtotime($key))] }}"
                class="btn btn-success me-2 mb-2">
                {{ date('d', strtotime($key)) }}
            </button>
            @foreach ($item as $sub_item)
                <button type="button" class="btn btn-outline-primary position-relative me-2 mb-2">{{ $sub_item->name }}
                    <div class="history-btn-weight bg-primary position-absolute text-white">
                        @php
                           
                                echo str_replace('g', '', $sub_item->weight) . 'g';
                            
                        @endphp </div>
                    <span class="text-muted font-11">

                        {{ ((int) str_replace('cal', '', $sub_item->cal) * (int) str_replace('g', '', $sub_item->weight)) / 100 . ' Cal' }}
                    </span>
                    @php
                        if ($sub_item->qty != 0) {
                            echo '<div class="history-btn-number bg-primary position-absolute text-white">';
                            $val = (int) str_replace('g', '', $sub_item->qty);
                            echo $val;
                            echo '</div>';
                        }
                    @endphp
                </button>
            @endforeach
            <a href="javascript:void(0)" data-href="{{ route('edit_meal') }}" data-created_at="{{ $key }}"
                class="py-0 px-2 btn-edit-food">Edit</a>
            <a href="javascript:void(0)" data-href="{{ route('delete_meal') }}" data-created_at="{{ $key }}"
                class="py-0 px-2 btn-delete-food">Delete</a>
        </div>
    @endforeach
</ul>
