@extends('layouts.master')
@section('title')
Auto Load Pallet
@endsection
@section('css')
<link rel="stylesheet" href="{{ asset('assets/css/app.css') }}">
<link href="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.11.4/jquery-ui.css" rel="stylesheet">
<style>
    .modal-lg {
        max-width: 90%;
    }

</style>
@endsection
@section('content')
<style>
    input {
        text-align: center;
    }

</style>
@php
$active = 'box';
@endphp
<div class="breadcrumb">
    <div class="breadcrumb-element">
        <a class="breadcrumb-item " href="{{ url('dashboard') }}"><i class="lnr lnr-home mr3"></i></a>
        <a class="breadcrumb-item " href="#">Auto Load Pallet</a>
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <div class="row">
                    <div class="col-6">
                        Auto Load Pallet
                    </div>
                    <div class="col-6  text-right">
                        <button type="button" class="btn btn-secondary" onclick="history.back()">กลับ</button>
                    </div>
                </div>
            </div>
            <div class="card-body">
                @if (\Session::has('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <i class="fas fa-check-circle pr5"></i>สำเร็จ!</strong> {!! \Session::get('success') !!}
                </div>
                @endif
                <form action="{{ url('autoload2') }}" method="POST">
                    @csrf
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover" id="product1">
                            <thead>
                                <tr class="text-center">
                                    <th class="text-center">#</th>
                                    <th class="text-center">Pallet QTY</th>
                                    <th class="text-center">FG ID</th>
                                    <th class="text-center">Part No</th>
                                    <th class="text-center">Description <br /> [Net, Gross]/pcs</th>
                                    <th class="text-center">Spec <br /> [Net, Gross]/pcs</th>
                                    <th class="text-center">PCS/CTN</th>
                                    <th class="text-center">L</th>
                                    <th class="text-center">C/N 1-UP</th>
                                    <th class="text-center">TOTAL</th>
                                    <th class="text-center">NET Weight</th>
                                    <th class="text-center">Gross Weight</th>
                                    <th class="text-center">M3</th>
                                    {{-- <th class="text-center">Change</th> --}}
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    {{-- <td class="text-center" colspan='12'>
                                                <select name="sel_location" id="sel_location" class="form-control" required>
                                                    <option value="">----- โปรดเลือก Location -----</option>
                                                    <option value="1">ROJ</option>
                                                    <option value="2">PIN</option>
                                                </select>
                                            </td> --}}
                                    <td class="text-center" colspan='5'>
                                        <select name="sel_location2" id="sel_location" class="form-control">
                                            <option value="">----- โปรดเลือก Location -----</option>
                                            <option value="1">ROJ</option>
                                            <option value="2">PIN</option>
                                        </select>
                                    </td>
                                    <td class="text-center" colspan='8'>
                                        <select name="pallet_layer2" id="pallet_layer" class="form-control">
                                            <option value="">----- โปรดเลือก Layer Pallet -----</option>
                                            <option value="1">Max Layer</option>
                                            <option value="2">Min Layer</option>
                                        </select>
                                    </td>
                                </tr>
                                @php
                                $key_check = 1;
                                $total_pallet =0;
                                $total_sum_box = 0;
                                $total_netweight = 0;
                                $total_grossweight = 0;
                                $count_key = 0;
                                $amout_pallet = 0;
                                $height_pallet = array();
                                $total_cbm = 0;
                                @endphp
                                @foreach ($boxs as $key => $box)
                                @php
                                $amout_pallet2 = 0;
                                @endphp
                                <tr>
                                    <td class="text-left" colspan='14'>
                                        Pallet Size : {{
                                        $box->tp_width.'x'.$box->tp_length.'x'.$box->tp_hieght }} ({{ $box->tp_weight.'
                                        kgs/PLT' }} ) </td>
                                    {{-- <td class="text-center" colspan='4'>[ <input type="text" style="text-align:center;"
                                            maxlength="4" size="2" value="1"> ] x XXX</td> --}}

                                </tr>
                                @php
                                $boxs2 = DB::table('tb_boxs')
                                ->select('tb_boxs.*','tb_typepalate.*','tb_subitems.*','tb_items.*')
                                // ->select('tb_boxs.bo_item')
                                ->leftjoin('tb_items','tb_boxs.bo_item','=','tb_items.it_name')
                                ->leftjoin('tb_subitems','tb_items.it_id','=','tb_subitems.sit_it_id')
                                ->leftjoin('tb_typepalate','tb_subitems.sit_pallet','=','tb_typepalate.tp_id')
                                ->whereIn('bo_id', explode(',',$comma_separated))
                                ->groupBy('bo_id')
                                // ->distinct('bo_item')
                                ->where('bo_pd_id','=',$id)
                                ->where('tp_id',$box->tp_id)
                                ->orderBy('bo_so','asc')
                                ->get();


                                $id2 = array();
                                foreach ($boxs2 as $key => $box2) {
                                $id2[] = $box2->bo_item;
                                }

                                $id_implode = implode(',',array_unique($id2));

                                $boxs3 = DB::table('tb_boxs')
                                // ->select(DB::raw('sum(bo_order_qty_sum) as sum_box'),'tb_boxs.*')
                                ->whereIn('bo_id', explode(',',$comma_separated))
                                ->whereIn('bo_item', explode(',',$id_implode))
                                ->where('bo_ct_id',$boxs_find->bo_ct_id)
                                // ->groupBy('bo_item')
                                // ->distinct('bo_item')
                                ->where('bo_pd_id','=',$id)
                                // ->where('tp_id',$box->tp_id)
                                ->orderBy('bo_so','asc')
                                ->get();

                                $boxs4 = DB::table('tb_subitems')
                                // ->select('tb_items.*','tb_subitems.*','tb_boxs.*')
                                ->leftjoin('tb_items','tb_subitems.sit_it_id','=','tb_items.it_id')
                                ->leftjoin('tb_typepalate','tb_subitems.sit_pallet','=','tb_typepalate.tp_id')
                                ->where('st_c_id',$boxs_find->bo_ct_id)
                                ->whereIn('it_name', explode(',',$id_implode))
                                ->groupBy('sit_it_id')
                                ->get();

                                // dd($boxs4);
                                @endphp
                                @foreach ($boxs3 as $key3 => $box3)
                                @php
                                $amout_pallet2 += floor((($box3->bo_order_qty_sum/$box3->bo_pack_qty) /
                                $boxs4[0]->sit_cartonlayer) / $boxs4[0]->sit_cartonperlayer);
                                if(($box3->bo_order_qty_sum/$box3->bo_pack_qty) -
                                floor(($box3->bo_order_qty_sum/$box3->bo_pack_qty) /
                                $boxs4[0]->sit_cartonlayer) * $boxs4[0]->sit_cartonlayer > 0){
                                $amout_pallet2 += 1;
                                }

                                $amout_pallet += floor((($box3->bo_order_qty_sum/$box3->bo_pack_qty) /
                                $boxs4[0]->sit_cartonlayer) / $boxs4[0]->sit_cartonperlayer);
                                if(($box3->bo_order_qty_sum/$box3->bo_pack_qty) -
                                floor(($box3->bo_order_qty_sum/$box3->bo_pack_qty) /
                                $boxs4[0]->sit_cartonlayer) * $boxs4[0]->sit_cartonlayer > 0){
                                $amout_pallet += 1;
                                }
                                @endphp
                                <tr>
                                    <td class="text-center"><input type="checkbox" name="mix_box[]"></td>
                                    <td class="text-center">
                                        {{ $box3->bo_so }} ({{ substr($box3->bo_fullfill_from,0,1) }})

                                        <br>
                                        <span class="text-success">{{ $boxs4[0]->sit_cartonperlayer }} ชั้น x {{
                                            $boxs4[0]->sit_cartonlayer }} = {{ $boxs4[0]->sit_palletvolume }}
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        {{ $box3->bo_item }} ({{ $box3->bo_revision }})
                                        <br>
                                        <span class="text-success"> {{
                                            floor(($box3->bo_order_qty_sum/$box3->bo_pack_qty) /
                                            $boxs4[0]->sit_cartonlayer) }} ชั้น กับอีก

                                            {{ ceil(($box3->bo_order_qty_sum/$box3->bo_pack_qty) -
                                            floor(($box3->bo_order_qty_sum/$box3->bo_pack_qty) /
                                            $boxs4[0]->sit_cartonlayer)
                                            * $boxs4[0]->sit_cartonlayer) }} กล่อง
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        {{ $box3->bo_cus_item }}

                                        <br>
                                        <span class="text-success"> Height : {{
                                            number_format($boxs4[0]->sit_cartonheigh,2,'.',',') }} </span>
                                    </td>
                                    <td class="text-center">
                                        {{ $box3->bo_size_mm }}
                                        <br>
                                        <span class="text-success"> [{{
                                            number_format($boxs4[0]->sit_netweight/$box3->bo_pack_qty,4,'.',',') }}
                                            - {{
                                            number_format($boxs4[0]->sit_grossweight/$box3->bo_pack_qty,4,'.',',')
                                            }}]</span>
                                    </td>
                                    <td class="text-center">
                                        {{ $box3->bo_cus_spec }}
                                        <br>
                                        <span class="text-success"> [{{
                                            number_format($boxs4[0]->sit_netweight,2,'.',',') }} - {{
                                            number_format($boxs4[0]->sit_grossweight,2,'.',',') }}]</span>
                                    </td>
                                    <td class="text-center">{{ ceil($box3->bo_pack_qty) }}</td>
                                    <td class="text-center">{{ ceil($box3->bo_order_qty_sum/$box3->bo_pack_qty) }}</td>
                                    <td class="text-center">
                                        @if ($key3 == 0)
                                        {{ "1 - ".ceil($box3->bo_order_qty_sum/$box3->bo_pack_qty) }}
                                        @else
                                        @php

                                        $value_max = (int)$total_pallet+(int)$box3->bo_order_qty_sum/$box3->bo_pack_qty;
                                        $value_min = $total_pallet+1;

                                        @endphp
                                        {{ ceil($value_min).' - '.ceil($value_max) }}
                                        @endif

                                    </td>
                                    <td class="text-center">{{ $box3->bo_order_qty_sum }}</td>
                                    <td class="text-center">{{
                                        number_format(($box3->bo_order_qty_sum / $box3->bo_pack_qty) * $boxs4[0]->sit_netweight,2,".",",") }}</td>
                                    <td class="text-center">{{
                                        number_format(($box3->bo_order_qty_sum/$box3->bo_pack_qty)*$boxs4[0]->sit_grossweight
                                        ,2,".",",") }}</td>
                                    <td class="text-center">{{ number_format($boxs4[0]->sit_cbm * ($box3->bo_order_qty_sum/$box3->bo_pack_qty),2,".",",") }}</td>
                                    {{-- <td class="text-center">
                                        <h5><i class="fas fa-arrow-circle-up"></i> <i class="fas fa-arrow-circle-down"></i></h5>
                                    </td> --}}
                                    <input type="hidden" name="box_select[]" id="box_select[]"
                                        value="{{ $box3->bo_id }}">
                                    <input type="hidden" name="box_item[]" id="box_item[]"
                                        value="{{ $box3->bo_order_qty_sum }}">

                                    @php
                                    $height_pallet[] = ($boxs4[0]->sit_cartonheigh *
                                    $boxs4[0]->sit_cartonperlayer ) + $boxs4[0]->tp_hieght;
                                    $total_pallet += ceil($box3->bo_order_qty_sum/$box3->bo_pack_qty);
                                    $total_sum_box += $box3->bo_order_qty_sum;
                                    $total_netweight +=
                                    ($box3->bo_order_qty_sum/$box3->bo_pack_qty)*$boxs4[0]->sit_netweight;
                                    $total_grossweight +=($box3->bo_order_qty_sum/$box3->bo_pack_qty)*$boxs4[0]->sit_grossweight+($amout_pallet*$boxs4[0]->tp_weight);
                                    $total_cbm += $boxs4[0]->sit_cbm * ($box3->bo_order_qty_sum/$box3->bo_pack_qty);
                                    $key_check += 1;
                                    @endphp
                                </tr>
                                @endforeach

                                @php
                                $count_key++;
                                @endphp
                                @endforeach
                                <tr>
                                    <td class="text-left" colspan='7'>
                                        <div class="row">
                                            <div class="col-6">
                                                คำนวณความสูงของ Pallet ได้ : <input type="text" maxlength="4" size="2"
                                                    value="{{ max($height_pallet) }}"> Cm.
                                                จำนวน <input type="text" maxlength="4" size="2" name="total_pallet"
                                                    id="total_pallet"
                                                    value="{{ floor($total_pallet / ($boxs4[0]->sit_cartonperlayer*$boxs4[0]->sit_cartonlayer)) }}">
                                                Pallet
                                            </div>
                                            <div class="col-6 text-right">
                                                @if ( floor($total_pallet /
                                                ($boxs4[0]->sit_cartonperlayer*$boxs4[0]->sit_cartonlayer)) <= 0) <label
                                                    class="custom-control custom-checkbox">
                                                    <input type="checkbox" class="custom-control-input mr10"
                                                        name="create_pallet" id="create_pallet" value="0">
                                                    <input type="hidden" class="custom-control-input mr10"
                                                        name="create_pallet" id="create_pallet" value="1">
                                                    <span class="custom-control-indicator"></span>
                                                    <br>
                                                    <span class="custom-control-description"><b>สร้าง 1 Pallet</b>
                                                    </span>
                                                    </label>
                                                    @endif

                                            </div>
                                        </div>
                                    </td>
                                    <td class="text-center">{{ $total_pallet }}</td>
                                    <td class="text-center"></td>
                                    <td class="text-center"> {{ number_format($total_sum_box,0,".",",") }}</td>
                                    <td class="text-center">{{ number_format($total_netweight,2,".",",") }}</td>
                                    <td class="text-center">{{ number_format($total_grossweight,2,".",",") }}</td>
                                    <td class="text-center">{{ number_format($total_cbm,2,".",",") }}</td>
                                    {{-- <td class="text-center"></td> --}}
                                </tr>
                            </tbody>
                        </table>
                        <div class="row">
                            <div class="col-12 text-right">
                                <input type="hidden" name="cartonlayer" id="cartonlayer"
                                    value="{{ $boxs4[0]->sit_cartonlayer }}">
                                <input type="hidden" name="cartonperlayer" id="cartonperlayer"
                                    value="{{ $boxs4[0]->sit_cartonperlayer }}">
                                <input type="hidden" name="total_sum_box" id="total_sum_box"
                                    value="{{ $total_pallet }}">
                                <input type="hidden" name="total_weight" id="total_weight"
                                    value="{{ $total_grossweight }}">

                                <button type="button" class="btn btn-primary send_value"
                                    {{ floor($total_pallet / ($boxs4[0]->sit_cartonperlayer*$boxs4[0]->sit_cartonlayer)) <= 0 ? 'disabled' : '' }}>Load
                                    Pallet</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>


<!-- The Modal -->
<div class="modal" id="myModal">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">

            <!-- Modal Header -->
            <div class="modal-header">
                <h4 class="modal-title">Overview Pallet</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <form action="{{ url('autoload2') }}" method="POST">
            <!-- Modal body -->
            <div class="modal-body">
                    @csrf
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover" id="product1">
                            <thead>
                                <tr class="text-center">
                                    <th class="text-center">#</th>
                                    <th class="text-center">Pallet QTY</th>
                                    <th class="text-center">FG ID</th>
                                    <th class="text-center">Part No</th>
                                    <th class="text-center">Description <br /> [Net, Gross]/pcs</th>
                                    <th class="text-center">Spec <br /> [Net, Gross]/pcs</th>
                                    <th class="text-center">PCS/CTN</th>
                                    <th class="text-center">L</th>
                                    <th class="text-center">C/N 1-UP</th>
                                    <th class="text-center">TOTAL</th>
                                    <th class="text-center">NET Weight</th>
                                    <th class="text-center">Gross Weight</th>
                                    <th class="text-center">CBM</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                $key_check = 1;
                                $total_pallet =0;
                                $total_sum_box = 0;
                                $total_netweight = 0;
                                $total_grossweight = 0;
                                $count_key = 0;
                                $amout_pallet = 0;
                                $height_pallet = array();
                                $total_cbm = 0;
                                @endphp
                                @foreach ($boxs as $key => $box)
                                @php
                                $amout_pallet2 = 0;
                                @endphp
                                <h5><b>Pallet Size : </b>{{  $box->tp_width.'x'.$box->tp_length.'x'.$box->tp_hieght }} ({{ $box->tp_weight.'kgs/PLT' }})</h5>
                                <br>
                                @php
                                $boxs2 = DB::table('tb_boxs')
                                ->select('tb_boxs.*','tb_typepalate.*','tb_subitems.*','tb_items.*')
                                // ->select('tb_boxs.bo_item')
                                ->leftjoin('tb_items','tb_boxs.bo_item','=','tb_items.it_name')
                                ->leftjoin('tb_subitems','tb_items.it_id','=','tb_subitems.sit_it_id')
                                ->leftjoin('tb_typepalate','tb_subitems.sit_pallet','=','tb_typepalate.tp_id')
                                ->whereIn('bo_id', explode(',',$comma_separated))
                                ->groupBy('bo_id')
                                // ->distinct('bo_item')
                                ->where('bo_pd_id','=',$id)
                                ->where('tp_id',$box->tp_id)
                                ->orderBy('bo_so','asc')
                                ->get();


                                $id2 = array();
                                foreach ($boxs2 as $key => $box2) {
                                $id2[] = $box2->bo_item;
                                }

                                $id_implode = implode(',',array_unique($id2));
                                
                                $boxs3 = DB::table('tb_boxs')
                                // ->select(DB::raw('sum(bo_order_qty_sum) as sum_box'),'tb_boxs.*')
                                ->whereIn('bo_id', explode(',',$comma_separated))
                                ->whereIn('bo_item', explode(',',$id_implode))
                                ->where('bo_ct_id',$boxs_find->bo_ct_id)
                                // ->groupBy('bo_item')
                                // ->distinct('bo_item')
                                ->where('bo_pd_id','=',$id)
                                // ->where('tp_id',$box->tp_id)
                                ->orderBy('bo_so','asc')
                                ->get();

                                $boxs4 = DB::table('tb_subitems')
                                // ->select('tb_items.*','tb_subitems.*','tb_boxs.*')
                                ->leftjoin('tb_items','tb_subitems.sit_it_id','=','tb_items.it_id')
                                ->leftjoin('tb_typepalate','tb_subitems.sit_pallet','=','tb_typepalate.tp_id')
                                ->where('st_c_id',$boxs_find->bo_ct_id)
                                ->whereIn('it_name', explode(',',$id_implode))
                                ->groupBy('sit_it_id')
                                ->get();


                                @endphp
                                @foreach ($boxs3 as $key3 => $box3)
                                @php
                                $amout_pallet2 += floor((($box3->bo_order_qty_sum/$box3->bo_pack_qty) /
                                $boxs4[0]->sit_cartonlayer) / $boxs4[0]->sit_cartonperlayer);
                                if(($box3->bo_order_qty_sum/$box3->bo_pack_qty) -
                                floor(($box3->bo_order_qty_sum/$box3->bo_pack_qty) /
                                $boxs4[0]->sit_cartonlayer) * $boxs4[0]->sit_cartonlayer > 0){
                                $amout_pallet2 += 1;
                                }

                                $amout_pallet += floor((($box3->bo_order_qty_sum/$box3->bo_pack_qty) /
                                $boxs4[0]->sit_cartonlayer) / $boxs4[0]->sit_cartonperlayer);
                                if(($box3->bo_order_qty_sum/$box3->bo_pack_qty) -
                                floor(($box3->bo_order_qty_sum/$box3->bo_pack_qty) /
                                $boxs4[0]->sit_cartonlayer) * $boxs4[0]->sit_cartonlayer > 0){
                                $amout_pallet += 1;
                                }
                                @endphp
                                  <tr>
                                    <td class="text-center"><input type="checkbox" name="mix_box[]"></td>
                                    <td class="text-center">
                                        {{ $box3->bo_so }} ({{ substr($box3->bo_fullfill_from,0,1) }})

                                        <br>
                                        <span class="text-success">{{ $boxs4[0]->sit_cartonperlayer }} ชั้น x {{
                                            $boxs4[0]->sit_cartonlayer }} = {{ $boxs4[0]->sit_palletvolume }}
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        {{ $box3->bo_item }} ({{ $box3->bo_revision }})
                                        <br>
                                        <span class="text-success"> {{
                                            floor(($box3->bo_order_qty_sum/$box3->bo_pack_qty) /
                                            $boxs4[0]->sit_cartonlayer) }} ชั้น กับอีก

                                            {{ ($box3->bo_order_qty_sum/$box3->bo_pack_qty) -
                                            floor(($box3->bo_order_qty_sum/$box3->bo_pack_qty) /
                                            $boxs4[0]->sit_cartonlayer)
                                            * $boxs4[0]->sit_cartonlayer }} กล่อง
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        {{ $box3->bo_cus_item }}

                                        <br>
                                        <span class="text-success"> Height : {{
                                            number_format($boxs4[0]->sit_cartonheigh,2,'.',',') }} </span>
                                    </td>
                                    <td class="text-center">
                                        {{ $box3->bo_size_mm }}
                                        <br>
                                        <span class="text-success"> [{{
                                            number_format($boxs4[0]->sit_netweight/$box3->bo_pack_qty,4,'.',',') }}
                                            - {{
                                            number_format($boxs4[0]->sit_grossweight/$box3->bo_pack_qty,4,'.',',')
                                            }}]</span>
                                    </td>
                                    <td class="text-center">
                                        {{ $box3->bo_cus_spec }}
                                        <br>
                                        <span class="text-success"> [{{
                                            number_format($boxs4[0]->sit_netweight,2,'.',',') }} - {{
                                            number_format($boxs4[0]->sit_grossweight,2,'.',',') }}]</span>
                                    </td>
                                    <td class="text-center">{{ ceil($box3->bo_pack_qty) }}</td>
                                    <td class="text-center">{{ ceil($box3->bo_order_qty_sum/$box3->bo_pack_qty) }}</td>
                                    <td class="text-center">
                                        @if ($key3 == 0)
                                        {{ "1 - ".ceil($box3->bo_order_qty_sum/$box3->bo_pack_qty) }}
                                        @else
                                        @php

                                        $value_max = (int)$total_pallet+(int)$box3->bo_order_qty_sum/$box3->bo_pack_qty;
                                        $value_min = $total_pallet+1;

                                        @endphp
                                        {{ ceil($value_min).' - '.ceil($value_max) }}
                                        @endif

                                    </td>
                                    <td class="text-center">{{ $box3->bo_order_qty_sum }}</td>
                                    <td class="text-center">{{
                                        number_format(($box3->bo_order_qty_sum / $box3->bo_pack_qty) * $boxs4[0]->sit_netweight,2,".",",") }}</td>
                                    <td class="text-center">{{
                                        number_format(($box3->bo_order_qty_sum/$box3->bo_pack_qty)*$boxs4[0]->sit_grossweight
                                        ,2,".",",") }}</td>
                                    <td class="text-center">{{ number_format($boxs4[0]->sit_cbm * ($box3->bo_order_qty_sum/$box3->bo_pack_qty),2,".",",") }}</td>
                                    {{-- <td class="text-center">
                                        <h5><i class="fas fa-arrow-circle-up"></i> <i class="fas fa-arrow-circle-down"></i></h5>
                                    </td> --}}
                                    <input type="hidden" name="box_select[]" id="box_select[]"
                                        value="{{ $box3->bo_id }}">
                                    <input type="hidden" name="box_item[]" id="box_item[]"
                                        value="{{ $box3->bo_order_qty_sum }}">

                                    @php
                                    $height_pallet[] = ($boxs4[0]->sit_cartonheigh *
                                    $boxs4[0]->sit_cartonperlayer ) + $boxs4[0]->tp_hieght;
                                    $total_pallet += ceil($box3->bo_order_qty_sum/$box3->bo_pack_qty);
                                    $total_sum_box += $box3->bo_order_qty_sum;
                                    $total_netweight +=
                                    ($box3->bo_order_qty_sum/$box3->bo_pack_qty)*$boxs4[0]->sit_netweight;
                                    $total_grossweight +=($box3->bo_order_qty_sum/$box3->bo_pack_qty)*$boxs4[0]->sit_grossweight+($amout_pallet*$boxs4[0]->tp_weight);
                                    $total_cbm += $boxs4[0]->sit_cbm * ($box3->bo_order_qty_sum/$box3->bo_pack_qty);
                                    $key_check += 1;
                                    @endphp
                                </tr>
                                @endforeach

                                @php
                                $count_key++;
                                @endphp
                                @endforeach
                                <tr>
                                    <td class="text-left" colspan='7'>
                                        <div class="row">
                                            <div class="col-6">
                                                <h5><b>คำนวณความสูงของ Pallet ได้ :</b>  {{ max($height_pallet) }}  Cm. / Pallet</h5> 
                                                <h5><b>จำนวน  :</b> <span id="show_pallet_total">{{ floor($total_pallet / ($boxs4[0]->sit_cartonperlayer*$boxs4[0]->sit_cartonlayer)) }}</span>  Pallet</h5> 
                                                
                                            </div>
                                        </div>
                                    </td>
                                    <td class="text-center">{{ $total_pallet }}</td>
                                    <td class="text-center"></td>
                                    <td class="text-center"> {{ number_format($total_sum_box,0,".",",") }}</td>
                                    <td class="text-center">{{ number_format($total_netweight,2,".",",") }}</td>
                                    <td class="text-center">{{ number_format($total_grossweight,2,".",",") }}</td>
                                    <td class="text-center">{{ number_format($total_cbm,2,".",",") }}</td>
                                </tr>
                            </tbody>
                        </table>
                        <div class="row">
                            <div class="col-12 text-right">
                                <input type="hidden" name="cartonlayer" id="cartonlayer"
                                    value="{{ $boxs4[0]->sit_cartonlayer }}">
                                <input type="hidden" name="cartonperlayer" id="cartonperlayer"
                                    value="{{ $boxs4[0]->sit_cartonperlayer }}">
                                <input type="hidden" name="total_sum_box" id="total_sum_box"
                                    value="{{ $total_pallet }}">
                                <input type="hidden" name="total_weight" id="total_weight"
                                    value="{{ $total_grossweight }}">
                               
                                    
                            </div>
                        </div>
                    </div>
                
            </div>

            <!-- Modal footer -->
            <div class="modal-footer">
            <input type="hidden" name="id" id="id" value="{{$id}}">
                <input type="hidden" name="sel_location">
                <input type="hidden" name="pallet_layer">
                <input type="hidden" name="total_pallet">
                <input type="hidden" name="create_pallet">
                <button type="submit" class="btn btn-primary" >Load
                    Pallet</button>
            </div>
        </form>
        </div>
    </div>
</div>
@endsection
@section('js')
<script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.11.4/jquery-ui.js"></script>
<script>
    $(".send_value").click(function () {
        var sel_location = $("#sel_location").val();
        var pallet_layer = $("#pallet_layer").val();
        var total_pallet = $("#total_pallet").val();
        if ($('#create_pallet').is(":checked")){
            var create_pallet = 1;
        }else{
            var create_pallet = 2;
        }
        
   
        if (sel_location == "") {
            swal('กรุณาเลือก Location')
            return false;
        }

        if (pallet_layer == "") {
            swal('กรุณาเลือก Pallet Layer')
            return false;
        }
        
        $('input[name=sel_location]').val(sel_location);
        $('input[name=pallet_layer]').val(pallet_layer);
        $('input[name=total_pallet]').val(total_pallet);
        $('input[name=create_pallet]').val(create_pallet);
        $('#show_pallet_total').html(total_pallet);
        $('#myModal').modal('show');
    });
    // $('tbody').sortable();

    $('#create_pallet').on('change', function () {
        //    this.value = this.checked ? 1 : 0;
        this.value = this.checked ? $('.send_value').prop("disabled", false) : $('.send_value').prop("disabled",
            true);
        this.value = this.checked ? $("#total_pallet").val(1) : $("#total_pallet").val(0);;

    });

</script>
@endsection
