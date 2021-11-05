<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\DailyBuying;
use App\Models\DailyDite;
use App\Models\Dite;
use App\Models\Shop;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PhpParser\Node\Stmt\TryCatch;

class BuyingController extends Controller
{
    /**
     * Display a listing of the resource.
     * 
     * @return \Illuminate\Http\Response
     * return response to  buying.blade.php 
     * display Buying Food items
     * 
     */
    public function index(Request $request)
    {
        $month = date("m");
        try {
            $input = $request->all();
            $data['data'] =  DailyDite::select(DB::raw('t.*,brands.name as brand_name'))
                ->from(DB::raw('(SELECT m1.* FROM dailybuyings m1 LEFT JOIN dailybuyings m2 
                ON m1.name = m2.name AND m1.id < m2.id and 
                (m1.shop_id=m2.shop_id and m1.brand_id=m2.brand_id and m1.unit_price = m2.unit_price) 
                WHERE m2.id IS NULL ORDER BY id asc) t'))
                ->whereMonth('t.created_at', $month)
                ->leftJoin("brands","t.brand_id","=",'brands.id')
                ->OrderBy('id', 'desc')
                ->paginate(25);
            $data['shop_id'] = isset($input['shop_id'])?$input['shop_id']:null;
            $data['shops'] = Shop::all();

            // If json response is required or default theme html
            if (isset($input['response_type']) and $input['response_type'] === "json") {
                return response()->json(compact("data"));
            } else
                return view("buying", compact("data"));
        } catch (Exception $e) {
            return response()->json(array("status" => "error", "message" => $e->getMessage()));
        }
    }


    /**
     * Add the items bought
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     *  return response to  buying.blade.php 
     * refresh Buying Food items when new are added
     */

    public function buy_meal(Request $request)
    {
        $inputs = $request->all();
        $rows = count($inputs['name']);
        $shop = (isset($inputs['shop_id']) and $inputs['shop_id'] != "") ? $inputs['shop_id'] : "null";

        try {
            for ($i = 0; $i < $rows; $i++) {
                if (isset($inputs['name'][$i]) and $inputs['name'][$i] != "") {
                    $newbuy = new  DailyBuying();
                    $newbuy->name =  $inputs['name'][$i];
                    $newbuy->shop_id =  $shop;
                    if ($inputs['unit'][$i] == "g")
                        $newbuy->weight =  $inputs['weight'][$i];
                    else
                        $newbuy->qty =  $inputs['weight'][$i];
                    $newbuy->unit_price = $inputs['weight'][$i] / $inputs['price'][$i];
                    $newbuy->price =  $inputs['price'][$i];
                    $newbuy->frequency =  isset($inputs['count'][$i]) ? $inputs['count'][$i] : 0;
                    if ($newbuy->save()) {
                        $saved[] = $inputs['name'][$i];
                    } else {
                        $rejected[] = $inputs['name'][$i];
                    }
                    $new_food_item = new Dite();
                    $row = $new_food_item->where("name", $inputs['name'][$i])->first();

                    if (!isset($row)) {
                        $new_food_item->name = $newbuy->name;
                        $new_food_item->weight = $inputs['unit'][$i] == "g" ? $newbuy->weight : 0;
                        $new_food_item->save();
                    }
                }
            }
            // return response to refresh buying page
            return $this->index($request);
        } catch (Exception $e) {
            return response()->json(array("status" => "error", "message" => $e->getMessage()));
        }
    }


    /**
     * Get buy items report per shop.
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     * 
     */

    public function buying_detail(Request $request,$id)
    {
        $input = $request->all();
        $month = date("m");
        $food_item = $input['food_item'];
        $monthly_buying = new DailyBuying();
        $data['data'] = $monthly_buying->selectRaw("dailybuyings.*,ifnull(shops.name,'') as shop_name,
                                                ifnull(brands.name,'')  as brand_name")
            ->where("dailybuyings.id", '=', $id)
            ->whereMonth('dailybuyings.created_at', $month)
            ->leftjoin("shops", 'dailybuyings.shop_id', '=', 'shops.id')
            ->leftjoin("brands", 'dailybuyings.shop_id', '=', 'brands.id')
            ->get();

            // If json response is required or default theme html            
            if (isset($input['response_type']) and $input['response_type'] === "json") {
                return response()->json($data);
            } else
            {   
                // default view code todo
                return  response()->json($data);
            }
                
    }

    /**
     * Show the form for creating a new resource.
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function edit_item_bought(Request $request)
    {
        try {
            $input = $request->all();
            $data['item'] = DailyBuying::find($input['id']);
            $data['id'] = $input['id'];
            $data['shops'] = Shop::all();
            $data['brands'] = Brand::all();

            // If json response is required or default theme html
            if (isset($input['response_type']) and $input['response_type'] === "json") {
                return response()->json(compact("data"));
            } else
                return view("edit_item_bought", compact('data'));
        } catch (Exception $e) {
            return response()->json(array("status" => "error", "message" => $e->getMessage()));
        }
    }


    /**
     * Show the form for updating a existing item.
     *
     * @return \Illuminate\Http\Response
     */
    public function update_item_bought(Request $request)
    {
        try {
            $inputs = $request->all();
            $item = DailyBuying::find($inputs['id']);
            $item->name =  $inputs['name'];
            if ($inputs['unit'] == "g")
                $item->weight =  $inputs['weight'];
            else
                $item->qty =  $inputs['weight'];
            $item->price =  $inputs['price'];
            $item->frequency =  isset($inputs['count']) ? $inputs['count'] : 0;
            $item->shop_id = isset($inputs['shop']) ? $inputs['shop'] : null;
            $item->brand_id = isset($inputs['brand']) ? $inputs['brand'] : null;
            if ($item->save()) {
                $saved[] = $inputs['name'];
            } else {
                $rejected[] = $inputs['name'];
            }
            $new_food_item = new Dite();

            $row = $new_food_item->where("name", $inputs['name'])->first();

            if (!isset($row)) {
                $new_food_item->name = $item->name;
                $new_food_item->weight = $inputs['unit'] == "g" ? $item->weight : 0;
                $new_food_item->save();


                // Add new item purchased into library. 
                $dite_results = new Dite();
                $dite_results->where("name", $item->name)->first();
                if (!isset($row)) {
                    $dite_results->name = $item->name;
                    $dite_results->weight = $inputs['unit'] == "g" ? $item->weight : 0;
                    $dite_results->save();
                }
            }
            $item->save();

            // If json response is required or default theme html
            if (isset($input['response_type']) and $input['response_type'] == "json") {
                return response()->json(json_encode(json_decode($item)));
            } else {
                return view("buying_item_card", compact("item"));
            }
        } catch (\Throwable $th) {
            return response()->json(array("status" => "error", "exception" => $th));
        }
    }

    /**
     * Delete the baught item. 
     *
     * @param $id
     */
    public function delete_item_bought($id)
    {
        try {
            $item = DailyBuying::where("id", $id)->delete();
            return response()->json(json_encode(json_decode($item)));
        } catch (Exception $e) {
            return response()->json(array("status" => "error", "message" => $e->getMessage()));
        }
    }

    /**
     * get monthly data report for buying items
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function show_monthly_buying(Request $request)
    {
        try {
            $month = date("m");
            $monthly_buy_dite = new DailyBuying();
            $result = $monthly_buy_dite->selectRaw("dailybuyings.name,dailybuyings.created_at, 
        dailybuyings.weight, dailybuyings.qty, coalesce(shops.name, 'No Shop Selected') as shopname, 
            dailybuyings.price")
                ->leftjoin("shops", 'dailybuyings.shop_id', '=', 'shops.id')
                ->whereMonth('dailybuyings.created_at', $month)
                ->orderBy("dailybuyings.created_at", "desc")
                ->get();
            $data = array();
            // Group the items by date
            foreach ($result as $row) {
                $data[date('Y-m-d', strtotime($row->created_at))][$row->shopname][] = $row;
            }

            // If json response is required or default theme html
            if (isset($input['response_type']) and $input['response_type'] == "json") {
                return response()->json($data);
            } else
                return view("buy_monthly_meal", compact("data"));
        } catch (Exception $e) {
            return response()->json(array("status" => "error", "message" => $e->getMessage()));
        }
    }
}
