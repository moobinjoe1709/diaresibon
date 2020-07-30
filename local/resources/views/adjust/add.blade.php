@extends('layouts.master')
@section('title')
Add Pallet to Container
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

        .fa-lock-open{
            cursor: pointer;
            font-size: 20px;
        }

        .fa-lock{
            cursor: pointer;
            font-size: 20px;
        }
</style>
@endsection
@section('content')
<div class="breadcrumb">
    <div class="breadcrumb-element">
        <a class="breadcrumb-item " href="{{ url('dashboard') }}"><i class="lnr lnr-home mr3"></i></a>
        <a class="breadcrumb-item " href="#"> Add Pallet to Container</a>
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header"> 
              <div class="row">
                    <div class="col-6">
                        Add Pallet to Container
                    </div>
                    <div class="col-6 text-right">
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
                <div class="row">
                    <div class="col-12 text-right">
                        <form action="{{url('updatecontainer')}}" method="post">
                            @csrf
                            <input type="hidden" name="pd_id" value="{{$container->ctn_pd_id}}">
                            {{-- <button type="submit" class="btn btn-primary"  onclick="return confirm('คุณต้องการ Update Contiainer ใช่หรือไม่?');">Update Container</button> --}}
                            <button type="submit" class="btn btn-primary"  >Update Container</button>
                        </form>
                    </div>
                </div>
                <br>
                <div class="row">
                    <div class="col-md-6 col-12">
                        <h5>1. Pallet ที่เตรียมโหลด เพิ่มเข้า Container</h5>
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover" id="product1">
                                <thead>
                                    <tr class="text-center">
                                        <th class="text-center">#</th>
                                        <th class="text-center"  width="10%">Loc</th>
                                        <th class="text-center">Pallet Q'TY</th>
                                        <th class="text-center">Item</th>
                                        <th class="text-center">QTY Item</th>
                                        <th class="text-center" width="25%">Size / Weight</th>
                                        <th class="text-center" width="15%">Net Weight</th>
                                        <th class="text-center" width="10%">Pick Up</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $sum_total = 0;
                                        $weight_net = 0;
                                 
                                    @endphp
                                    @if (count($pallets) > 0)
                                        @foreach ($pallets as $key => $pallet)
                                        @php
                                        $sum_pallet = 0;
                                        $per_pallet = $pallet->mp_qty_main/$pallet->mp_pallet_qty_main;
                                        
                                        @endphp
                                        <tr>
                                            <td class="text-center">
                                                @if ($pallet->mp_status == 1)
                                                    <i class="fas fa-lock unlock_pallet" atr="{{$pallet->mp_id}}"></i> 
                                                @else 
                                                    <i class="fas fa-lock-open lock_pallet" atr="{{$pallet->mp_id}}"></i> 
                                                @endif
                                            </td>
                                            <td class="text-center">{{ $pallet->mp_location == 1 ? 'ROJ' : 'PIN' }}</td>
                                            <td class="text-left"> <input type="text" class="form-control" name="pallet_qty" id="pallet_qty{{$pallet->mp_id}}" value="{{$pallet->mp_pallet_qty}}"></td>
                                            <td>{{$pallet->bo_item}}</td>
                                            <td class="text-center">{{$pallet->mp_qty }}</td>
                                            <td class="text-left">Size : {{ $pallet->tp_width.'x'.$pallet->tp_length.'x'.$pallet->tp_hieght  }}  W : {{ $pallet->tp_weight }}</td>
                                            <td>{{($pallet->mp_qty * $pallet->sit_netweight)}}</td>
                                            <td class="text-center">
                                                @if ($pallet->mp_status == 1)
                                                    <h4><p class="btn btn-danger"><i class="fas fa-times-circle"></i></p></h4>
                                                @else   
                                                    @if ($pallet->mp_pallet_qty != 0)
                                                        <h5><button type="button"  class="btn btn-success load_container"     atr="{{$pallet->mp_pallet_qty}}" atr2="{{$pallet->mp_id}}"   atr3="{{$pallet->mp_qty}}" style="cursor: pointer" ><i class="fas fa-arrow-circle-right"></i></button></h5>
                                                    @else 
                                                        <h5><p><i class="fas fa-times-circle"></i></p></h5>
                                                    @endif
                                                @endif
                                            
                                            </td>
                                            <input type="hidden" name="pallet_id" id="pallet_id" value="{{$pallet->mp_pallet_qty}}">
                                        </tr>
                                        <tr>
                                        </tr>
                                        @php
                                            $sum_total += $pallet->mp_pallet_qty;
                                            $weight_net += ($pallet->mp_qty * $pallet->sit_netweight);
                                        @endphp
                                        @endforeach
                                    @else 
                                        <td colspan="7" class="text-center">ไม่มีสินค้าให้เลือก</td>
                                    @endif
                                    {{-- <tr>
                                        <td colspan='3' class="text-right"><b>จำนวน Pallet </b></td>
                                        <td  colspan='3'>
                                            <input type="text" class="form-control" name="total_pallet" id="total_pallet"
                                                value="{{ floor($sum_total)}}" readonly>
                                        </td>
                                        <td>Pallet</td>
                                    </tr>
                                    <tr>
                                        <td colspan='3' class="text-right"><b>น้ำหนัก Net Weight</b></td>
                                        <td  colspan='3'>
                                            <input type="text" class="form-control" name="weight_pallet" id="weight_pallet"
                                                value="{{$weight_net}}" readonly>
                                        </td>
                                        <td>KK</td>
                                    </tr> --}}
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="col-md-6 col-12">
                        @php
                            $new_array = [];
                            $sumpallet = $lastpallet == 1 ? 0 : $lastpallet-1;
                            $countlap  = 0;
                            $sumbox_qty = $lastitem == 1 ? 0 : $lastitem-1;
                            $count_lap = 0;
                            $box_qty = 0;
                        @endphp
                        <h5>2.Pallet ที่มีอยู่แล้วใน Container</h5>
                        <input type="hidden" name="container_size" id="container_size" value="{{$container->ctn_size}}">
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <th class="text-center">Pallet No.</th>
                                        <th class="text-center">Item</th>
                                       
                                        <th class="text-center">PCS/CTN</th>
                                        <th class="text-center">L</th>
                                        <th class="text-center">C/NO. 1-UP</th>
                                        <th class="text-center">TOTAL</th>
                                        <th class="text-center">NET WEIGHT</th>
                                        <th class="text-center">GROSS WEIGHT</th>
                                        {{-- <th class="text-center">M<sup>3</sup></th> --}}
                                    </tr>
                                </thead>
                                <tbody>
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
                                                        {{$lastpallet}} - {{$sumpallet}}
                                                    @else 
                                                        {{++$lastlap}} - {{$sumpallet}}
                                                        
                                                    @endif
                                                @else 
                                                {{ $sumpallet}}
                                                @endif
                                            </td>
                                            <td class="text-left"></td>
                                            <td class="text-center"></td>
                                            <td class="text-center"></td>
                                            <td class="text-center"></td>
                                            <td class="text-center"></td>
                                            <td class="text-center"></td>
                                            <td class="text-center"></td>
                                            {{-- <td class="text-center"></td> --}}
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
                                                <td class="text-center">
                                                    @if ($countlap == 0)
                                                        {{$lastitem}} - {{$sumbox_qty}}
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
                                                {{-- <td class="text-center"> 
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
                                                </td> --}}
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
                                                    <td class="text-center">{{$sum_l}}</td>
                                                    <td class="text-center"> </td>
                                                    <td class="text-center">{{$sum_up}}</td>
                                                    <td class="text-center">{{number_format($sum_net,2,'.',',') }}</td>
                                                    <td class="text-center">{{number_format($sum_gross,2,'.',',') }}</td>
                                                    {{-- <td class="text-center">{{$sum_dia}}</td> --}}
                                                </tr>
                                           
                                            @endif
                                            @php
                                                $countlap += 1;
                                                $lastlap = $sumpallet;
                                                $key_new = $key-1;
                                            @endphp
            
                                    @endforeach
                                    @if (Session::has('container'))
                                        @foreach (Session::get('container') as $key => $val)
                                        @php
                                            $check_lap              = 1;
                                            $qty_box_all            = 0; //จำนวนกล่อง รวมทั้งหมด
                                            $qty_item_all           = 0; //จำนวนชิ้น รวมทั้งหมด
                                            $netweight_sum_all      = 0; //netweight รวมทั้งหมด
                                            $grossweight_sum_all    = 0; //grossweight รวมทั้งหมด
                                            $check_lapp             = 0;
                                            $lastsumbox2            = 0;
                                        @endphp
                                        @foreach ($val['pallet_id'] as $no => $item)
                                            @php
                                                $val['container_id'][$no]; //ตำแหน่ง group item
                                                $item; //ตำแหน่ง item ใน group
                                            
                                                $array                 = Session::get('pallet_weight')[$val['container_id'][$no]][$item]['tpl'];
                                                $check_lap2            = 1;
                                                $count_array           = count($array);
                                                $qty_box               = 0; //จำนวนกล่อง รวมต่อpalet
                                                $qty_item              = 0; //จำนวนชิ้น รวมต่อpalet
                                                $netweight_sum         = 0; //netweight รวมต่อpalet
                                                $grossweight_sum       = 0; //grossweight รวมต่อpalet
                                            @endphp
                                            <tr>
                                                <td class="text-center">
                                                    {{ $sumpallet++}}
                                                </td>
                                                <td class="text-left">
                                                    {{-- {{$item->tp_width.'x'.$item->tp_length.'x'.$item->tp_hieght.' CM'}} --}}
                                                </td>
                                                <td class="text-center"></td>
                                                <td class="text-center"></td>
                                                <td class="text-center"></td>
                                                <td class="text-center"></td>
                                                <td class="text-center"></td>
                                                <td class="text-center">
                                                    <p class="btn btn-danger delete_container"  atr="{{$key}}" atr2="{{$no}}" style="cursor: pointer;color:white;">X</p>
                                                </td>
                                                {{-- <td class="text-center"></td> --}}
                                            </tr>
                                            @php
                                                $check_key = 0;
                                            @endphp
                                            @foreach ($array as $key => $value) 
                                                @php
                                                    $pallet = DB::table('tb_pallet')
                                                                ->leftjoin('tb_boxs','tb_pallet.tpl_bo_id','=','tb_boxs.bo_id')
                                                                ->leftjoin('tb_items','tb_boxs.bo_item','=','tb_items.it_name') 
                                                                ->leftjoin('tb_subitems','tb_items.it_id','=','tb_subitems.sit_it_id')
                                                                ->leftjoin('tb_typepalate','tb_subitems.sit_pallet','=','tb_typepalate.tp_id')
                                                                ->where('tpl_id',$key)
                                                                ->first();

                                                    $mainpallet = DB::table('tb_mainpallet')
                                                                ->leftjoin('tb_pallet','tb_mainpallet.mp_id','=','tb_pallet.tpl_mp_id')
                                                                ->leftjoin('tb_boxs','tb_pallet.tpl_bo_id','=','tb_boxs.bo_id')
                                                                ->leftjoin('tb_items','tb_boxs.bo_item','=','tb_items.it_name') 
                                                                ->leftjoin('tb_subitems','tb_items.it_id','=','tb_subitems.sit_it_id')
                                                                ->leftjoin('tb_typepalate','tb_subitems.sit_pallet','=','tb_typepalate.tp_id')
                                                                ->where('tpl_id',$key)
                                                                ->first();

                                                
                                                @endphp     
                                                
                                                <tr class="text-center">
                                                    <td class="text-center" style="font-size:10px;">
                                                        {{$pallet->bo_so}}
                                                    </td>
                                                    <td class="text-center">
                                                        {{$pallet->bo_item}}
                                                
                                                    </td>
                                                    <td class="text-center">
                                                        {{$pallet->bo_pack_qty}}
                                                    </td>                                    
                                                    <td class="text-center">{{ceil($value / $mainpallet->sit_netweight)}} </td>
                                                    <td>
                                                            @if ($check_lapp == 0)
                                                                {{$lastsumbox +1 }} - {{($lastsumbox+1) + ceil($value / $mainpallet->sit_netweight)}}
                                                            @else 
                                                               
                                                                {{ $lastsumbox +1 }} - {{$lastsumbox + ceil($value / $mainpallet->sit_netweight)}}
                                                            @endif
                                                    </td>
                                                    {{-- <td class="text-center">C/N 1-UP</td> --}}
                                                    <td class="text-center">{{ ceil($pallet->bo_pack_qty *($value / $mainpallet->sit_netweight)) }}</td>
                                                    <td class="text-center">{{number_format($value,2,'.',',')}}</td>
                                                    <td class="text-center">{{ number_format(($value / $mainpallet->sit_netweight) *  $mainpallet->sit_grossweight,2,'.',',')}}</td>
                                                    {{-- <td></td> --}}
                                                </tr>
                                                @php
                                                    ++$check_lap2;
                                                    $qty_box            +=  $value / $mainpallet->sit_netweight; //จำนวน กล่อง รวม
                                                    $qty_item           +=  ($pallet->bo_pack_qty *($value / $mainpallet->sit_netweight)); //จำนวน ชิ้น รวม
                                                    $netweight_sum      +=     ($value / $mainpallet->sit_netweight) * $mainpallet->sit_netweight; //netweightรวม
                                                    $grossweight_sum    += ($value / $mainpallet->sit_netweight) *  $mainpallet->sit_grossweight ; //grossweight รวม
                                                    $check_lapp++;
                                                    $check_key++;
                                                 
                                                    $lastsumbox       +=  ceil($value / $mainpallet->sit_netweight);
                                                   
                                                @endphp
                                            @endforeach
                                                @if ($count_array > 1)
                                                    <tr class="text-center">
                                                        <td></td>
                                                        <td></td>
                                                        <td></td>
                                                        <td>{{ceil($qty_box)}}</td>
                                                        <td class="text-center"></td>
                                                        <td class="text-center">{{number_format($qty_item,2,'.',',')}}</td>
                                                        <td class="text-center">{{number_format($netweight_sum,2,'.',',')}}</td>
                                                        <td class="text-center">{{number_format($grossweight_sum,2,'.',',')}}</td>
                                                        {{-- <td class="text-center"></td> --}}
                                                    </tr>
                                                @endif
                                            
                                            @php
                                                ++$check_lap;
                                                $qty_box_all            += $qty_box; //จำนวนกล่อง รวมทั้งหมด
                                                $qty_item_all           += $qty_item; //จำนวนชิ้น รวมทั้งหมด
                                                $netweight_sum_all      += $netweight_sum; //netweight รวมทั้งหมด
                                                // echo $netweight_sum."<br/>";
                                                $grossweight_sum_all    += $grossweight_sum; //grossweight รวมทั้งหมด
                                                $lastsumbox2            +=  ceil($value / $mainpallet->sit_netweight);
                                               
                                            @endphp
                                        @endforeach
                                        {{-- <tr>
                                            <td  colspan="11" style="padding: 1.5rem;"></td>

                                        </tr>
                                        <tr>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td class="text-center">Total</td>
                                            <td class="text-center">{{ceil($qty_box_all)}}</td>
                                            <td class="text-center">{{number_format($qty_item_all,2,'.',',')}}</td>
                                            <td class="text-center">{{number_format($netweight_sum_all,2,'.',',')}}</td>
                                            <td class="text-center">{{number_format($grossweight_sum_all,2,'.',',')}}</td>
                                        </tr> --}}
                                        @endforeach
                                    @endif            
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
          
            </div>
        </div>
    </div>
</div>
@endsection
@section('js')

<script>
$(".lock_pallet").click(function(){
    var id = $(this).attr('atr');
    $.ajax({
        url:"{{url('changelock')}}/"+id,
        method:"get",
    }).done(function(data){
        
        window.location.reload();
    });
});
$(".unlock_pallet").click(function(){
    var id = $(this).attr('atr');
    $.ajax({
        url:"{{url('changeunlock')}}/"+id,
        method:"get",
    }).done(function(data){
        window.location.reload();
    });
});

$(".load_container").click(function(){
    var qty = $(this).attr('atr');
    var id = $(this).attr('atr2');
    var qty_item = $(this).attr('atr3');

    var qty_old = $("#pallet_qty"+id).val();
    var token = $("input[name=_token]").val();
    
    var container_size = $("#container_size").val();
    

    if(parseInt(qty_old) > parseInt(qty) || parseInt(qty_old) <= 0){
        
    }else{
        $.ajax({
            url:"{{url('load_pallet_container')}}",
            type:"post",
            data:{
                _method:"post",
                _token:token,   
                'qty':qty,
                'id':id,
                'qty_item':qty_item,
                'qty_old':qty_old,
                'container_size':container_size,
            }
        }).done(function(data){
            // console.log(data);
            location.reload();
        });
    }
  
});


$(".delete_container").click(function(){
    var id = $(this).attr('atr');
    var id2 = $(this).attr('atr2');
    // var qty = $(this).attr('atr3');
    // var qty_item = $(this).attr('atr4');
    var token = $("input[name=_token]").val();
    $.ajax({
        url:"{{url('delete_pallet_out_container')}}",
        type:"post",
        data:{
            _method:"post",
            _token:token,
            'id':id,
            'id2':id2,
            // 'qty_item':qty_item,
            // 'qty':qty
        }
    }).done(function(data){
        // console.log(data);
        location.reload();
    });
  
});

    //    $(".del_con").click(function(){
    //     var id = $(this).attr('atr');
    //     var token = $("input[name=_token]").val();
    //     swal({
    //             title: "คำเตือน!!",
    //             text: "คุณจะทำการ ลบข้อมูล Container หรือไม่!",
    //             icon: "warning",
    //             buttons: true,
    //             dangerMode: true,
    //         })
    //         .then((willDelete) => {
    //             if (willDelete) {
    //                 $.ajax({
    //                     url:"{{url('adjust')}}/"+id,
    //                     type:"post",
    //                     data:{
    //                         "_token"  : token,
    //                         "_method" : "delete",
    //                         "id": id
    //                     }
    //                 }).done(function(data){
    //                     if(data == 1){
    //                         swal({
    //                             title: "เรียบร้อย?",
    //                             text: "คุณทำการลบข้อมูล Container เรียบร้อย!",
    //                             icon: "success",
    //                         }).then((willDelete) => {
    //                             window.location.reload();
    //                         });
    //                     }else{
    //                         swal("ยกเลิก!", "คุณยกเลิกการลบข้อมูล!", "error");
    //                     }
    //                 });
    //             } else {
    //                 swal("ยกเลิก!", "คุณยกเลิกการลบข้อมูล!", "error");
    //             }
    //     });
    // });

    // $(".del_pallet").click(function(){
    //     var id = $(this).attr('atr');
    //     var token = $("input[name=_token]").val();
    //     swal({
    //             title: "คำเตือน!!",
    //             text: "คุณจะทำการ ลบข้อมูล Pallet หรือไม่!",
    //             icon: "warning",
    //             buttons: true,
    //             dangerMode: true,
    //         })
    //         .then((willDelete) => {
    //             if (willDelete) {
    //                 $.ajax({
    //                     url:"{{url('delpallet')}}/"+id,
    //                     type:"post",
    //                     data:{
    //                         "_token"  : token,
    //                         "_method" : "delete",
    //                         "id": id
    //                     }
    //                 }).done(function(data){
    //                     if(data == 1){
    //                         swal({
    //                             title: "เรียบร้อย?",
    //                             text: "คุณทำการลบข้อมูล Item เรียบร้อย!",
    //                             icon: "success",
    //                         }).then((willDelete) => {
    //                             window.location.reload();
    //                         });
    //                     }else{
    //                         swal("ยกเลิก!", "คุณยกเลิกการลบข้อมูล!", "error");
    //                     }
    //                 });
    //             } else {
    //                 swal("ยกเลิก!", "คุณยกเลิกการลบข้อมูล!", "error");
    //             }
    //     });
    // });

    // $(".del_items").click(function(){
    //     var id = $(this).attr('atr');
    //     var token = $("input[name=_token]").val();
    //     swal({
    //             title: "คำเตือน!!",
    //             text: "คุณจะทำการ ลบข้อมูล Item หรือไม่!",
    //             icon: "warning",
    //             buttons: true,
    //             dangerMode: true,
    //         })
    //         .then((willDelete) => {
    //             if (willDelete) {
    //                 $.ajax({
    //                     url:"{{url('delitems')}}/"+id,
    //                     type:"post",
    //                     data:{
    //                         "_token"  : token,
    //                         "_method" : "delete",
    //                         "id": id
    //                     }
    //                 }).done(function(data){
    //                     if(data == 1){
    //                         swal({
    //                             title: "เรียบร้อย?",
    //                             text: "คุณทำการลบข้อมูล Item เรียบร้อย!",
    //                             icon: "success",
    //                         }).then((willDelete) => {
    //                             window.location.reload();
    //                         });
    //                     }else{
    //                         swal("ยกเลิก!", "คุณยกเลิกการลบข้อมูล!", "error");
    //                     }
    //                 });
    //             } else {
    //                 swal("ยกเลิก!", "คุณยกเลิกการลบข้อมูล!", "error");
    //             }
    //     });
    // });
</script>
@endsection
