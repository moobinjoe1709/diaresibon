<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\Chkmanualpallet;
use Illuminate\Support\Facades\Validator;
use App\Pallets;
use App\Boxs;
use App\Container;
use App\Mainpallet;
use App\ContainerDes;
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

        if(Session::get('container') > 0){
            // dd(Session::get('container'),Session::get('pallet_weight'),Session::get('mainpallet'),Session::get('concat'));

            $count_lap = 0;

            foreach(Session::get('mainpallet') as $key => $item){
            
                $mainpallet = Mainpallet::find($item);
                
                $per_pallet         =   $mainpallet->mp_qty_main / $mainpallet->mp_pallet_qty_main;     //จำนวนกล่่องต่อ pallet
                $count_pallet       =   $mainpallet->mp_pallet_qty - Session::get('concat')[$count_lap]; //จำนวน pallet ที่เหลิอ
                $qty_box_remain     =   $per_pallet*$count_pallet;
              
                // echo Session::get('concat')[$count_lap]."<br/>";
                $mainpallet2 = Mainpallet::find($item);
                $mainpallet2->mp_qty         = $qty_box_remain;
                $mainpallet2->mp_pallet_qty  = $count_pallet;
                $mainpallet2->save();
                
                
                $qty_box_use        =   0;
                $qty_box_remain     =   Session::get('concat')[$count_lap]*$per_pallet;
              
                // echo $qty_box_remain."<br/>";
                // echo "<br/>";
                $pallets = Pallets::where('tpl_mp_id',$item)->get();
                foreach($pallets as $pallet){
                    $pallet_1 = Pallets::where('tpl_id',$pallet->tpl_id)->first();
                    $pallet_edit = Pallets::where('tpl_id',$pallet->tpl_id)->first();

                    if( $qty_box_remain != $qty_box_use){
                        if($qty_box_use <= $qty_box_remain && $qty_box_remain >= $pallet->tpl_qty){
                            // echo " 1 <br/>";
                            if($qty_box_remain >= $pallet->tpl_qty){
                                $pallet_1->tpl_qty   = $pallet_edit->tpl_qty-$pallet->tpl_qty;
                                // echo $pallet->tpl_qty." 1 <br/>";
                                $qty_box_use         +=   $pallet->tpl_qty;
                                $qty_box_remain      -=  $pallet->tpl_qty;
                            }else{
                                $pallet_1->tpl_qty   = $pallet_edit->tpl_qty-$qty_box_remain;
                                // echo $qty_box_remain." 2 <br/>";
                                $qty_box_use         +=   $qty_box_remain;
                                $qty_box_remain      =    $qty_box_remain;
                            }
                        }else{
                            // echo " 2 <br/>";
                            if($qty_box_remain >= $pallet->tpl_qty){
                                $pallet_1->tpl_qty   = $pallet_edit->tpl_qty-$pallet->tpl_qty;
                                // echo $pallet->tpl_qty." 3 <br/>";
                                $qty_box_use         +=   $pallet->tpl_qty;
                                $qty_box_remain      -=   $pallet->tpl_qty;
                            }else{
                                $pallet_1->tpl_qty   = $pallet_edit->tpl_qty-$qty_box_remain;
                                // echo $qty_box_remain." 4 <br/>";
                                $qty_box_use         +=   $qty_box_remain;
                                $qty_box_remain       =   $qty_box_remain;
                            }
                        }
                    }
                    $pallet_1->save();
                }
                $count_lap++;
            }
    

            $container_group = DB::table('tb_containers')->where('ctn_pd_id',$request->id)->GroupBy('ctn_group')->get();
     
            $container_group = count($container_group)+1;

            foreach(Session::get('container') as $key => $val){
                $container_idm[] = implode(',',$val['container_id']);
                $im_pallet      = implode(',',$val['pallet_id']);
                $im_container   = implode(',',$val['container_id']);
                $container_count = Container::where('ctn_pd_id',$request->id)->count();

                $container = new Container;
                $container->ctn_pd_id       = $request->id;
                $container->ctn_size        = $request->container_size;
                $container->ctn_location    = $request->location;
                $container->ctn_over_kk     = $request->weight_over;
                $container->ctn_number      = $key;
                $container->ctn_use         = $val['use'];
                $container->ctn_remain      = $val['remain'];
                $container->ctn_date        = now();
                $container->pallet_id       = $im_pallet;
                $container->container_id    = $im_container;
                $container->save();
            }
     
            $result = array();
            foreach ($container_idm as $array) {
                $result = array_merge($result, explode(',',$array));
            }

     
            $numpallet = array_unique($result);
            // dd($numpallet);
            $status_pallet = 0;
            foreach($numpallet as $numpal){
                $previousKey = null;
                $previousId = null;
                foreach(Session::get('pallet_weight')[$numpal] as $key => $val){
                    // echo $val['max'];
                    if(count($val['tpl']) != null){
                        if(count($val['tpl']) > 1){
                            $containerde_type = 2;
                        }else{
                            $containerde_type = 1;
                        }
                    
                        $containerdetail  = new ContainerDetial();
                        $containerdetail->ctnd_pd_id    = $request->id;
                        $containerdetail->ctnd_ctn_id   = $container->ctn_id;
                        $containerdetail->ctnd_group    = $container_group;
                        $containerdetail->ctnd_type     = $containerde_type;
                        $containerdetail->ctnd_key      = $key;
                        $containerdetail->ctnd_max      = $val['max'];
                        $containerdetail->save();

                    
                        foreach($val['tpl'] as $num => $item){
                            
                        $containerdes = new ContainerDes();
                        $containerdes->ctnds_ctnd_id  =   $containerdetail->ctnd_id;
                        $containerdes->ctnds_key      =   $num;
                        $containerdes->ctnds_max      =   $item;
                        $containerdes->save();

                        
                        if($containerdetail->ctnd_type != $previousKey){
                                ++$status_pallet;
                            }
                
                            $containerdes2 = ContainerDes::find($containerdes->ctnds_id);
                            $containerdes2->ctnds_group    =   $status_pallet;
                            $containerdes2->save();

                        $previousKey  =       $containerdetail->ctnd_type;

                        }
                    }
                }
            }

            Session::forget('container');
            Session::forget('pallet_weight');
            Session::forget('cart');
            return redirect()->back()->with('success','Load Paller ขึ้น Container เรียบร้อย!!');
        }else{
            Session::forget('container');
            Session::forget('pallet_weight');
            Session::forget('cart');
            return redirect()->back()->with('danger','มีข้อผิดพลาดในการ Load Container กรุณาทำการโหลดอีกครั้ง');
        }
      
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
       $con_size = ($request->container_size * 1000) + $request->weight_over;
       if($request->pallet_qty_sel != null){
        $pallet_qty_check = $request->pallet_qty_sel;
       }else{
        $pallet_qty_check = ($request->container_size == 20 ? 20 : 40 );
       }
      
       
        $count_pallet = 0;
        $digi = 0;
        $qty_pallet = ($request->qty_pallet == null ? $pallet_qty_check : $request->qty_pallet);
      
        // dd($request->pallet_select,Session::get('cart'));
        
        $arr_items = array();
        foreach(Session::get('cart') as $item){
            $arr_items[] = $item['id'];
        }

        // dd($arr_items);
        $arr_results = array_filter( $arr_items );
      
     
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
    //    dd($pallet_item_id,$mp_id);
    

      
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
      

        $id                     = $request->id;
        $type                   = $request->type;
        $container_size         = $request->container_size;
        $location               = $request->location;
        $over_kk                = $request->over_kk;
        $qty_pallet             = $qty_pallet;
        $container_qty          = $count_container+1;
      
        // dd(count($container[count($container)]['pallet_id']),$container_size);

        // if(count($container[count($container)]['pallet_id']) != $container_size){
        //     unset($container[count($container)]);
        // }
        // dd($container,$request,$container_size);

        $use_pallet_all = 0;
        for($i=1;$i<=count($container);$i++){
           $use_pallet_all += count($container[$i]['pallet_id']);
        }

        
        $all_palletload = 0;
        foreach($arr_results as $val){
            $mainpallet = Mainpallet::find($val);
            $all_palletload += $mainpallet->mp_pallet_qty;
            if($all_palletload){

            }
        }



        $max_count_pallet = 0;
        $remain_pallet = $use_pallet_all;


        $concat = [];
        $check_lap = 0;
        $arr_qty    = [];
        foreach(Session::get('cart') as $item){
            $arr_qty[] = $item['qty'];
        }
      
        foreach($arr_results as $key => $val){
            $mainpallet = Mainpallet::find($val);
          
            // print_r($mainpallet);
            if($check_lap == 0){
                $use_pallet = 0;
            }   
            // echo $max_count_pallet." != ".$use_pallet_all."<br/>";
            if($max_count_pallet != $use_pallet_all){
                // echo $use_pallet." <= ".$remain_pallet." && ".$use_pallet_all." >= ".$arr_qty[$key]."<br/>";
                if($use_pallet <= $remain_pallet && $use_pallet_all >= $arr_qty[$key]){
                       
                    if($remain_pallet > $arr_qty[$key]){
                        // echo $arr_qty[$key]." : 1 <br/>";
                        $use_pallet             += $arr_qty[$key];
                        $remain_pallet          -= $arr_qty[$key];
                        $max_count_pallet       += $arr_qty[$key];
                        $concat[]               = $arr_qty[$key];
                    }else{
                        // echo $remain_pallet." : 2 <br/>";
                        $use_pallet             += $remain_pallet;
                        $remain_pallet          = $remain_pallet;
                        $max_count_pallet       += $remain_pallet;
                        $concat[]               = $remain_pallet;
                        
                    }
                }else{
                    if($remain_pallet > $arr_qty[$key]){
                        // echo $remain_pallet." 3 <br/>";
                        $use_pallet         =   $arr_qty[$key];
                        $concat[]           =   $arr_qty[$key];
                        $max_count_pallet   +=  $arr_qty[$key];
                        $remain_pallet      -=  $arr_qty[$key];
                    }else{
                        // echo $remain_pallet." 4 <br/>";
                        $use_pallet         =   $remain_pallet;
                        $concat[]           =   $remain_pallet;
                        $max_count_pallet   +=  $remain_pallet;
                        $remain_pallet      =  $remain_pallet;
                    }
                 
                }
               
            }else{
                $concat[]       =  0;
            }
            // echo $remain_pallet;
            $check_lap++;
        }
  
        // dd($container);
        // dd(count($container),$container,$pallet_item_id,$mp_id);

        $id                 = $request->id;
        $type               = $request->type;
        $container_size     = $request->container_size;
        $location           = $request->location;
        $weight_over        = $request->weight_over;
        $pallet_qty_sel     = $request->pallet_qty_sel;
        $container_qty      = $count_container+1;

       
        Session::put('container',$container);
        Session::put('pallet_weight',$pallet_item_id);
        Session::put('mainpallet',$arr_results);
        Session::put('concat',$concat);

        $data = array(
            'id'                => $id,
            'type'              => $type,
            'container_size'    => $container_size,
            'location'          => $location,
            'pallet_weight'     => $pallet_item_id,
            'container'         => $container,
            'weight_over'       => $weight_over,
            'pallet_qty_sel'    => $pallet_qty_sel,
            'container_qty'     => $container_qty,
            'weight_all_kk'     => $request->weight_all_kk,
        );  

        // dd(Session::get('container'),Session::get('pallet_weight'),Session::get('mainpallet'),Session::get('concat'));
        return view('box.preview_manual',$data);
    }

    public function load_container(Request $request){
        
        $main = Mainpallet::where('mp_id',$request->id)->first();

        if((int)$request->qty_old <  (int)$request->qty){
            $qty        = $request->qty_old;
            $qty_item   =  $request->qty_old  * ($main->mp_qty / $request->qty);
        }else{
            $qty        = $request->qty;
            $qty_item   = $request->qty  * ($main->mp_qty / $request->qty);
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
}

