<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\DailyBuying;
use App\Models\DailyDite;
use App\Models\Shop;
use Illuminate\Http\Request;

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
}
