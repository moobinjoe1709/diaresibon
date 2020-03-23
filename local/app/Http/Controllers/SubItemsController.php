<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Session;
use Redirect;
use App\Items;
use App\SubItems;
use App\Http\Requests\Checksubitem;
class SubItemsController extends Controller
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
    public function store(Checksubitem $request)
    {
        $item  = new SubItems;
        $item->sit_it_id            = $request->id_item;
        $item->st_c_id            = $request->c_id;
        $item->sit_netweight        = $request->net_weight;
        $item->sit_grossweight      = $request->gross_weight;
        $item->sit_cbm              = $request->cbm;
        $item->sit_cartonwidth      = $request->carton_width;
        $item->sit_cartonlenght     = $request->carton_length;
        $item->sit_cartonheigh      = $request->carton_height;
        $item->sit_palletvolume     = $request->pallet_volume;
        $item->sit_cartonlayer      = $request->pallet_layer;
        $item->sit_cartonperlayer   = $request->pallet_per_layer;
        $item->sit_typepallet       = $request->type_pallet;   
        $item->sit_pallet           = $request->pallet;   
        $item->save();

        return Redirect::back()->with('success', 'บันทึกรายการ Sub Items เรียบร้อยแล้ว!!    ');
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
                        ->leftjoin('tb_typepalate','tb_subitems.sit_pallet','=','tb_typepalate.tp_id')
                        ->leftjoin('tb_customers','tb_customers.ct_id','=','tb_subitems.st_c_id')
                        ->where('sit_it_id',$id)
                        ->get();

        $im_ct_id = $items->implode('st_c_id', ',');


        $customers = DB::table('tb_customers')->get();


        $items2 = DB::table('tb_items')
                ->where('it_id',$id)
                ->first();

        $typepalates = DB::table('tb_typepalate')->get();
        $data = array(
            'items' => $items,
            'items2' => $items2,
            'typepalates' => $typepalates,
            'customers' => $customers
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
        $items = DB::table('tb_subitems')
                ->leftjoin('tb_items','tb_items.it_id','=','tb_subitems.sit_it_id')
                ->leftjoin('tb_customers','tb_customers.ct_id','=','tb_subitems.st_c_id')
                ->where('sit_id',$id)
                ->first();

        $typepalates = DB::table('tb_typepalate')->get();

        $customers = DB::table('tb_customers')->get();
       $data = array(
       'items' => $items,
       'typepalates' => $typepalates,
       'customers' => $customers
       );

       return view('item.edit',$data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Checksubitem $request, $id)
    {
        $item  = SubItems::find($id);
        $item->sit_it_id            = $request->id_item;
        $item->st_c_id             = $request->c_id;
        $item->sit_netweight        = $request->net_weight;
        $item->sit_grossweight      = $request->gross_weight;
        $item->sit_cbm              = $request->cbm;
        $item->sit_cartonwidth      = $request->carton_width;
        $item->sit_cartonlenght     = $request->carton_length;
        $item->sit_cartonheigh      = $request->carton_height;
        $item->sit_palletvolume     = $request->pallet_volume;
        $item->sit_cartonlayer      = $request->pallet_layer;
        $item->sit_cartonperlayer   = $request->pallet_per_layer;
        // $item->sit_typepallet       = $request->type_pallet;   
        $item->sit_pallet           = $request->pallet;   
        
        $item->save();

        return redirect(url('subitem',$request->id_item))->with('success', 'บันทึกรายการ Sub Items เรียบร้อยแล้ว!!    ');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $items = SubItems::destroy($id);
        echo $items;
    }
}
