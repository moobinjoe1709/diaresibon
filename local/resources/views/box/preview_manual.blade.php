
<div class="row">
    <div class="col-md-12">
        <div class="card">
            {{-- <div class="card-header">
                <div class="row">
                    <div class="col-6">
                        Preview Manual Load Container
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
                <form action="{{url('manualload')}}" method="post">
                    @csrf
                    <input type="hidden" name="id" value="{{$id}}">
                    <div class="row">
                        <div class="col-md-3 col-12">
                            <select name="container_size" id="container_size" class="form-control" readonly required>
                                <option value="">------- โปรดเลือกขนาดตู้คอนเทรนเนอร์ --------</option>
                                <option value="20" {{$container_size == 20 ? 'selected' : ''}}>20 ตัน</option>
                                <option value="40" {{$container_size == 40 ? 'selected' : ''}}>40 ตัน</option>
                            </select>
                        </div>
                        <div class="col-md-3 col-12">
                            <select name="location" id="location" class="form-control" readonly required>
                                <option value="">------- โปรดเลือกสาขาที่ขึ้นตู้ --------</option>
                                <option value="1" {{$location == 1 ? 'selected' : ''}}>ROJ</option>
                                <option value="2" {{$location == 2 ? 'selected' : ''}}>PIN</option>
                            </select>
                        </div>
                        <div class="col-md-2 col-12">
                            <input type="text" name="weight_over" id="weight_over" class="form-control" placeholder="+- น้ำหนักส่วนเกิน" value="{{$weight_over}}" readonly>
                        </div>
                        <div class="col-md-2 col-12">
                            <input type="text" name="pallet_qty_sel" id="pallet_qty_sel" class="form-control" placeholder="จำนวน Pallet"  value="{{$pallet_qty_sel}}" readonly>
                        </div>
                        <div class="col-md-2 text-right">
                            <button type="submit" class="btn btn-primary" {{Session::get('cart') != null ? '' : 'disabled'}}>Load Container</button>
                        </div>
                    </div>
                    <hr>
                    <div class="col-md-12 col-12">
                        <div class="table-responsive">
                            
                           
                            @foreach ($container as $key => $val)
                            {{-- {{ print_r($val['pallet_id'])}} --}}
                            {{-- {{dd($container,$pallet_weight)}} --}}
                            <div class="row">
                                <div class="col-6 text-left">
                                    <b>Container  No :  </b>   {{$key}} ( Pallet : {{count($val['pallet_id'])}} ea)
                                    <br>
                                    <b>Size Continer :  </b>   {{$val['pallet_max'] * 1000}} KK. (Use : {{$val['use']}} KK.)

                                </div>

                                <div class="col-6 text-right">
                                    <b>Date load :  </b>  {{thai_date_fullmonth2(date("Y-m-d"))}}
                                </div>
                            </div>
                            <table class="table table-bordered table-hover" id="product1">
                                <thead>
                                    <tr class="text-center">
                                        <th class="text-center">#</th>
                                        <th class="text-center">FG ID</th>
                                        <th class="text-center">Part No</th>
                                        <th class="text-center">Description <br /> [Net, Gross]/pcs</th>
                                        <th class="text-center">Spec <br /> [Net, Gross]/pcs</th>
                                        <th class="text-center">PCS/CTN</th>
                                        <th class="text-center">L</th>
                                        {{-- <th class="text-center">C/N 1-UP</th> --}}
                                        <th class="text-center">TOTAL</th>
                                        <th class="text-center">NET Weight</th>
                                        <th class="text-center">Gross Weight</th>
                                    </tr>
                                </thead>
                                <tbody>
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
                                        //  echo $no;
                                        //  echo "<pre>";
                                        //  print_r($array);
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
                                                <td class="text-center">
                                                    {{$pallet->bo_so}}
                                               
                                                </td>
                                                <td class="text-left">
                                                    {{$pallet->bo_item}}
                                                </td>
                                                <td class="text-left">
                                                    {{ $pallet->bo_cus_item }}
                                                </td>
                                                <td class="text-left"> {{ $pallet->bo_size_mm }} <br /> </td>
                                                <td class="text-center"> {{ $pallet->bo_cus_spec }} <br /></td>
                                                <td class="text-center"> {{ $pallet->bo_pack_qty }} </td>
                                                <td class="text-center">{{ceil($value / $mainpallet->sit_netweight)}} </td>
                                                {{-- <td class="text-center">C/N 1-UP</td> --}}
                                                <td class="text-center">{{ ceil($pallet->bo_pack_qty *($value / $mainpallet->sit_netweight)) }}</td>
                                                <td class="text-center">{{$value}}</td>
                                                <td class="text-center">{{ ($value / $mainpallet->sit_netweight) *  $mainpallet->sit_grossweight}}</td>
                                            </tr>
                                            @php
                                                ++$check_lap2;
                                                $qty_box            +=  $value / $mainpallet->sit_netweight; //จำนวน กล่อง รวม
                                                $qty_item           +=  ($pallet->bo_pack_qty *($value / $mainpallet->sit_netweight)); //จำนวน ชิ้น รวม
                                                $netweight_sum      +=     ($value / $mainpallet->sit_netweight) * $mainpallet->sit_netweight; //netweightรวม
                                                $grossweight_sum    += ($value / $mainpallet->sit_netweight) *  $mainpallet->sit_grossweight ; //grossweight รวม
                                            @endphp
                                        @endforeach
                                            @if ($count_array > 1)
                                                <tr>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td class="text-center"></td>
                                                    <td class="text-center">{{ceil($qty_box)}}</td>
                                                    <td class="text-center">{{$qty_item}}</td>
                                                    <td class="text-center">{{$netweight_sum}}</td>
                                                    <td class="text-center">{{$grossweight_sum}}</td>
                                                </tr>
                                            @endif
                                            <tr>
                                                <td colspan="10"></td>
                                            </tr>
                                        @php
                                            ++$check_lap;
                                            $qty_box_all            += $qty_box; //จำนวนกล่อง รวมทั้งหมด
                                            $qty_item_all           += $qty_item; //จำนวนชิ้น รวมทั้งหมด
                                            $netweight_sum_all      += $netweight_sum; //netweight รวมทั้งหมด
                                            // echo $netweight_sum."<br/>";
                                            $grossweight_sum_all    += $grossweight_sum; //grossweight รวมทั้งหมด
                                        @endphp
                                    @endforeach
                                    <tr>
                                        <td  colspan="10" style="padding: 1.5rem;"></td>

                                    </tr>
                                    <tr>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td class="text-center">Total</td>
                                        <td class="text-center">{{ceil($qty_box_all)}}</td>
                                        <td class="text-center">{{$qty_item_all}}</td>
                                        <td class="text-center">{{$netweight_sum_all}}</td>
                                        <td class="text-center">{{$grossweight_sum_all}}</td>
                                    </tr>
                                </tbody>
                            </table>  
                            <hr>
                            @endforeach
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

