<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use PDF;
use Session;
use App\Container;
use App\Report;
use App\Remark;
use App\ContainerDetial;
use App\ContainerDes;
use App\Pallets;
use App\Mainpallet;
class AdjustLoadingController extends Controller
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

        return view('adjust.index',$data);
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
        $containers = DB::table('tb_containers')->leftjoin('tb_products','tb_products.pd_id','=','tb_containers.ctn_pd_id')->where('ctn_pd_id',$id)->get();
        $report    = Report::where('rh_pd_id',$id)->first();
        $remarks     = Remark::where('re_pd_id',$id)->get();
        
        $data = array(
            'containers' => $containers,
            'id' => $id,
            'report' => $report,
            'remarks' => $remarks,
        );
  
        return view('adjust.show',$data);
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
  
        $container = Container::find($id);
        $ex_pallet = explode(',',$container->pallet_id);


        $keys = array();
        $array_key_mpid = array();
        foreach($ex_pallet as $key => $item ){
           
            $containerdetials = ContainerDetial::leftjoin('tb_container_des','tb_container_detail.ctnd_id','=','tb_container_des.ctnds_ctnd_id')->where('ctnd_key',$item)->get();
           
            foreach($containerdetials as $num => $val){
                $keys[] =  $val->ctnds_key;
           }
        

        }
       
        $key_find = array_unique($keys);
        foreach($key_find as $no => $value){
            $pallets = Pallets::find($value);
            $array_key_mpid[] = $pallets->tpl_mp_id;
        }

        $key_find_mp =  array_unique($array_key_mpid);
        foreach($key_find_mp as $no => $value){
            $mainpallet = Mainpallet::find($value);

            $mainpallet2 = Mainpallet::find($value);
            $mainpallet2->mp_qty           = $mainpallet->mp_qty_main;
            $mainpallet2->mp_pallet_qty    = $mainpallet->mp_pallet_qty_main;
            $mainpallet2->save();
        }
       
        //Delete 
        $container = Container::find($id);
        $ex_pallet = explode(',',$container->pallet_id);
        foreach($ex_pallet as $key => $item ){
           
            $containerdetials = ContainerDetial::leftjoin('tb_container_des','tb_container_detail.ctnd_id','=','tb_container_des.ctnds_ctnd_id')->where('ctnd_key',$item)->delete();

        }
        $container = Container::destroy($id);

        if($container){
            echo 1;
        }else{
            echo 0;
        }
    }

    public function report($id)
    {

        // $containers = DB::table('tb_containers')
        //             ->groupBy('ctn_number')
        //             ->where('ctn_pd_id',$id)->get();
        $containers = DB::table('tb_containers')->leftjoin('tb_products','tb_products.pd_id','=','tb_containers.ctn_pd_id')->where('ctn_pd_id',$id)->get();
        
        $report    = Report::where('rh_pd_id',$id)->first();
        $remarks     = Remark::where('re_pd_id',$id)->get();

        $data = array(
            'containers' => $containers,
            'report' => $report,
            'remarks' => $remarks,
            'id' => $id,
        );

        // $pdf = App::make('dompdf.wrapper');
        // $pdf->loadHTML('<h1>Test</h1>');
        // return $pdf->stream();

        $pdf = PDF::loadView('adjust.report', $data)->setPaper('a4', 'landscape');
        return $pdf->stream();
        // return view('adjust.report', $data);

    }

    public function adjustcontainer($id){

        $container = DB::table('tb_containers')->leftjoin('tb_products','tb_products.pd_id','=','tb_containers.ctn_pd_id')->where('ctn_id',$id)->first();
        if($container == null ){
            return redirect(url('adjust',20))->with('danger','เกิดข้อผิดพลาดในการใช้งาน กรุณาลองทำการอีกครั้ง!!');
        }
        $data = array(
            'container' => $container,
            'id' => $id,
        );
        // dd($data,$id);
        return view('adjust.adjust',$data);
    }

    public function addremark(Request $request){
        $report    = Report::where('rh_pd_id',$request->pd_id)->delete();
        $remarks     = Remark::where('re_pd_id',$request->pd_id)->delete();

        $report                 = new Report();
        $report->rh_pd_id       = $request->pd_id;
        $report->rh_sono        = $request->so_no;
        $report->rh_customer    = $request->cus_name;
        $report->save();

        if(count($request->remarks) > 0){
            foreach($request->remarks as $item){
                if($item != null){
                    $remark                 = new Remark();
                    $remark->re_pd_id       = $request->pd_id;
                    $remark->re_remark      = $item;
                    $remark->save();
                }
            }
        }

        return redirect()->back()->with('success','บันทึกรายละเอียดข้อมูลรายงาน เรียบร้อย!!');
    }


    public function delitems(Request $request,$id){ 
        $find_container = ContainerDes::find($id);
    
        $del_containerdetail = ContainerDetial::destroy($find_container->ctnds_ctnd_id);
        $del_containerdes = ContainerDes::destroy($id);
        
        if($del_containerdes && $del_containerdetail){
            echo 1; 
        }else{
            echo 0;
        }
    }

    public function delpallet(Request $request,$id){ 
        $containerdetail_finds    = DB::table('tb_container_detail')
                                    ->leftjoin('tb_container_des','tb_container_des.ctnds_ctnd_id','=','tb_container_detail.ctnd_id')
                                    ->where('ctnd_id',$id)
                                    ->get();
       
        foreach($containerdetail_finds as $key => $find){

            $mainpallet = DB::table('tb_pallet')->where('tpl_id',$find->ctnds_key)
                            ->leftjoin('tb_boxs','tb_pallet.tpl_bo_id','=','tb_boxs.bo_id')
                            ->leftjoin('tb_items','tb_boxs.bo_item','=','tb_items.it_name')
                            ->leftjoin('tb_subitems','tb_items.it_id','=','tb_subitems.sit_it_id')
                            ->first();

            $weight_per_pallet  = $mainpallet->sit_netweight*$mainpallet->sit_palletvolume; //น้ำหนักต่อ Pallet
            $box_per_pallet         = $mainpallet->sit_palletvolume; //กล่องต่อ pallet 
            $items_per_pallet       = $box_per_pallet * $mainpallet->bo_pack_qty; //ชิ้นต่อ pallet 

           

            $qty_pallet =  $find->ctnds_max /  $weight_per_pallet; //จำนวน pallet ที่เตรียมเอากลับเข้าคลัง


          
            // print_r($weight_per_pallet);
            $mainpallet_edit  = Pallets::leftjoin('tb_mainpallet','tb_pallet.tpl_mp_id','=','tb_mainpallet.mp_id')->where('tpl_id',$find->ctnds_key)->first();
            if($mainpallet_edit->mp_qty < 0){
                $plus_mp_qty = 0;
            }else{
                $plus_mp_qty = $mainpallet_edit->mp_qty;
            }

            if($mainpallet_edit->mp_pallet_qty < 0){
                $plus_mp_pallet_qty = 0;
            }else{
                $plus_mp_pallet_qty = $mainpallet_edit->mp_pallet_qty;
            }

            $mainpallet2 = Mainpallet::find($mainpallet_edit->mp_id);
            $mainpallet2->mp_qty           = $plus_mp_qty + $box_per_pallet;
            $mainpallet2->mp_pallet_qty    = $plus_mp_pallet_qty + $qty_pallet;
            $mainpallet2->save();

            
        }

        $containerdetail_del    = DB::table('tb_container_detail')->where('ctnd_id',$id)->delete();
        $containerdes_del       = DB::table('tb_container_des')->where('ctnds_ctnd_id',$id)->delete();

        if($containerdetail_del == 1 && $containerdes_del == 1){
            echo 1;
        }else{
            echo 2;
        }
        // echo "<pre>";
        // print_r($containerdetail_finds);
        // $del_containerdetail    = ContainerDetial::destroy($id);
        // $del_containerdes       = ContainerDes::where('ctnds_ctnd_id',$id)->delete();
    }

    public function addcontainer($id,$lastpallet,$lastitem){

        // dd(Session::get('cart'),Session::get('container'),Session::get('pallet_weight'));
        // Session::forget('cart');
        // Session::forget('container');
        // Session::forget('pallet_weight');

        $container = DB::table('tb_containers')->leftjoin('tb_products','tb_products.pd_id','=','tb_containers.ctn_pd_id')->where('ctn_id',$id)->first();
        
        $pallets2 = DB::table('tb_mainpallet')
            ->select('tb_mainpallet.*','tb_pallet.*','tb_boxs.*','tb_items.*','tb_subitems.*','tb_typepalate.*')
            ->leftjoin('tb_pallet','tb_mainpallet.mp_id','=','tb_pallet.tpl_mp_id')
            ->leftjoin('tb_boxs','tb_pallet.tpl_bo_id','=','tb_boxs.bo_id')
            ->leftjoin('tb_items','tb_boxs.bo_item','=','tb_items.it_name') 
            ->leftjoin('tb_subitems','tb_items.it_id','=','tb_subitems.sit_it_id')
            ->leftjoin('tb_typepalate','tb_subitems.sit_pallet','=','tb_typepalate.tp_id')
            ->groupBy('mp_id')
            ->where('tpl_pd_id', $container->ctn_pd_id)    
            ->whereNull('mp_status_load')
            ->where('mp_pallet_qty','>',0)    
            ->where('mp_qty','<>',0)             
            ->get();

        $data = array(
            'container'     => $container,
            'pallets'       => $pallets2,
            'id'            => $id,
            'lastpallet'    => $lastpallet,
            'lastitem'    => $lastitem
        );
        // dd($data);
        return view('adjust.add',$data);
    }

    public function load_pallet_container(Request $request){
        $con_size = ($request->container_size * 1000) + $request->weight_over;

        // if($request->pallet_qty_sel != null){
        //     $pallet_qty_check = $request->pallet_qty_sel;
        // }else{
            $pallet_qty_check = ($request->container_size == 20 ? 20 : 40 );
        // }

     
        $main = Mainpallet::where('mp_id',$request->id)->first();

       

        if((int)$request->qty_old <  (int)$request->qty){
            $qty        = $request->qty_old;
            $qty_item   =  $request->qty_old  * ($main->mp_qty / $request->qty);
        }else{
            $qty        = $request->qty;
            $qty_item = $request->qty  * ($main->mp_qty / $request->qty);
        }

        
      
        // //  Session::forget('cart');
        $product = collect([
            'qty'   =>  $qty,
            'id'    =>   $request->id,
            'item'  =>  $qty_item,
        ]);

        // print_r($product);
        Session::push('cart', $product);

        $count_pallet = 0;
        $digi = 0;
       
        foreach (Session::get('cart') as $key => $value){
      
            $mainpallet = DB::table('tb_mainpallet')
                        ->leftjoin('tb_pallet','tb_mainpallet.mp_id','=','tb_pallet.tpl_mp_id')
                        ->leftjoin('tb_boxs','tb_pallet.tpl_bo_id','=','tb_boxs.bo_id')
                        ->leftjoin('tb_items','tb_boxs.bo_item','=','tb_items.it_name') 
                        ->leftjoin('tb_subitems','tb_items.it_id','=','tb_subitems.sit_it_id')
                        ->leftjoin('tb_typepalate','tb_subitems.sit_pallet','=','tb_typepalate.tp_id')
                        ->where('tpl_mp_id',$value['id'])
                        ->first();
 
            $pallets = DB::table('tb_pallet')
                        ->leftjoin('tb_boxs','tb_pallet.tpl_bo_id','=','tb_boxs.bo_id')
                        ->leftjoin('tb_items','tb_boxs.bo_item','=','tb_items.it_name') 
                        ->leftjoin('tb_subitems','tb_items.it_id','=','tb_subitems.sit_it_id')
                        ->leftjoin('tb_typepalate','tb_subitems.sit_pallet','=','tb_typepalate.tp_id')
                        ->where('tpl_mp_id',$mainpallet->mp_id)
                        ->get();
 
            $per_pallet[$digi]        = $mainpallet->mp_qty_main/$mainpallet->mp_pallet_qty_main; //จำนวน กล่อง ต่อ pallet
            $pallet_qty[$digi]        =  $value['qty'];// จำนวน pallet ต่อ mainpallet
      
         //    $tpl_id[$digi]            = $mainpallet->mp_id; // id main pallet
            $weight_qty[$digi]        = $mainpallet->sit_netweight; //น้ำหนักของแต่ละ pallet 
            $pallet_item              = array();
            $mainpallet_id[$digi]     = $mainpallet->mp_id;//mp_id
            
            foreach($pallets as $key => $pallet){
             $max_weight_per_pallet = 0;
             //    echo $mainpallet->mp_id." : ".$pallet->tpl_qty." *  ".$pallet->sit_netweight."<br/>";
 
                 $mp_id[$mainpallet->mp_id][$pallet->tpl_id] = array(
                     'qtybox'        => ceil($pallet->tpl_qty),
                     'weightbox'     => ceil($pallet->tpl_qty) *  $pallet->sit_netweight,
                     'useweight'     => 0,
                     'remainweightbox' => ceil($pallet->tpl_qty) *  $pallet->sit_netweight,
                     'statusmp' => "",
                 );
 
                 if($mainpallet->mp_pallet_qty_main == 1){
                     $max_weight_per_pallet += $mp_id[$mainpallet->mp_id][$pallet->tpl_id]['weightbox'];
                     // echo $mainpallet->mp_id." - ".$pallet->tpl_id." : ".$max_weight_per_pallet."<br/>";
                     $weight_pallet[$digi][$key] = $max_weight_per_pallet;
                  }else{
                     $max_weight_per_pallet += $per_pallet[$digi] * $weight_qty[$digi];
                     // $max_weight_per_pallet += ($pallet->tpl_qty*$weight_qty[$digi]) / $mainpallet->mp_pallet_qty_main;
                     // echo $max_weight_per_pallet."<br/>";
                     // echo $mainpallet->mp_id." - ".$pallet->tpl_id." : ".$pallet->tpl_qty*$weight_qty[$digi]."<br/>";
                     $weight_pallet[$digi][0] = $max_weight_per_pallet;
                  }
 
             
                 
                 // echo $mp_id[$mainpallet->mp_id][$pallet->tpl_id]['weightbox']."<br/>";
               
 
            }
         //    echo $max_weight_per_pallet."<br/>";
 
            $mp_id_digi[$digi]  = $mainpallet->mp_id;
            ++$digi; //นับรวม main pallet ทั้งหมดมีกี่ main pallet
        }

        $check_loop = 0;
        for($a=0;$a<$digi;$a++){
                $max_weight_per_pallet =  array_sum($weight_pallet[$a]);
            for($b=0;$b<$pallet_qty[$a];$b++){
                $pallet_item[$a][$check_loop] = $per_pallet[$a] * $weight_qty[$a]; //กล่อง * น้ำหนักต่อกล่อง
                $pallet_item_id[$a][$check_loop] = array(
                        'max' =>  $max_weight_per_pallet, //น้ำหนักรวมของ pallet แต่ละตัว
                        'use' => 0, //น้ำหนักที่ใช้ไป
                        'tpl' => array(), //tpl_id ที่จะมาเก็บ
                        'remain' =>  $max_weight_per_pallet,
                        'status' => "",
                ); 

                
                $check_loop++;
            }
        }

        foreach($pallet_item_id as $digis => $array1){
     
            foreach($array1 as $check_l => $v){
             
                foreach($mp_id[$mp_id_digi[$digis]] as $tpl_id =>  $mp){
                    
                    if(@$mp['remainweightbox'] > 0 && @$pallet_item_id[$digis][$check_l]['remain'] > 0 ){
                      
                        if(@$pallet_item_id[$digis][$check_l]['remain'] >= @$mp['remainweightbox'] ){ //น้ำหนัก pallet มากกว่า น้ำหนักล่อง
                            if(@$pallet_item_id[$digis][$check_l]['max'] >= @$mp_id[$mp_id_digi[$digis]][$tpl_id]['weightbox']){
                                @$mp_id[$mp_id_digi[$digis]][$tpl_id]['useweight']  += @$mp_id[$mp_id_digi[$digis]][$tpl_id]['weightbox'];
                                @$mp_id[$mp_id_digi[$digis]][$tpl_id]['remainweightbox']    = @$mp_id[$mp_id_digi[$digis]][$tpl_id]['weightbox'] -  @$mp_id[$mp_id_digi[$digis]][$tpl_id]['useweight'];

                                @$pallet_item_id[$digis][$check_l]['use']           += @$mp_id[$mp_id_digi[$digis]][$tpl_id]['weightbox'];
                                @$pallet_item_id[$digis][$check_l]['tpl'][$tpl_id]  += @$mp_id[$mp_id_digi[$digis]][$tpl_id]['weightbox'];
                                @$pallet_item_id[$digis][$check_l]['remain']        = @$pallet_item_id[$digis][$check_l]['max'] - @$pallet_item_id[$digis][$check_l]['use'];
                               
                                @$pallet_item_id[$digis][$check_l]['status']    .= 1;
                                @$mp_id[$mp_id_digi[$digis]][$tpl_id]['statusmp'] .= 1;
                            }else{
                                if(@$mp_id[$mp_id_digi[$digis]][$tpl_id]['remainweightbox'] <= 0){
                                    continue;
                                }
                                    if(@$pallet_item_id[$digis][$check_l]['remain'] > @$mp_id[$mp_id_digi[$digis]][$tpl_id]['remainweightbox'] ){
         
                                        @$mp_id[$mp_id_digi[$digis]][$tpl_id]['useweight']  += @$mp_id[$mp_id_digi[$digis]][$tpl_id]['remainweightbox'];
                                    

                                        @$pallet_item_id[$digis][$check_l]['use']           += @$mp_id[$mp_id_digi[$digis]][$tpl_id]['remainweightbox'];
                                        @$pallet_item_id[$digis][$check_l]['tpl'][$tpl_id]  += @$mp_id[$mp_id_digi[$digis]][$tpl_id]['remainweightbox'];
                                        @$pallet_item_id[$digis][$check_l]['remain']                = @$pallet_item_id[$digis][$check_l]['max'] - @$pallet_item_id[$digis][$check_l]['use'];
                                        
                                        @$mp_id[$mp_id_digi[$digis]][$tpl_id]['remainweightbox']    = @$mp_id[$mp_id_digi[$digis]][$tpl_id]['weightbox'] -  @$mp_id[$mp_id_digi[$digis]][$tpl_id]['useweight'];
                                       

                                        @$pallet_item_id[$digis][$check_l]['status']        .= '2@';
                                        @$mp_id[$mp_id_digi[$digis]][$tpl_id]['statusmp'] .= '2@('.@$mp_id[$mp_id_digi[$digis]][$tpl_id]['remainweightbox'].')';
                                    }else{

                                        @$pallet_item_id[$digis][$check_l]['use']                   += @$pallet_item_id[$digis][$check_l]['remainweightbox'];
                                        @$pallet_item_id[$digis][$check_l]['tpl'][$tpl_id]          += @$mp_id[$mp_id_digi[$digis]][$tpl_id]['remainweightbox'];
                                        @$pallet_item_id[$digis][$check_l]['remain']                = @$pallet_item_id[$digis][$check_l]['max'] - @$pallet_item_id[$digis][$check_l]['use'];
                                        
                                        @$pallet_item_id[$digis][$check_l]['status']    .= '2#';
                                        @$mp_id[$mp_id_digi[$digis]][$tpl_id]['statusmp'] .= '2#(0)';
                                    }   
                            }

                        }else{
                            if(@$pallet_item_id[$digis][$check_l]['use'] <= @$pallet_item_id[$digis][$check_l]['max'] ){ 
                                if(@$pallet_item_id[$digis][$check_l]['remain'] >= @$pallet_item_id[$digis][$check_l]['max'] ){

                                

                                    @$mp_id[$mp_id_digi[$digis]][$tpl_id]['useweight']           += @$pallet_item_id[$digis][$check_l]['max'];
                                    @$mp_id[$mp_id_digi[$digis]][$tpl_id]['remainweightbox']     = @$mp_id[$mp_id_digi[$digis]][$tpl_id]['weightbox']- @$mp_id[$mp_id_digi[$digis]][$tpl_id]['useweight'];

                                    @$pallet_item_id[$digis][$check_l]['use']                   += @$pallet_item_id[$digis][$check_l]['max'];
                                    @$pallet_item_id[$digis][$check_l]['tpl'][$tpl_id]          += @$pallet_item_id[$digis][$check_l]['max'];
                                    @$pallet_item_id[$digis][$check_l]['remain']                = @$pallet_item_id[$digis][$check_l]['max'] - @$pallet_item_id[$digis][$check_l]['use'];

                                    @$pallet_item_id[$digis][$check_l]['status']    .= "3@";
                                    @$mp_id[$mp_id_digi[$digis]][$tpl_id]['statusmp'] .= '3@('.@$pallet_item_id[$digis][$check_l]['max'].')';
                                }else{
                                    if(@$mp_id[$mp_id_digi[$digis]][$tpl_id]['remainweightbox']     >= @$pallet_item_id[$digis][$check_l]['remain']){
                                        @$mp_id[$mp_id_digi[$digis]][$tpl_id]['useweight']           += @$pallet_item_id[$digis][$check_l]['remain'];
                                        @$mp_id[$mp_id_digi[$digis]][$tpl_id]['remainweightbox']     = @$mp_id[$mp_id_digi[$digis]][$tpl_id]['weightbox']- @$mp_id[$mp_id_digi[$digis]][$tpl_id]['useweight'];

                                        @$pallet_item_id[$digis][$check_l]['use']                   += @$pallet_item_id[$digis][$check_l]['remain'];
                                        @$pallet_item_id[$digis][$check_l]['tpl'][$tpl_id]          += @$pallet_item_id[$digis][$check_l]['remain'];
                                        @$pallet_item_id[$digis][$check_l]['remain']                = @$pallet_item_id[$digis][$check_l]['max'] - @$pallet_item_id[$digis][$check_l]['use'];
                                        
                                        @$pallet_item_id[$digis][$check_l]['status']    .= "3#";
                                        @$mp_id[$mp_id_digi[$digis]][$tpl_id]['statusmp'] .= '3#('.@$pallet_item_id[$digis][$check_l]['remain'].')';
                                    }
                                       
                                }
                               
                            }else{

                            }
                           
                        }
                    }
                }
            }
       }

       $pallet = $check_loop;
        $pallet_weight = $pallet_item_id;
      
        $container = array();    

        $container2 = array(
            'max'           => $con_size,
            'use'           => 0,
            'remain'        => $con_size,
            'pallet_max'    => $pallet_qty_check,
            'pallet_id'     => [],
        );


        $count_container = 0;
        for($a=0;$a<$digi;$a++){
            foreach($pallet_weight[$a] as $key => $val){
                if($container2['remain'] > $pallet_weight[$a][$key]['max'] && count($container2['pallet_id']) < $container2['pallet_max']){
                    $container2['use']                          = $container2['use'] + $pallet_weight[$a][$key]['max'];
                    $container2['remain']                       = $container2['max'] - $container2['use'];
                    $container2['pallet_id'][]                  += $key;
                }else{
                    ++$count_container;
                    $container2 = array(
                        'max'           => $con_size,
                        'use'           => 0,
                        'remain'        => $con_size,
                        'pallet_max'    => $pallet_qty_check,
                        'pallet_id'     => [],
                    );
                }
            }
        }

       

        for($i=1;$i<=$count_container+1;$i++){
            $container[$i] = array(
                'max'           => $con_size,
                'use'           => 0,
                'remain'        => $con_size,
                'pallet_max'    => $pallet_qty_check,
                // 'pallet_weight'     => [],
                'pallet_id'     => [],
                'container_id'  => [],
            );
        }

     
      
        for($a=0;$a<$digi;$a++){
              foreach($pallet_weight[$a] as $key => $val){
                for($c=1;$c<=count($container);$c++){

                        if($container[$c]['remain'] > $pallet_weight[$a][$key]['max'] && count($container[$c]['pallet_id']) < $container[$c]['pallet_max']){
                            // echo "<pre>";
                            // print_r($pallet_weight[$a][$key]);
                            $container[$c]['use']                          = $container[$c]['use'] + $pallet_weight[$a][$key]['max'];
                   
                            $container[$c]['remain']                       = $container[$c]['max'] - $container[$c]['use'];
                            // $container[$c]['pallet_id'][]                 =  $pallet_weight[$a][$key]['tpl'];
                            $container[$c]['pallet_id'][]                  += $key;
                            $container[$c]['container_id'][]                += $a;
                            unset($pallet_weight[$a][$key]);
                            break;
                        }
                }
            }

            
        }

        // print_r($container);

        Session::put('container',$container);
        Session::put('pallet_weight',$pallet_item_id);

        $mainpallet2 = Mainpallet::where('mp_id',$request->id)->first();


        $mainpallet = Mainpallet::where('mp_id',$request->id)->first();
        $mainpallet->mp_qty         =  $mainpallet2->mp_qty - $qty_item;
        $mainpallet->mp_pallet_qty  =  $mainpallet2->mp_pallet_qty - $qty;
        $mainpallet->save();
        echo 1;
       
    }

       
    public function delete_pallet_out_container(Request $request){

        // $mainpallet = Mainpallet::where('mp_id',$request->id)->first();
        // $mainpallet2 = Mainpallet::where('mp_id',$request->id)->first();

        // $pallet_qty = $mainpallet2->mp_pallet_qty  + $request->qty;
        // $qty        = $mainpallet2->mp_qty + $request->qty_item;

        // // echo $pallet_qty." <= ".$mainpallet->mp_pallet_qty_main;
        // // echo $qty." <= ".$mainpallet->mp_qty_main;
        // if(($pallet_qty <= $mainpallet->mp_pallet_qty_main) || ($qty <= $mainpallet->mp_qty_main)){
        //     // echo "tttttttttttt";
        //     $mainpallet->mp_pallet_qty  = $mainpallet2->mp_pallet_qty  + $request->qty;
        //     $mainpallet->mp_qty         = $mainpallet2->mp_qty + $request->qty_item;
        //     $mainpallet->save();
        // }
          
    //    echo $request->id2;

        Session::forget('container.1.pallet_id.'.$request->id2);
        Session::forget('container.1.container_id.'.$request->id2);

        Session::forget('pallet_weight.0.'.$request->id2);

        // print_r(Session::get('pallet_weight.0.'.$request->id2));

        // print_r(Session::get('container.1.pallet_id.'.$request->id2));
        echo 1;
      
    }

    public function updatecontainer(Request $request){
    
        // dd($request);
        Session::forget('cart');
        Session::forget('container');
        Session::forget('pallet_weight');

        return redirect(url('adjust',$request->pd_id))->with('success','ทำการ Update Contianer เรียบร้อย!!');
    }
}
