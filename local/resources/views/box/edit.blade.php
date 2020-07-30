@extends('layouts.master')
@section('title')
Load Pallet
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
        <a class="breadcrumb-item " href="#"> Load Pallet</a>
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        
      
          
            <div class="card">
                <div class="card-header">
                    <div class="row">
                        <div class="col-6">
                            Load Pallet
                        </div>
                        <div class="col-6  text-right">
                            <a class="btn btn-secondary" href="{{ url('box') }}">กลับ</a>
                        </div>
                    </div>
                </div>
       
                @if (count($tb_boxs) != 0)
                <form action="{{ url('box',$tb_boxs[0]->bo_pd_id) }}" method="post">
                    @csrf
                    {{ method_field('PUT') }}
                    <div class="card-body">
                        @if (\Session::has('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                            <i class="fas fa-check-circle pr5"></i><strong>สำเร็จ!</strong> {!! \Session::get('success')
                            !!}
                        </div>
                        @endif
                        @if (\Session::has('danger'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                            <i class="fas fa-check-circle pr5"></i><strong>ยกเลิก!</strong> {!! \Session::get('danger')
                            !!}
                        </div>
                        @endif
                        @if ($errors->has('sel'))
                        <div class="alert alert-info alert-dismissible fade show" role="alert">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                            <i class="fas fa-check-circle pr5"></i>{{ $errors->first('sel') }}
                        </div>
                        @endif
                        @php
                        $date = date_create($tb_boxs[0]->bo_promise_date);
                        @endphp
                        <div class="row">
                            <div class="col-12 text-right">
                                <table class="table table-bordered bg-primary text-white" id="">
                                    <tbody>
                                        <tr class="text-center">
                                            <th class="text-center">Ship From : <strong>{{ $tb_boxs[0]->bo_mas_loc }}</strong></th>
                                            <th class="text-center"> Customer : <strong>{{ $tb_boxs[0]->bo_customer }}</strong></th>
                                            <th class="text-center">Location : <strong>{{ $tb_boxs[0]->bo_cus_ship_loc }}</strong></th>
                                            <th class="text-center">Promise Date : <strong>{{ date_format($date, 'd-M-y')}}</strong></th>
                                        </tr>

                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-6 text-left">
                                    {{-- <a   href="{{ url('ContainerLoad').'/'.$tb_boxs[0]->bo_pd_id.'/'.'1' }}" class="btn btn-warning" name="button_load" value="auto_load">
                                        <i class="fas fa-plus"></i> Auto Load</a> --}}
                            </div>
                            <div class="col-6 text-right">
                                <div class="row">
                                    <div class="col-12 col-sm-8 col-md-9 mt-2">
                                        <a   href="{{ url('ContainerLoad').'/'.$tb_boxs[0]->bo_pd_id.'/'.'1' }}" class="btn btn-warning" name="button_load" value="auto_load">
                                            Auto Load 
                                        </a>
                                        <a  href="{{ url('ContainerLoad/').'/'.$tb_boxs[0]->bo_pd_id.'/'.'2'}}" class="btn btn-success" name="button_load" value="manual_load">
                                            Manual Load
                                        </a>
                                        {{-- <a  href="{{ url('palletmanage').'/'.$tb_boxs[0]->bo_pd_id}}" class="btn btn-primary" name="button_load" value="manual_load">
                                            Manage Pallet
                                        </a> --}}
                                    </div>

                                    <div class="col-12 col-sm-4 col-md-3 mt-2">
                                        {{-- <label class="custom-control custom-checkbox">
                                            <input type="checkbox" class="custom-control-input" name="check_manual" id="check_manual">
                                            <span class="custom-control-indicator"></span>
                                            <span class="custom-control-description">No Cal</span>
                                        </label> --}}
                                        <button type="submit" class="btn btn-info" name="button_load" value="load"><i class="fas fa-cloud-upload-alt"></i>
                                            Load</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <br>

                        <div class="table-responsive">
                            <table class="table table-bordered table-hover " id="" style="font-size:12px;">
                                <thead>
                                    {{-- <tr class="text-center">
                                        <th class="text-center" colspan="5">Products Information</th>
                                        <th class="text-center" colspan="3">Products Information</th>
                                    </tr> --}}
                                    <tr class="text-center bg-gray-light">
                                        <th class="text-center" rowspan="2" width="5%">#</th>
                                        <th class="text-center" width="10%" rowspan="2">So</th>
                                        {{-- <th class="text-center">Promise Date</th>
                                        <th class="text-center">FullFill From</th> --}}
                                        <th class="text-center" width="13%" rowspan="2">Item/Revision</th>
                                        <th class="text-center" width="12%" rowspan="2">Size/Spec</th>
                                        <th class="text-center" width="7%" rowspan="2">Order Q'TY</th>
                                        <th class="text-center" width="7%" rowspan="2">Carton Q'TY</th>
                                        @foreach ($tb_boxs2 as $key => $tb_box2)
                                        <th class="text-center"  width="15%">{{
                                            $tb_box2->tp_width.'x'.$tb_box2->tp_length.'x'.$tb_box2->tp_hieght }} <br> ({{
                                            $tb_box2->tp_weight.' kgs/PLT' }} ) 
                                        </th>
                                        @endforeach

                                    </tr>   
                                </thead>
                                <tbody>
                                    @php
                                        $sum_order = 0;
                                        $sum_carton  = 0;
                                    @endphp
                                    @foreach ($tb_boxs as $key => $tb_box)

                                    <tr>
                                        <td class="text-center">{{ ++$key }}</td>
                                        <td class="text-left">{{ $tb_box->bo_so }} ({{
                                            substr($tb_box->bo_fullfill_from,0,1) }})
                                            <br>
                                            <span class="text-success text-descript">
                                                weight :
                                                {{$tb_box->sit_grossweight*($tb_box->bo_order_qty_sum/$tb_box->bo_pack_qty)}}
                                                {{-- @if($tb_box->sit_grossweight != "")
                                                {{ $tb_box->sit_grossweight*$tb_box->bo_unit_box }}
                                                @else
                                                {{ '-' }}
                                                @endif --}}
                                            </span> </td>
                                        {{-- <td class="text-center">{{ $tb_box->bo_promise_date }}</td>
                                        <td class="text-center">{{ $tb_box->bo_fullfill_from }}</td> --}}
                                        <td class="text-left">{{ $tb_box->bo_item }} ({{ $tb_box->bo_revision }}) <br>
                                            <span class="text-success text-descript">pcs/ctn :
                                                @php
                                                if($tb_box->bo_pack_qty != ""){
                                                echo $tb_box->bo_pack_qty;
                                                }else{
                                                echo '-';
                                                }
                                                @endphp
                                            </span></td>
                                        <td class="text-left">{{ $tb_box->bo_size_mm }} <br> <span class="text-success text-descript">
                                                ctn :
                                                @php
                                                if($tb_box->sit_palletvolume != ""){
                                                echo $tb_box->sit_palletvolume;
                                                }else{
                                                echo '-';
                                                }
                                                @endphp
                                                height: @php
                                                if($tb_box->sit_cartonheigh != ""){
                                                echo $tb_box->sit_cartonheigh;
                                                }else{
                                                echo '-';
                                                }
                                                @endphp cm</span></td>
                                        <td class="text-right">
                                            @php
                                                $sum_order += $tb_box->bo_order_qty_sum;
                                            @endphp
                                            {{  $tb_box->bo_order_qty_sum }} 
                                        
                                        </td>
                                        <td class="text-right">
                                            @if ($tb_box->bo_order_qty_sum != "")
                                                @php
                                                    $sum_carton += ceil($tb_box->bo_order_qty_sum/$tb_box->bo_pack_qty);
                                                @endphp
                                                {{ ceil($tb_box->bo_order_qty_sum/$tb_box->bo_pack_qty) }}
                                            @else
                                            {{ '-' }}
                                            @endif
                                        </td>
                                        @php
                                            //    dd($tb_boxs2);
                                        @endphp
                                    
                                        @foreach ($tb_boxs2 as $key => $tb_box2)
                                        @php
                                            $pallet = DB::table('tb_boxs')
                                                ->leftjoin('tb_items','tb_boxs.bo_item','=','tb_items.it_name') 
                                                ->leftjoin('tb_subitems','tb_items.it_id','=','tb_subitems.sit_it_id')
                                                ->leftjoin('tb_master','tb_items.it_id','=','tb_master.mt_fg_id')
                                                ->leftjoin('tb_typepalate','tb_subitems.sit_pallet','=','tb_typepalate.tp_id')
                                                ->where('bo_id',$tb_box->bo_id)
                                                ->where('sit_pallet',$tb_box2->sit_pallet)
                                                ->first();
                                        @endphp
                                            
                                        @if ( $pallet != null)
                                                    <td class="text-center" >   
                                                        <label class="custom-control custom-checkbox">
                                                            <input type="checkbox" class="custom-control-input mr10" name="sel[]" id="sel[]" value="{{$tb_box->bo_id}}">
                                                            <input type="hidden" name="pallet_type" value="{{$pallet->sit_id}}">
                                                            <span class="custom-control-indicator"></span>
                                                            <br>
                                                            <span class="custom-control-description">{{ $pallet->sit_cartonlayer.'x'.$pallet->sit_cartonperlayer }}</span>
                                                        </label>
                                                    </td>
                                            @else 
                                                <td></td>
                                        @endif
                                            {{-- {{ $tb_box2->sit_pallet." == ".$pallet->sit_pallet }} --}}
                                            {{--
                                            @if($tb_box->st_c_id == $boxs_find->bo_ct_id)
                                                @if ($tb_box->sit_pallet == $tb_boxs3->sit_pallet)
                                                    <td class="text-center" >
                                                        <label class="custom-control custom-checkbox">
                                                            <input type="checkbox" class="custom-control-input mr10" name="sel[]" id="sel[]" value="{{$tb_box->bo_id}}">
                                                            <span class="custom-control-indicator"></span>
                                                            <br>
                                                            <span class="custom-control-description">{{ $tb_box2->sit_cartonlayer.'x'.$tb_box2->sit_cartonperlayer }}</span>
                                                        </label>
                                                    </td>
                                                @else
                                                    <td class="text-center" >1</td>
                                                @endif
                                            @else
                                                <td class="text-center" >2</td>
                                            @endif --}}
                                        @endforeach
                                        {{-- @foreach ($tb_boxs2_1 as $key3 => $tb_box2_1)
                                            @php
                                                $boxs = DB::table('tb_boxs')
                                                            ->leftjoin('tb_items','tb_boxs.bo_item','=','tb_items.it_name') 
                                                            ->leftjoin('tb_subitems','tb_items.it_id','=','tb_subitems.sit_it_id')
                                                            ->leftjoin('tb_typepalate','tb_subitems.sit_pallet','=','tb_typepalate.tp_id')
                                                            ->where('bo_id','=',$tb_box->bo_id)
                                                            ->where('tp_id','=',$tb_box2_1->tp_id)
                                                            ->where('sit_id','=',$tb_box2_1->sit_id)
                                                            ->orderBy('bo_so','asc')
                                                            ->groupBy('bo_id')
                                                            ->get();
                                                // dd($tb_box->bo_id);
                                            @endphp 
                                                @if (count($boxs) ==  1)
                                                    <td class="text-center" >
                                                        <label class="custom-control custom-checkbox">
                                                            <input type="checkbox" class="custom-control-input mr10" name="sel[]" id="sel[]" value="{{$tb_box->bo_id}}">
                                                            <span class="custom-control-indicator"></span>
                                                            <br>
                                                            <span class="custom-control-description">{{ $boxs[0]->sit_cartonlayer.'x'.$boxs[0]->sit_cartonperlayer }}</span>
                                                        </label>
                                                    </td>
                                                @else
                                                    <td class="text-center" ></td>
                                                @endif
                                        @endforeach --}}
                                        
                                    </tr>
                                    @endforeach
                                    <tr>
                                        <td class="text-right" colspan="4">รวม</td>
                                        <td class="text-right" >{{$sum_order}}</td>
                                        <td class="text-right" >{{ $sum_carton}}</td>
                                        <td class="text-center" ></td>
                                    </tr>
                                </tbody>
                            </table>

                        </div>

                    </div>
                </form>
                @else 
                <div class="col-12 text-center">
                    <h4>ไม่มี Pallet</h4>
                </div>
                @endif
            </div>
    </div>
</div>
<div id="ShowProducts"></div>
<?php
// $valPa  = array(1,2);
// $sizePa = array('xl','m');
// // $countPa = count($valPa);
// foreach($valPa as $key => $value){
//     $varget['palletId'][]=$value;
//     $varget['size'][]=$sizePa[$key];
  
// }
// $text = '{"palletId":[1,2],"size":["xl","m"]}';

// echo "<pre>";
// echo count(json_decode($text)['palletId']);
?>
@endsection
@section('js')
@csrf
<script>
    $(document).ready(function () {
        $("[rel='tooltip']").tooltip();
    });

    $(document).ready(function () {
        $('#product1').DataTable();
        $('#product2').DataTable();
    });

    function viewShow(id) {
        $.ajax({
            url: "{{ url('box') }}/" + id,
            data: {
                id: id
            },
            type: 'GET',
            success: function (data) {
                $('#ShowProducts').html(data);
                $("#exampleModal").modal('show');
                $('#exampleModal').modal({
                    backdrop: 'static',
                    keyboard: false
                });
            }
        });
    }

</script>

@endsection
