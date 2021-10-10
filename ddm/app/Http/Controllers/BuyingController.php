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
        if (isset($input['response-type']) and $input['response-type'] === "json") {
            return response() . json_encode(compact("data"));
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
        $saved = [];
        $rejected = [];
        $itemlist = explode(",", $input['items']);

        foreach ($itemlist as $item) {
            $newbuy = new  DailyBuying();
            $fooditem = explode(" ", trim($item));
            $newbuy->weight = str_replace("g", "", strtolower($fooditem[count($fooditem)-3]));
            $newbuy->price = str_replace("RM", "", strtoupper($fooditem[count($fooditem)-2]));
            $newbuy->frequency = $fooditem[count($fooditem)-1];
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
        return redirect()->route("buying-index", compact($data));
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
        $data['data'] = $monthly_buying->where("dailybuyings.name", '=', $food_item)
            ->whereMonth('dailybuyings.created_at', $month)
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
            $fooditem = DailyBuying::find($input['id']);
            $food_item = explode(" ", trim($input['food-item']));
            $fooditem->weight = str_replace("g", "", strtolower($food_item[count($food_item)-3]));
            $fooditem->price = str_replace("RM", "", strtoupper($food_item[count($food_item)-2]));
            $fooditem->frequency = $food_item[count($food_item)-1];
            unset($food_item[count($food_item)-1]);
            unset($food_item[count($food_item)-1]);
            unset($food_item[count($food_item)-1]);
            $fooditem->name =   implode(" ", $food_item);
            $fooditem->shop_id = $input['shop'];
            $fooditem->brand_id = $input['brand'];
            $fooditem->save();
        } catch (\Throwable $th) {
            //throw $th;
        }
    }

    /**
     *
     * @return \Illuminate\Http\Response
     */
    public function show_monthly_buying(Request $request)
    {
        return "To Do";
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
