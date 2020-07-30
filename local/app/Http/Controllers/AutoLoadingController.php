<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Session;
use App\Boxs;
use App\Pallets;
use App\Mainpallet;
use App\Container;
use App\ContainerDetial;
use App\ContainerDes;
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
                              
                // echo "<br/>";
                // echo "1 :".$qty_box_remain;
                // echo "<br/>";
                // echo "2 :".$qty_box_use;
                // echo "<hr>";

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
                $container->ctn_size        = $request->selec_con;
                $container->ctn_location    = $request->location;
                $container->ctn_over_kk     = $request->over_kk;
                $container->ctn_number      = $container_count;
                $container->ctn_use         = $val['use'];
                $container->ctn_remain      = $val['remain'];
                $container->ctn_date        = now();
                $container->pallet_id       = $im_pallet;
                $container->container_id    = $im_container;
                $container->ctn_group       = $container_group;
                $container->save();
              
            }
            
            $result = array();
            foreach ($container_idm as $array) {
                $result = array_merge($result, explode(',',$array));
            }
    
          
            $numpallet = array_unique($result);
    
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
       


        // dd( $numpallet,Session::get('container'),Session::get('pallet_weight'),Session::get('mainpallet'),Session::get('concat'));

    
        
        // foreach(Session::get('pallet_weight') as $key => $val){
        //     echo "<pre>";
        //     print_r($val);
        // }
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
                        ->where('mp_location',$request->location)
                        ->where('mp_status',"<>",1)
                        ->first();

            // dd($mainpallet,$value,$request->location);
            if($mainpallet !== null){

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
            'pallet_max'    => $qty_pallet,
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
                        'pallet_max'    => $qty_pallet,
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
                'pallet_max'    => $qty_pallet,
                'pallet_id'     => [],
                'container_id'  => [],
            );
        }

     
       
        for($a=0;$a<$digi;$a++){
              foreach($pallet_weight[$a] as $key => $val){
                for($c=1;$c<=count($container);$c++){
                        if($container[$c]['remain'] > $pallet_weight[$a][$key]['max'] && count($container[$c]['pallet_id']) < $container[$c]['pallet_max']){
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
        $qty_pallet             = $qty_pallet;
        $container_qty          = $count_container+1;
        
       
        
     
        // dd(count($container),$container, $qty_pallet);

        // for($i=1;$i<=count($container);$i++){
            if(count($container[count($container)]['pallet_id']) != $container_size){
                unset($container[count($container)]);
            }
        // }
        $use_pallet_all = 0;
        for($i=1;$i<=count($container);$i++){
           $use_pallet_all += count($container[$i]['pallet_id']);
        }
  
        // $pallet_qty_all_load = count($container)*$container_size;


     

        $all_palletload = 0;
        foreach($arr_results as $val){
            $mainpallet = Mainpallet::find($val);
            $all_palletload += $mainpallet->mp_pallet_qty;
            if($all_palletload){

            }
        }

        
        $max_count_pallet = 0;
        $remain_pallet = $use_pallet_all;

        // dd($use_pallet_all);
        $concat = [];
        $check_lap = 0;
        foreach($arr_results as $val){
            $mainpallet = Mainpallet::find($val);
            if($check_lap == 0){
                $use_pallet = 0;
            }   
            // echo $max_count_pallet." != ".$use_pallet_all."<br/>";
            if($max_count_pallet != $use_pallet_all){
                // echo $use_pallet." <= ".$remain_pallet." && ".$use_pallet_all." >= ".$mainpallet->mp_pallet_qty."<br/>";
                if($use_pallet <= $remain_pallet && $use_pallet_all >= $mainpallet->mp_pallet_qty){
                    // echo $mainpallet->mp_pallet_qty." : 1 <br/>";
                    if($remain_pallet > $mainpallet->mp_pallet_qty){
                        $use_pallet             += $mainpallet->mp_pallet_qty;
                        $remain_pallet          -= $mainpallet->mp_pallet_qty;
                        $max_count_pallet       += $mainpallet->mp_pallet_qty;
                        $concat[]               = $mainpallet->mp_pallet_qty;
                    }else{
                        $use_pallet             += $remain_pallet;
                        $remain_pallet          = $remain_pallet;
                        $max_count_pallet       += $remain_pallet;
                        $concat[]               = $remain_pallet;
                        
                    }
                }else{
                    if($remain_pallet > $mainpallet->mp_pallet_qty){
                        // echo $remain_pallet." 2 <br/>";
                        $use_pallet         =   $mainpallet->mp_pallet_qty;
                        $concat[]           =   $mainpallet->mp_pallet_qty;
                        $max_count_pallet   +=  $mainpallet->mp_pallet_qty;
                        $remain_pallet      -=  $mainpallet->mp_pallet_qty;
                    }else{
                        // echo $remain_pallet." 3 <br/>";
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
        
       
        Session::put('container',$container);
        Session::put('pallet_weight',$pallet_item_id);
        Session::put('mainpallet',$arr_results);
        Session::put('concat',$concat);

        // dd(Session::get('container'),Session::get('pallet_weight'),Session::get('mainpallet'),Session::get('concat'));
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

