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

       dd($request);
       
        $count_pallet = 0;
        $count_container = Container::whereRaw('ctn_number = (select max(`ctn_number`) from tb_containers)')->first();

     
        if($count_container == null){
            $max_container_num = 1;
        }else{
            $max_container_num = $count_container->ctn_number + 1;
        }

        $weight_con =  ($request->selec_con * 1000); //น้ำหนัก Container
        $weight_diff = $weight_con + $request->over_kk; //น้ำหนัก Container รวมกับ +- 
        $pallet_qty = ($request->qty_pallet == null ? 0 : $request->qty_pallet ); //จำนวน pallet ที่กำหนด

        $weight_all = 0; //น้ำหนักเริ่มต้น
        $pallet_cal = 0;
     
        $container = new Container;
        $container->ctn_type        = $request->container_size;
        $container->ctn_location    = $request->location;
        $container->ctn_pd_id       = $request->id;
        $container->ctn_over_kk     = $request->over_kk;
        $container->ctn_number      = $max_container_num;
        $container->ctn_date        = Date('Y-m-d');
        $container->save();

        foreach($request->pallet_select as $item){
            $mainpallet = Mainpallet::where('mp_id','=',$item)->where('mp_status','<>',1)->where('mp_type_load','<>',1)->first();
            if($mainpallet != null){
                $pallets = Pallets::where('tpl_mp_id','=',$mainpallet->mp_id)->get();
                $sum_pallet = 0;
                foreach($pallets as $pallet){
                    $box = Boxs::leftjoin('tb_items','tb_items.it_name','=','tb_boxs.bo_item')
                                ->leftjoin('tb_subitems','tb_subitems.sit_it_id','=','tb_items.it_id')
                                ->where('bo_id',$pallet->tpl_bo_id)
                                ->first();

                    if($request->over_kk == null && $request->qty_pallet == null){
                        if($weight_all <= $weight_con){
                            if($pallet->tpl_qty != 0){
                                $weight_all += ($box->sit_grossweight)*$pallet->tpl_qty;
                                $containerdetial = new ContainerDetial();
                                $containerdetial->ctnd_ctn_id       = $container->ctn_id;
                                $containerdetial->ctnd_mp_id        = $mainpallet->mp_id;
                                $containerdetial->ctnd_pallet_qty   = $pallet->tpl_qty;
                                $containerdetial->remark            = "";
                                $containerdetial->save();  

                                $pallet2 =  DB::table('tb_pallet')->where('tpl_id', $pallet->tpl_id)->first();
                                $cal_pallet =   $pallet2->tpl_qty - $pallet->tpl_qty;
                                $pallet3 = DB::table('tb_pallet')->where('tpl_id', $pallet->tpl_id)->update([
                                    'tpl_qty' => $cal_pallet
                                ]);

                                $sum_pallet += $pallet->tpl_qty;
                                $status = 1;
                                $main = DB::table('tb_mainpallet')->where('mp_id',$mainpallet->mp_id)->update([
                                    'mp_qty' => $mainpallet->mp_qty - $sum_pallet,
                                ]);
                              
                            }
                        }
                    }            

                    if($request->over_kk != null && $request->qty_pallet == null){
                        if($weight_all <= $weight_diff){
                            if($pallet->tpl_qty != 0){
                                $weight_all += ($box->sit_grossweight)*$pallet->tpl_qty;
                                $containerdetial = new ContainerDetial();
                                $containerdetial->ctnd_ctn_id       = $container->ctn_id;
                                $containerdetial->ctnd_mp_id        = $mainpallet->mp_id;
                                $containerdetial->ctnd_pallet_qty   = $pallet->tpl_qty;
                                $containerdetial->remark            = "";
                                $containerdetial->save();  

                                $pallet2 =  DB::table('tb_pallet')->where('tpl_id', $pallet->tpl_id)->first();
                                $cal_pallet =   $pallet2->tpl_qty - $pallet->tpl_qty;
                                $pallet3 = DB::table('tb_pallet')->where('tpl_id', $pallet->tpl_id)->update([
                                    'tpl_qty' => $cal_pallet
                                ]);

                                $sum_pallet += $pallet->tpl_qty;
                                $status = 1;
                                $main = DB::table('tb_mainpallet')->where('mp_id',$mainpallet->mp_id)->update([
                                    'mp_qty' => $mainpallet->mp_qty - $sum_pallet,
                                ]);
                              
                            }
                        }
                    }
                   
                    if($request->over_kk == null && $request->qty_pallet != null){
                          if($pallet_cal <= $pallet_qty){
                            if($pallet->tpl_qty != 0){
                                $pallet_cal += $pallet->tpl_qty/$box->sit_palletvolume;
                                $weight_all += ($box->sit_grossweight)*$pallet->tpl_qty;

                                $containerdetial = new ContainerDetial();
                                $containerdetial->ctnd_ctn_id       = $container->ctn_id;
                                $containerdetial->ctnd_mp_id        = $mainpallet->mp_id;
                                $containerdetial->ctnd_pallet_qty   = $pallet->tpl_qty;
                                $containerdetial->remark            = "";
                                $containerdetial->save();  

                                $pallet2 =  DB::table('tb_pallet')->where('tpl_id', $pallet->tpl_id)->first();
                                $cal_pallet =   $pallet2->tpl_qty - $pallet->tpl_qty;
                                $pallet3 = DB::table('tb_pallet')->where('tpl_id', $pallet->tpl_id)->update([
                                    'tpl_qty' => $cal_pallet
                                ]);

                                $sum_pallet += $pallet->tpl_qty;
                                $status = 2;
                                $main = DB::table('tb_mainpallet')->where('mp_id',$mainpallet->mp_id)->update([
                                    'mp_qty' => $mainpallet->mp_qty - $sum_pallet,
                                ]);
                              
                            }
                           

                          }
                      }

                    if($request->over_kk != null && $request->qty_pallet != null){
                        if($weight_all <= $weight_diff && $pallet_cal <= $pallet_qty){
                             if($pallet->tpl_qty != 0){
                                $pallet_cal += $pallet->tpl_qty/$box->sit_palletvolume;
                                $weight_all += ($box->sit_grossweight)*$pallet->tpl_qty;
                                
                                $containerdetial = new ContainerDetial();
                                $containerdetial->ctnd_ctn_id       = $container->ctn_id;
                                $containerdetial->ctnd_mp_id        = $mainpallet->mp_id;
                                $containerdetial->ctnd_pallet_qty   = $pallet->tpl_qty;
                                $containerdetial->remark            = "";
                                $containerdetial->save();  
                            }

                        }
                    }

                }   
            }
       
        }

        
         
        $container2 = Container::find($container->ctn_id);
        $container2->ctn_pallet = $pallet_cal;
        $container2->save();

        // echo "weight_all : ".($weight_all)."</br>";
        // echo "pallet_all : ".$pallet_cal."</br>";
        // dd(($request->weight_total*1000) + $request->over_kk);
        // exit();
       

        return redirect(url('box').'/'.$request->id.'/edit')->with('success','Load Container ที่เลือกเรียบร้อย');
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
       
    //   foreach($request->sel_box as $sel_box){
    //     echo $sel_box;
    //   }
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

        $pallet_qty = $mainpallet2->mp_pallet_qty  + $request->qty;
        $qty        = $mainpallet2->mp_qty + $request->qty_item;

        // echo $pallet_qty." <= ".$mainpallet->mp_pallet_qty_main;
        // echo $qty." <= ".$mainpallet->mp_qty_main;
        if(($pallet_qty <= $mainpallet->mp_pallet_qty_main) || ($qty <= $mainpallet->mp_qty_main)){
            // echo "tttttttttttt";
            $mainpallet->mp_pallet_qty  = $mainpallet2->mp_pallet_qty  + $request->qty;
            $mainpallet->mp_qty         = $mainpallet2->mp_qty + $request->qty_item;
            $mainpallet->save();
        }
          
       

        Session::forget('cart.'.$request->id2);

        echo 1;
      
    }

    public function preview_auto(Request $request){

        $con_size = ($request->selec_con * 1000) + $request->over_kk;
    
        $pallet_qty_check = ($request->selec_con == 20 ? 20 : 40 );
        $count_pallet = 0;
        $digi = 0;
        $qty_pallet = ($request->qty_pallet == null ? $pallet_qty_check : $request->qty_pallet);
      
        $arr_items = $request->pallet_select ;
        $arr_results = array_filter( $arr_items );
 
      
        foreach($arr_results as $key => $value){
            
            $mainpallet = DB::table('tb_mainpallet')
                        ->leftjoin('tb_pallet','tb_mainpallet.mp_id','=','tb_pallet.tpl_mp_id')
                        ->leftjoin('tb_boxs','tb_pallet.tpl_bo_id','=','tb_boxs.bo_id')
                        ->leftjoin('tb_items','tb_boxs.bo_item','=','tb_items.it_name') 
                        ->leftjoin('tb_subitems','tb_items.it_id','=','tb_subitems.sit_it_id')
                        ->leftjoin('tb_typepalate','tb_subitems.sit_pallet','=','tb_typepalate.tp_id')
                        ->where('tpl_mp_id',$value)
                        ->where('mp_status',"<>",1)
                        ->first();

  

            
                $pallets = DB::table('tb_pallet')
                        ->leftjoin('tb_boxs','tb_pallet.tpl_bo_id','=','tb_boxs.bo_id')
                        ->leftjoin('tb_items','tb_boxs.bo_item','=','tb_items.it_name') 
                        ->leftjoin('tb_subitems','tb_items.it_id','=','tb_subitems.sit_it_id')
                        ->leftjoin('tb_typepalate','tb_subitems.sit_pallet','=','tb_typepalate.tp_id')
                        ->where('tpl_mp_id',$mainpallet->mp_id)
                        ->get();

           
            $per_pallet[$digi]        = $mainpallet->mp_qty_main/$mainpallet->mp_pallet_qty_main; //จำนวน กล่อง ต่อ pallet
            $pallet_qty[$digi]        =  $request->pallet_qty[$key];// จำนวน pallet ต่อ mainpallet
                  
            $weight_qty[$digi]        = $mainpallet->sit_netweight; //น้ำหนักของแต่ละ pallet 
            $pallet_item              = array();
            $mainpallet_id[$digi]     = $mainpallet->mp_id;//mp_id

            foreach($pallets as $key => $pallet){
                $max_weight_per_pallet = 0;
                    $mp_id[$mainpallet->mp_id][$pallet->tpl_id] = array(
                        'qtybox'        => $pallet->tpl_qty,
                        'weightbox'     => $pallet->tpl_qty *  $pallet->sit_netweight,
                        'useweight'     => 0,
                        'remainweightbox' => $pallet->tpl_qty *  $pallet->sit_netweight,
                        'statusmp' => "",
                    );
    
                    if($mainpallet->mp_pallet_qty_main == 1){
                        $max_weight_per_pallet += $mp_id[$mainpallet->mp_id][$pallet->tpl_id]['weightbox'];
                        $weight_pallet[$digi][$key] = $max_weight_per_pallet;
                     }else{
                        $max_weight_per_pallet += $per_pallet[$digi] * $weight_qty[$digi];
                        $weight_pallet[$digi][0] = $max_weight_per_pallet;
                     }
               }
    
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
                if($container2['remain'] > $pallet_weight[$a][$key]['max'] && count($container2['pallet_id']) < $qty_pallet){
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
                'pallet_id'     => [],
                'container_id'  => [],
            );
        }

     
      
        for($a=0;$a<$digi;$a++){
              foreach($pallet_weight[$a] as $key => $val){
                for($c=1;$c<=count($container);$c++){

                        if($container[$c]['remain'] > $pallet_weight[$a][$key]['max'] && count($container[$c]['pallet_id']) < $qty_pallet){
                            $container[$c]['use']                          = $container[$c]['use'] + $pallet_weight[$a][$key]['max'];
                            $container[$c]['remain']                       = $container[$c]['max'] - $container[$c]['use'];
                            $container[$c]['pallet_id'][]                  += $key;
                            $container[$c]['container_id'][]                += $a;
                            unset($pallet_weight[$a][$key]);
                            break;
                        }
                }
            }

            
        }

        $id                     = $request->id;
        $type                   = $request->type;
        $container_size         = $request->selec_con;
        $location               = $request->location;
        $over_kk                = $request->over_kk;
        $qty_pallet             = $request->qty_pallet;
        $container_qty          = $count_container+1;
        
        $data = array(
            'id'                    => $id,
            'type'                  => $type,
            'container_size'        => $container_size,
            'location'              => $location,
            'pallet_weight'         => $pallet_item_id,
            'container'             => $container,
            'over_kk'               => $over_kk,
            'qty_pallet'            => $qty_pallet,
            'container_qty'         => $container_qty,
        );  

        // foreach(Session::get('cart') as $value){
        //     echo $value['id'];
        // }

        // $id                     = $request->id;
        // $selec_con              = $request->selec_con;
        // $qty_pallet             = $request->qty_pallet;
        // $location               = $request->location;
        // $weight_total           = $request->weight_total;
        // $amout_pallet           = $request->amout_pallet;
        // $pallet_select          = $request->pallet_select; 
        // $type                   = $request->type;

        // if($qty_pallet == null){
        //     $qty_pallet = 9999;
        // }
        // $data = array(
        //     'id' => $id,
        //     'container_size' => $selec_con,
        //     'over_kk' => $over_kk,
        //     'qty_pallet' => $qty_pallet,
        //     'location' => $location,
        //     'amout_pallet' => $amout_pallet,
        //     'pallet_select' => $pallet_select,
        //     'type' => $type,
        // );  

        // dd($data);
        return view('box.preview_auto',$data);
    }
    
}

