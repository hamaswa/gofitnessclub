<?php

namespace App\Http\Controllers;

use App\Models\DailyBuying;
use App\Models\DailyDite;
use Illuminate\Http\Request;

class CommonController extends Controller
{
   
    /**
     * Store or update image for dite.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function upload_image(Request $request)
    {

        $input = $request->all();
        $id = $input['id'];
        if($input['table']=="dailydite"){
        $foodItem = DailyDite::find($id);
        }
        else 
        $foodItem = DailyBuying::find($id);


        if (!$request->hasFile('image')) {
            return response()->json(['upload_file_not_found'], 400);
        }
        $file = $request->file('image');
        if (!$file->isValid()) {
            return response()->json(['invalid_file_upload'], 400);
        }
        if (isset($foodItem->image) and $foodItem->image != "")
            if (file_exists(public_path() . $foodItem->image))
                unlink(public_path() . $foodItem->image);
        $path =  public_path() . '/uploads/images/store/';
        $name = time() . '.' . $file->getClientOriginalName();
        $file->move($path, $name);

        $foodItem->image = '/uploads/images/store/' . $name;
        $foodItem->save();
        $class = "";
        if (file_exists($path . $name)) {
            $image = getimagesize($path . $name);
            $width = $image[0];
            $height = $image[1];
            $class = ($width / $height) < 1.2 ? "img-full-width" : "img-full-height";
        }
        return response()->json(array("image" => $foodItem->image, "class" => $class));
    }
}
