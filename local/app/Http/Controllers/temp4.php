<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\Chkmanualpallet;
use Illuminate\Support\Facades\Validator;
use App\Pallets;
use App\Boxs;
use App\Container;
use App\Mainpallet;
use App\ContainerDetial;
use DB;
use Session;
class ManualLoadingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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

    public function store(Request $request){
       
        $count_container = Container::whereRaw('ctn_number = (select max(`ctn_number`) from tb_containers)')->first();

        if($count_container == null){
            $max_container_num = 1;
        }else{
            $max_container_num = $count_container->ctn_number + 1;
        }
        
        $qty_pallet = 0;
        $check_lap = 0;
        
        
        
        foreach(Session::get('cart') as $key => $value){
            $sum_qty =  $value['qty'];
        
            $mainpallet = Mainpallet::find($value['id']);
            $mainpallet2 = Mainpallet::find($value['id']);
        
         
            $sum_item =  (($mainpallet->mp_qty_main/$mainpallet->mp_pallet_qty_main)*$value['qty']);
        
        
            $qty_pallet += $value['qty'];
        
            $pallets = DB::table('tb_pallet')
                            ->leftjoin('tb_boxs','tb_pallet.tpl_bo_id','=','tb_boxs.bo_id')
                            // ->leftjoin('tb_items','tb_boxs.bo_item','=','tb_items.it_name')
                            // ->leftjoin('tb_subitems','tb_items.it_id','=','tb_subitems.sit_it_id')
                            ->where('tpl_mp_id',$value['id'])
                            ->get();
            $item = 0;
            $temp_qty = 0;
            $temp_qty2 = $sum_item;
            foreach($pallets as $key2 => $pallet){
                if($temp_qty2 < $mainpallet->mp_qty_main){
                    echo $temp_qty." != ".$temp_qty2." ----> ";
                    if($temp_qty != $temp_qty2){
                        echo $sum_item." <= ".$pallet->tpl_qty." : ";
                        if($sum_item <= $pallet->tpl_qty){
                             $sum_item = $sum_item - $pallet->tpl_qty;
                            if($sum_item < 0){
                                echo $sum_item = abs($sum_item);
                                $temp_qty += ($pallet->tpl_qty - $sum_item);
                            }else{
                                echo $sum_item = $sum_item;
                            }
                            // $temp_qty += 
                        }else{
                            echo $sum_item =  abs($sum_item - $pallet->tpl_qty);
                            $temp_qty += ($pallet->tpl_qty);
                        }
                    }
                }else{
                        // if($temp_qty <= $sum_item){
                        //     if($sum_item <= $pallet->tpl_qty){
                        //         $sum_item =  $pallet->tpl_qty - $sum_item;
                        //         $temp_qty =  $pallet->tpl_qty - $sum_item ;
                                
                        //     }else{
                        //         $sum_item =  $sum_item - $pallet->tpl_qty;
                        //         $temp_qty =  $sum_item -$pallet->tpl_qty;
                        //     }
                        // }else{
                        //     $sum_item = $pallet->tpl_qty;
                        // }
                }
                
                   
                  
                // echo $sum_item."<br/>"; //ค่าที่เหลือใน pallet 
                // echo $temp_qty."<br/>";
                // echo $item."<br/>";
                echo "<br/>"."<br/>";  
        
        
              
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
        $mainpallet = Mainpallet::find($id);
        $pallet = Pallets::leftjoin('tb_boxs','tb_boxs.bo_id','=','tb_pallet.tpl_bo_id')->where('tpl_mp_id','=',$mainpallet->mp_id)->get();
        $data = array(
            'mainpallet' =>  $mainpallet,
            'pallet' => $pallet,
        );
        return $data;
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

    public function delete_pallet(Request $request)
    {
        $mainpallet = Mainpallet::find($request->id);
        $pallets = Pallets::where('tpl_mp_id','=',$mainpallet->mp_id)->get();
        foreach($pallets as $pallet){
            $boxs2 = DB::table('tb_boxs')->where('bo_id','=',$pallet->tpl_bo_id)->first();
            if($boxs2->bo_order_qty_sum == 0){
                $boxs = DB::table('tb_boxs')->where('bo_id','=',$pallet->tpl_bo_id)->update([
                    'bo_order_qty_sum' => $pallet->tpl_sum_qty,
                ]);
            }else{
                $boxs = DB::table('tb_boxs')->where('bo_id','=',$pallet->tpl_bo_id)->update([
                    'bo_order_qty_sum' =>  $boxs2->bo_order_qty_sum +  $pallet->tpl_sum_qty ,
                ]);
            }
        }
        $pallets = Pallets::where('tpl_mp_id','=',$mainpallet->mp_id)->delete();

        $mainpallet = Mainpallet::destroy($request->id);
        
        if($mainpallet && $pallets){
            return 1;
        }else{
            return 0;
        }
       
    } 

    public function  delete_box(Request $request){

        $pallet = Pallets::where('tpl_mp_id','=',$request->id)->where('tpl_bo_id','=',$request->id2)->first();
        $boxs2 = DB::table('tb_boxs')->where('bo_id','=',$pallet->tpl_bo_id)->first();
      
        if($boxs2->bo_order_qty_sum == 0){
           
            $total_pallet = $pallet->tpl_qty;
            $mainpallet2 = Mainpallet::find($request->id);
            $boxs_sel = DB::table('tb_boxs')->leftjoin('tb_items','tb_boxs.bo_item','=','tb_items.it_name')->leftjoin('tb_subitems','tb_items.it_id','=','tb_subitems.sit_it_id')->where('bo_id',$pallet->tpl_bo_id)->get();
            
            $mainpallet = Mainpallet::find($request->id);

            $qty_pallet = (($mainpallet2->mp_qty - $total_pallet)/$boxs_sel[0]->sit_palletvolume); 
      
         
            $mainpallet->mp_qty                  = $mainpallet2->mp_qty - $total_pallet;
            $mainpallet->mp_qty_main             = $mainpallet2->mp_qty - $total_pallet;
            $mainpallet->mp_pallet_qty           = ceil($qty_pallet);
            $mainpallet->mp_pallet_qty_main      = ceil($qty_pallet);
            $mainpallet->save();

             $boxs = DB::table('tb_boxs')->where('bo_id','=',$pallet->tpl_bo_id)->update([
                'bo_order_qty_sum' => $pallet->tpl_sum_qty,
            ]);

        }else{
       
     
            $total_pallet = $pallet->tpl_qty;
            $mainpallet2 = Mainpallet::find($request->id);
            $boxs_sel = DB::table('tb_boxs')->leftjoin('tb_items','tb_boxs.bo_item','=','tb_items.it_name')->leftjoin('tb_subitems','tb_items.it_id','=','tb_subitems.sit_it_id')->where('bo_id',$pallet->tpl_bo_id)->get();
            
            $mainpallet = Mainpallet::find($request->id);

            $qty_pallet = (($mainpallet2->mp_qty - $total_pallet)/$boxs_sel[0]->sit_palletvolume); 

            $mainpallet->mp_qty                  = $mainpallet2->mp_qty - $total_pallet;
            $mainpallet->mp_qty_main             = $mainpallet2->mp_qty - $total_pallet;
            $mainpallet->mp_pallet_qty           = ceil($qty_pallet);
            $mainpallet->mp_pallet_qty_main      = ceil($qty_pallet);
            $mainpallet->save();

            $boxs2 = DB::table('tb_boxs')->where('bo_id','=',$pallet->tpl_bo_id)->first();
            $boxs = DB::table('tb_boxs')->where('bo_id','=',$pallet->tpl_bo_id)->update([
                'bo_order_qty_sum' => $pallet->tpl_sum_qty + $boxs2->bo_order_qty_sum,
            ]);
        }
        $pallets = Pallets::where('tpl_id','=',$pallet->tpl_id)->delete();

        if($pallets){
            return 1;
        }else{
            return 0;
        }
    } 

    public function  change_box(Request $request){
    
        if($request->type_edit != null){
            $check_array = 0;
            foreach($request->qty as $key => $value){
                if($value != null){

                    $value_new = $request->qty_old[$key] - $value;
                    $pallet = Pallets::find($request->tpl_id[$key]);
                 
                    $boxs2 = DB::table('tb_boxs')->where('bo_id','=',$pallet->tpl_bo_id)->first();
                    $pallet->tpl_sum_qty = $value;
                    $pallet->tpl_qty =  $value /  $boxs2->bo_pack_qty;
                    $pallet->save();

                    $boxs3 = Boxs::find($pallet->tpl_bo_id);
                    if($boxs3->bo_order_qty_sum != 0){
                        $boxs3->bo_order_qty_sum =  $boxs3->bo_order_qty_sum + $value_new;
                    }else{
                        $boxs3->bo_order_qty_sum =  $value_new;
                    }
                    $boxs3->save();
                }else{
                    ++$check_array;
                }
            }
            // dd(count($request->qty) ."!=". $check_array);
            if(count($request->qty) != $check_array){
          
                $total_pallet = 0;
                $pallet_sel = Pallets::where('tpl_mp_id',$pallet->tpl_mp_id)->get();
                
                foreach($pallet_sel as $key => $value){
                    $boxs_sel = DB::table('tb_boxs')->leftjoin('tb_items','tb_boxs.bo_item','=','tb_items.it_name')->leftjoin('tb_subitems','tb_items.it_id','=','tb_subitems.sit_it_id')->where('bo_id',$value->tpl_bo_id)->get();
                    $total_pallet += $value->tpl_qty;
                }
                // dd($total_pallet/$boxs_sel[0]->sit_palletvolume);
                // // echo $total_pallet;
                if(strpos($total_pallet/$boxs_sel[0]->sit_palletvolume,".") !== false){
                    $qty_pallet = ($total_pallet/$boxs_sel[0]->sit_palletvolume) + 1; 
                }else{
                    $qty_pallet = ($total_pallet/$boxs_sel[0]->sit_palletvolume); 
                }
  
                $mainpallet = Mainpallet::find($request->mp_id);
                $mainpallet->mp_qty             = $total_pallet;
                $mainpallet->mp_pallet_qty      = $qty_pallet;
                $mainpallet->mp_location             = $request->sel_location;
                // $mainpallet->mp_pallet_qty      = $qty_pallet;
                $mainpallet->save();    
                // dd($mainpallet);
            }
           
            
            return redirect(url('ContainerLoad').'/'.$request->pd_id.'/'.$request->type)->with('success','แก้ไข Pallet เรียบร้อย');
        }else{
            $mainpallet = Mainpallet::find($request->mp_id);
            $mainpallet->mp_location             = $request->sel_location;
            $mainpallet->save();    

            foreach($request->qty as $key => $value){
                if($value != null){
                    $value_new = $request->qty_old[$key] - $value;
                    $pallet = Pallets::find($request->tpl_id[$key]);
                    $boxs2 = DB::table('tb_boxs')->where('bo_id','=',$pallet->tpl_bo_id)->first();
                    $pallet->tpl_sum_qty = $value;
                    $pallet->tpl_qty =  $value /  $boxs2->bo_pack_qty;
                    $pallet->save();
                }   
            }
            return redirect(url('ContainerLoad').'/'.$request->pd_id.'/'.$request->type)->with('success','แก้ไข Pallet เรียบร้อย');
        }
      

        
    }


    public function preview_manual(Request $request){
       
       $con_qty = $request->con_qty;
       $con_size = $request->container_size * 1000;
       $pallet_qty_check = ($request->container_size == 20 ? 20 : 40 );
       // dd(Session::get('cart'));
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

            

                // echo $mp_id[$mainpallet->mp_id][$pallet->tpl_id]['weightbox']."<br/>";
              

           }

           $mp_id_digi[$digi]  = $mainpallet->mp_id;
           ++$digi; //นับรวม main pallet ทั้งหมดมีกี่ main pallet
       }

    //    dd($weight_pallet);
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

    //    dd($mp_id,$pallet_item_id);

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
                                        @$mp_id[$mp_id_digi[$digis]][$tpl_id]['remainweightbox']    = @$mp_id[$mp_id_digi[$digis]][$tpl_id]['weightbox'] -  @$mp_id[$mp_id_digi[$digis]][$tpl_id]['useweight'];
                                       

                                        @$pallet_item_id[$digis][$check_l]['use']           += @$mp_id[$mp_id_digi[$digis]][$tpl_id]['remainweightbox'];
                                        @$pallet_item_id[$digis][$check_l]['tpl'][$tpl_id]  += @$mp_id[$mp_id_digi[$digis]][$tpl_id]['remainweightbox'];
                                        @$pallet_item_id[$digis][$check_l]['remain']                = @$pallet_item_id[$digis][$check_l]['max'] - @$pallet_item_id[$digis][$check_l]['use'];
                                        
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
       dd($pallet_item_id,$mp_id);
    

      
    //    dd($per_pallet,$pallet_qty,$weight_qty,$pallet_item,$mp_id,count($pallet_item));
        
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
                            // $container[$c]['pallet_id'][]                  =  $pallet_weight[$a][$key]['tpl'];
                            $container[$c]['pallet_id'][]                  += $key;
                           
                            unset($pallet_weight[$a][$key]);
                            break;
                        }
                }
            }

            
        }

       
       
        

        dd($per_pallet,$pallet_qty,$weight_qty,$pallet_item_id,$container,$pallet_weight);
        // dd(count($container),$container);

        // $id                 = $request->id;
        // $type               = $request->type;
        // $container_size     = $request->container_size;
        // $location           = $request->location;
        // $con_qty           = $request->con_qty;
    

        // $data = array(
        //     'id' => $id,
        //     'type' => $type,
        //     'container_size' => $container_size,
        //     'location' => $location,
        //     'con_qty' => $con_qty,
        //     'container' => $container,
        // );  
        // // dd($data);
        // return view('box.preview_manual',$data);
    }
}

