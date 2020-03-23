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
    public function store(Request $request)
    {
     
        $count_pallet = 0;
        $count_container = Container::whereRaw('ctn_number = (select max(`ctn_number`) from tb_containers)')->first();

     
        if($count_container == null){
            $max_container_num = 1;
        }else{
            $max_container_num = $count_container->ctn_number + 1;
        }


        // dd($request);
        foreach($request->pallet_select as $item){
            $mainpallet = Mainpallet::where('mp_id','=',$item)->where('mp_status','<>',1)->first();
            $pallets = Pallets::where('tpl_mp_id','=',$mainpallet->mp_id)->get();
            $weight_all = 0;
            foreach($pallets as $pallet){
                $boxs = Boxs::where('bo_id','=',$pallet->tpl_bo_id)->get();
                
            }
        }
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
}

