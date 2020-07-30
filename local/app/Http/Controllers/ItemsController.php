<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Session;
use Redirect;
use Excel;
use App;
use App\Items;
use App\SubItems;
use App\Customers;
use App\Http\Requests\Checkitem;
class ItemsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $itmes = DB::table('tb_items')->leftjoin('tb_customers','tb_items.it_ct_id','=','tb_customers.ct_id')->get();
        $customers = Customers::all();
        $data = array(
            'items' => $itmes,
            'customers' => $customers
        );
        return view('item.index',$data);
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
    public function store(Checkitem $request)
    {
        $item  = new Items;
        $item->it_name  = $request->item;
        // $item->it_ct_id  = $request->cus_id;
        $item->save();

        return Redirect::back()->with('success', 'บันทึกรายการ Items เรียบร้อยแล้ว!!    ');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        // $item = SubItems::find($id);
        $items = DB::table('tb_subitems')
                    ->where('sit_it_id',$id)
                    ->get();

        $items2 = DB::table('tb_items')
                    ->where('it_id',$id)
                    ->first();

      
        $data = array(
            'items' => $items,
            'items2' => $items2,
           
        );

        return view('item.show',$data);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $item = Items::find($id);

        $data = array(
            'item' => $item
        );

        return $data;
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
        
        $item = Items::find($id);
        $item->it_name           = $request->item;
        $item->save();

        echo 1;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $items = Items::destroy($id);
        $subitem = DB::table('tb_subitems')->where('sit_it_id','=',$id)->delete();
        $subitem = DB::table('tb_master')->where('mt_fg_id','=',$id)->delete();
        
        echo $items;
    }

    public function createsubitem(Request $request,$id)
    {
        $item  = new SubItems;
        $item->sit_it_id            = $id;
        $item->sit_netweight        = $request->net_weight;
        $item->sit_grossweight      = $request->gross_weight;
        $item->sit_cbm              = $request->cbm;
        $item->sit_cartonwidth      = $request->carton_width;
        $item->sit_cartonlenght     = $request->carton_length;
        $item->sit_cartonheigh      = $request->carton_height;
        $item->sit_palletvolume     = $request->pallet_volume;
        $item->sit_cartonlayer      = $request->pallet_layer;
        $item->sit_cartonperlayer   = $request->pallet_per_layer;
        $item->save();

        return Redirect::back()->with('success', 'บันทึกรายการ Sub Items เรียบร้อยแล้ว!!    ');
    }

    public function searchitem($id){
        $item  = DB::table('tb_items')->leftjoin('tb_customers','tb_items.it_ct_id','=','tb_customers.ct_id')->where('it_ct_id','=',$id)->get();
        return $item;
    }

    public function importmaster(){
      
        return view('item.import');
    }

    public function loadmaster(){
       // Excel::create('New file', function($excel) {
        //     $excel->sheet('New sheet', function($sheet) {
        //         $sheet->loadView('item.master');
        //     });
        // });
        // $itmes = DB::table('tb_items')->leftjoin('tb_customers','tb_items.it_ct_id','=','tb_customers.ct_id')->get();
        // $customers = Customers::all();
        // $data = array(
        //     'items' => $itmes,
        //     'customers' => $customers
        // );

        // dd($data);
        // Excel::create('Filename', function($excel) use ($data) {
        //     $excel->sheet('Filename', function($sheet) use ($data) {
        //         $sheet->loadView('item.master',$data);
        //     });
        
        // })->download('xls');
        
        // Excel::create('Filename', function($excel){
        //     $excel->sheet('Filename', function($sheet) {
        //         $sheet->loadView('item.master');
        //     });
        // })->export('xls');
    }

    public function addmaster(Request $request){
  
        $path = $request->file('upload')->getRealPath();
        $data = Excel::load($path,function($reader){})->get();

        
        foreach($data  as $key => $value){
            if($value['customer_id'] != null){
                $items = new Items;
                $items->it_ct_id        = $value['customer_id'];
                $items->it_name         = $value['item'];
                $items->save();
    
                $subitems = new SubItems;
                $subitems->sit_it_id            =   $items->it_id;
                $subitems->st_c_id              =   $value['customer_id'];
                $subitems->sit_netweight        =   $value['netweight'];
                $subitems->sit_grossweight      =   $value['grossweight'];
                $subitems->sit_cbm              =   $value['cbm'];
                $subitems->sit_cartonwidth      =   $value['cartonwidth'];
                $subitems->sit_cartonlenght     =   $value['cartonlenght'];
                $subitems->sit_cartonheigh      =   $value['cartonheight'];
                $subitems->sit_palletvolume     =   $value['pallet_volume'];
                $subitems->sit_cartonlayer      =   $value['cartonlayer'];
                $subitems->sit_cartonperlayer   =   $value['cartonperlayer'];
                $subitems->sit_pallet   =   $value['type_pallet'];
                $subitems->save();      
            }
           
        }
        return Redirect::back()->with('success', 'บันทึกรายการ Master เรียบร้อยแล้ว!!    ');
    }
    
}
