
<div class="row">
    <div class="col-md-12">
        <div class="card">
            {{-- <div class="card-header">
                <div class="row">
                    <div class="col-6">
                        Preview Atuo Load Container
                    </div>
                    <div class="col-6  text-right">
                        <a class="btn btn-secondary" href="{{ url('ContainerLoad').'/'.$id.'/'.$type}}">กลับ</a>
                    </div>
                </div>
            </div> --}}
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
                <form action="{{url('autoload')}}" method="post">
                    @csrf
                    <div class="row">
                        <div class="col-md-9 col-9">
                            เลือก Container :
                            <select name="selec_con" id="selec_con" disabled>
                                <option value="">----- โปรดเลือก Container -----</option>
                                <option value="20" {{$container_size == 20 ? 'selected' : ''}}>Container 20 ตัน</option>
                                <option value="40" {{$container_size == 40 ? 'selected' : ''}}>Container 40 ตัน</option>
                            </select>
                            <input type="hidden" name="selec_con" value="{{$container_size}}">
                            + - ไม่เกิน
                            <input type="text" name="over_kk" id="over_kk" value="{{$over_kk}}" disabled> 
                            <input type="hidden" name="over_kk" value="{{$over_kk}}">
                            Kg.
                             จำนวน 
                            <input type="text" name="qty_pallet" id="over_qty_palletkk" size="2" value="{{$qty_pallet == 9999 ? '' : $qty_pallet}}" disabled>
                            <input type="hidden" name="qty_pallet" value="{{$qty_pallet == 9999 ? '' : $qty_pallet}}">
                            Pallet
                        </div>
                        <div class="col-md-3 col-3 text-right">
                            <select name="location" id="location" disabled>
                                <option value="">----- โปรดเลือก Location -----</option>
                                <option value="1" {{$location == "1" ? 'selected' : ''}}>ROJ</option>
                                <option value="2" {{$location == "2" ? 'selected' : ''}}>PIN</option>
                            </select>
                            <input type="hidden" name="selec_con" value="{{$location}}">
                        </div>
                        
                        
                    </div>
                    <br>
                    <div class="row">
                        <div class="col-md-12 col-12 text-right">
                            <button style="cursor: pointer;" type="submit" id="add_box" class="btn btn-primary">Load Container</button>
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-md-10 col-10">
                            <h5><b> Load Container ทั้งหมด จำนวน {{$container_qty}} ตู้</b></h5>
                        </div>
                        <div class="col-2 text-right">
                            <b>Date load :  </b>  {{date("Y-m-d")}}
                        </div>
                     </div>
                     <hr>
                     <div class="row">
                        <div class="col-md-12 col-12">
                            <div class="table-responsive">
                                {{-- {{dd($container,$pallet_weight)}} --}}
                                
                                @foreach ($container as $key2 => $val)
                                
                              
                                @php
                                    $check_lap              = 1;
                                    $qty_box_all            = 0; //จำนวนกล่อง รวมทั้งหมด
                                    $qty_item_all           = 0; //จำนวนชิ้น รวมทั้งหมด
                                    $netweight_sum_all      = 0; //netweight รวมทั้งหมด
                                    $grossweight_sum_all    = 0; //grossweight รวมทั้งหมด
                                @endphp
                                    @foreach ($val['pallet_id'] as $no => $item)
                                        @php
                                            $val['container_id'][$no]; //ตำแหน่ง group item
                                            $item; //ตำแหน่ง item ใน group
                                        
                                            $array                 = $pallet_weight[$val['container_id'][$no]][$item]['tpl'];
                                            $check_lap2            = 1;
                                            $count_array           = count($array);
                                            $qty_box               = 0; //จำนวนกล่อง รวมต่อpalet
                                            $qty_item              = 0; //จำนวนชิ้น รวมต่อpalet
                                            $netweight_sum         = 0; //netweight รวมต่อpalet
                                            $grossweight_sum       = 0; //grossweight รวมต่อpalet
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

                                                    ++$check_lap2;
                                                    $qty_box            += ceil($value / $mainpallet->sit_netweight); //จำนวน กล่อง รวม
                                                    $qty_item           +=  ceil($pallet->bo_pack_qty *($value / $mainpallet->sit_netweight)); //จำนวน ชิ้น รวม
                                                    $netweight_sum      +=     $value; //netweightรวม
                                                    $grossweight_sum    += ($value / $mainpallet->sit_netweight) *  $mainpallet->sit_grossweight ; //grossweight รวม
                                                @endphp  
                                            @endforeach
                                        @php
                                            ++$check_lap;
                                            $qty_box_all            += $qty_box; //จำนวนกล่อง รวมทั้งหมด
                                            $qty_item_all           += $qty_item; //จำนวนชิ้น รวมทั้งหมด
                                            $netweight_sum_all      += $netweight_sum; //netweight รวมทั้งหมด
                                            $grossweight_sum_all    += $grossweight_sum; //grossweight รวมทั้งหมด
                                        @endphp
                                    @endforeach
                                    <div class="row">
                                        <div class="col-12 text-left">
                                            <div class="row">
                                                <div class="col-2">
                                                    <b>Container  No :  </b>
                                                </div>
                                                <div class="col-1">
                                                    {{$key2}} 
                                                </div>
                                                <div class="col-3">
                                                   
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-2">
                                                    <b>Pallet QTY : </b>
                                                </div>
                                                <div class="col-1">
                                                    {{count($val['pallet_id'])}} 
                                                </div>
                                                <div class="col-3">
                                                    Pallet
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-2">
                                                    <b>Summary QTY' Box  :  </b>
                                                </div>
                                                <div class="col-1">
                                                    {{$qty_box_all}} 
                                                </div>
                                                <div class="col-3">
                                                    ea
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-2">
                                                    <b>Net Weight :  </b>
                                                </div>
                                                <div class="col-1">
                                                    {{$netweight_sum_all}} 
                                                </div>
                                                <div class="col-3">
                                                    KK.
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <hr>
                                @endforeach
                            </div>
                        </div>
                     </div>
                </form>
            </div>
        </div>
    </div>
</div>

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
        // console.log(data);
        location.reload();
    });
  
});

</script>