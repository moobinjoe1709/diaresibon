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
            <form action="{{ url('autoload') }}" method="POST">
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
                                            <th class="text-center">Loc</th>
                                            <th class="text-center">Pallet Q'TY</th>
                                            <th class="text-center">Size / Weight</th>
                                            <th class="text-center">Pick Up</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php
                                            $sum_total = 0;
                                        // dd($pallets2);
                                        @endphp
                                        @foreach ($pallets2 as $key => $pallet)
                                        @php
                                           $sum_pallet = 0;
                                            //    $pallets = DB::table('tb_mainpallet')
                                            //                 ->select('tb_mainpallet.*','tb_pallet.*','tb_boxs.*','tb_items.*','tb_subitems.*','tb_typepalate.*')
                                            //                 ->leftjoin('tb_pallet','tb_mainpallet.mp_id','=','tb_pallet.tpl_mp_id')
                                            //                 ->leftjoin('tb_boxs','tb_pallet.tpl_bo_id','=','tb_boxs.bo_id')
                                            //                 ->leftjoin('tb_items','tb_boxs.bo_item','=','tb_items.it_name') 
                                            //                 ->leftjoin('tb_subitems','tb_items.it_id','=','tb_subitems.sit_it_id')
                                            //                 ->leftjoin('tb_typepalate','tb_subitems.sit_pallet','=','tb_typepalate.tp_id')
                                            //                 ->groupBy('mp_id')
                                            //                 ->where('tp_id',$pallet->tp_id)    
                                            //                 ->get();
                                            // foreach ($pallets as $key => $pallet) {
                                            //    $sum_pallet +=  $pallet->mp_pallet_qty;
                                            // }
                                        @endphp
                                        <tr>
                                            <td class="text-center">{{ ++$key }}</td>
                                            <td class="text-center">{{ $pallet->mp_location == 1 ? 'ROJ' : 'PIN' }}</td>
                                            <td class="text-left"> <input type="text" class="form-control" name="pallet_qty" id="pallet_qty{{$pallet->mp_id}}" value="{{$pallet->mp_pallet_qty}}"></td>
                                            <td class="text-left">Size : {{ $pallet->tp_width.'x'.$pallet->tp_length.'x'.$pallet->tp_hieght  }}  W : {{ $pallet->tp_weight }}</td>
                                            <td class="text-center">
                                                @if ($pallet->mp_pallet_qty != 0)
                                                    <h5><button type="button"  class="btn btn-success load_container" {{$pallet->mp_status == 1 ? 'disabled' : ''}}    atr="{{$pallet->mp_pallet_qty}}" atr2="{{$pallet->mp_id}}"   atr3="{{$pallet->mp_qty}}" style="cursor: pointer" ><i class="fas fa-arrow-circle-right"></i></button></h5>
                                                @else 
                                                    <h5><p><i class="fas fa-times-circle"></i></p></h5>
                                                @endif
                                            </td>
                                            <input type="hidden" name="pallet_id" id="pallet_id" value="{{$pallet->mp_pallet_qty}}">
                                        </tr>
                                        <tr>
                                           
                                            {{-- <td class="text-center">{{ ++$i }}</td>
                                            <td class="text-center">{{ $box->bo_mas_loc }}</td>
                                            <td class="text-left"> <input type="text" class="form-control" name="pallet_qty" id="pallet_qty" value="{{ $amout_pallet2 }}"></td>
                                            <td class="text-left">Size : {{ $box->tp_width.'x'.$box->tp_length.'x'.$box->tp_hieght  }}  W : {{ $box->tp_weight }}</td>
                                            <td class="text-center">
                                                <input type="checkbox" name="check_box[]" id="check_box" class="check_box"  value="{{ $bo_id_implode }}">
                                            </td>
                                            <input type="hidden" name="pallet_id" id="pallet_id" value="{{ $bo_id_implode }}"> --}}
                                        </tr>
                                        @php
                                            $sum_total += $pallet->mp_pallet_qty;
                                        @endphp
                                        @endforeach
                                        <tr>
                                            <td colspan='2' class="text-center"><b>Total</b></td>
                                            <td>
                                                <input type="text" class="form-control" name="total_pallet" id="total_pallet"
                                                    value="{{ floor($sum_total)}}" readonly></td>
                                            <td></td>
                                            <td> </td>
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
                                            <th class="text-center">Pallet Q'TY</th>
                                            <th class="text-center">Pallet No.</th>
                                            <th class="text-center">Size / Weight</th>
                                            <th class="text-center">Loc</th>
                                            <th class="text-center">Delete</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        {{-- {{dd(Session::get('cart'))}}    --}}
                                        @if (Session::get('cart') != null)
                                            @php
                                                $sum_total = 0;
                                                $i = 0;
                                            @endphp

                                            @foreach (Session::get('cart') as $key => $value)
                                            @php
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
                                            @endphp
                                            <tr>
                                                <td class="text-center">{{++$i}}</td>
                                                <td class="text-center">{{$value['qty']}}</td>
                                                <td class="text-center"></td>
                                                <td class="text-center">{{ $pallets_de->tp_width.'x'.$pallets_de->tp_length.'x'.$pallets_de->tp_hieght  }}  W : {{ $pallets_de->tp_weight }}</td>
                                                <td class="text-center">{{$pallets_de->bo_mas_loc}}</td>
                                                <td class="text-center"><p class="btn btn-danger delete_container" atr="{{$value['id']}}" atr2="{{$key}}"  atr3="{{$value['qty']}}"   atr4="{{$value['item']}}" style="cursor: pointer;color:white;">X</p></td>
                                            </tr>
                                            @php
                                                $sum_total += $value['qty'];
                                            
                                            @endphp
                                            @endforeach 
                                        @else 
                                            @php
                                                $sum_total = 0;
                                            @endphp
                                            <tr>
                                                <td colspan="6" class="text-center">ยังไม่มีการเลือก Pallet</td>
                                                {{-- <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td> --}}
                                            </tr>
                                        @endif
                                       
                                        <tr>
                                            <td>Total</td>
                                            <td><input type="text" name="total" id="total" class="form-control" value="{{$sum_total}}" size="2"
                                                    maxlength="4" readonly></td>
                                                <input type="hidden" name="id" id="id" class="form-control" value="{{$id}}" ></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="col-12 text-right">
                                <button type="submit" class="btn btn-primary" {{Session::get('cart') != null ? '' : 'disabled'}}>Load Container</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
@section('js')
<script>
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
        location.reload();
    });
  
});

</script>
@endsection
