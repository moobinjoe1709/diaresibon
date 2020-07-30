@extends('layouts.master')
@section('title')
Auto Load container
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
<style>
    input{
    text-align: center;
}

.fa-lock-open{
    cursor: pointer;
}

.fa-lock{
    cursor: pointer;
}
label{
    font-weight: bold;
}
</style>
@php
$active = 'box';
@endphp
<div class="breadcrumb">
    <div class="breadcrumb-element">
        <a class="breadcrumb-item " href="{{ url('dashboard') }}"><i class="lnr lnr-home mr3"></i></a>
        <a class="breadcrumb-item " href="#">  Auto Load container </a>
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <div class="row">
                    <div class="col-6">
                        Auto Load container
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
                <form method="POST" id="form_loadpreview">
                    @csrf
                    <div class="row">
                        <div class="col-md-4 col-12">
                            <label for="selec_con">เลือก Container :</label>
                            <select name="selec_con" class="form-control" id="selec_con" required>
                                <option value="">----- โปรดเลือก Container -----</option>
                                <option value="20">Container 20 ตัน</option>
                                <option value="40">Container 40 ตัน</option>
                            </select>
                        </div>
                        <div class="col-md-3 col-12">
                            <label for="location"> Location </label>
                            <select name="location" id="location" class="form-control" required>
                                <option value="">----- โปรดเลือก Location -----</option>
                                <option value="1">ROJ</option>
                                <option value="2">PIN</option>
                            </select>
                        </div>
                        <div class="col-md-3 col-12">
                            <label for="over_kk"> + - น้ำหนักไม่เกิน (KK.)</label>
                            <input type="text" name="over_kk" class="form-control" id="over_kk" placeholder="KK."> 
                      
                        </div>
                        <div class="col-md-2 col-12">
                            <label for="qty_pallet"> จำนวน Pallet </label>
                            <input type="text" name="qty_pallet" class="form-control"  id="qty_pallet" size="2" placeholder="Pallet">
                            
                        </div>
                      
                    </div>
                    <br>
                    <div class="row">
                        <div class="col-md-2 col-3">
                            @php
                                   $count_roj = DB::table('tb_mainpallet')->where('mp_pd_id',$id)->where('mp_location',1)->sum('mp_pallet_qty_main');
                                   $count_pin = DB::table('tb_mainpallet')->where('mp_pd_id',$id)->where('mp_location',2)->sum('mp_pallet_qty_main');
                            @endphp
                            <h4>Roj : {{$count_roj}}</h4> 
                        </div>
                        <div class="col-md-2 col-3">
                           <h4> Pin : {{$count_pin}}</h4> 
                        </div>
                        <div class="col-md-8 col-6 text-right">
                            <button style="cursor: pointer;" type="button" id="add_box" class="btn btn-primary">Preview Container</button>
                        </div>
                    </div>
                    <br>

                    <div class="table-responsive">
                        <table class="table table-bordered table-hover" id="product1">
                            <thead>
                                {{-- <tr>
                                    <td colspan='7'>
                                        เลือก Container :
                                        <select name="selec_con" id="selec_con" required oninvalid="this.setCustomValidity('Please Select Container')"
                                            oninput="setCustomValidity('')">
                                            <option value="">----- โปรดเลือก Container -----</option>
                                            <option value="20">Container 20 ตัน</option>
                                            <option value="40">Container 40 ตัน</option>
                                        </select>
                                        + - ไม่เกิน
                                        <input type="text" name="over_kk" id="over_kk"> Kg.
                                         จำนวน 
                                        <input type="text" name="qty_pallet" id="over_qty_palletkk" size="2">
                                        Pallet
                                    </td>
                                    <td colspan='3'>
                                        เลือก Location :
                                        <select name="location" id="location" required oninvalid="this.setCustomValidity('Please Select Location')"
                                            oninput="setCustomValidity('')">
                                            <option value="">----- โปรดเลือก Location -----</option>
                                            <option value="1">ROJ</option>
                                            <option value="2">PIN</option>
                                        </select>
                                    </td>
                                    <td colspan="4" class="text-right" >
                                        <button style="cursor: pointer;" type="button" id="add_box" class="btn btn-primary">Preview Container</button>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-center" colspan='14'></td>
                                </tr> --}}
                                <tr class="text-center"  style="background-color:#8b7eef ;color:white;">
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
                                    <th class="text-center"></th>
                                </tr>
                            </thead>
                            <tbody>
                             
                                @php
                             

                                $key_check = 1;
                                $amout_pallet = 0;
                                $total_pallet2 =0;
                                $total_pallet =0;
                                $total_sum_box2 = 0;
                                $total_netweight2 = 0;
                                $total_grossweight2 = 0;
                                $key_check2 = 0;
                                $count_lock = 0;
                                $count_lock2 = 0;
                                $count_qty_pallet = 0;
                                @endphp
                         
                                @foreach ($boxs as $key => $box)
                                
                                @php
                                 ++$count_lock2;
                                if($box->mp_status == 1){
                                    ++$count_lock;
                                }
                                $amout_pallet2 = 0;
                                $total_sum_box = 0;
                                $total_netweight = 0;
                                $total_grossweight = 0;
                                $count_key = 0;
                                $height_pallet = $box->tp_hieght+$box->mp_height;
                               
                               
                                @endphp
                                <tr  style="{{$box->mp_status == 1 ? 'background-color:#F76464;' : 'background-color:darkgray;'}} color:white;" class="ui-state-disabled">
                                    {{-- <td class="text-center" colspan='2'></td> --}}
                                    <td class="text-left" colspan='12'>
                                        @if ($box->mp_status == 1)
                                            <i class="fas fa-lock unlock_pallet" atr="{{$box->mp_id}}"></i> 
                                        @else 
                                            <i class="fas fa-lock-open lock_pallet" atr="{{$box->mp_id}}"></i> 
                                        @endif
                                            ({{$boxs[$key]->mp_no}} - {{($boxs[$key]->mp_no-1)+$boxs[$key]->mp_pallet_qty}})
                                            {{-- ({{$count_qty_pallet + 1}} -   {{ $boxs[$key]->mp_pallet_qty + $count_qty_pallet}}) --}}
                                     
                                        
                                        Pallet Size : {{
                                        $box->tp_width.'x'.$box->tp_length.'x'.$height_pallet }} ({{ $box->tp_weight.'kgs/PLT' }} )
                                        ({{$boxs[$key]->mp_location == 1 ? 'ROJ' : 'PIN'}})
                                    </td> 
                                    {{-- <td colspan='4'>[ XX ] x XXX น้ำหนัก Pallet รวม : <input type="text" name="total_weight"
                                            id="total_weight" size="2"></td> --}}

                                    <td class="text-right" >
                                        <button  type="button" class="btn btn-success edit_pallet" style="color:white;cursor: pointer;" atr="{{$box->mp_id}}" {{$box->mp_status == 1 ? 'disabled' : ''}}> <i class="far fa-edit"></i> แก้ไข </button>
                                        <button  type="button" class="btn btn-danger delete_pallet" style="color:white;cursor: pointer;" atr="{{$box->mp_id}}" {{$box->mp_status == 1 ? 'disabled' : ''}}> <i class="far fa-trash-alt"></i> ลบ </button>  
                                    </td> 
                                </tr>
                                @php
                                   $boxs2 = DB::table('tb_pallet')
                                        ->select('tb_boxs.*','tb_typepalate.*','tb_subitems.*','tb_items.*','tb_pallet.*')
                                        // ->select('tb_boxs.bo_item')
                                        ->leftjoin('tb_boxs','tb_pallet.tpl_bo_id','=','tb_boxs.bo_id')
                                        ->leftjoin('tb_items','tb_boxs.bo_item','=','tb_items.it_name')
                                        ->leftjoin('tb_subitems','tb_items.it_id','=','tb_subitems.sit_it_id')
                                        ->leftjoin('tb_typepalate','tb_subitems.sit_pallet','=','tb_typepalate.tp_id')
                                        // ->whereIn('bo_id', explode(',',$comma_separated))
                                        ->groupBy('tpl_id')
                                        // ->distinct('bo_item')
                                        ->where('tpl_pd_id','=',$id)
                                        ->where('tpl_mp_id',$box->mp_id)
                                        ->where('st_c_id',$box->bo_ct_id)
                                        ->where('tpl_qty',"<>",0)
                                        // ->orderBy('bo_so','asc')
                                        ->get();
                                
                                // dd ($boxs2,$id,$box->mp_id,$box->bo_ct_id,0);
                                // $boxs2 = DB::table('tb_boxs')
                                //         ->select('tb_boxs.*','tb_typepalate.*','tb_subitems.*','tb_items.*','tb_pallet.*')
                                //         // ->select('tb_boxs.bo_item')
                                //         ->leftjoin('tb_items','tb_boxs.bo_item','=','tb_items.it_name')
                                //         ->leftjoin('tb_subitems','tb_items.it_id','=','tb_subitems.sit_it_id')
                                //         ->leftjoin('tb_typepalate','tb_subitems.sit_pallet','=','tb_typepalate.tp_id')
                                //         ->leftjoin('tb_pallet','tb_boxs.bo_id','=','tb_pallet.tpl_bo_id')
                                //         // ->whereIn('bo_id', explode(',',$comma_separated))
                                //         // ->groupBy('bo_id')
                                //         // ->distinct('bo_item')
                                //         ->where('tpl_pd_id','=',$id)
                                //         ->where('tp_id',$box->tp_id)
                                //         ->orderBy('bo_so','asc')
                                //         ->get();
                               
                             
                                $id2 = array();
                                foreach ($boxs2 as $key => $box2) {
                                $id2[] = $box2->bo_item;
                                }
                               
                                $id_implode = implode(',',array_unique($id2));
                             
                                $boxs3 = DB::table('tb_pallet')
                                            ->select(DB::raw('sum(tpl_sum_qty) as sum_box'),'tb_boxs.*','tb_typepalate.*','tb_subitems.*','tb_items.*','tb_pallet.*')
                                            ->leftjoin('tb_boxs','tb_pallet.tpl_bo_id','=','tb_boxs.bo_id')
                                            ->leftjoin('tb_items','tb_boxs.bo_item','=','tb_items.it_name')
                                            ->leftjoin('tb_subitems','tb_items.it_id','=','tb_subitems.sit_it_id')
                                            ->leftjoin('tb_typepalate','tb_subitems.sit_pallet','=','tb_typepalate.tp_id')
                                            // ->whereIn('bo_id', explode(',',$comma_separated))
                                            ->whereIn('bo_item', explode(',',$id_implode))
                                            ->groupBy('bo_item','bo_so','bo_fullfill_from')
                                            // ->groupBy('bo_item')
                                            // ->distinct('bo_item')
                                            ->where('bo_pd_id','=',$id)
                                            // ->where('tp_id',$box->tp_id)
                                            ->where('tpl_status','<>',1)   
                                            ->orderBy('bo_so','asc')
                                            ->get();
                                // $boxs3 = DB::table('tb_pallet')
                                //         ->select('tb_boxs.*','tb_typepalate.*','tb_subitems.*','tb_items.*','tb_pallet.*')
                                //         // ->select('tb_boxs.bo_item')
                                //         ->leftjoin('tb_boxs','tb_pallet.tpl_bo_id','=','tb_boxs.bo_id')
                                //         ->leftjoin('tb_items','tb_boxs.bo_item','=','tb_items.it_name')
                                //         ->leftjoin('tb_subitems','tb_items.it_id','=','tb_subitems.sit_it_id')
                                //         ->leftjoin('tb_typepalate','tb_subitems.sit_pallet','=','tb_typepalate.tp_id')
                                //         // ->whereIn('bo_id', explode(',',$comma_separated))
                                //         // ->groupBy('bo_so')
                                //         // ->distinct('bo_item')
                                //         ->where('tpl_pd_id','=',$id)
                                //         ->where('tpl_mp_id',$box->tpl_mp_id)
                                //         // ->orderBy('bo_so','asc')
                                //         ->get();
                                        
                                $boxs4 = DB::table('tb_subitems')
                                            // ->select('tb_items.*','tb_subitems.*','tb_boxs.*')
                                            ->leftjoin('tb_items','tb_subitems.sit_it_id','=','tb_items.it_id')
                                            ->leftjoin('tb_typepalate','tb_subitems.sit_pallet','=','tb_typepalate.tp_id')
                                            ->whereIn('it_name', explode(',',$id_implode))
                                            ->groupBy('sit_it_id')
                                            ->get();

                                $total_pallet3 = 0;   
                                // echo $box->mp_qty/($box->mp_qty_main/$box->mp_pallet_qty_main);
                                $amout_pallet += ceil($box->mp_qty/($box->mp_qty_main/$box->mp_pallet_qty_main));       
                            

                            // echo "<pre>";
                            //     print_r($boxs3);
                            // dd($boxs2);
                            @endphp
                        
                                @foreach ($boxs2 as $key3 => $box3)
                                    @php
                                        $pallet_item = DB::table('tb_pallet')->leftjoin('tb_mainpallet','tb_mainpallet.mp_id','=','tb_pallet.tpl_mp_id')->where('tpl_bo_id',$box3->bo_id)->first();
                                        // print_r($pallet_item);
                                    @endphp
                                    
                                    <tr class="sorting_tr">   
                                        <td class="text-center" style="vertical-align: middle;">    {{++$key3}}</td>
                                        <td class="text-left">
                                             {{ $box3->bo_so }} ({{substr($box3->bo_fullfill_from,0,1)}}) 
                                            <br> 
                                            <span class="text-success">{{ $box->sit_cartonperlayer }} ชั้น x {{ $box->sit_cartonlayer }} = {{ $box->sit_palletvolume }} </span> 
                                        </td>
                                        <td class="text-left">
                                            {{ $box3->bo_item }} ({{ $box3->bo_revision }})
                                            <br> 
                                            <span class="text-success">  
                                                {{ floor(($box3->tpl_qty) /  $box->sit_cartonlayer)  }} ชั้น กับอีก 
                                                {{  ($box3->tpl_qty) - floor(($box3->tpl_qty) / $box->sit_cartonlayer) * $box->sit_cartonlayer  }}    กล่อง  
                                            </span> 
                                        </td>
                                        <td class="text-center">
                                                {{ $box3->bo_cus_item }}
                                                <br>
                                                <span class="text-success"> Height :  {{  $box->tp_hieght }} </span> 
                                            </td>
                                            <td class="text-left">
                                                {{ $box3->bo_size_mm }}
                                                <br>
                                                <span class="text-success"> [{{  number_format($box->sit_netweight/$box3->bo_pack_qty,3,'.',',') }} -  {{  number_format($box->sit_grossweight/$box3->bo_pack_qty,3,'.',',')  }}]</span> 
                                            </td>
                                            <td class="text-left">
                                                {{ $box3->bo_cus_spec }}
                                                <br>
                                                <span class="text-success"> [{{  number_format($box->sit_netweight,3,'.',',') }} -  {{  number_format($box->sit_grossweight,3,'.',',')  }}]</span> 
                                            </td>
                                        <td class="text-center">{{ $box3->bo_pack_qty }}</td>
                                        <td class="text-center">{{ ceil($box3->tpl_qty)  }}</td> 
                                        <td class="text-center">
                                                @if ($key_check2 == 0)
                                                    {{ "1 - ".ceil($box3->tpl_qty) }}
                                                @else
                                                    @php
                                                   
                                                    $value_max = (int)$total_pallet+(int)ceil($box3->tpl_qty);
                                                    $value_min = $total_pallet+1;
                                                    @endphp
                                                    {{ $value_min.' - '.$value_max  }}
                                                @endif
                                        </td>
                                        <td class="text-center">
                                            {{-- {{ ceil($box3->tpl_qty) * $box3->bo_pack_qty }} --}}
                                            {{ ceil($box3->tpl_qty * $box3->bo_pack_qty) }}
                                        </td>
                                        <td class="text-center">{{  number_format((ceil($box3->tpl_qty))*$box->sit_netweight ,2,".",",") }}</td>
                                        <td class="text-center">{{  number_format((ceil($box3->tpl_qty))*$box->sit_grossweight ,2,".",",") }}</td>
                                         <td class="text-center"> 
                                            <button type="button" class="btn btn-danger delete_box" style="color:white;cursor:pointer;"  {{$box->mp_status == 1 ? 'disabled' : ''}} atr="{{$box->mp_id}}"  atr2="{{$box3->bo_id}}"><i class="fas fa-times"></i></button>
                                        </td>
                                    </tr>
                                    @php
                                        $total_pallet +=  ceil($box3->tpl_qty);
                                        $total_pallet2 +=  ceil($box3->tpl_qty);
                                        $total_pallet3 +=  ceil($box3->tpl_qty);
                                        $total_sum_box +=  ceil($box3->tpl_qty) * $box3->bo_pack_qty;
                                        $total_sum_box2 +=  ceil($box3->tpl_qty) * $box3->bo_pack_qty;
                                        $total_netweight2 +=  (ceil($box3->tpl_qty))*$box->sit_netweight;
                                        $total_grossweight2 += (ceil($box3->tpl_qty))*$box->sit_grossweight+($amout_pallet2*$box->tp_weight);
                                        $total_netweight +=  (ceil($box3->tpl_qty))*$box->sit_netweight;
                                        $total_grossweight += (ceil($box3->tpl_qty))*$box->sit_grossweight+($amout_pallet2*$box->tp_weight);
                                        $key_check += 1;
                                        $count_key++;
                                        $key_check2 += 1;
                                    @endphp
                                @endforeach
                                <tr>
                                        <td class="text-right" colspan='7'>[{{ $box->mp_pallet_qty }}] x {{ $total_pallet3 }} </b> </td>
                                        <td class="text-center">{{ $total_pallet3 }}</td>
                                        <td class="text-center"> </td>
                                        <td class="text-center"> {{  number_format($total_sum_box,0,".",",")  }}</td>
                                        <td class="text-center">{{  number_format($total_netweight,2,".",",") }}</td>
                                        <td class="text-center">{{ number_format($total_grossweight,2,".",",") }}</td>
                                        <td class="text-center">{{$total_pallet*$box->sit_cbm}}</td>
                                    </tr>
                                    <input type="hidden" name="pallet_select[]" id="pallet_select[]" value="{{$box->mp_status != 1 ? $box->mp_id : '' }}">
                                    <input type="hidden" name="pallet_qty[]" id="pallet_qty[]" value="{{$box->mp_pallet_qty}}">
                                    <input type="hidden" name="item_qty[]" id="item_qty[]" value="{{$total_pallet3}}">
                                @php
                                    $count_qty_pallet += $box->mp_pallet_qty;
                                @endphp
                            @endforeach
                            <tr style="background-color:#8b7eef ;color:white;">
                                    <td class="text-right" colspan='7'> 
                                        
                                        รวมทั้งหมด  {{ $amout_pallet }} พาเลท
                                        <br>
                                        รวมน้ำหนัก {{  number_format($total_grossweight2/1000,2,".",",")  }} ตัน
                                        <input type="hidden" name="weight_total" id="weight_total" value="{{ $total_grossweight2/1000}}">
                                        <input type="hidden" name="id" id="id" value="{{ $id }}">
                                        <input type="hidden" name="amout_pallet" id="amout_pallet" value="{{ $amout_pallet }}">
                                        <input type="hidden" name="type" id="type" value="{{ $type }}">
                                        {{-- <input type="hidden" name="comma_separated" id="comma_separated" value="{{  $comma_separated }}"> --}}
                                    </td>
                                    <td class="text-center">{{ number_format($total_pallet2,0,".",",")}}</td>
                                    <td class="text-center"></td>
                                    <td class="text-center">{{ number_format($total_sum_box2,0,".",",") }}</td>
                                    <td class="text-center">{{ number_format($total_netweight2,2,".",",") }}</td>
                                    <td class="text-center">{{ number_format($total_grossweight2,2,".",",") }} </td>
                                    <td class="text-center"></td>
                                    
                                </tr>
                               
                        </tbody>
                    </table>
                </div>
                
                </form>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="exampleModalLabel">แก้ไข Pallet</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <form action="{{url('change_box')}}" method="post">
                @csrf
                <div class="modal-body">
                    <div class="row">
                        <div class="col-6 text-left">
                            <label for="sel_location"> Location Selected :</label>
                            <select name="sel_location" id="sel_location" class="form-control" required>
                                
                            </select>
                        </div>
                        <div class="col-6 text-right">
                            <label class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input mr10" name="type_edit" id="type_edit" value="1">
                                <span class="custom-control-indicator"></span>
                                <br>
                                <span class="custom-control-description"> Return to Pallet</span>
                            </label>      
                        </div>
                    </div>
                    <br>
                    <table id="tblOne" class="table table-bordered table-hover table-stiped">
                        <thead>
                            <tr>
                                <td class="text-center">#</td>
                                <td class="text-center">Item</td>
                                <td class="text-center">FG ID</td>
                                <td class="text-center">ชิ้น / กล่อง</td>
                                <td class="text-center">กล่อง</td>
                              
                                <td class="text-center">จำนวนชิ้น</td>
                                <td class="text-center" width="15%">Change to</td>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
                <div class="modal-footer">
                    <input type="hidden" name="pd_id" id="pd_id" value="{{$id}}">
                    <input type="hidden" name="type" id="type" value="{{$type}}">
                    <input type="hidden" name="mp_id" id="mp_id" value="">
                    <button type="submit" class="btn btn-primary">Save changes</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </form>
          </div>
        </div>
      </div>

      <div class="modal fade" id="exampleModal2" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="exampleModalLabel">Preview Auto Load Container</h5>
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
<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.11.4/jquery-ui.js"></script> 
<script>
$("#add_box").click(function(){
    var formdata = $('#form_loadpreview').serialize();
    $.ajax({
        url:"{{ url('preview_auto') }}",
        type: 'POST',
        data:formdata,
    }).done(function(data){
        $("#exampleModal2").modal();
        console.log(data);
        $(".show_load").html(data);
       
    });
   
});

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

$(".edit_pallet").click(function(){
    var id = $(this).attr('atr');
    $.ajax({
        url:"{{url('show_pallet')}}/"+id,
        type:"get",
    }).done(function(data){
        if(data.mainpallet.mp_location === 1){
            var selected_loca = "selected";
            var selected_loca2 = "";
        }else if(data.mainpallet.mp_location === 2){
            var selected_loca = "";
            var selected_loca2 = "selected";
        }else{
            var selected_loca = "";
            var selected_loca2 = "";
        }
        console.log(data);
        $("#sel_location").html('<option value="">----- โปรดเลือก Location -----</option>'+
                '<option value="1" '+ selected_loca   +'>ROJ</option>'+
                '<option value="2" '+ selected_loca2  +'>PIN</option>');
        var table = $('#tblOne');
        var res='';
        var res2='';
        var sum1=0;
        var sum2=0;
        $.each( data.pallet, function( key, value ) {
            sum1 += parseInt(value.tpl_qty);
            sum2 += parseInt(value.tpl_sum_qty);
            key += 1;
            res +=
            '<tr>'+
                '<td  class="text-center">'+key+'</td>'+
                '<td class="text-center">'+value.bo_item+'</td>'+
                '<td class="text-center">'+value.bo_so+'</td>'+
                '<td class="text-center">'+value.bo_pack_qty+'</td>'+
                '<td class="text-center">'+value.tpl_qty+'</td>'+
                '<td class="text-center"><input type="number" name="qty_old[]" id="qty " value="'+value.tpl_sum_qty+'" readonly/></td>'+
                '<td class="text-center"><input type="number" name="qty[]" id="qty " value="" size="5" min="1" max="'+value.tpl_sum_qty+'" /></td>'+
                '<input type="hidden" name="tpl_id[]" id="tpl_id " value="'+value.tpl_id+'"/>'+
            '</tr>';
       
        });
        res +=
            '<tr>'+
                '<td class="text-center" colspan="4">รวม</td>'+
                '<td class="text-center">'+sum1+'</td>'+
                '<td class="text-center">'+sum2+'</td>'+
                // '<td class="text-center"><a href="{{url("editpallet")}}/'+data.mainpallet.mp_id+'/'+data.mainpallet.mp_pd_id+'" class="btn btn-warning" style="color:white;">เพิ่มกล่อง</a> </td>'+
                '<td class="text-center"></td>'+
            '</tr>';
        // console.log(data.mainpallet.mp_id);   
        $("#mp_id").val(data.mainpallet.mp_id);
        var test = $('#tblOne > tbody:last').html(res);
        $("#exampleModal").modal('show');
    });
});


$(".delete_pallet").click(function(){
    var id = $(this).attr('atr');
    var token = $("input[name=_token]").val();
            swal({
                title: "คำเดือน",
                text: "คุณต้องการลบข้อมูล Pallet นี้ ใช่หรือไม่?",
                icon: "warning",
                buttons: true,
                dangerMode: true,
            }).then((willDelete) => {
                if (willDelete) {
                    $.ajax({
                        url: "{{url('delete_pallet')}}",
                        method: "post",
                        data: {
                            _method: "post",
                            _token: token,
                            id: id
                        }
                    }).done(function (data) {
                        if (data == 1) {
                            swal({
                                title: "เรียบร้อย",
                                text: "ลบข้อมูล Pallet เรียบร้อย!!",
                                type: "success",
                                icon: "success",
                                timer: 3000,
                                showConfirmButton: false
                            }).then((willDelete) => {
                                window.location.reload();
                            });

                        }
                    });
                }else{
                    swal({
                        title: "ยกเลิก",
                        text: "ยกเลิกการลบข้อมูล Pallet",
                        type: "error",
                        icon: "error",
                        timer: 3000,
                        showConfirmButton: false
                    }).then((willDelete) => {
                               
                    });
                }
            });
});

$(".delete_box").click(function(){
    var id = $(this).attr('atr');
    var id2 = $(this).attr('atr2');
    var token = $("input[name=_token]").val();
            swal({
                title: "คำเดือน",
                text: "คุณต้องการลบข้อมูล  Box นี้ ใช่หรือไม่?",
                icon: "warning",
                buttons: true,
                dangerMode: true,
            }).then((willDelete) => {
                if (willDelete) {
                    $.ajax({
                        url: "{{url('delete_box')}}",
                        method: "post",
                        data: {
                            _method: "post",
                            _token: token,
                            id: id,
                            id2: id2
                        }
                    }).done(function (data) {
                        if (data == 1) {
                            swal({
                                title: "เรียบร้อย",
                                text: "ลบข้อมูล Box เรียบร้อย!!",
                                type: "success",
                                icon: "success",
                                timer: 3000,
                                showConfirmButton: false
                            }).then((willDelete) => {
                                window.location.reload();
                            });

                        }
                    });
                }else{
                    swal({
                        title: "ยกเลิก",
                        text: "ยกเลิกการลบข้อมูล Box",
                        type: "error",
                        icon: "error",
                        timer: 3000,
                        showConfirmButton: false
                    }).then((willDelete) => {
                               
                    });
                }
            });
});
</script>
<script>
    // $('tbody').sortable();
    </script>
@endsection
