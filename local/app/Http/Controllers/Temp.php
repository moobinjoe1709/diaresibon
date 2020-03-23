<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use App\Boxs;
use App\Pallets;
use App\Mainpallet;
use Session;
use App\Http\Requests\CheckOverview;

class BoxController extends Controller
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
        // $tb_products = DB::table('tb_products')->get();  
        $data = array(
            'boxs' => $boxs,
            // 'tb_products' =>$tb_products,
        );

        return view('box.index',$data);
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
      
        $boxs = DB::table('tb_boxs')
                    ->orderBy('bo_so','asc')
                    ->where('bo_pd_id','=',$id)
                    ->get();


        $data = array(
            'boxs' => $boxs,
        );
        return view ('box.modal',$data);
                
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {  

      
        $tb_boxs = DB::table('tb_boxs')
                ->leftjoin('tb_items','tb_boxs.bo_item','=','tb_items.it_name') 
                ->leftjoin('tb_subitems','tb_items.it_id','=','tb_subitems.sit_it_id')
                ->leftjoin('tb_master','tb_items.it_id','=','tb_master.mt_fg_id')
                ->leftjoin('tb_typepalate','tb_subitems.sit_pallet','=','tb_typepalate.tp_id')
                ->where('bo_pd_id','=',$id)
                ->where('bo_order_qty_sum','<>',0)
                ->orderBy('bo_so','asc')
                ->groupBy('bo_id')
                ->get();

        $tb_boxs2 = DB::table('tb_boxs')
                ->leftjoin('tb_items','tb_boxs.bo_item','=','tb_items.it_name') 
                ->leftjoin('tb_subitems','tb_items.it_id','=','tb_subitems.sit_it_id')
                ->leftjoin('tb_typepalate','tb_subitems.sit_pallet','=','tb_typepalate.tp_id')
                ->where('bo_pd_id','=',$id)
                ->whereNotNull('tp_id')
                ->orderBy('bo_so','asc')
                ->groupBy('tp_id')
                ->get();

        $tb_boxs2_1 = DB::table('tb_boxs')
                ->leftjoin('tb_items','tb_boxs.bo_item','=','tb_items.it_name') 
                ->leftjoin('tb_subitems','tb_items.it_id','=','tb_subitems.sit_it_id')
                ->leftjoin('tb_typepalate','tb_subitems.sit_pallet','=','tb_typepalate.tp_id')
                ->leftjoin('tb_master','tb_items.it_id','=','tb_master.mt_fg_id')
                ->where('bo_pd_id','=',$id)
                ->where('bo_order_qty_sum','<>',0)
                ->whereNotNull('tp_id')
                ->orderBy('bo_so','asc')
                ->groupBy('tp_id', 'sit_typepallet')
                ->get();
        
        $boxs = DB::table('tb_boxs')
                ->leftjoin('tb_items','tb_boxs.bo_item','=','tb_items.it_name') 
                ->leftjoin('tb_subitems','tb_items.it_id','=','tb_subitems.sit_it_id')
                ->leftjoin('tb_typepalate','tb_subitems.sit_pallet','=','tb_typepalate.tp_id')
                ->where('bo_pd_id','=',$id)
                ->whereNotNull('tp_id')
                ->orderBy('bo_so','asc')
                ->groupBy('bo_item')
                ->get();

        // dd($tb_boxs2_1);

        $data = array(
            'tb_boxs' => $tb_boxs,
            'tb_boxs2' => $tb_boxs2,
            'tb_boxs2_1' => $tb_boxs2_1,
            'boxs' => $boxs
        );
       
        return view ('box.edit',$data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(CheckOverview $request, $id)
    {
       
        $value = $request->sel;

        $comma_separated = implode(",", $value);
     
        // $boxs = DB::table('tb_boxs')->select(DB::raw('sum(bo_unit_box) as sum_box','tb_boxs.*'))->whereIn('bo_id', explode(',',$comma_separated))->groupBy('bo_item')->get();
        $boxs = DB::table('tb_boxs')
            ->select(DB::raw('sum(bo_order_qty_sum) as sum_box'),'tb_boxs.*','tb_typepalate.*','tb_subitems.*')
            ->leftjoin('tb_items','tb_boxs.bo_item','=','tb_items.it_name') 
            ->leftjoin('tb_subitems','tb_items.it_id','=','tb_subitems.sit_it_id')
            ->leftjoin('tb_typepalate','tb_subitems.sit_pallet','=','tb_typepalate.tp_id')
            ->whereIn('bo_id', explode(',',$comma_separated))
            ->groupBy('tp_id')
            ->where('bo_pd_id','=',$id)
            // ->where('bo_order_qty_sum','<>',0)
            ->orderBy('bo_so','asc')
            ->get();

        // dd($boxs);
        $boxs2 = DB::table('tb_boxs')->where('bo_pd_id','=',$id)->whereIn('bo_id', explode(',',$comma_separated))->get();
        $check_load = 0;
        foreach($boxs2 as $box2){
            // echo $boxs2[0]->bo_size_mm.' = '.$box2->bo_size_mm.'<br/>';
            if($boxs2[0]->bo_size_mm != $box2->bo_size_mm){
            
                ++$check_load;
            }
        }

        $data = array(
            'boxs' => $boxs,
            'comma_separated' => $comma_separated,
            'id' => $id,
        );
       
        if($check_load >= 1){
            // dd('1');
            return view('box.show',$data);
        }else{
            
            // dd('3');
            return view('box.overview',$data);
        }
       
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
       
    }

    public function calculate($id,$type,$qty)
    {
      
        $row_box = 1;
        $boxrow[] = [];

        
       
        // exit();
       $boxs = Boxs::where('bo_id',$id)
                    ->leftjoin('tb_items','tb_boxs.bo_item','=','tb_items.it_name')
                    ->leftjoin('tb_subitems','tb_items.it_id','=','tb_subitems.sit_it_id')
                    ->groupBy('sit_it_id')
                    ->first();
        $qty_total = $boxs->bo_order_qty_sum/$boxs->bo_pack_qty;
     

        // echo $qty*$boxs->sit_grossweight;
        if($qty <= 0){
            echo 3;
        }else{
            if( $qty_total < $boxs->sit_cartonlayer){
                $product = collect([
                'id'  =>  $id,
                'qty' =>  $qty*$boxs->bo_pack_qty,
                'weight' =>  $qty*$boxs->sit_grossweight
                ]);
                Session::push('cart', $product);

                $boxs2 = Boxs::where('bo_id',$id)->first();
                $boxs2->bo_order_qty_sum = $boxs->bo_order_qty_sum - $qty*$boxs->bo_pack_qty;
                $boxs2->save();
                echo 1;
                // echo $boxs->bo_order_qty_sum - $qty;
                // echo $qty;
            }else{

                $product = collect([
                    'id'  =>  $id,
                    'qty' =>  $boxs->sit_cartonlayer*$boxs->bo_pack_qty,
                    'weight' =>  ($boxs->sit_cartonlayer*$boxs->bo_pack_qty)*$boxs->sit_grossweight
                ]);

                Session::push('cart', $product);

                $boxs2 = Boxs::where('bo_id',$id)->first();
                $boxs2->bo_order_qty_sum = $boxs->bo_order_qty_sum - $boxs->sit_cartonlayer*$boxs->bo_pack_qty;
                $boxs2->save();
                echo 2;
                // echo  $boxs->bo_order_qty_sum - $boxs->sit_cartonlayer;
                // echo  $boxs->bo_order_qty_sum - $boxs->sit_cartonlayer*$boxs->bo_pack_qty;

            }
        }
    }


    public function deletepallet($id,$qty,$box){
        $boxs = Boxs::where('bo_id',$box)->first();
        
        $boxs2 = Boxs::where('bo_id',$box)->first();
        $boxs2->bo_order_qty_sum = $boxs->bo_order_qty_sum+$qty;
        $boxs2->save();

        Session::forget('cart.'.$id);
    }

    public function loadpalletman(Request $request){
        $all_weight = 0;
        foreach(Session::get('cart') as $item){
            $all_weight += $item['weight'];
        }

        $mainpallet = new Mainpallet();
        $mainpallet->mp_qty         = $request->total_pallet;
        $mainpallet->mp_location    = $request->sel_location;
        $mainpallet->mp_weight      =  $all_weight;
        $mainpallet->mp_pallet_qty  = 1;
        $mainpallet->mp_height      = $request->total_height;
        $mainpallet->mp_layer      =  $request->pallet_layer;
        $mainpallet->save();

        $pallet = Pallets::whereRaw('tpl_num = (select max(`tpl_num`) from tb_pallet)')->first();
      
        if($pallet == null){
            $max_pallet_num = 1;
        }else{
            $max_pallet_num = $pallet->tpl_num + 1;
        }

        foreach(Session::get('cart') as $item){
            $boxs = Boxs::find($item['id']);
            $pallets                =   new Pallets;
            $pallets->tpl_mp_id     =   $mainpallet->mp_id;
            $pallets->tpl_num       =   $max_pallet_num;
            $pallets->tpl_bo_id     =   $boxs->bo_id;
            $pallets->tpl_pd_id     =   $boxs->bo_pd_id;
            $pallets->tpl_qty       =   abs($item['qty']/$boxs->bo_pack_qty);
            $pallets->tpl_sum_qty   =   abs($item['qty']);
            $pallets->save(); 
        }
       
        Session::forget('cart');

        return redirect(url('box').'/'.$boxs->bo_pd_id.'/edit')->with('success','Load Pallet Success');
    }
    
      // $value = array_unique($request->sel);
        // $comma_separated = implode(",", $value);
       
        // // $boxs = DB::table('tb_boxs')->select(DB::raw('sum(bo_unit_box) as sum_box','tb_boxs.*'))->whereIn('bo_id', explode(',',$comma_separated))->groupBy('bo_item')->get();
        // $boxs = DB::table('tb_boxs')
        //     ->select(DB::raw('sum(bo_order_qty_sum) as sum_box'),'tb_boxs.*','tb_typepalate.*','tb_subitems.*')
        //     ->leftjoin('tb_items','tb_boxs.bo_item','=','tb_items.it_name') 
        //     ->leftjoin('tb_subitems','tb_items.it_id','=','tb_subitems.sit_it_id')
        //     ->leftjoin('tb_typepalate','tb_subitems.sit_pallet','=','tb_typepalate.tp_id')
        //     ->whereIn('bo_id', explode(',',$comma_separated))
        //     ->groupBy('tp_id')
        //     ->where('bo_pd_id','=',$id)
        //     ->orderBy('bo_so','asc')
        //     ->get();
        // $data = array(
        //     'boxs' => $boxs,
        //     'comma_separated' => $comma_separated,
        //     'id' => $id,
        // );
       
        // if($request->button_load == 'manual_load'){
        //     // dd('1');
        //     return view('box.manual',$data);
        // }else if($request->button_load == 'auto_load'){
        //     // dd('2');
        //     return view('box.auto',$data);
        // }else{
        //     // dd('3');
        //     return view('box.show',$data);
        // }

        public function showpallet(){
            return view('box.showpallet');
        }

        public function showtable($item,$id){
            
            $boxs = DB::table('tb_boxs')
            ->select(DB::raw('sum(bo_order_qty_sum) as sum_box'),'tb_boxs.*','tb_typepalate.*','tb_subitems.*')
            ->leftjoin('tb_items','tb_boxs.bo_item','=','tb_items.it_name') 
            ->leftjoin('tb_subitems','tb_items.it_id','=','tb_subitems.sit_it_id')
            ->leftjoin('tb_typepalate','tb_subitems.sit_pallet','=','tb_typepalate.tp_id')
            ->whereIn('bo_id', explode(',',$item))
            ->groupBy('tp_id')
            ->where('bo_pd_id','=',$id)
            // ->where('bo_order_qty_sum','<>',0)
            ->orderBy('bo_so','asc')
            ->get();

            $boxs2 = DB::table('tb_boxs')->whereIn('bo_id', explode(',',$item))->get();
            $check_load = 0;
            foreach($boxs2 as $box2){
                // echo $boxs2[0]->bo_size_mm.' = '.$box2->bo_size_mm.'<br/>';
                if($boxs2[0]->bo_size_mm != $box2->bo_size_mm){
                
                    ++$check_load;
                }
            }

            $data = array(
                'boxs' => $boxs,
                'item' => $item,
                'id' => $id,
            );

            return view('box.showtable',$data);
        }
        
        public function changelock($id){
            $mainpallet = Mainpallet::find($id);
            $mainpallet->mp_status = 1;
            $mainpallet->save();

            echo 1;
        }

        public function changeunlock($id){
            $mainpallet = Mainpallet::find($id);
            $mainpallet->mp_status = 0;
            $mainpallet->save();

            echo 1;
        }

        public function autoload2(Request $request){
        
        $boxs = Boxs::where('bo_id',$request->box_select[0])
                    ->leftjoin('tb_items','tb_boxs.bo_item','=','tb_items.it_name')
                    ->leftjoin('tb_subitems','tb_items.it_id','=','tb_subitems.sit_it_id')
                    ->first();
        $mainpallet                 = new Mainpallet();
        $mainpallet->mp_qty         = ($request->total_pallet)  * ($request->cartonlayer * $request->cartonperlayer);
        $mainpallet->mp_location    = $request->sel_location;
        $mainpallet->mp_weight      = $request->total_weight;
        $mainpallet->mp_pallet_qty  = $request->total_pallet;
        $mainpallet->mp_height      = $boxs->sit_cartonheigh*$request->cartonperlayer;
        $mainpallet->mp_layer      =  $request->pallet_layer;
        // $mainpallet->save();

        $pallet_amount       = ($request->total_pallet)  * ($request->cartonlayer * $request->cartonperlayer);
        $pallet_amount_check = ($request->total_pallet)  * ($request->cartonlayer * $request->cartonperlayer);
        $total_sum_box       = $request->total_sum_box;
        $box_selects         = $request->box_select;
        $box_items           = $request->box_item;
        $total               = 0;
        $max_array           = count($box_items);

       
        $pallet = Pallets::whereRaw('tpl_num = (select max(`tpl_num`) from tb_pallet)')->first();

        if($pallet == null){
            $max_pallet_num = 1;
        }else{
            $max_pallet_num = $pallet->tpl_num + 1;
        }
    
        $sum_check = 0;
        
        $one_pallet = $request->cartonlayer*$request->cartonperlayer;
        $count_pallet  = 0;
        $plus_pallet = 0;
        $count_one_pallet = 0;
        foreach($box_items as $key => $box_item){
            $boxs = Boxs::find($box_selects[$key]);

            $loop_pallet = ($box_item/$boxs->bo_pack_qty) / $one_pallet.'<br/>';
            $amount_pallet = $box_item/$boxs->bo_pack_qty;

            for($a=0;$a<$loop_pallet;$a++){
                if($amount_pallet < $one_pallet){
                    $amount_pallet  =  (int)$amount_pallet.'<br/>';
                    $plus_pallet = 1;
                    ++$count_one_pallet;
                    if($count_one_pallet == 1){
                        echo $one_pallet.'<br/>';
                    }
                }else{
                    $amount_pallet  =  (int)$amount_pallet -  $one_pallet;
                    ++$count_pallet;
                    echo $one_pallet.'<br/>';
                }
                
            }
            
        }

        echo $count_pallet+$plus_pallet;
        // return redirect(url('box').'/'.$boxs->bo_pd_id.'/edit')->with('success','Load Pallet Success');
    }
        
}
