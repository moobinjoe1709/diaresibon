<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use App;
use PDF;
class ReportController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $boxs = DB::table('tb_boxs')
                    ->groupBy('bo_sale_ccn', 'bo_mas_loc','bo_promise_date','bo_ship_via','bo_customer')
                    ->orderBy('bo_pd_id','asc')
                    ->get();

        $data = array(
            'boxs' => $boxs,
        );

        return view('report.index',$data);
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
        $containers = DB::table('tb_containers')
                        ->groupBy('ctn_number')
                        ->where('ctn_pd_id',$id)->get();

        $data = array(
            'containers' => $containers,
            'id'         => $id,
        );
            
        if(count($containers) > 0){
            return view('report.show',$data);
        }else{
            return redirect(url('report'))->with('danger','Shipment นี้ยังไม่มีการ Load Container!!');
        }
           
          
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
