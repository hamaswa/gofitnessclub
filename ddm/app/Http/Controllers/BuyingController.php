<?php

namespace App\Http\Controllers;

use App\Models\DailyBuying;
use App\Models\DailyDite;
use App\Models\Dite;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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
            $newbuy->name = $fooditem[0];
            $newbuy->weight = str_replace("g", "", strtolower($fooditem[1]));
            $newbuy->price = str_replace("RM", "", strtoupper($fooditem[2]));
            $newbuy->frequency = $fooditem[3];
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

     public function buying_detail(Request $request){
         
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
