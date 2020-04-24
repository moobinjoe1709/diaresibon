@extends('layouts.master')
@section('title')
Manual Load Container
@endsection
@section('css')
<link rel="stylesheet" href="{{ asset('assets/css/app.css') }}">
<style>
    .modal-lg {
        max-width: 1200px;
    }

    input{
        text-align: center;
    }
    
    .load_pallet{
        cursor: pointer;
    }
</style>
@endsection
@section('content')
<div class="breadcrumb">
    <div class="breadcrumb-element">
        <a class="breadcrumb-item " href="{{ url('dashboard') }}"><i class="lnr lnr-home mr3"></i></a>
        <a class="breadcrumb-item " href="#">  Manual Load Container</a>
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <div class="row">
                    <div class="col-6">
                        Manual Load Container
                    </div>
                    <div class="col-6  text-right">
                        <a class="btn btn-secondary" href="{{ url('box',$id).'/edit' }}">กลับ</a>
                    </div>
                </div>
            </div>
            <form action="" method="POST" id="load_preview">
                @csrf
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
                    ขั้นตอนที่ 1 เลือกขนาดตู้คอนเทรนเนอร์ และสาขาที่ขึ้นตู้
                    <hr>
                    <div class="row">
                        <div class="col-md-3 col-12">
                            <select name="container_size" id="container_size" class="form-control" required>
                                <option value="">------- โปรดเลือกขนาดตู้คอนเทรนเนอร์ --------</option>
                                <option value="20">20 ตัน</option>
                                <option value="40">40 ตัน</option>
                            </select>
                        </div>
                        <div class="col-md-3 col-12">
                            <select name="location" id="location" class="form-control" required>
                                <option value="">------- โปรดเลือกสาขาที่ขึ้นตู้ --------</option>
                                <option value="1">ROJ</option>
                                <option value="2">PIN</option>
                            </select>
                        </div>
                        <div class="col-md-2 col-12">
                            <input type="text" name="weight_over" id="weight_over" class="form-control" placeholder="+- น้ำหนักส่วนเกิน">
                        </div>
                        <div class="col-md-2 col-12">
                            <input type="text" name="pallet_qty_sel" id="pallet_qty_sel" class="form-control" placeholder="จำนวน Pallet">
                        </div>
                        <div class="col-md-2 text-right">
                            <button type="button" class="btn btn-primary load_previewmanual" {{Session::get('cart') != null ? '' : 'disabled'}}>Preview Container</button>
                        </div>
                    </div>
                    
                    <hr>
                    <div class="row">
                        <div class="col-md-6 col-12">
                            <p>ขั้นตอนที่ 2 เลือก Pallet ที่ต้องการ</p>
                            <div class="row">
                                <div class="col-12">
                                        <h5 class="text-success"><span>จำนวน Pallet ที่ยังไม่ได้ Load ขึ้น</span> </h5>
                                </div>
                                <div class="col-6 text-right">
                                        {{-- <p><button class="btn btn-success" type="button" id="select_box" >select</button></p> --}}
                                </div>
                            </div>
                            <div class="table-responsive">
                                <table class="table table-bordered table-hover" id="product1">
                                    <thead>
                                        <tr class="text-center">
                                            <th class="text-center">#</th>
                                            <th class="text-center"  width="10%">Loc</th>
                                            <th class="text-center">Pallet Q'TY</th>
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
                                        @if (count($pallets2) > 0)
                                            @foreach ($pallets2 as $key => $pallet)
                                            @php
                                            $sum_pallet = 0;
                                            $per_pallet = $pallet->mp_qty_main/$pallet->mp_pallet_qty_main;
                                            
                                            @endphp
                                            <tr>
                                                <td class="text-center">{{ ++$key }}</td>
                                                <td class="text-center">{{ $pallet->mp_location == 1 ? 'ROJ' : 'PIN' }}</td>
                                                <td class="text-left"> <input type="text" class="form-control" name="pallet_qty" id="pallet_qty{{$pallet->mp_id}}" value="{{$pallet->mp_pallet_qty}}"></td>
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
                                        <tr>
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
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        {{-- {{dd(Session::get('cart'))}} --}}
                        <div class="col-md-6 col-12">
                            <p>ขั้นตอนที่ 3 ตรวจสอบ Pallet ที่เลือกและยืนยัน</p>
                            <h5 class="text-success">จำนวน Pallet ที่ Load ขึ้น Container แล้ว</h5>
                            <div class="table-responsive">
                                <table class="table table-bordered table-hover" id="product1">
                                    <thead>
                                        <tr class="text-center">
                                            <th class="text-center">#</th>
                                            <th class="text-center">Loc</th>
                                            <th class="text-center">Pallet Q'TY</th>
                                            <th class="text-center">QTY Item</th>
                                            <th class="text-center">Size / Weight Pallet</th>
                                            <th class="text-center">Net Weight</th>
                                            <th class="text-center">Delete</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        {{-- {{dd(Session::get('cart'))}}    --}}
                                        @if (Session::get('cart') != null)
                                        @php
                                        $sum_total = 0;
                                        $i = 0;
                                        $weight_pallet = 0;
                                        @endphp
                                        @foreach (Session::get('cart') as $key => $value)
                                        @php
                                            $pallet_diff = 0;
                                            $pallets_de = DB::table('tb_mainpallet')
                                                        ->select('tb_mainpallet.*','tb_pallet.*','tb_boxs.*','tb_items.*','tb_subitems.*','tb_typepalate.*')
                                                        ->leftjoin('tb_pallet','tb_mainpallet.mp_id','=','tb_pallet.tpl_mp_id')
                                                        ->leftjoin('tb_boxs','tb_pallet.tpl_bo_id','=','tb_boxs.bo_id')
                                                        ->leftjoin('tb_items','tb_boxs.bo_item','=','tb_items.it_name') 
                                                        ->leftjoin('tb_subitems','tb_items.it_id','=','tb_subitems.sit_it_id')
                                                        ->leftjoin('tb_typepalate','tb_subitems.sit_pallet','=','tb_typepalate.tp_id')
                                                        ->groupBy('mp_id')
                                                        ->where('mp_id',$value['id'])    
                                                        ->first();
                                            
                                            $pallets_counts = DB::table('tb_mainpallet')
                                                        ->select('tb_mainpallet.*','tb_pallet.*','tb_boxs.*','tb_items.*','tb_subitems.*','tb_typepalate.*')
                                                        ->leftjoin('tb_pallet','tb_mainpallet.mp_id','=','tb_pallet.tpl_mp_id')
                                                        ->leftjoin('tb_boxs','tb_pallet.tpl_bo_id','=','tb_boxs.bo_id')
                                                        ->leftjoin('tb_items','tb_boxs.bo_item','=','tb_items.it_name') 
                                                        ->leftjoin('tb_subitems','tb_items.it_id','=','tb_subitems.sit_it_id')
                                                        ->leftjoin('tb_typepalate','tb_subitems.sit_pallet','=','tb_typepalate.tp_id')
                                                        ->where('tpl_mp_id',$value['id'])
                                                        ->get();    
                                            
                                                  
                                            $per_pallet = $pallets_de->mp_qty_main/$pallets_de->mp_pallet_qty_main;
                                            $sum_pallet_sel = $per_pallet*$value['qty'];
                                            $weight_per = 0;
                                            foreach ($pallets_counts as $pallets_count) {
                                                // $weight_per += ($per_pallet * $value['qty']) * ($pallets_count->sit_netweight / $pallets_count->sit_palletvolume);
                                    
                                                if($pallet_diff <= $sum_pallet_sel){
                                                    if($pallets_count->tpl_qty > $sum_pallet_sel){
                                                        $pallet_diff += $sum_pallet_sel;
                                                    }else{
                                                        if($pallet_diff > $sum_pallet_sel){
                                                            $pallet_diff +=  ($pallet_diff - $sum_pallet_sel);
                                                        }else{
                                                            $pallet_diff += ($pallets_count->tpl_qty);
                                                        }
                                                    }
                                                       
                                                }
                                            }
                                            
                                            $weight_pallet += $pallets_de->sit_netweight * ($per_pallet * $value['qty']);
                                          
                                            @endphp
                                            <tr>
                                                <td class="text-center">{{++$i}}</td>
                                                <td class="text-center">{{$pallets_de->bo_mas_loc}}</td>
                                                <td class="text-center">{{$value['qty']}}</td>
                                                <td class="text-center">{{$per_pallet * $value['qty']}}</td>
                                                <td class="text-center">{{ $pallets_de->tp_width.'x'.$pallets_de->tp_length.'x'.$pallets_de->tp_hieght  }}  W : {{ $pallets_de->tp_weight }}</td>
                                             
                                                <td class="text-center">{{$pallets_de->sit_netweight * ($per_pallet * $value['qty'])}}</td>
                                                <td class="text-center"><p class="btn btn-danger delete_container" atr="{{$value['id']}}" atr2="{{$key}}"  atr3="{{$value['qty']}}"   atr4="{{$value['item']}}" style="cursor: pointer;color:white;">X</p></td>
                                            </tr>
                                            @php
                                              $sum_total += $value['qty'];
                                            @endphp
                                            @endforeach 
                                        @else 
                                            @php
                                                $sum_total = 0;
                                                $weight_pallet = 0;
                                            @endphp
                                            <tr>
                                                <td colspan="8" class="text-center">ยังไม่มีการเลือก Pallet</td>
                                                {{-- <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td> --}}
                                            </tr>
                                        @endif
                                       
                                        <tr>
                                            <td  class="text-right" colspan="5">จำนวน Pallet ที่เตรียมจะ Load</td>
                                            <td>
                                                <input type="text" name="total" id="total" class="form-control" value="{{$sum_total}}" size="2"
                                                    maxlength="4" readonly></td>
                                                <input type="hidden" name="id" id="id" class="form-control" value="{{$id}}" >
                                                <input type="hidden" name="type" id="type" class="form-control" value="{{$type}}" >
                                            </td>
                                            <td>Pallet</td>
                                        </tr>
                                        <tr>
                                            <td class="text-right"  colspan="5">น้ำหนัก</td>
                                            <td><input type="text" name="weight_all" id="weight_all" class="form-control" value="{{$weight_pallet}}" readonly></td>
                                            <td>KK</td>
                                        </tr>
                                        <tr>
                                            <td class="text-right"  colspan="5">น้ำหนัก</td>
                                            <td><input type="text" name="weight_all" id="weight_all" class="form-control" value="{{$weight_pallet / 1000}}" readonly></td>
                                            <td>ton</td>
                                        </tr>
                                       
                                    </tbody>
                                </table>
                            </div>
                        </div>
                       
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="exampleModal2" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Preview Manual Load Container</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
            <div class="show_load"></div>
        </div>
      </div>
    </div>
  </div>
@endsection
@section('js')
<script>

$(".load_previewmanual").click(function(){
    var formdata = $("#load_preview").serialize();

    $.ajax({
        url:"{{ url('preview_manual') }}",
        type: 'POST',
        data:formdata,
    }).done(function(data){
        $("#exampleModal2").modal();
        console.log(data);
        $(".show_load").html(data);
       
    });
});

$("#select_box").click(function(){
    var sel_box = $('.check_box:checked').serialize();
    var token = $("input[name=_token]").val();
    $.ajax({
        url:"{{ url('select_box')}}",
        type:"post",
        data:{
            _method:"post",
            _token:token,
            'sel_box':sel_box
        }
    }).done(function(data){
        console.log(data);
    });
  
});

$(".load_container").click(function(){
    var qty = $(this).attr('atr');
    var id = $(this).attr('atr2');
    var qty_item = $(this).attr('atr3');

    var qty_old = $("#pallet_qty"+id).val();
    var token = $("input[name=_token]").val();
    

    if(parseInt(qty_old) > parseInt(qty) || parseInt(qty_old) <= 0){
        
    }else{
        $.ajax({
            url:"{{url('load_container')}}",
            type:"post",
            data:{
                _method:"post",
                _token:token,   
                'qty':qty,
                'id':id,
                'qty_item':qty_item,
                'qty_old':qty_old,
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
    var qty = $(this).attr('atr3');
    var qty_item = $(this).attr('atr4');
    var token = $("input[name=_token]").val();
    $.ajax({
        url:"{{url('delete_container')}}",
        type:"post",
        data:{
            _method:"post",
            _token:token,
            'id':id,
            'id2':id2,
            'qty_item':qty_item,
            'qty':qty
        }
    }).done(function(data){
        // console.log(data);
        location.reload();
    });
  
});



</script>
@endsection
