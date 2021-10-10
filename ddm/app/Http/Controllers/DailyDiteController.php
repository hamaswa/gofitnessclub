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
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function home()
    {
        $data =  DailyDite::select(DB::raw('t.*'))
            ->from(DB::raw('(SELECT m1.* FROM dailydite m1 LEFT JOIN 
                                        dailydite m2 ON (m1.name = m2.name AND m1.id < m2.id) 
                                        WHERE m2.id IS NULL ORDER BY id asc) t'))
            ->OrderBy('id', 'desc')
            ->paginate(25);
        // print_r($data);                      
        return view("home", compact("data"));
    }

    public function library()
    {
        $data =  Dite::orderBy("id", 'desc')->paginate(50);
        return view("library", compact("data"));
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

    public function food_item_report(Request $request)
    {
        $input = $request->all();
        $month = date("m");
        $food_item = $input['food_item'];
        $monthly_dite = new DailyDite();
        $data['data'] = $monthly_dite->selectRaw("dailydite.name, dailydite.qty as qty, dailydite.weight as weight, 
                                        dailydite.created_at, ifnull(dite.energy,0) as cal")
            ->where("dailydite.name", '=', $food_item)
            ->whereMonth('dailydite.created_at', $month)
            ->leftjoin("dite", 'dailydite.name', '=', 'dite.name')
            ->get();
        return  response()->json($data);
    }

    public function show_monthly_dite(int $month, Request $request)
    {
        $input = $request->all();
        //$month = $input['month'];
        $monthly_dite = new DailyDite();
        $data = $monthly_dite->whereMonth('created_at', $month)->orderBy("created_at", "asc")->get();
        $response_data = array();
        foreach ($data as $row) {
            $templateData[date("Y-m-d", strtotime($row->created_at))][] = $row;
        }
        return response()->json(compact("templateData"));
    }

    public function show_monthly_dite_report(int $month, Request $request)
    {
        $input = $request->all();
        //$month = $input['month'];
        $monthly_dite = new DailyDite();
        $data = $monthly_dite->selectRaw("name,sum(qty) as qty,sum(weight) as weight")
            ->whereMonth('created_at', $month)
            ->groupBy("name")
            ->get();
        return response()->json(compact("data"));
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
    
    public function dite_defaults(int $id)
    {
        $dite = Dite::find($id);
        return view('dite_defaults', ["dite" => $dite]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\DailyDite  $dailyDite
     * @return \Illuminate\Http\Response
     */
    public function delete_food_item(Request $request)
    {
        $input = $request->all();
        $id = $input['id'];
        $food_item = Dite::where("id", $id)->delete();
        print_r($food_item);
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
        $ditelist = explode(",", $input['dite']);

        if (is_numeric(str_replace("kg", "", $input['dite']))) {
            $userweight = UserWeight::Create(
                ['user_id' => 1, 'weight' => $input['dite']]
            );
            $data = ['weight' => $input['dite'], 'status' => 'success', 'msg' => '<strong>Success!</strong> Weight successfully Added'];
            return response()->json($data);
        } else {
            foreach ($ditelist as $dite) {
                $dailydite = new DailyDite();
                $diteitem = explode(" ", trim($dite));
                //$dailydite = new DailyDite();//::whereDate('created_at' , Carbon::today())
                //->firstOrNew(array('name' => $diteitem[0])); 

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

                $dailydite->name = $name;
                $dailydite->qty = $qty;
                $dailydite->weight = $weight;
                $dailydite->save();

                if (!isset($row)) {
                    $dite_results->name = $name;
                    $dite_results->weight = $dailydite->weight;
                    $dite_results->save();
                }
                //unset($row);           

            }
        }

        $data =  DailyDite::select(DB::raw('t.*'))
            ->from(DB::raw('(SELECT * FROM dailydite ORDER BY created_at DESC) t'))
            ->groupBy('t.name')
            ->get();
        return redirect()->route("home", compact($data));
    }


    /**
     * Store a newly created resource in storage.
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
     * 
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response 
     */

    public function delete_dite(Request $request)
    {
        $input = $request->input();
        $dailydite = DailyDite::where("created_at", $input['created_at'])->delete();
        print_r($dailydite);
    }

    /**
     * 
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response 
     */

    public function edit_dite(Request $request)
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
        
        $energy = (int) trim(str_replace("cal/100g","", $defaults[1]));
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
            return response()->json(array("success"=>"error","message"=>"Invalide Format"));

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
        $data['data'] = UserWeight::where("user_id", "1")->get();
        return response()->json($data);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\DailyDite  $dailyDite
     * @return \Illuminate\Http\Response
     */
    public function destroy(DailyDite $dailyDite)
    {
        //
    }
}
