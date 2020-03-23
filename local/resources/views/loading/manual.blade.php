@extends('layouts.master')
@section('title')
Manual Load pallet 
@endsection
@section('css')
<link rel="stylesheet" href="{{ asset('assets/css/app.css') }}">
<style>
    .modal-lg{
        max-width:1200px;
    }
</style>
@endsection
@section('content')
@php
    $active = 'box';
@endphp
<div class="breadcrumb">
    <div class="breadcrumb-element">
        <a class="breadcrumb-item " href="{{ url('dashboard') }}"><i class="lnr lnr-home mr3"></i></a>
        <a class="breadcrumb-item " href="#">Manual Load pallet </a>
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <div class="row">
                    <div class="col-6">
                        Manual Load pallet 
                    </div>
                    <div class="col-6  text-right">
                        <a class="btn btn-secondary" href="{{ url('box',$id).'/edit' }}">กลับ</a>
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
                <table class="table table-bordered table-hover" id="product1">
                    <thead>
                        <tr class="text-center">
                            <th class="text-center">#</th>
                            <th class="text-center">Pallet QTY</th>
                            <th class="text-center">FG ID</th>
                            <th class="text-center">Part No</th>
                            <th class="text-center">Description <br/> [Net, Gross]/pcs</th>
                            <th class="text-center">Spec <br/> [Net, Gross]/pcs</th>
                            <th class="text-center">PCS/CTN</th>
                            <th class="text-center">L</th>
                            <th class="text-center">Check</th>
                            <th class="text-center">C/N 1-UP</th>
                            <th class="text-center">TOTAL</th>
                            <th class="text-center">NET Weight</th>
                            <th class="text-center">Gross Weight</th>
                            <th class="text-center">Change</th>
                        </tr>
                    </thead>
                    <tbody>
                      
                        <tr>
                            <td class="text-center" colspan='14'>
                                <select name="" id="" class="form-control">
                                    <option value="0"></option>
                                    <option value="1">ROJ</option>
                                    <option value="2">PIN</option>
                                </select>
                            </td>
                        </tr>
                        @php
                             $key_check = 1;
                             $total_pallet =0;
                             $total_sum_box  = 0;
                             $total_netweight = 0;
                             $total_grossweight = 0;
                             $count_key = 0;
                        @endphp
                        @foreach ($boxs as $key => $box)
                       
                        <tr>
                            <td class="text-center" colspan='2'><i class="fas fa-shuttle-van"></i> 1</td>
                            <td class="text-left" colspan='5'>Pallet Size : {{ $box->tp_width.'x'.$box->tp_length.'x'.$box->tp_hieght  }}  ({{ $box->tp_weight.'  kgs/PLT' }} ) </td>  
                            <td class="text-center" colspan='4'>[ <input type="text" style="text-align:center;" maxlength="4" size="2" value="1"> ] x XXX</td>
                            <td class="text-center" colspan='3'></td>
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
                                        ->select(DB::raw('sum(bo_order_qty_sum) as sum_box'),'tb_boxs.*')
                                        ->whereIn('bo_id', explode(',',$comma_separated))
                                        ->whereIn('bo_item', explode(',',$id_implode))
                                        ->groupBy('bo_item')
                                        // ->distinct('bo_item')
                                        ->where('bo_pd_id','=',$id)
                                        // ->where('tp_id',$box->tp_id)
                                        ->orderBy('bo_so','asc')
                                        ->get();

                                    $boxs4 = DB::table('tb_subitems')
                                        // ->select('tb_items.*','tb_subitems.*','tb_boxs.*')
                                        ->leftjoin('tb_items','tb_subitems.sit_it_id','=','tb_items.it_id') 
                                        ->leftjoin('tb_typepalate','tb_subitems.sit_pallet','=','tb_typepalate.tp_id')
                                        ->whereIn('it_name', explode(',',$id_implode))
                                        ->groupBy('sit_it_id')
                                        ->get();
                                // echo "<pre>";
                                // print_r($boxs3);
                                // exit();
                        @endphp
                        @foreach ($boxs3 as $key3 => $box3)
                               
                        <tr>
                                <td class="text-center">{{  $key_check }}</td>
                                <td class="text-center">
                                    {{ $box3->bo_so }} ({{ substr($box3->bo_fullfill_from,0,1) }}) 
                                    
                                    <br> 
                                    <span class="text-success">{{ $boxs4[$key3]->sit_cartonperlayer }} ชั้น x {{ $boxs4[$key3]->sit_cartonlayer }} = {{ $boxs4[$key3]->sit_palletvolume }} </span> 
                                </td>
                                <td class="text-center">
                                    {{ $box3->bo_item }} ({{ $box3->bo_revision }})
                                    <br> 
                                    <span class="text-success"> {{ floor(($box3->sum_box/$box3->bo_pack_qty) / $boxs4[$key3]->sit_cartonlayer) }} ชั้น กับอีก {{ ($box3->sum_box/$box3->bo_pack_qty) - floor(($box3->sum_box/$box3->bo_pack_qty) / $boxs4[$key3]->sit_cartonlayer) *  $boxs4[$key3]->sit_cartonlayer }} กล่อง  </span> 
                                </td>
                                <td class="text-center">
                                    {{ $box3->bo_cus_item }}
                                    <br>
                                    <span class="text-success"> Height :  {{  $boxs4[$key3]->tp_hieght }} </span> 
                                </td>
                                <td class="text-center">
                                    {{ $box3->bo_size_mm }}
                                    <br>
                                    <span class="text-success"> [{{  number_format($boxs4[$key3]->sit_netweight/$box3->bo_pack_qty,3,'.',',') }} -  {{  number_format($boxs4[$key3]->sit_grossweight/$box3->bo_pack_qty,3,'.',',')  }}]</span> 
                                </td>
                                <td class="text-center">
                                    {{ $box3->bo_cus_spec }}
                                    <br>
                                    <span class="text-success"> [{{  number_format($boxs4[$key3]->sit_netweight,3,'.',',') }} -  {{  number_format($boxs4[$key3]->sit_grossweight,3,'.',',')  }}]</span> 
                                </td>
                                <td class="text-center">{{ $box3->bo_pack_qty }}</td>
                                <td class="text-center">{{ $box3->sum_box/$box3->bo_pack_qty }}</td>
                                <td class="text-center"><i class="fas fa-info-circle"></i></td>
                                <td class="text-center">
                                    @if ($key3 == 0)
                                        {{ "1 - ".$box3->sum_box/$box3->bo_pack_qty }}
                                    @else
                                        @php
                                        
                                         $value_max = (int)$total_pallet+(int)$box3->sum_box/$box3->bo_pack_qty;
                                         $value_min = $total_pallet+1;
                                        
                                        @endphp
                                        {{ $value_min.' - '.$value_max  }}
                                    @endif
                                        
                                    
                                </td>
                                <td class="text-center">{{ $box3->sum_box }}</td>
                                <td class="text-center">{{  number_format(($box3->sum_box/$box3->bo_pack_qty)*$boxs4[$key3]->sit_netweight ,2,".",",") }}</td>
                                <td class="text-center">{{  number_format(($box3->sum_box/$box3->bo_pack_qty)*$boxs4[$key3]->sit_grossweight ,2,".",",") }}</td>
                                <td class="text-center"> <h5><i class="fas fa-arrow-circle-up"></i> <i class="fas fa-arrow-circle-down"></i></h5> </td>
                        @php
                            $total_pallet += $box3->sum_box/$box3->bo_pack_qty;
                            $total_sum_box += $box3->sum_box;
                            $total_netweight += ($box3->sum_box/$box3->bo_pack_qty)*$boxs4[$key3]->sit_netweight;
                            $total_grossweight += ($box3->sum_box/$box3->bo_pack_qty)*$boxs4[$key3]->sit_grossweight;
                            $key_check += 1;
                        @endphp
                        </tr>
                        @endforeach

                        @php
                            $count_key++;
                        @endphp
                        @endforeach
                       
                        <tr>
                                <td class="text-left" colspan='7'>คำนวณความสูงของ Pallet ได้ : <input type="text" maxlength="4" size="2"> Cm. จำนวน <input type="text" maxlength="4" size="2"> Pallet</td>
                                <td class="text-center">{{ $total_pallet }}</td>
                                <td class="text-center" colspan='2'></td>
                                <td class="text-center"> {{  number_format($total_sum_box,2,".",",")  }}</td>
                                <td class="text-center">{{  number_format($total_netweight,2,".",",") }}</td>
                                <td class="text-center">{{ number_format($total_grossweight,2,".",",") }}</td>
                                <td class="text-center"></td>
                            </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@endsection
@section('js')
@endsection
