<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Excel;
use DB;
use Session;
use App\Master;
use App\TypePallet;
use Redirect;
use App\Http\Requests\Checkpallet;
class PalateController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $tb_boxs = DB::table('tb_boxs')
                        ->leftjoin('tb_master','tb_boxs.bo_item','=','tb_master.mt_fg_id')
                        ->leftjoin('tb_typepalate','tb_master.mt_pallet_no1','=','tb_typepalate.tp_id')
                        ->orderBy('bo_so','asc')
                        ->get();

       
       $data = array(
        'tb_boxs' => $tb_boxs,
      
       );
        return view('palate.index',$data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // $typepalates = TypePallet::all();
        $typepalates = DB::table('tb_typepalate')->orderBy('tp_id','asc')->get();

        $data = array(
         'typepalates' => $typepalates,
        );

        return view('palate.create',$data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Checkpallet $request)
    {
        // dd($request  );
        $typepallet = new TypePallet;
        $typepallet->tp_weight  = $request->wieght_pallet;
        $typepallet->tp_width  = $request->width_cm;
        $typepallet->tp_length  = $request->length_cm;
        $typepallet->tp_hieght  = $request->hieght_cm;
        $typepallet->save();

        return Redirect::back()->with('success', 'บันทึกรายการ Pallet เรียบร้อยแล้ว!!    ');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
       
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $typetpallet = TypePallet::find($id);
      

        $data = array(
            'typetpallet' => $typetpallet,

        );

        return view('palate.edit',$data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Checkpallet $request, $id)
    {
        $typepallet = TypePallet::find($id);
        $typepallet->tp_weight  = $request->wieght_pallet;
        $typepallet->tp_width  = $request->width_cm;
        $typepallet->tp_length  = $request->length_cm;
        $typepallet->tp_hieght  = $request->hieght_cm;
        $typepallet->save();
        return redirect(url('palate/create'))->with('success', 'แก้ไขรายการ Pallet เรียบร้อยแล้ว!!    ');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $typepallet = TypePallet::destroy($id);
        echo $typepallet;
    }


    public function palletoverview(Request $request){
        dd($request);
    }
}
