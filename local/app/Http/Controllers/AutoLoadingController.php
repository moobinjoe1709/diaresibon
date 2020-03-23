<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Session;
use App\Boxs;
use App\Pallets;
use App\Mainpallet;
use App\Container;
use App\ContainerDetial;
use DB;
class AutoLoadingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return redirect(url('box').'/1'.'/edit');
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
    
        
        
        $count_container = Container::whereRaw('ctn_number = (select max(`ctn_number`) from tb_containers)')->first();

        if($count_container == null){
            $max_container_num = 1;
        }else{
            $max_container_num = $count_container->ctn_number + 1;
        }

        $qty_pallet = 0;
        $check_lap = 0;

       

        foreach(Session::get('cart') as $key => $value){

            $mainpallet = Mainpallet::find($value['id']);
            $mainpallet2 = Mainpallet::find($value['id']);

            $qty_pallet += $value['qty'];

            $pallets = DB::table('tb_pallet')
                            ->leftjoin('tb_boxs','tb_pallet.tpl_bo_id','=','tb_boxs.bo_id')
                            ->leftjoin('tb_items','tb_boxs.bo_item','=','tb_items.it_name')
                            ->leftjoin('tb_subitems','tb_items.it_id','=','tb_subitems.sit_it_id')
                            ->where('tpl_mp_id',$value['id'])
                            ->get();
            
            foreach($pallets as $key2 => $pallet){
                if($check_lap == 0){
                     $check_lap;
                     $container = new Container;
                     $container->ctn_type        = $request->container_size;
                     $container->ctn_location    = $request->location;
                     $container->ctn_pd_id       = $request->id;
                     $container->ctn_over_kk     = $request->over_kk;
                     $container->ctn_number      = $max_container_num;
                     $container->ctn_date        = Date('Y-m-d');
                     $container->save();
                 }


                 $containerdetial = new ContainerDetial();
                 $containerdetial->ctnd_ctn_id       = $container->ctn_id;
                 $containerdetial->ctnd_mp_id        = $mainpallet->mp_id;
                 $containerdetial->ctnd_pallet_qty   = $pallet->tpl_qty;
                 $containerdetial->remark            = "";
                 $containerdetial->save();   

                 $pallet = Pallets::find($pallet->tpl_id);
                            
                 DB::table('tb_pallet')->where('tpl_id',$pallet->tpl_id)->update([
                     'tpl_status' =>  1,
                 ]);


                 ++$check_lap;
            }
            
        
        }

        
        $container2 = Container::find($container->ctn_id);
        $container2->ctn_pallet = $qty_pallet;
        $container2->save();


        Session::forget('cart');
    return redirect(url ('ContainerLoad').'/'.$request->id.'/2')->with('success','Load Container ที่เลือกเรียบร้อย');
    
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

    public function select_box(Request $request){
      foreach($request->sel_box as $sel_box){
        echo $sel_box;
      }
    }

    public function load_container(Request $request){
        
        $main = Mainpallet::where('mp_id',$request->id)->first();

        if((int)$request->qty_old <  (int)$request->qty){
            $qty        = $request->qty_old;
            $qty_item   =  $request->qty_old  * ($main->mp_qty / $request->qty);
        }else{
            $qty        = $request->qty;
            $qty_item = $request->qty  * ($main->mp_qty / $request->qty);
        }

        
      
        //  Session::forget('cart');
        $product = collect([
            'qty' =>  $qty,
            'id' =>  $request->id,
            'item' =>  $qty_item,
        ]);

        Session::push('cart', $product);
        $mainpallet2 = Mainpallet::where('mp_id',$request->id)->first();


        $mainpallet = Mainpallet::where('mp_id',$request->id)->first();
        $mainpallet->mp_qty         =  $mainpallet2->mp_qty - $qty_item;
        $mainpallet->mp_pallet_qty  =  $mainpallet2->mp_pallet_qty - $qty;
        $mainpallet->save();
        echo 1;
       
    }

    
    public function delete_container(Request $request){

        $mainpallet = Mainpallet::where('mp_id',$request->id)->first();
        $mainpallet2 = Mainpallet::where('mp_id',$request->id)->first();

        $mainpallet->mp_pallet_qty  = $mainpallet2->mp_pallet_qty  + $request->qty;
        $mainpallet->mp_qty         = $mainpallet2->mp_qty + $request->qty_item;
        $mainpallet->save();
       

        Session::forget('cart.'.$request->id2);

        echo 1;
      
    }

    
    
}

