<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Excel;
use Session;
use Redirect;
use App\Customers;
use App\Master;
use App\Items;
use App\Http\Requests\CheckCustomer;
use App\Http\Requests\CheckMaster;
class MasterController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // $masters = DB::table('tb_master')->get();
        $customers = DB::table('tb_customers')->get();
        $typepalates = DB::table('tb_typepalate')->get();
        $data = array(
            // 'masters' => $masters,
            'customers' => $customers,
            'typepalates' => $typepalates
        );
        return view('master.index',$data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $customers = DB::table('tb_customers')->get();
        $typepalates = DB::table('tb_typepalate')->get();
        $data = array(
            'customers' => $customers,
            'typepalates' => $typepalates
        );
        return view('master.create',$data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CheckMaster $request)
    {
            // dd($request);
           
            $masters = new Master;
            $masters->mt_fg_id                          = $request->fg_id;
            $masters->mt_ct_id                          = $request->customer_id;
            // $masters->mt_pl_id                          = $request->pallet_type;
            
            // $masters->mt_ct_id                    = $request->customer_name;
            // $masters->mt_type                           = $request->type;
            // $masters->mt_fg_id                          = $request->fg_id;
            // $masters->mt_part_no                        = $request->part_no;
            // $masters->mt_size_wheel_mm                  = $request->size_wheel_mm;
            // $masters->mt_size_wheel_inch                = $request->size_wheel_inch;
            // $masters->mt_spec                           = $request->spec;
            // $masters->mt_packing_qty_inner              = $request->inner;
            // $masters->mt_packing_qty_outer              = $request->outer;
            // $masters->mt_over_outer                     = $request->over_outer;
            // $masters->mt_diamension_box_width           = $request->width_cm;
            // $masters->mt_diamension_box_leght           = $request->length_cm;
            // $masters->mt_diamension_box_higth           = $request->highth_cm;
            // $masters->mt_diamension_box_cbm             = $request->cbm;
            // $masters->mt_diamension_box_netweight       = $request->net_weight;
            // $masters->mt_diamension_box_grossweight     = $request->gross_weight;
            // $masters->mt_pallet_no1                     = $request->pallet_size1;
            // $masters->mt_pallet_no2                     = $request->pallet_size2;
            // $masters->mt_pallet_no3                     = $request->pallet_size3;
            // if($request->pallet_size1 != 0){
            //     $masters->mt_pallet_size1               = $request->pallet_size_1;
            // }
            // if($request->pallet_size2 != 0){
            //     $masters->mt_pallet_size2               = $request->pallet_size_2;
            // }
            // if($request->pallet_size3 != 0){
            //     $masters->mt_pallet_size3               = $request->pallet_size_3;
            // }

            $masters->save();

            return Redirect::back()->with('success', 'บันทึกรายการ Master เรียบร้อยแล้ว!!    ');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
       
        $customers =  DB::table('tb_customers')
                    ->where('ct_id','=',$id)
                    ->first();
       
        $items =  DB::table('tb_items')->where('it_ct_id',$id)->get();
        $masters =  DB::table('tb_master')
                    ->leftjoin('tb_items','tb_master.mt_fg_id','=','tb_items.it_id')
                    ->leftjoin('tb_customers','tb_master.mt_ct_id','=','tb_customers.ct_id')
                    ->where('mt_ct_id','=',$id)->get();
        

      
        $data = array(
            'customers' => $customers,
            'items' => $items,
            'masters' => $masters,
        );
        return view('master.show',$data);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $masters =  DB::table('tb_master')
                        ->leftjoin('tb_customers','tb_master.mt_customer_id','=','tb_customers.ct_id')
                        ->leftjoin('tb_typepalate','tb_master.mt_pallet_no1','=','tb_typepalate.tp_id')
                        ->where('mt_id','=',$id)
                        ->first();
        // dd( $masters );
        $customers = DB::table('tb_customers')->get();
        $typepalates = DB::table('tb_typepalate')->get();
        $data = array(
            'masters' => $masters,
            'customers' => $customers,
            'typepalates' => $typepalates,
        );

        return view('master.edit',$data);
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
        // dd( $request);
        // $pallet_size = implode(",",$request->pallet_size);
        $masters =  Master::find($id);
        $masters->mt_customer_id                = $request->customer_name;
        $masters->mt_type                       = $request->type;
        $masters->mt_fg_id                      = $request->fg_id;
        $masters->mt_part_no                    = $request->part_no;
        $masters->mt_size_wheel_mm              = $request->size_wheel_mm;
        $masters->mt_size_wheel_inch            = $request->size_wheel_inch;
        $masters->mt_spec                       = $request->spec;
        $masters->mt_packing_qty_inner          = $request->inner;
        $masters->mt_packing_qty_outer          = $request->outer;
        $masters->mt_over_outer                 = $request->over_outer;
        $masters->mt_diamension_box_width       = $request->width_cm;
        $masters->mt_diamension_box_leght       = $request->length_cm;
        $masters->mt_diamension_box_higth       = $request->highth_cm;
        $masters->mt_diamension_box_cbm         = $request->cbm;
        $masters->mt_diamension_box_netweight   = $request->net_weight;
        $masters->mt_diamension_box_grossweight = $request->gross_weight;
        $masters->mt_pallet_no1                 = $request->pallet_size1;
        $masters->mt_pallet_no2                 = $request->pallet_size2;
        $masters->mt_pallet_no3                 = $request->pallet_size3;
        if($request->pallet_size1 != 0){
            $masters->mt_pallet_size1           = $request->pallet_size_1;
        }
        if($request->pallet_size2 != 0){
            $masters->mt_pallet_size2           = $request->pallet_size_2;
        }
        if($request->pallet_size3 != 0){
            $masters->mt_pallet_size3           = $request->pallet_size_3;
        }

        $masters->save();

        return redirect(url('master'))->with('success', 'แก้ไขรายการ Master เรียบร้อยแล้ว!!    ');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $master = Master::destroy($id);
        return $master;
    }

    public function uploadmaster(Request $request){
        $path = $request->file('upload')->getRealPath();
        // config(['excel.import.startRow' => 4 ]);
        $data = Excel::load($path,function($reader){})->get();
        // echo "<pre>";
        // print_r($data);

        foreach($data[0]  as $key => $value){
           
            
            // echo $key.'-'.$value['fg_id'].'   '.$value['20_kgsplt'].'   '.$value['18_kgsplt'].'   '.$value['25_kgsplt']."<br>";
            // echo "<br>";
            $masters = new Master;
            $masters->mt_customer_id                = $value['customerid'];
            $masters->mt_type                       = $value['type'];
            $masters->mt_fg_id                      = $value['fg_id'];
            $masters->mt_part_no                    = $value['part_no'];
            $masters->mt_size_wheel_mm              = $value['size_wheelsmm'];
            $masters->mt_size_wheel_inch            = $value['size_wheelsinch'];
            $masters->mt_spec                       = $value['spec'];
            $masters->mt_packing_qty_inner          = $value['packingqtyinner'];
            $masters->mt_packing_qty_outer          = $value['packingqtyouter'];
            $masters->mt_over_outer                 = $value['overouter'];
            $masters->mt_diamension_box_width       = $value['diamensionwidth'];
            $masters->mt_diamension_box_leght       = $value['diamensionlength'];
            $masters->mt_diamension_box_higth       = $value['diamensionhighth'];
            $masters->mt_diamension_box_cbm         = $value['cbm'];
            $masters->mt_diamension_box_netweight   = $value['net_weight'];
            $masters->mt_diamension_box_grossweight = $value['gross_weight'];
            $masters->save();
        }
        return Redirect::back()->with('success', 'บันทึกรายการ Master เรียบร้อยแล้ว!!    ');
    }

    public function managercustomer(){
        $customers = DB::table('tb_customers')->get();
        $data = array(
            'customers' => $customers
        );
        return view('master.customer',$data);
    }

    public function createcustomer(CheckCustomer $request){ 
            $customers = new Customers;
            $customers->ct_sales_ccn           = $request->sales_ccn;
            $customers->ct_name                = $request->customer;
            $customers->ct_cus_ship_loc        = $request->cus_ship_loc;
            $customers->save();
            return Redirect::back()->with('success', 'บันทึกสร้าง Customer เรียบร้อยแล้ว!!    ');
    }

    public function delcustomer($id){
        $customer = Customers::destroy($id);
        echo $customer;
    }

    public function editcustomer($id){
        $customer = Customers::find($id);

        $data = array(
            'customer' => $customer
        );

        return $data;
    }

    public function updatecustomer(Request $request,$id){

        $customer = Customers::find($request->id);
        $customer->ct_sales_ccn           = $request->sales_ccn;
        $customer->ct_name                = $request->customer;
        $customer->ct_cus_ship_loc        = $request->cus_ship_loc;
        $customer->save();

        echo 1;
    }

    public function finditem($id){
        $item = DB::table('tb_items')
                ->leftjoin('tb_subitems','tb_items.it_id','=','tb_subitems.sit_it_id')
                ->where('sit_it_id','=',$id)
                ->first();

        return response()->json($item);
    }
}
