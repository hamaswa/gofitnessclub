<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\Shop;
use App\Models\ShopList;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SettingsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function get_shop_list()
    {
        $data =  Shop::select(DB::raw('t.*'))
            ->from(DB::raw('(SELECT m1.* FROM shops m1 LEFT JOIN 
                                    shops m2 ON (m1.name = m2.name AND m1.id < m2.id) 
                                    WHERE m2.id IS NULL ORDER BY id asc) t'))
            ->OrderBy('id', 'desc')
            ->paginate(25);
        return view("shoplist", compact("data"));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

    public function save_shop_list(Request $request)
    {
        $input = $request->all();
        if (!isset($input['id']) OR $input['id'] === "") {
            $shoplist = explode(",", trim($input['input']));
            foreach ($shoplist as $shop) {
                $shopdetail = explode("-", trim($shop));
                $shopname = trim($shopdetail[0]);
                $shoplocation = isset($shopdetail[1]) ? trim($shopdetail[1]) : "";
                $shop = new Shop();
                $shop->name = $shopname;
                $shop->location = $shoplocation;
                $shop->save();
            }
        }
        else {
            $shopdetail = explode("-", trim($input['input']));
            $shop = Shop::find($input['id']);
            $shop->name= trim($shopdetail[0]);
            $shop->location = isset($shopdetail[1]) ? trim($shopdetail[1]) : "";
            $shop->save();

        }

        return response()->json(array("status" => "success", "msg" => "<strong>Success!</strong> Item Successfully Added"));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function delete_shop($id)
    {
        try {
            $shop = Shop::where("id", $id)->delete();
            return response()->json(array("status" => "success", "msg" => "<strong>Success!</strong> Item Successfully Added"));
        } catch (\Throwable $th) {
            //throw $th;
        }
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function get_brand_list()
    {
        $data =  Brand::select(DB::raw('t.*'))
            ->from(DB::raw('(SELECT m1.* FROM brands m1 LEFT JOIN 
                                    brands m2 ON (m1.name = m2.name AND m1.id < m2.id) 
                                    WHERE m2.id IS NULL ORDER BY id asc) t'))
            ->OrderBy('id', 'desc')
            ->paginate(25);
        return view("brandlist", compact("data"));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

    public function save_brand_list(Request $request)
    {
        $input = $request->all();
        if (!isset($input['id']) OR $input['id'] === "") {
            $brandlist = explode(",", trim($input['input']));
            foreach ($brandlist as $brand) {
                // $branddetail = explode("-", trim($brand));
                $brandname = trim($brand);
               // $brandlocation = isset($branddetail[1]) ? trim($branddetail[1]) : "";
                $brand = new Brand();
                $brand->name = $brandname;
                //$brand->location = $brandlocation;
                $brand->save();
            }
        }
        else {
            $brandname =  trim($input['input']);
            $brand = Brand::find($input['id']);
            $brand->name= trim($brandname);
            $brand->save();

        }

        return response()->json(array("status" => "success", "msg" => "<strong>Success!</strong> Item Successfully Saved"));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function delete_brand($id)
    {
        try {
            $brand = Brand::where("id", $id)->delete();
            return response()->json(array("status" => "success", "msg" => "<strong>Success!</strong> Brand Successfully Added"));
        } catch (\Throwable $th) {
            //throw $th;
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }
}
