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
     * Store or update image for dite.
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

    public function monthly_dite_report()
    {
        $month = date("m");
        $monthly_dite = new DailyDite();
        $data['data'] = $monthly_dite->selectRaw("dailydite.name, count(dailydite.name) as count,sum(dailydite.qty) as qty, 
                                          sum(dailydite.weight) as weight, dite.energy as cal")
            ->whereMonth('dailydite.created_at', $month)
            ->leftjoin("dite", 'dailydite.name', '=', 'dite.name')
            ->groupBy("dailydite.name")
            ->get();
        $data['meals'] = $monthly_dite->selectRaw("created_at")->whereMonth("created_at", $month)->groupBy('created_at')->get();
        $data['weight'] =  DB::table('user_weights')->latest('created_at')->first();


        return view("monthly_dite_report", compact("data"));
    }

    public function monthly_dite()
    {
        $month = date("m");
        $monthly_dite = new DailyDite();
        $result = $monthly_dite->selectRaw("dailydite.name,dailydite.created_at, dailydite.qty as qty, 
        dailydite.weight as weight, dite.energy as cal")
            ->whereMonth('dailydite.created_at', $month)
            ->leftjoin("dite", 'dailydite.name', '=', 'dite.name')
            ->orderBy("dailydite.created_at", "desc")
            ->get();
        $data = array();
        foreach ($result as $row) {
            $data[date($row->created_at)][] = $row;
        }

        return view("monthly_dite", compact("data"));
    }
}
