<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\DailyBuying;
use App\Models\DailyDite;
use App\Models\Dite;
use App\Models\Shop;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PhpParser\Node\Stmt\TryCatch;

class BuyingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $input = $request->all();
        $data =  DailyDite::select(DB::raw('t.*'))
            ->from(DB::raw('(SELECT m1.* FROM dailybuyings m1 LEFT JOIN 
            dailybuyings m2 ON (m1.name = m2.name AND m1.id < m2.id) 
                                    WHERE m2.id IS NULL ORDER BY id asc) t'))
            ->OrderBy('id', 'desc')
            ->paginate(25);
        if (isset($input['response_type']) and $input['response_type'] === "json") {
            return response()->json(compact("data"));
        } else
            return view("buying", compact("data"));
    }


    /**
     * Add the items bought
     * @return \Illuminate\Http\Response
     * @param  \Illuminate\Http\Request  $request
     */

    public function buy_meal(Request $request)
    {
        $input = $request->all();
        print_r($input);
        exit();
        $saved = [];
        $rejected = [];
        $itemlist = explode(",", $input['items']);

        foreach ($itemlist as $item) {
            $newbuy = new  DailyBuying();
            $fooditem = explode(" ", trim($item));
            $newbuy->weight = str_replace("g", "", strtolower($fooditem[count($fooditem)-3]));
            $newbuy->price = str_replace("RM", "", strtoupper($fooditem[count($fooditem)-2]));
            $lastval = $fooditem[count($fooditem)-1];
            if(is_numeric($lastval) OR strpos($lastval,"x")){
                $newbuy->frequency = $lastval;
            } 
            unset($fooditem[count($fooditem)-1]);
            unset($fooditem[count($fooditem)-1]);
            unset($fooditem[count($fooditem)-1]);

            $newbuy->name =   trim(implode(" ", $fooditem));

            if ($newbuy->save()) {
                $saved[] = $item;
            } else {
                $rejected[] = $item;
            }
        }

        $data =  DailyBuying::select(DB::raw('t.*'))
            ->from(DB::raw('(SELECT * FROM dailybuyings ORDER BY created_at DESC) t'))
            ->groupBy('t.name')
            ->get();
        return redirect()->route("buying-index", compact("data"));
    }


    /**
     * 
     * 
     */

    public function buying_detail(Request $request)
    {
        $input = $request->all();
        $month = date("m");
        $food_item = $input['food_item'];
        $monthly_buying = new DailyBuying();
        $data['data'] = $monthly_buying->selectRaw("dailybuyings.*,ifnull(shops.name,'') as shop_name,
                                                ifnull(brands.name,'')  as brand_name")
            ->where("dailybuyings.name", '=', $food_item)
            ->whereMonth('dailybuyings.created_at', $month)
            ->leftjoin("shops", 'dailybuyings.shop_id', '=', 'shops.id')
            ->leftjoin("brands", 'dailybuyings.shop_id', '=', 'brands.id')
            ->get();
        return  response()->json($data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function edit_item_bought(Request $request)
    {
        $input = $request->all();
        $data['item'] = DailyBuying::find($input['id']);
        $data['id'] = $input['id'];
        $data['shops'] = Shop::all();
        $data['brands'] = Brand::all();
        return view("edit_item_bought", compact('data'));
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function update_item_bought(Request $request)
    {
        try {
            $input = $request->all();
            $item = DailyBuying::find($input['id']);
            //print_r($fooditem);
            $food_item = explode(" ", trim($input['food-item']));
            $item->weight = str_replace("g", "", strtolower($food_item[count($food_item)-3]));
            $item->price = str_replace("RM", "", strtoupper($food_item[count($food_item)-2]));
            $item->frequency = $food_item[count($food_item)-1];
            unset($food_item[count($food_item)-1]);
            unset($food_item[count($food_item)-1]);
            unset($food_item[count($food_item)-1]);
            $item->name =   implode(" ", $food_item);
            $item->shop_id = $input['shop'];
            $item->brand_id = $input['brand'];
            $item->save();
            if(isset($input['response_type']) and $input['response_type']=="json"){
                return response()->json(json_encode(json_decode($item)));
            }
            else {
                return view("buying_item_card",compact("item"));
            }
            
        } catch (\Throwable $th) {
            return response()->json(array("status"=>"error","exception"=>$th));
        }
    }

     /**
     * Show the form for creating a new resource.
     *
     * @param $id
     */
    public function delete_item_bought($id)
    {
        try {
            $item = DailyBuying::where("id", $id)->delete();
            return response()->json(json_encode(json_decode($item)));
            
        } catch (\Throwable $th) {
            return response()->json(array("status"=>"error","exception"=>$th));
        }
    }

    /**
     *
     * @return \Illuminate\Http\Response
     */
    public function show_monthly_buying(Request $request)
    {
        $month = date("m");
        $monthly_buy_dite = new DailyBuying();
        $result = $monthly_buy_dite->selectRaw("dailybuyings.name,dailybuyings.created_at, dailybuyings.weight, 
             dailybuyings.price")
            ->whereMonth('dailybuyings.created_at', $month)
            ->orderBy("dailybuyings.created_at", "desc")
            ->get();
            $data = array();
            foreach ($result as $row) {
                $data[date('Y-m-d',strtotime($row->created_at))][] = $row;
            }
            return view("buy_monthly_meal", compact("data"));

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

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
