<?php

namespace App\Http\Controllers;

use App\Models\DailyDite;
use App\Models\Dite;
use App\Models\UserWeight;
use Exception;
use Hamcrest\Type\IsNumeric;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Intervention\Image\ImageManagerStatic as Image;



class DailyDiteController extends Controller
{
    /**
     * Return daily consumed food items list.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response JSON OR (default html)
     */
    public function home(Request $request)
    {
        try {
            $input = $request->all();
            if (isset($input['month']) and $input['month'] != "") {
                $date = explode("-", $input['month']);
                $month = $date[0];
                $year = $date[1];
            } else {
                $month = date("m");
                $year = date("Y");
            }
            $data['month'] = $month;
            $data['year'] = $year;
            $data['data'] =  DailyDite::select(DB::raw('t.*'))
                ->from(DB::raw('(SELECT m1.* FROM dailydite m1 LEFT JOIN 
                                        dailydite m2 ON (m1.name = m2.name AND m1.id < m2.id) 
                                        WHERE m2.id IS NULL ORDER BY id asc) t'))
                ->whereMonth('t.created_at', $month)
                ->whereYear('t.created_at', $year)
                ->OrderBy('id', 'desc')
                ->paginate(25);


            // If json response is required or default theme html
            if (isset($input['response_type']) and $input['response_type'] === "json") {
                return response()->json(compact("data"));
            } else
                return view("home", compact("data"));
        } catch (Exception $e) {
            return response()->json(array("status" => "error", "message" => $e->getMessage()));
        }
    }


    /**
     * Return daily consumed food items list.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response JSON OR (default html)
     */

    public function library()
    {
        try {
            $data =  Dite::orderBy("id", 'desc')->paginate(50);
            if (isset($input['response_type']) and $input['response_type'] === "json") {
                return response()->json(compact("data"));
            } else
                return view("library", compact("data"));
        } catch (Exception $e) {
            return response()->json(array("status" => "error", "message" => $e->getMessage()));
        }
    }


    /**
     * Food item monthly report.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response JSON
     */

    public function food_item_report(Request $request)
    {
        try {
            $input = $request->all();
            $input = $request->all();
            if (isset($input['month']) and $input['month'] != "") {
                $date = explode("-", $input['month']);
                $month = $date[0];
                $year = $date[1];
            } else {
                $month = date("m");
                $year = date("Y");
            }
            $food_item = $input['food_item'];
            $monthly_dite = new DailyDite();
            $data['data'] = $monthly_dite->selectRaw("dailydite.name, dailydite.qty as qty, dailydite.weight as weight, 
                                        dailydite.created_at, ifnull(dite.energy,0) as cal")
                ->where("dailydite.name", '=', $food_item)
                ->whereMonth('dailydite.created_at', $month)
                ->whereYear('dailydite.created_at', $year)
                ->leftjoin("dite", 'dailydite.name', '=', 'dite.name')
                ->get();
            return  response()->json($data);
        } catch (Exception $e) {
            return response()->json(array("status" => "error", "message" => $e->getMessage()));
        }
    }



    /**
     * Food item monthly report.
     *
     * @param int $id
     * @return \Illuminate\Http\Response JSON
     */
    public function dite_defaults(int $id)
    {
        try {
            $dite = Dite::find($id);
            return view('dite_defaults', ["dite" => $dite]);
        } catch (Exception $e) {
            return response()->json(array("status" => "error", "message" => $e->getMessage()));
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\DailyDite  $dailyDite
     * @return \Illuminate\Http\Response
     */
    public function delete_food_item(Request $request)
    {
        try {
            $input = $request->all();
            $id = $input['id'];
            $food_item = Dite::where("id", $id)->delete();
            if ($food_item) {
                return response()->json(array("status" => "success", "message" => "item Successfully deleted"));
            } else {
                return response()->json(array("status" => "error", "message" => "Database error occured. Please contact site administrator"));
            }
        } catch (Exception $e) {
            return response()->json(array("status" => "error", "message" => $e->getMessage()));
        }
    }




    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function add_dite(Request $request)
    {
        $input = $request->all();
        try {
            // if the input is integer followed by kg it meant persons weight is entered.
            if (is_numeric(str_replace("kg", "", $input['dite']))) {
                $userweight = UserWeight::Create(
                    ['user_id' => 1, 'weight' => $input['dite']]
                );
                $data = ['weight' => $input['dite'], 'status' => 'success', 'msg' => '<strong>Success!</strong> Weight successfully Added'];
                return response()->json($data);
            } else {
                //explode input data string by "," to create list of consumed food items
                $ditelist = explode(",", $input['dite']);
                //loop on the consumed food items
                foreach ($ditelist as $dite) {
                    $dite_results = new Dite();

                    // if empty wrong food item input
                    if (trim($dite) == '')
                        continue;
                    $dailydite = new DailyDite();

                    // explode the food item by " " for further porcessing. 
                    $diteitem = explode(" ", trim($dite));
                    $arr_length = count($diteitem);


                    // if food item is like formate "food item name 100g". check if in the end there is integer followed by g.
                    // it means food is consumed in weight. 
                    if (strpos($diteitem[$arr_length - 1], 'g') and  is_numeric(str_replace("g", "", $diteitem[$arr_length - 1]))) {
                        // get the weight
                        $weight = str_replace("g", "", $diteitem[$arr_length - 1]);

                        // if the 2nd last word is an integer or an integer followed by pcs, it is quantity.
                        $qty = is_numeric(str_replace("pcs", "", $diteitem[$arr_length - 2])) ? str_replace("pcs", "", $diteitem[$arr_length - 2]) : 0;

                        // to get the name of the food item if quantity is not entered. remove the last word from text and the rest is the name.
                        // else remove the 2nd last and last words from text and the rest is the name of the food item.

                        if ($qty == 0) {
                            $name = trim(str_replace($diteitem[$arr_length - 1], "", $dite));
                        } else {
                            $name = trim(str_replace(array($diteitem[$arr_length - 1], $diteitem[$arr_length - 2]), "", $dite));
                        }
                        // check if the food item is already in food item library. 
                        // will insert into library if not found.
                        $row = $dite_results->where("name", $name)->first();
                    }
                    // if the last item is integer followed by pcs i.e format like "Food item name 2pcs" or "Food item Name 2"
                    else if (is_numeric(str_replace("pcs", "", $diteitem[$arr_length - 1]))) {
                        // last word as quantity
                        $qty = str_replace("pcs", "", $diteitem[$arr_length - 1]);
                        // remove the last word for text to get the name. 
                        $name = trim(str_replace($diteitem[$arr_length - 1], "", $dite));
                        // check if the food item is already in food item library. 
                        // will insert into library if not found.
                        $row = $dite_results->where("name", $name)->first();
                        //if item found in library and default weight is set then get the default weight and multiply with quantity to find the
                        // weight consumed.
                        $weight = (isset($row->weight) and $row->weight != false) ? (int)str_replace("g", "", $row->weight) * $qty : 0;
                    }
                    // if no integer or integer followed by g/pcs. e.g "Food item name"
                    // count quantity as 1
                    // get the default weight from food item library. 
                    else {
                        $qty = 1;
                        $name = trim($dite);
                        $row = $dite_results->where("name", $name)->first();
                        $weight = (isset($row->weight) and $row->weight != false) ? (int)str_replace("g", "", $row->weight) : 0;
                    }

                    // if no valid name found. check for other food item in the string. 
                    if ($name == "")
                        continue;

                    // save the food item.
                    $dailydite->name = $name;
                    $dailydite->qty = $qty;
                    $dailydite->weight = $weight;
                    $dailydite->save();


                    // if food item not found in library then add to library. 
                    if (!isset($row)) {
                        $dite_results->name = $name;
                        $dite_results->weight = $dailydite->weight;
                        $dite_results->save();
                    }
                }
            }
        } catch (Exception $e) {
            return response()->json(array("status" => "error", "err_code" => $e->getCode(), "message" => $e->getMessage()));
        }

        $data =  DailyDite::select(DB::raw('t.*'))
            ->from(DB::raw('(SELECT * FROM dailydite ORDER BY created_at DESC) t'))
            ->groupBy('t.name')
            ->get();
        return redirect()->route("home", compact($data));
    }


    /**
     * Udate  resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update_dite(Request $request)
    {
        $input = $request->all();
        $created_at = $input['created_at'];
        $ditelist = explode(",", $input['dite']);
        DailyDite::where("created_at", $created_at)->delete();
        foreach ($ditelist as $dite) {

            $diteitem = explode(" ", trim($dite));
            $arr_length = count($diteitem);
            $dite_results = new Dite();
            if (strpos($diteitem[$arr_length - 1], 'g') and  is_numeric(str_replace("g", "", $diteitem[$arr_length - 1]))) {
                $weight = str_replace("g", "", $diteitem[$arr_length - 1]);

                $qty = is_numeric(str_replace("pcs", "", $diteitem[$arr_length - 2])) ? str_replace("pcs", "", $diteitem[$arr_length - 2]) : 0;

                if ($qty == 0) {
                    $name = trim(str_replace($diteitem[$arr_length - 1], "", $dite));
                } else {
                    $name = trim(str_replace(array($diteitem[$arr_length - 1], $diteitem[$arr_length - 2]), "", $dite));
                }
                $row = $dite_results->where("name", $name)->first();
            } else if (is_numeric(str_replace("pcs", "", $diteitem[$arr_length - 1]))) {
                $qty = str_replace("pcs", "", $diteitem[$arr_length - 1]);
                $name = trim(str_replace($diteitem[$arr_length - 1], "", $dite));
                $row = $dite_results->where("name", $name)->first();
                $weight = (isset($row->weight) and $row->weight != false) ? (int)str_replace("g", "", $row->weight) * $qty : 0;
            } else {
                $qty = 1;
                $name = trim($dite);
                $row = $dite_results->where("name", $name)->first();
                $weight = (isset($row->weight) and $row->weight != false) ? (int)str_replace("g", "", $row->weight) : 0;
            }

            if ($name == "")
                continue;

            $dailydite =  new DailyDite(); //::where("name",$name)->where("created_at",$created_at);
            $dailydite->name = $name;
            $dailydite->qty = $qty;
            $dailydite->created_at = $created_at;
            $dailydite->weight = $weight;
            $dailydite->save();

            if (!isset($row)) {
                $dite_results->name = $name;
                $dite_results->weight = $dailydite->weight;
                $dite_results->save();
            }
            //unset($row);           

        }

        return redirect()->route("monthly_dite"); //, compact($data));



    }


    /**
     * Delete the resource 
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response 
     */

    public function delete_meal(Request $request)
    {
        $input = $request->input();
        try {
            $dailydite = DailyDite::where("created_at", $input['created_at'])->delete();
            if ($dailydite) {
                return response()->json(array("status" => "success", "message" => "Successfully delete meal"));
            } else
                return response()->json(array("status" => "error", "message" => "Database Error Occured. Please contact site administrator"));
        } catch (Exception $e) {
            return response()->json(array("status" => "error", "message" => $e->getMessage()));
        }
    }

    /**
     * 
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response 
     */

    public function edit_meal(Request $request)
    {
        $food = "";
        $input = $request->input();
        $dailydite = DailyDite::where("created_at", $input['created_at'])->get();
        foreach ($dailydite as $row) {
            $food .= ($food != "" ? "," : "") .  $row['name']  . ($row->qty ? " " . $row->qty : "") . ($row->weight ? " " . $row->weight . "g" : "");
        }
        print_r(json_encode(array("created_at" => $input['created_at'], "food" => $food)));
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\DailyDite  $dailyDite
     * @return \Illuminate\Http\Response
     */
    public function add_dite_defaults(Request $request)
    {

        $input = $request->all();
        $defaults = substr($input['defaults'], strpos($input['defaults'], "=") + 1);
        $name = trim(explode("=", $input['defaults'])[0]);
        $defaults = explode(",", $defaults);
        $weight = trim($defaults[0]);

        $energy = (int) trim(str_replace("cal/100g", "", $defaults[1]));
        if (is_numeric($energy)) {
            if (isset($input['id']) and $input['id'] != "") {
                $id = $input['id'];
                $dite =  Dite::find($id);
                $dite->weight = $weight;
                $dite->energy = $energy;
                $dite->save();
            } else {
                $dite =  new Dite();
                $dite->name = trim($name);
                $dite->weight = $weight;
                $dite->energy = $energy;
                $dite->save();
            }
        } else {
            return response()->json(array("success" => "error", "message" => "Invalide Format"));
        }
        $dailydite = DB::statement("update `dailydite` set `weight` = qty * " . str_replace("g", "", $weight) . " where `name` = '$name' and `weight` = 0");

        $data =  Dite::paginate(50);
        // return response().back();//redirect()->route("library", compact($data));

        return view("library", compact("data"));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\DailyDite  $dailyDite
     * @return \Illuminate\Http\Response
     */
    public function edit(int $id, DailyDite $dailyDite)
    {
        return $dailyDite->find($id)->toJson();
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function user_weight_report(Request $request)
    {
        $input = $request->all();
        if (isset($input['month']) and $input['month'] != "") {
            $date = explode("-", $input['month']);
            $month = $date[0];
            $year = $date[1];
        } else {
            $month = date("m");
            $year = date("Y");
        }
        $data['data'] = UserWeight::where("user_id", "1")
            ->whereMonth('created_at', $month)
            ->whereYear('created_at', $year)
            ->get();
        return response()->json($data);
    }
}
