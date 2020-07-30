<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Pallets;
use DB;
use Session;
class ContainerController extends Controller
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
                    ->orderBy('bo_so','asc')
                    ->get();
        // $tb_products = DB::table('tb_products')->get();  
        $data = array(
            'boxs' => $boxs,
            // 'tb_products' =>$tb_products,
        );

        return view('container.index',$data);
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
        dd($id);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        
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

    public function LoadContianer($id,$type){
        Session::forget('container');
        Session::forget('pallet_weight');
        // $pallets = DB::table('tb_pallet')
        //                     ->select('tb_pallet.*','tb_boxs.*','tb_items.*','tb_subitems.*','tb_typepalate.*')
        //                     ->leftjoin('tb_boxs','tb_pallet.tpl_bo_id','=','tb_boxs.bo_id')
        //                     ->leftjoin('tb_items','tb_boxs.bo_item','=','tb_items.it_name') 
        //                     ->leftjoin('tb_subitems','tb_items.it_id','=','tb_subitems.sit_it_id')
        //                     ->leftjoin('tb_typepalate','tb_subitems.sit_pallet','=','tb_typepalate.tp_id')
        //                     ->groupBy('tp_id')
        //                     ->where('tpl_pd_id',$id)
        //                     ->get();

        $pallets = DB::table('tb_mainpallet')
                            ->select('tb_mainpallet.*','tb_pallet.*','tb_boxs.*','tb_items.*','tb_subitems.*','tb_typepalate.*')
                            ->leftjoin('tb_pallet','tb_mainpallet.mp_id','=','tb_pallet.tpl_mp_id')
                            ->leftjoin('tb_boxs','tb_pallet.tpl_bo_id','=','tb_boxs.bo_id')
                            ->leftjoin('tb_items','tb_boxs.bo_item','=','tb_items.it_name') 
                            ->leftjoin('tb_subitems','tb_items.it_id','=','tb_subitems.sit_it_id')
                            ->leftjoin('tb_typepalate','tb_subitems.sit_pallet','=','tb_typepalate.tp_id')
                            ->groupBy('tpl_mp_id')
                            ->where('tpl_pd_id',$id)    
                            ->whereNull('mp_status_load')
                            // ->where('mp_pallet_qty','<>',0)    
                            ->where('mp_qty','>',0)    
                            ->get();
        // dd($pallets,$id);
        $pallets2 = DB::table('tb_mainpallet')
                            ->select('tb_mainpallet.*','tb_pallet.*','tb_boxs.*','tb_items.*','tb_subitems.*','tb_typepalate.*')
                            ->leftjoin('tb_pallet','tb_mainpallet.mp_id','=','tb_pallet.tpl_mp_id')
                            ->leftjoin('tb_boxs','tb_pallet.tpl_bo_id','=','tb_boxs.bo_id')
                            ->leftjoin('tb_items','tb_boxs.bo_item','=','tb_items.it_name') 
                            ->leftjoin('tb_subitems','tb_items.it_id','=','tb_subitems.sit_it_id')
                            ->leftjoin('tb_typepalate','tb_subitems.sit_pallet','=','tb_typepalate.tp_id')
                            ->groupBy('mp_id')
                            ->where('tpl_pd_id',$id)    
                            ->whereNull('mp_status_load')
                            ->where('mp_pallet_qty','>',0)    
                            ->where('mp_qty','<>',0)             
                            ->get();
    
        $pallets3 = DB::table('tb_mainpallet')
                            // ->select('tb_mainpallet.*','tb_pallet.*','tb_boxs.*','tb_items.*','tb_subitems.*','tb_typepalate.*')
                            ->leftjoin('tb_pallet','tb_mainpallet.mp_id','=','tb_pallet.tpl_mp_id')
                            ->leftjoin('tb_boxs','tb_pallet.tpl_bo_id','=','tb_boxs.bo_id')
                            ->leftjoin('tb_items','tb_boxs.bo_item','=','tb_items.it_name') 
                            ->leftjoin('tb_subitems','tb_items.it_id','=','tb_subitems.sit_it_id')
                            ->leftjoin('tb_typepalate','tb_subitems.sit_pallet','=','tb_typepalate.tp_id')
                            // ->groupBy('tpl_mp_id')
                            ->where('mp_pd_id',$id)    
                            ->where('mp_status_load',0) 
                            
                            ->Orwhere('mp_status_load',null) 
                            ->Orwhere('mp_status_load',"") 
                            ->get();
        // dd($pallets3 );
        $data = array(
            'pallets' => $pallets,
            'pallets2' => $pallets2,
            'pallets3' => $pallets3,
            'boxs' => $pallets,
            'id' => $id,
            'type' => $type,

        );
        // dd($data);
        if($type == 1){
            return view('box.auto',$data);
        }else{
            return view('box.manual',$data);
        }
        // dd($pallets);
       
        // return view('box.auto');
    }

    

    
}
