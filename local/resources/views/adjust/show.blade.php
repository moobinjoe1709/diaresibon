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
</style>
@endsection
@section('content')
<div class="breadcrumb">
    <div class="breadcrumb-element">
        <a class="breadcrumb-item " href="{{ url('dashboard') }}"><i class="lnr lnr-home mr3"></i></a>
        <a class="breadcrumb-item " href="#">Adjust Container</a>
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header"> 
              <div class="row">
                    <div class="col-6">
                        Adjust Container
                    </div>
                    <div class="col-6 text-right">
                        
                        <a target="_blank" href="{{route('reportcontainer',$id)}}" class="btn btn-success"><i class="fas fa-sticky-note"></i> Report PDF</a>
                        <button style="cursor: pointer;" class="btn btn-warning" data-toggle="modal" data-target="#myModal"> จัดการข้อมูล Report</button>
                        <a href="{{url('adjust')}}" class="btn btn-secondary"> กลับ</a>
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
                @csrf
                {{-- <div class="row">
                    <div class="col-6">
                        
                    </div>
                    <div class="col-6 text-right">
                        <a target="_blank" href="{{route('reportcontainer',$id)}}" class="btn btn-success"><i class="fas fa-sticky-note"></i> Report PDF</a>
                    </div>
                </div> 
                <hr> --}}
                @php
                     $new_array = [];
                     $sumpallet = 0;
                     $countlap  = 0;
                     $sumbox_qty = 0;
                     $count_lap = 0;
                     $box_qty = 0;
                @endphp
                @foreach ($containers as $keyss => $container)

                <div class="row">
                    <div class="col-6">
                        Container No. {{++$keyss}}
                    </div>
                    <div class="col-6 text-right">
                        <a href="{{url('addcontainer',$container->ctn_id)}}/{{$sumpallet+1}}/{{$sumbox_qty+1}}" class="btn btn-primary " atr="{{$container->ctn_id}}" style="cursor: pointer;color:white;">จัดการเพิ่ม</a>
                        <a href="{{url('adjustcontainer',$container->ctn_id)}}" class="btn btn-danger " atr="{{$container->ctn_id}}" style="cursor: pointer;color:white;">จัดการลบ</a>
                        {{-- <a class="btn btn-danger del_con" atr="{{$container->ctn_id}}" style="cursor: pointer;color:rgb(27, 11, 11);">ลบ</a> --}}
                    </div>
                </div>
                <br>
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
                            <th class="text-center">M<sup>3</sup></th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td colspan="14"> 
                              
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
                                                    ->leftjoin('tb_containers','tb_containers.ctn_id','=','tb_container_detail.ctnd_ctn_id')
                                                    ->leftjoin('tb_pallet','tb_pallet.tpl_id','=','tb_container_des.ctnds_key')
                                                    ->leftjoin('tb_boxs','tb_boxs.bo_id','=','tb_pallet.tpl_bo_id')
                                                    ->leftjoin('tb_items','tb_boxs.bo_item','=','tb_items.it_name') 
                                                    ->leftjoin('tb_subitems','tb_items.it_id','=','tb_subitems.sit_it_id')
                                                    ->leftjoin('tb_typepalate','tb_subitems.sit_pallet','=','tb_typepalate.tp_id')
                                                    ->whereIn('ctnd_key', explode(",",$container->pallet_id))
                                                    ->where('ctn_group', $container->ctn_group)
                                                    ->groupBy('ctnds_group')
                                                    ->get();
                         
                           
                        @endphp
                        
                        @foreach ($tb_container_detail as $keys => $item)
                        @php
                        // echo "<pre>";
                        // print_r($item);
                            // echo $item->count_pallets." == 1 && ".$item->ctnd_type." == 2 <br/>";
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
                                            {{++$lastlap}} - {{$sumpallet}}
                                            
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
                            <td class="text-center"></td>
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
                                        
                                            $sum_up += $val->bo_pack_qty*ceil($box_qty*$val->count_pallet);
                                        @endphp
                                        {{$val->bo_pack_qty*ceil($box_qty*$val->count_pallet)}}
                                    </td>
                                    <td class="text-center">
                                        @php
                                           
                                            $sum_net += ceil($box_qty*$val->count_pallet)*$val->sit_netweight;
                                        @endphp
                            
                                        {{number_format(ceil($box_qty*$val->count_pallet)*$val->sit_netweight,2,'.',',')}}
                                    </td>
                                    <td class="text-center">
                                        @php
                                      
                                            $sum_gross += ceil($box_qty*$val->count_pallet)*$val->sit_grossweight;
                                        @endphp
                                        {{number_format(ceil($box_qty*$val->count_pallet)*$val->sit_grossweight,2,'.',',')}}
                                    </td>
                                    <td class="text-center"> 
                                        @php
                                            
                                            // echo  ($item->tp_width);
                                            // echo ($item->tp_length);
                                            // echo ($item->tp_hieght);
                                             
                                            $dimention = (($item->tp_width/100)*($item->tp_length/100)*((rand(70,95)/100)) * $val->count_pallet) ;
                                            $sum_dia += $dimention;
                                            if (count($container_detail) <= 1){
                                                echo $dimention;
                                            }
                                        @endphp
                                    </td>
                                </tr>
                               
                                @php
                                    $lastsumbox = $sumbox_qty;
                                @endphp
                                @endforeach
                                @if (count($container_detail) > 1)
                                    <tr>
                                        <td class="text-center"> </td>
                                        <td class="text-center"> </td>
                                        <td class="text-center"> </td>
                                        <td class="text-center"> </td>
                                        <td class="text-center"> </td>
                                        <td class="text-center"> </td>
                                        <td class="text-center">{{$sum_l}}</td>
                                        <td class="text-center"> </td>
                                        <td class="text-center"> </td>
                                        <td class="text-center">{{$sum_up}}</td>
                                        <td class="text-center">{{number_format($sum_net,2,'.',',') }}</td>
                                        <td class="text-center">{{number_format($sum_gross,2,'.',',') }}</td>
                                        <td class="text-center">{{$sum_dia}}</td>
                                    </tr>
                               
                                @endif
                                @php
                                    $countlap += 1;
                                    $lastlap = $sumpallet;
                                    $key_new = $key-1;
                                @endphp

                        @endforeach

                    </tbody>
                </table>

                <hr>
                @php
                 $count_lap++
                @endphp
                @endforeach
            </div>
        </div>
    </div>
</div>
<div id="ShowProducts"></div>

<!-- The Modal -->
<div class="modal" id="myModal">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
  
        <!-- Modal Header -->
        <div class="modal-header">
          <h4 class="modal-title">จัดการข้อมูลรายงาน</h4>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
  
        <!-- Modal body -->
        <form action="{{url('addremark')}}" method="post">
            <div class="modal-body">
                    @csrf
                    <div class="row">
                        <div class="col-12 col-md-6">
                            <label for="so_no">SO No.</label>
                        <input type="text" class="form-control" name="so_no" id="so_no" value="{{$report != null ? $report->rh_sono : ''}}">
                        </div>
                    </div>
                    <br>
                    <div class="row">
                        <div class="col-12 col-md-6">
                            <label for="cus_name">Customer Name</label>
                            <input type="text" class="form-control" name="cus_name" id="cus_name" value="{{$report != null ? $report->rh_customer : ''}}">
                        </div>
                    </div>
                    <br>
                    <hr>
                    <div class="row">
                        <div class="col-md-12">
                            <h5>หมายเหตุ</h5>
                        </div>
                    </div>
                   
                    <div id="packagingappendhere2">
                        @if (count($remarks) > 0)
                       
                            @foreach ($remarks as $item)
                            <div class="row  mt-3">
                                <div class="div col-md-6">
                                    <textarea type="text" name="remarks[]" class="form-control">{{$item->re_remark}}</textarea>
                                </div>
                                <div class="col-md-2 tn-buttons">
                                    <button type="button" class="mb-xs mr-xs btn btn-info addmore"><i class="fa fa-plus"></i></button>
                                    <button type="button" class="mb-xs mr-xs btn btn-info removemore"><i class="fas fa-minus"></i></button>
                                </div>
                            </div>
                            @endforeach
                    @endif
                    </div>
                    <div class="row clonedata mt-3">
                        <div class="div col-md-6">
                            <textarea type="text" name="remarks[]" class="form-control"></textarea>
                        </div>
                        
                        <div class="col-md-2 tn-buttons">
                            <button type="button" class="mb-xs mr-xs btn btn-info addmore"><i class="fa fa-plus"></i></button>
                            <button type="button" class="mb-xs mr-xs btn btn-info removemore"><i class="fas fa-minus"></i></button>
                        </div>
                    </div>
                    
                    <div id="packagingappendhere"></div>
                    
            </div>
  
            <!-- Modal footer -->
            <div class="modal-footer">
                <input type="hidden" name="pd_id" id="pd_id" value="{{$id}}">
            <button type="submit" class="btn btn-primary" style="cursor: pointer;">บันทึก</button>
            <button type="button" class="btn btn-danger" data-dismiss="modal" style="cursor: pointer;">ปิด</button>
            </div>
        </form>
      </div>
    </div>
  </div>
@endsection
@section('js')
@csrf
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
    
    //jquery
        $(document).on('click', '.addmore', function (ev) {
            var $clone = $(this).parent().parent().clone(true);
            var $newbuttons = "<button type='button' class='mb-xs mr-xs btn btn-info addmore'><i class='fa fa-plus'></i></button><button type='button' class='mb-xs mr-xs btn btn-info removemore'><i class='fa fa-minus'></i></button>";
            $clone.find('.tn-buttons').html($newbuttons).end().appendTo($('#packagingappendhere'));
        });

        $(document).on('click', '.removemore', function () {
            $(this).parent().parent().remove();
        });
</script>

@endsection
