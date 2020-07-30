<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Excel;
use DB;
use Session;
use App\Products;
use App\Product_details;
use App\Boxs;
use App\Customers;
use App\Http\Requests\Checkimport;
class ProductImportController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $tb_products = DB::table('tb_products')->orderBy('pd_id','asc')->get();
    
        $customers = Customers::all();

        

        $data = array(
        'tb_products' => $tb_products,
        'customers' => $customers

        );
        return view ('product.index',$data);
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
    public function store(Checkimport $request)
    {   
        
        $path = $request->file('upload')->getRealPath();
        $data = Excel::load($path,function($reader){})->get();
        
     
       
        $products = DB::table('tb_products')
                    ->where('sales_ccn','=', $data[0]->sales_ccn)
                    ->where('mas_loc','=', $data[0]->mas_loc)
                    ->where('promise_date','=',$data[0]->promise_date)
                    ->where('ship_via','=', $data[0]->ship_via)
                    ->where('customer','=', $data[0]->customer)
                    ->get();
     
                 
        if(count($products) == 0){
            // dd(1);
                $customers = DB::table('tb_customers')
                    ->where('ct_sales_ccn','=', $data[0]->sales_ccn)
                    ->where('ct_name','=', $data[0]->customer)
                    ->where('ct_cus_ship_loc','=', $data[0]->cus_ship_loc)
                    ->get();
               
                if(count($customers) <= 0){
                    $customer = new Customers;
                    $customer->ct_sales_ccn         = $data[0]->sales_ccn;
                    $customer->ct_name              = $data[0]->customer;
                    $customer->ct_cus_ship_loc      = $data[0]->cus_ship_loc;
                    $customer->save();
                    session(['customer_id' => $customer->ct_id]);
                }else{
                    session(['customer_id' => $customers[0]->ct_id]);
                }
                
                foreach($data  as $key => $value){
                    if($key == 0){
                        $products = new Products;
                        $products->pd_ct_id          = Session::get('customer_id');;
                        $products->sales_ccn        = $value['sales_ccn'];
                        $products->mas_loc          = $value['mas_loc'];
                        $products->promise_date     = $value['promise_date'];
                        $products->ship_via         = $value['ship_via'];
                        $products->customer         = $value['customer'];
                        $products->cus_ship_loc     = $value['cus_ship_loc'];
                        $products->save();
                        session(['product_id' => $products->pd_id]);
                    }

                    $product_details = new Product_details;
                    $product_details->pdt_pd_id        = $products->pd_id;
                    $product_details->so               = $value['so'];
                    $product_details->so_line          = $value['so_line'];
                    $product_details->so_delivery      = $value['so_delivery'];
                    $product_details->fullfill_from    = $value['fullfill_from'];
                    $product_details->cus_item         = $value['cus_item'];
                    $product_details->cus_po           = $value['cus_po'];
                    $product_details->item             = $value['item'];
                    $product_details->revision         = $value['revision'];
                    $product_details->ordered_qty      = $value['ordered_qty'];
                    $product_details->um_scalar        = $value['um_scalar'];
                    $product_details->currency         = $value['currency'];
                    $product_details->sell_um          = $value['sell_um'];
                    $product_details->unit_price       = $value['unit_price'];
                    $product_details->pack_qty         = $value['pack_qty'];
                    $product_details->cus_spec         = $value['cus_spec'];
                    $product_details->size_mm          = $value['size_mm'];
                    $product_details->pc               = $value['pc'];
                    $product_details->pd_status        = 0;
                    $product_details->total            = $value['ordered_qty'];
                    $product_details->save();
                }
              
                    $product_groups = DB::table('tb_product_details')
                                    ->leftjoin('tb_products','tb_product_details.pdt_pd_id','=','tb_products.pd_id')
                                    ->where('pdt_pd_id','=',$product_details->pdt_pd_id)
                                    ->get();     
                              
                    foreach($product_groups as $key2 => $product_group){
                        // echo $product_group->pdt_id."</br>";
                        $boxs = new Boxs;
                        $boxs->bo_sale_ccn          = $product_group->sales_ccn;
                        $boxs->bo_pd_id             = Session::get('product_id');
                        $boxs->bo_ct_id             = Session::get('customer_id');
                        $boxs->bo_pdt_id            = $product_group->pdt_id;
                        $boxs->bo_mas_loc           = $product_group->mas_loc;
                        $boxs->bo_promise_date      = $product_group->promise_date;
                        $boxs->bo_ship_via          = $product_group->ship_via;
                        $boxs->bo_customer          = $product_group->customer;
                        $boxs->bo_fullfill_from     = $product_group->fullfill_from;
                        $boxs->bo_cus_ship_loc      = $product_group->cus_ship_loc;
                        $boxs->bo_so                = $product_group->so;
                        $boxs->bo_so_line           = $product_group->so_line;
                        $boxs->bo_so_delivery       = $product_group->so_delivery;
                        $boxs->bo_cus_item          = $product_group->cus_item;
                        $boxs->bo_cus_po            = $product_group->cus_po;
                        $boxs->bo_item              = $product_group->item;
                        $boxs->bo_revision          = $product_group->revision;
                        $boxs->bo_order_qty_sum     = $product_group->ordered_qty;
                        $boxs->bo_um_scalar         = $product_group->um_scalar;
                        $boxs->bo_currency          = $product_group->currency;
                        $boxs->bo_sell_um           = $product_group->sell_um;
                        $boxs->bo_unit_price        = $product_group->unit_price;
                        $boxs->bo_pack_qty          = $product_group->pack_qty;
                        $boxs->bo_cus_spec          = $product_group->cus_spec;
                        $boxs->bo_size_mm           = $product_group->size_mm;
                        $boxs->bo_pc                = $product_group->pc;
                        $boxs->bo_total             = $product_group->ordered_qty;
                        // $boxs->bo_unit_box          = floor($product_group->ordered_qty/$product_group->pack_qty);
                        // $boxs->bo_frangment         = $product_group->ordered_qty%$product_group->pack_qty;
                        // $boxs->bo_count             = floor($product_group->ordered_qty/$product_group->pack_qty);
                        $boxs->save();

                        // if($product_group->ordered_qty%$product_group->pack_qty != 0){
                        //     $boxs2 = Boxs::find($boxs->bo_id);
                        //     $boxs2->bo_unit_box      = (int)$boxs2->bo_unit_box + 1;
                        //     $boxs->bo_count          = (int)$boxs2->bo_unit_box + 1;
                        //     $boxs2->save();
                        // }
                    }
                Session::forget('product_id');
                Session::forget('customer_id');
            return Redirect::back()->with('success', 'บันทึกรายการสินค้า เรียบร้อยแล้ว!!    ');
        }else{
            return Redirect::back()->with('danger', 'มีการบันทึก Shipment นี้แล้ว!!    ');
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
        $products = DB::table('tb_products')
                        ->leftjoin('tb_product_details','tb_products.pd_id','=','tb_product_details.pdt_pd_id')
                        ->where('pdt_pd_id','=',$id)
                        ->orderBy('pdt_id','asc')
                        ->get();


        $data = array(
        'products' => $products,

        );
        return view ('product.show',$data);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $tb_products = DB::table('tb_products')->where('pd_id','=',$id)->first();
        $boxs = DB::table('tb_boxs')
                        ->where('bo_pd_id','=',$id)
                        ->orderBy('bo_id','asc')
                        ->get();

        $data = array(
        'tb_products' => $tb_products,
        'boxs' => $boxs,
        );


        return view ('product.edit',$data);

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Checkimport $request, $id)
    {
    
        $path = $request->file('upload')->getRealPath();
        $data = Excel::load($path,function($reader){})->get();
        foreach($data as $key => $value){
            $products = DB::table('tb_products')
                        ->leftjoin('tb_product_details','tb_products.pd_id','=','tb_product_details.pdt_pd_id')
                        ->where('pdt_pd_id','=', $id)
                        ->where('so','=', $value['so'])
                        ->where('so_line','=', $value['so_line'])
                        ->where('so_delivery','=', $value['so_delivery'])
                        ->first();
            
            if($products != null){
      
                $product_details2 = DB::table('tb_product_details')->where('pdt_id','=',$products->pdt_id)->first();
           
                $product_details = Product_details::find($products->pdt_id);
                if($product_details->total < $value['ordered_qty']){
                    $qty_sum    = $value['ordered_qty'] - $product_details->total;
                    $qty_total  =  $product_details->ordered_qty+$qty_sum;
                }else{
                    $qty_sum    = $value['ordered_qty'];
                    $qty_total  =  $value['ordered_qty'];
                }
                // echo $value['ordered_qty'].' - '.$product_details->total.' = '.$qty_sum.' / '.$qty_total."<br/>";

                $product_details->pdt_pd_id        = $id;
                $product_details->so               = $value['so'];
                $product_details->so_line          = $value['so_line'];
                $product_details->so_delivery      = $value['so_delivery'];
                $product_details->fullfill_from    = $value['fullfill_from'];
                $product_details->cus_item         = $value['cus_item'];
                $product_details->cus_po           = $value['cus_po'];
                $product_details->item             = $value['item'];
                $product_details->revision         = $value['revision'];
                $product_details->ordered_qty      = $qty_total;
                $product_details->um_scalar        = $value['um_scalar'];
                $product_details->currency         = $value['currency'];
                $product_details->sell_um          = $value['sell_um'];
                $product_details->unit_price       = $value['unit_price'];
                $product_details->pack_qty         = $value['pack_qty'];
                $product_details->cus_spec         = $value['cus_spec'];
                $product_details->size_mm          = $value['size_mm'];
                $product_details->pc               = $value['pc'];
                $product_details->pd_status        = 0;
                $product_details->total            = $qty_total;
                $product_details->save();

                $product_group = DB::table('tb_boxs')
                                ->where('bo_pd_id','=',$id)
                                ->where('bo_so','=',$value['so'])
                                ->where('bo_so_line','=',$value['so_line'])
                                ->where('bo_so_delivery','=',$value['so_delivery'])
                                ->first(); 
                $cus_id =    DB::table('tb_boxs')->where('bo_pd_id',$product_details2->pdt_pd_id)->first();
           
                if($product_group != null){
                   
                    $boxs = Boxs::find($product_group->bo_id);
                    if($boxs->bo_order_qty_sum == 0){
                        $qty_sum    = $value['ordered_qty'] - $boxs->bo_total;
                        $qty_total  =  $boxs->bo_total+$qty_sum;
                    }else{
                        if($boxs->bo_total < $value['ordered_qty']){
                           
                            $qty_sum    = ($value['ordered_qty'] - $boxs->bo_total)+$boxs->bo_order_qty_sum;
                            $qty_total  =  $boxs->bo_total+($value['ordered_qty'] - $boxs->bo_total);

                          
                        }else{
                      
                            $qty_sum    = $boxs->bo_order_qty_sum;
                            $qty_total  =  $value['ordered_qty'];
                        }
                    }
                       
                    $boxs->bo_ct_id             = $cus_id->bo_ct_id;
                    $boxs->bo_sale_ccn          = $value['sales_ccn'];
                    $boxs->bo_pd_id             = $id;
                    $boxs->bo_pdt_id            = $product_details->pdt_id;
                    $boxs->bo_mas_loc           = $value['mas_loc'];
                    $boxs->bo_promise_date      = $value['promise_date'];
                    $boxs->bo_ship_via          = $value['ship_via'];
                    $boxs->bo_customer          = $value['customer'];
                    $boxs->bo_fullfill_from     = $value['fullfill_from'];
                    $boxs->bo_cus_ship_loc      = $value['cus_ship_loc'];
                    $boxs->bo_so                = $value['so'];
                    $boxs->bo_so_line           = $value['so_line'];
                    $boxs->bo_so_delivery       = $value['so_delivery'];
                    $boxs->bo_cus_item          = $value['cus_item'];
                    $boxs->bo_cus_po            = $value['cus_po'];
                    $boxs->bo_item              = $value['item'];
                    $boxs->bo_revision          = $value['revision'];
                    $boxs->bo_order_qty_sum     = $qty_sum;
                    $boxs->bo_um_scalar         = $value['um_scalar'];
                    $boxs->bo_currency          = $value['currency'];
                    $boxs->bo_sell_um           = $value['sell_um'];
                    $boxs->bo_unit_price        = $value['unit_price'];
                    $boxs->bo_pack_qty          = $value['pack_qty'];
                    $boxs->bo_cus_spec          = $value['cus_spec'];
                    $boxs->bo_size_mm           = $value['size_mm'];
                    $boxs->bo_pc                = $value['pc'];
                    $boxs->bo_total             = $qty_total;
                    // $boxs->bo_unit_box          = floor($product_group->bo_order_qty_sum/$product_group->bo_pack_qty) + floor($value['ordered_qty']/$value['pack_qty']);
                    // $boxs->bo_frangment         = $product_group->bo_order_qty_sum%$product_group->bo_pack_qty + $value['ordered_qty']%$value['pack_qty'];
                    // $boxs->bo_count             = floor($product_group->bo_order_qty_sum/$product_group->bo_pack_qty) + floor($value['ordered_qty']/$value['pack_qty']);
                    $boxs->save();
                }
               
            }else{
      
                $cus_id =    DB::table('tb_boxs')->where('bo_pd_id',$id)->first();
               
                if($value['so'] != null){
                    $product_details = new Product_details;
                    $product_details->pdt_pd_id        = $id;
                    $product_details->so               = $value['so'];
                    $product_details->so_line          = $value['so_line'];
                    $product_details->so_delivery      = $value['so_delivery'];
                    $product_details->fullfill_from    = $value['fullfill_from'];
                    $product_details->cus_item         = $value['cus_item'];
                    $product_details->cus_po           = $value['cus_po'];
                    $product_details->item             = $value['item'];
                    $product_details->revision         = $value['revision'];
                    $product_details->ordered_qty      = $value['ordered_qty'];
                    $product_details->um_scalar        = $value['um_scalar'];
                    $product_details->currency         = $value['currency'];
                    $product_details->sell_um          = $value['sell_um'];
                    $product_details->unit_price       = $value['unit_price'];
                    $product_details->pack_qty         = $value['pack_qty'];
                    $product_details->cus_spec         = $value['cus_spec'];
                    $product_details->size_mm          = $value['size_mm'];
                    $product_details->pc               = $value['pc'];
                    $product_details->pd_status        = 0;
                    $product_details->total            = $value['ordered_qty'];
                    $product_details->save();

                    $boxs = new Boxs();
                    $boxs->bo_ct_id             = $cus_id->bo_ct_id;
                    $boxs->bo_sale_ccn          = $value['sales_ccn'];
                    $boxs->bo_pd_id             = $id;
                    $boxs->bo_pdt_id            = $product_details->pdt_id;
                    $boxs->bo_mas_loc           = $value['mas_loc'];
                    $boxs->bo_promise_date      = $value['promise_date'];
                    $boxs->bo_ship_via          = $value['ship_via'];
                    $boxs->bo_customer          = $value['customer'];
                    $boxs->bo_fullfill_from     = $value['fullfill_from'];
                    $boxs->bo_cus_ship_loc      = $value['cus_ship_loc'];
                    $boxs->bo_so                = $value['so'];
                    $boxs->bo_so_line           = $value['so_line'];
                    $boxs->bo_so_delivery       = $value['so_delivery'];
                    $boxs->bo_cus_item          = $value['cus_item'];
                    $boxs->bo_cus_po            = $value['cus_po'];
                    $boxs->bo_item              = $value['item'];
                    $boxs->bo_revision          = $value['revision'];
                    $boxs->bo_order_qty_sum     = $value['ordered_qty'];
                    $boxs->bo_um_scalar         = $value['um_scalar'];
                    $boxs->bo_currency          = $value['currency'];
                    $boxs->bo_sell_um           = $value['sell_um'];
                    $boxs->bo_unit_price        = $value['unit_price'];
                    $boxs->bo_pack_qty          = $value['pack_qty'];
                    $boxs->bo_cus_spec          = $value['cus_spec'];
                    $boxs->bo_size_mm           = $value['size_mm'];
                    $boxs->bo_pc                = $value['pc'];
                    // $boxs->bo_unit_box          = floor($value['ordered_qty']/$value['pack_qty']);
                    // $boxs->bo_frangment         = $value['ordered_qty']%$value['pack_qty'];
                    // $boxs->bo_count             = floor($value['ordered_qty']/$value['pack_qty']);
                    $boxs->bo_total             = $value['ordered_qty'];
                    $boxs->save();
                }
            }
        }
       
        return Redirect::back()->with('success', 'แก้ไขรายการสินค้า เรียบร้อยแล้ว!!    ');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {

        $product_details = DB::table('tb_product_details')->where('pdt_id', '=', $id)->delete();
        $tb_boxs = DB::table('tb_boxs')->where('bo_pdt_id', '=', $id)->delete();

        if($tb_boxs && $product_details){
            echo 1;
        }else{
            echo 2;
        }

    }

    public function ROJ()
    {
        $products = DB::table('tb_products')->where('fullfill_from','=','ROJ')->get();
        $data = array(
           'products' => $products
        );
        return view('product.roj',$data);
    }

    public function PIN()
    {
        $products2 = DB::table('tb_products')->where('fullfill_from','=','PIN')->get();
        $data = array(
           'products2' => $products2
        );
        return view('product.pin',$data);
    }

    public function delete($id)
    {
      
        $product = DB::table('tb_products')->where('pd_id', '=', $id)->first();
  
        $products = DB::table('tb_products')->where('pd_id', '=', $product->pd_id)->delete();
        $product_details = DB::table('tb_product_details')->where('pdt_pd_id', '=', $product->pd_id)->delete();

        $tb_boxs = DB::table('tb_boxs')->where('bo_pd_id', '=', $id)->delete();

        if($tb_boxs && $product_details &&  $tb_boxs){
            echo 1;
        }else{
            echo 2;
        }

    }
}
