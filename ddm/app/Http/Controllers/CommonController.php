<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\DailyBuying;
use App\Models\DailyDite;
use App\Models\Shop;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CommonController extends Controller
{
   
    /**
     * Store or update image.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function upload_image($folder,Request $request)
    {
       
        $input = $request->all();
        $id = $input['id'];
        if($input['table']=="eat")
        $obj = DailyDite::find($id);
        else if($input['table']=='buy')
        $obj = DailyBuying::find($id);
        else if($input['table']=='prdbrd')
        $obj = Brand::find($id);
        else if($input['table']=='prdshp')
        $obj = Shop::find($id);


        if (!$request->hasFile('image')) {
            return response()->json(['upload_file_not_found'], 400);
        }
        $file = $request->file('image');
        if (!$file->isValid()) {
            return response()->json(['invalid_file_upload'], 400);
        }
        if (isset($obj->image) and $obj->image != "")
            if (file_exists(public_path() . $obj->image))
                unlink(public_path() . $obj->image);
        $path =  public_path() . "/uploads/images/$folder";
        $name = time() . '.' . $file->getClientOriginalName();
        $file->move($path, $name);

        $obj->image = "/uploads/images/$folder/" . $name;
        $obj->save();
        $class = "";
        if (file_exists($path . $name)) {
            $image = getimagesize($path . $name);
            $width = $image[0];
            $height = $image[1];
            $class = ($width / $height) < 1.2 ? "img-full-width" : "img-full-height";
        }
        return response()->json(array("image" => $obj->image, "class" => $class));
    }

    public function monthly_dite_report(Request $request)
    {
        $input = $request->all();
        if(isset($input['month']) and $input['month']!=""){
            $date = explode("-",$input['month']);
            $month = $date[0];
            $year = $date[1];
        }
        else {
        $month = date("m");
        $year = date("y");
        }
        $data['month'] = $month."-".$year;

        $monthly_dite = new DailyDite();
        $data['data'] = $monthly_dite->selectRaw("dailydite.name, count(dailydite.name) as count,sum(dailydite.qty) as qty, 
                                          sum(dailydite.weight) as weight, dite.energy as cal")
            ->whereMonth('dailydite.created_at', $month)
            ->whereYear('dailydite.created_at', $year)
            ->leftjoin("dite", 'dailydite.name', '=', 'dite.name')
            ->groupBy("dailydite.name")
            ->get();
        $data['meals'] = $monthly_dite->selectRaw("created_at")->whereMonth("created_at", $month)->groupBy('created_at')->get();
        $data['weight'] =  DB::table('user_weights')->latest('created_at')->first();
         $data['price'] = DB::table('dailybuyings')
        ->select(DB::raw('sum(price) as price, sum(qty) as qty,sum(weight) as weight, name'))
        ->groupBy('name')
        ->get();
        $_price=[];
        foreach($data['price'] as $price){
            $weight = $price->weight;
            $qty = $price->qty;
            if(isset($price->qty) and $price->qty > 0){
                $_price[$price->name]['perpiece'] = $price->price/$price->qty;
            }
            else if(isset($price->weight) and $price->weight > 0){
                $_price[$price->name]['pergram'] = $price->price / $weight;
            }
            $_price[$price->name]['total_weight'] = $price->weight;
            $_price[$price->name]['total_qty'] = $qty;
        }
        $data['price']  = $_price;
        return view("monthly_dite_report", compact("data"));
    }

    public function monthly_dite(Request $request)
    {
        $input = $request->all();
        if(isset($input['month']) and $input['month']!=""){
            $date = explode("-",$input['month']);
            $month = $date[0];
            $year = $date[1];
        }
        else {
        $month = date("m");
        $year = date("y");
        }

        $monthly_dite = new DailyDite();
        $result = $monthly_dite->selectRaw("dailydite.name,dailydite.created_at, dailydite.qty as qty, 
        dailydite.weight as weight, dite.energy as cal")
        ->whereMonth('dailydite.created_at', $month)
        ->whereYear('dailydite.created_at', $year)
        ->leftjoin("dite", 'dailydite.name', '=', 'dite.name')
            ->orderBy("dailydite.created_at", "desc")
            ->get();
        $data = array();
        foreach ($result as $row) {
            $data[date($row->created_at)][] = $row;
        }
        $data['data'] = $data;
        $data['month'] = $month."-".$year;


        return view("monthly_dite", compact("data"));
    }
}
