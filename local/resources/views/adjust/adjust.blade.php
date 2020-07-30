@extends('layouts.master')
@section('title')
Report
@endsection
@section('css')
<link rel="stylesheet" href="{{ asset('assets/css/app.css') }}">
<style>
    .modal-lg{
        max-width:1200px;
    }

        body{ 
            padding: 5px; 
            /* border: 1px solid black;  */
            font-size: 14px;
        }

        p.header{
            text-transform: uppercase;
            font-weight: bold;
            text-align: center;
        }

        table{
            border-collapse: collapse;
            width: 100%;
        }
        table, td, th{
            font-size: 13px;
            border: 1px solid black;
        }

        /* thead {
            background-color: azure;
        } */
        
        thead tr th{
            border: 1px solid #696969; 
        }

        tbody tr td{
            border: 1px solid #D3D3D3;
        }
        .pull-left {float:left;}
        .pull-right {float:right;}
        .text-center {text-align:center;}
        .page_break { page-break-before: always; }
</style>
@endsection
@section('content')
<div class="breadcrumb">
    <div class="breadcrumb-element">
        <a class="breadcrumb-item " href="{{ url('dashboard') }}"><i class="lnr lnr-home mr3"></i></a>
        <a class="breadcrumb-item " href="#">Edit Container</a>
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header"> 
              <div class="row">
                    <div class="col-6">
                        Edit Container
                    </div>
                    <div class="col-6 text-right">
                        <button class="btn btn-danger del_con" atr="{{$container->ctn_id}}" style="cursor: pointer;color:white;">ลบ Container</button>
                        <a href="{{url('adjust',$container->ctn_pd_id)}}" class="btn btn-secondary"> กลับ</a>
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
                <br>
                @endif
            @php
                $new_array = [];
                $sumpallet = 0;
                $countlap  = 0;
                $sumbox_qty = 0;
                $count_lap = 0;
                $box_qty = 0;
           @endphp
           
            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead>
                        <tr>
                            <th class="text-center">Pallet No.</th>
                            <th class="text-center" width="150">F/G ID </th>
                            <th class="text-center">Part no.</th>
                            <th class="text-center">DESCRIPTION</th>
                            <th class="text-center">SPEC</th>
                            <th class="text-center">PCS/CTN</th>
                            <th class="text-center">L</th>
                            <th class="text-center">Check</th>
                            <th class="text-center">C/NO. 1-UP</th>
                            <th class="text-center">TOTAL</th>
                            <th class="text-center">NET WEIGHT</th>
                            <th class="text-center">GROSS WEIGHT</th>
                            <th class="text-center">Edit</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td colspan="13"> 
                              
                                Cont : {{$container->ctn_size}} ton
                                <br>
                                Ship Location : {{$container->ctn_location == 1 ? 'ROJ' : 'PIN'}} | Ship To : {{$container->customer}}, {{$container->cus_ship_loc}}
                                <br>
                                Actual Weight : {{$container->ctn_use}} (Over Weight : {{$container->ctn_over_kk != null ? $container->ctn_over_kk : '0'}} )
                                <br>
                                Actual Pallet : {{count(explode(",", $container->pallet_id))}} Pallet
                                <br>
                                Ex Fact Date : {{ $container->ctn_date}}
                                <br>
                                Net weight : {{$container->ctn_use}} KK. | Gross weight : {{$container->ctn_use}}
                            </td>
                        </tr>
              
                        @php
                            $tb_container_detail = DB::table('tb_container_detail')
                                                    ->selectRaw('*,  count(ctnds_group) as count_pallets')
                                                    ->leftjoin('tb_container_des','tb_container_des.ctnds_ctnd_id','=','tb_container_detail.ctnd_id')
                                                    ->leftjoin('tb_pallet','tb_pallet.tpl_id','=','tb_container_des.ctnds_key')
                                                    ->leftjoin('tb_containers','tb_containers.ctn_id','=','tb_container_detail.ctnd_ctn_id')
                                                    ->leftjoin('tb_boxs','tb_boxs.bo_id','=','tb_pallet.tpl_bo_id')
                                                    ->leftjoin('tb_items','tb_boxs.bo_item','=','tb_items.it_name') 
                                                    ->leftjoin('tb_subitems','tb_items.it_id','=','tb_subitems.sit_it_id')
                                                    ->leftjoin('tb_typepalate','tb_subitems.sit_pallet','=','tb_typepalate.tp_id')
                                                    ->whereIn('ctnd_key', explode(",",$container->pallet_id))
                                                    ->where('ctn_group', $container->ctn_group)
                                                    ->orderBy('ctnd_id','asc')
                                                    ->groupBy('ctnds_ctnd_id')
                                                    ->get();
     
                        @endphp
                        
                        @foreach ($tb_container_detail as $keys => $item)
                        @php
                        // echo "<pre>";
                        // print_r($item);
                            
                            if($item->count_pallets > 1 && $item->ctnd_type == 2){
                                $sumpallet += 1;
                            }else{
                                if($item->count_pallets != 0 ){
                                    $sumpallet += $item->count_pallets;
                                }else{
                                    $sumpallet += 1;
                                }
                               
                            }
                            
                          
                            $container_detail = DB::table('tb_container_detail')
                                                    ->selectRaw('*, sum(ctnds_max) as sum_ctnds_max, count(ctnds_group) as count_pallet')
                                                    ->leftjoin('tb_container_des','tb_container_des.ctnds_ctnd_id','=','tb_container_detail.ctnd_id')
                                                    ->leftjoin('tb_pallet','tb_pallet.tpl_id','=','tb_container_des.ctnds_key')
                                                    ->leftjoin('tb_boxs','tb_boxs.bo_id','=','tb_pallet.tpl_bo_id')
                                                    ->leftjoin('tb_items','tb_boxs.bo_item','=','tb_items.it_name') 
                                                    ->leftjoin('tb_subitems','tb_items.it_id','=','tb_subitems.sit_it_id')
                                                    ->leftjoin('tb_typepalate','tb_subitems.sit_pallet','=','tb_typepalate.tp_id')
                                                    ->whereIn('ctnd_key', explode(",",$container->pallet_id))
                                                    ->where('ctnds_group',$item->ctnds_group)
                                                    ->groupBy('ctnds_key')
                                                    ->get();
                            // dd($container_detail,$item->ctnds_group,$container->pallet_id);
                            // $sum_wieght =    $container_detail->sum('amount');
                            
                        @endphp
                            <tr>
                                <td class="text-center">
                                    @if($item->count_pallets > 1 && $item->ctnd_type != 2)
                                        @if ($countlap == 0)
                                            1 - {{$sumpallet}}
                                        @else 
                                            
                                            {{$lastlap}} - {{$sumpallet}}
                                        @endif
                                    @else 
                                    
                                    {{ $sumpallet}}
                                    @endif
                                </td>
                                <td class="text-left">{{$item->tp_width.'x'.$item->tp_length.'x'.$item->tp_hieght.' CM'}}</td>
                                 <td class="text-center"></td>
                                 <td class="text-center"></td>
                                 <td class="text-center"></td>
                                 <td class="text-center"></td>
                                 <td class="text-center"></td>
                                 <td class="text-center"></td>
                                 <td class="text-center"></td>
                                 <td class="text-center"></td>
                                 <td class="text-center"></td>
                                 <td class="text-center"></td>
                                 <td class="text-center"> 
                                     <button class="btn btn-danger del_pallet" style="color:white;cursor: pointer;"  atr="{{$item->ctnd_id}}"> ลบ </button>
                                 </td>
                            </tr>
                                @php
                                    $sum_l = 0;
                                    $sum_up = 0;
                                    $sum_net = 0;
                                    $sum_gross = 0;
                                    $sum_dia = 0;
                                @endphp
                                @foreach ($container_detail as $key => $val)
     
                                <tr>
                                    <td class="text-left" style="font-size:10px;">{{$val->bo_so}}</td>
                                    <td class="text-left">{{$val->bo_item}}</td>
                                    <td class="text-left">{{$val->bo_cus_item}}</td>
                                    <td class="text-center">{{$val->bo_size_mm}}</td>
                                    <td class="text-center">{{$val->bo_cus_spec}}</td>
                                    <td class="text-center">{{$val->bo_pack_qty}}</td>
                                    <td class="text-center">
                                        @if ($val->ctnds_max != "" && $val->sit_netweight != "")
                                            @php
                                                $box_qty = $val->ctnds_max / $val->sit_netweight;
                                                $sumbox_qty += ceil($box_qty*$val->count_pallet);
     
                                                $sum_l += ceil($box_qty*$val->count_pallet);
                                            @endphp
                                            {{ceil($box_qty*$val->count_pallet)}}
                                        @endif
                                    </td>
                                    <td class="text-center"></td>
                                    <td class="text-center">
                                        @if ($countlap == 0)
                                            1 - {{$sumbox_qty}}
                                        @else 
                                        {{ $lastsumbox +1 }}  - {{ $sumbox_qty }}
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        @php
                                            $sum_up += ceil($box_qty*$val->bo_pack_qty);
                                        @endphp
                                        {{ceil($box_qty*$val->bo_pack_qty)}}
                                    </td>
                                    <td class="text-center">
                                        @php
                                            $sum_net += $box_qty*$val->sit_netweight;
                                        @endphp
                                        {{number_format($box_qty*$val->sit_netweight,2,'.',',')}}
                                    </td>
                                    <td class="text-center">
                                        @php
                                            $sum_gross += $box_qty*$val->sit_grossweight;
                                        @endphp
                                        {{number_format($box_qty*$val->sit_grossweight,2,'.',',')}}
                                    </td>
                                    <td class="text-center">
                                        {{-- <a  style="color:red;cursor: pointer;" class="del_items" atr="{{$item->ctnds_id}}" ><h5>X</h5></a> --}}
                                     </td>
                                </tr>
                               
                                @php
                                    $lastsumbox = $sumbox_qty;
                                @endphp
                                @endforeach
                            
                                @php
                                    $countlap += 1;
                                    $lastlap = $sumpallet;
                                    $key_new = $key-1;
                                @endphp
     
                        @endforeach
     
                    </tbody>
                </table>
            </div>

           <hr>
          
          
            </div>
        </div>
    </div>
</div>
@endsection
@section('js')

<script>
       $(".del_con").click(function(){
        var id = $(this).attr('atr');
        var token = $("input[name=_token]").val();
        swal({
                title: "คำเตือน!!",
                text: "คุณจะทำการ ลบข้อมูล Contianer หรือไม่!",
                icon: "warning",
                buttons: true,
                dangerMode: true,
            })
            .then((willDelete) => {
                if (willDelete) {
                    $.ajax({
                        url:"{{url('adjust')}}/"+id,
                        type:"post",
                        data:{
                            "_token"  : token,
                            "_method" : "delete",
                            "id": id
                        }
                    }).done(function(data){
                        if(data == 1){
                            swal({
                                title: "เรียบร้อย?",
                                text: "คุณทำการลบข้อมูล Contianer เรียบร้อย!",
                                icon: "success",
                            }).then((willDelete) => {
                                window.location.reload();
                            });
                        }else{
                            swal("ยกเลิก!", "คุณยกเลิกการลบข้อมูล!", "error");
                        }
                    });
                } else {
                    swal("ยกเลิก!", "คุณยกเลิกการลบข้อมูล!", "error");
                }
        });
    });

    $(".del_pallet").click(function(){
        var id = $(this).attr('atr');
        var token = $("input[name=_token]").val();
        swal({
                title: "คำเตือน!!",
                text: "คุณจะทำการ ลบข้อมูล Pallet หรือไม่!",
                icon: "warning",
                buttons: true,
                dangerMode: true,
            })
            .then((willDelete) => {
                if (willDelete) {
                    $.ajax({
                        url:"{{url('delpallet')}}/"+id,
                        type:"post",
                        data:{
                            "_token"  : token,
                            "_method" : "delete",
                            "id": id
                        }
                    }).done(function(data){
                        if(data == 1){
                            swal({
                                title: "เรียบร้อย?",
                                text: "คุณทำการลบข้อมูล Item เรียบร้อย!",
                                icon: "success",
                            }).then((willDelete) => {
                                window.location.reload();
                            });
                        }else{
                            swal("ยกเลิก!", "คุณยกเลิกการลบข้อมูล!", "error");
                        }
                    });
                } else {
                    swal("ยกเลิก!", "คุณยกเลิกการลบข้อมูล!", "error");
                }
        });
    });

    $(".del_items").click(function(){
        var id = $(this).attr('atr');
        var token = $("input[name=_token]").val();
        swal({
                title: "คำเตือน!!",
                text: "คุณจะทำการ ลบข้อมูล Item หรือไม่!",
                icon: "warning",
                buttons: true,
                dangerMode: true,
            })
            .then((willDelete) => {
                if (willDelete) {
                    $.ajax({
                        url:"{{url('delitems')}}/"+id,
                        type:"post",
                        data:{
                            "_token"  : token,
                            "_method" : "delete",
                            "id": id
                        }
                    }).done(function(data){
                        if(data == 1){
                            swal({
                                title: "เรียบร้อย?",
                                text: "คุณทำการลบข้อมูล Item เรียบร้อย!",
                                icon: "success",
                            }).then((willDelete) => {
                                window.location.reload();
                            });
                        }else{
                            swal("ยกเลิก!", "คุณยกเลิกการลบข้อมูล!", "error");
                        }
                    });
                } else {
                    swal("ยกเลิก!", "คุณยกเลิกการลบข้อมูล!", "error");
                }
        });
    });
</script>
@endsection
