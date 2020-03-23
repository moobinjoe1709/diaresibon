<form action="{{ url('outstandardload') }}" method="POST">

    @csrf
    <h5>
        Pallet ที่เลือก คือ {{ $boxs[0]->tp_width.'x'.$boxs[0]->tp_length.'x'.$boxs[0]->tp_hieght }}
    </h5>
    {{-- <p>มาตรฐานความสูง Pallet: <input type="text" size="3" name="height_pallet" id="height_pallet"
            value="{{ $boxs[0]->tp_width }}">
        Cm</p> --}}

    <div class="table-responsive">
        <table class="table table-bordered table-hover" id="product1">
            <thead>
                <tr class="text-center">
                    <th class="text-center">#</th>
                    <th class="text-center">Item</th>
                    <th class="text-center">Layer Q'ty</th>
                    <th class="text-center">Q'ty</th>
                    <th class="text-center">Pcs Q'ty</th>
                    <th class="text-center">Change</th>
                    <th class="text-center">Space</th>
                    <th class="text-center">Weight</th>
                    <th class="text-center">Width</th>
                    <th class="text-center">Length</th>
                    <th class="text-center">Height</th>
                    <th class="text-center">Area</th>
                    <th class="text-center">Standard</th>
                    <th class="text-center">Status</th>
                    <th class="text-center">Size</th>
                    <th class="text-center">Load</th>
                </tr>
            </thead>
            <tbody>
                @php
                $key_check = 1;
                $sum_box1 =0;
                $sum_box2 = 0;
                $count_key = 0;
                @endphp
                @foreach ($boxs as $key => $box)
                @php
                $boxs2 = DB::table('tb_boxs')
                ->select('tb_boxs.*','tb_typepalate.*','tb_subitems.*','tb_items.*')
                // ->select('tb_boxs.bo_item')
                ->leftjoin('tb_items','tb_boxs.bo_item','=','tb_items.it_name')
                ->leftjoin('tb_subitems','tb_items.it_id','=','tb_subitems.sit_it_id')
                ->leftjoin('tb_typepalate','tb_subitems.sit_pallet','=','tb_typepalate.tp_id')
                ->whereIn('bo_id', explode(',',$item))
                // ->groupBy('bo_id')
                // ->distinct('bo_item')
                ->where('bo_pd_id','=',$id)
                ->where('tp_id','=',$box->tp_id)
                // ->where('sit_typepallet','=',2)
                ->orderBy('bo_so','asc')
                ->get();
        


                $id2 = array();
                foreach ($boxs2 as $key => $box2) {
                $id2[] = $box2->bo_item;
                }

                $id_implode = implode(',',array_unique($id2));
               
                $boxs3 = DB::table('tb_boxs')
                // ->select(DB::raw('sum(bo_order_qty_sum) as sum_box'),'tb_boxs.*')
                ->whereIn('bo_id', explode(',',$item))
                ->whereIn('bo_item', explode(',',$id_implode))
                // ->groupBy('bo_item')
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
                @endphp
                @foreach ($boxs3 as $key3 => $box3)
                @php
                $amout_pallet2 = 0;

                $boxs4_2 = DB::table('tb_subitems')
                    ->leftjoin('tb_items','tb_subitems.sit_it_id','=','tb_items.it_id')
                    ->leftjoin('tb_typepalate','tb_subitems.sit_pallet','=','tb_typepalate.tp_id')
                    ->where('it_name','=',$box3->bo_item)
                    ->first();

                   
                @endphp
                <tr>
                    <td class="text-center">{{  $key_check }}</td>
                    <td class="text-left">
                        {{ $box3->bo_fullfill_from}} : {{ $box3->bo_item }} ()
                        <br>
                        <span class="text-success">pcs/ctn : {{ $box3->bo_pack_qty }}</span>
                    </td>
                    <td class="text-center">
                     
                        @if (($box3->bo_order_qty_sum/$box3->bo_pack_qty) / $boxs4_2->sit_cartonlayer < 1 && ($box3->bo_order_qty_sum/$box3->bo_pack_qty) / $boxs4_2->sit_cartonlayer > 0) 
                            @php 
                                $floor_pallet = 1; 
                                echo $amout_pallet2=1; 
                            @endphp 
                        
                        @elseif(($box3->bo_order_qty_sum/$box3->bo_pack_qty) / $boxs4_2->sit_cartonlayer == 0)
                            @php 
                                 $floor_pallet = 0; 
                                echo $amout_pallet2=0; 
                            @endphp 
                            
                        @else 
                            @php
                                $floor_pallet = floor(($box3->bo_order_qty_sum/$box3->bo_pack_qty) / $boxs4_2->sit_cartonlayer);
                                $amout_pallet2 += floor(($box3->bo_order_qty_sum/$box3->bo_pack_qty) / $boxs4_2->sit_cartonlayer);
                                echo $amout_pallet2;
                            @endphp
                            {{-- {{ floor(($box3->sum_box/$box3->bo_pack_qty) /  $boxs4_2->sit_cartonlayer)  }} --}}
                            {{-- {{ ($box3->sum_box/$box3->bo_pack_qty) - floor(($box3->sum_box/$box3->bo_pack_qty) / $boxs4_2->sit_cartonlayer) * $boxs4_2->sit_cartonlayer }} --}}
                           
                        @endif
                            ชั้น
                            <br>
                            <div class="progress">
                                @php
                                $per = ($floor_pallet *100) /$boxs4_2->sit_cartonlayer;
                                if($per > 100){
                                $per2 = 100;
                                }else{
                                $per2 = $per;
                                }
                                @endphp
                                <div class="progress-bar bg-success border-success progress-bar-striped progress-bar-animated"
                                    role="progressbar"
                                    aria-valuenow="{{ $boxs4_2->sit_cartonperlayer }}"
                                    aria-valuemin="0" aria-valuemax="100" style="width:{{ $per2 }}%">
                                </div>
                            </div>
                    </td>
                    <td class="text-center">
                            <input type="text"  class="form-control" name="sum_box[]" id="sum_box{{$key3}}" size="3"
                            value="{{ ceil($box3->bo_order_qty_sum/$box3->bo_pack_qty) }}" readonly>
                    </td>
                    <td class="text-center">
                        <input type="text" class="form-control" name="sum_boxs[]" id="sum_boxs{{$key3}}" size="3"
                            value="{{ $box3->bo_order_qty_sum }}" readonly>
                    </td>
                    <td><input type="text" class="form-control" name="qty[]" min="1" max="{{ ceil($box3->bo_order_qty_sum/$box3->bo_pack_qty) }}" id="qty{{$key3}}" size="3"
                        value=""    >
                    </td>
                    <td class="text-center">
                        @php

                        $pallet_frag = (ceil($box3->bo_order_qty_sum/$box3->bo_pack_qty)) - (
                        floor(($box3->bo_order_qty_sum/$box3->bo_pack_qty) / $boxs4_2->sit_cartonlayer)
                        * $boxs4_2->sit_cartonlayer) ;

                        if($pallet_frag != 0){
                        $check_zero = 1;
                        echo abs(($amout_pallet2 * $boxs4_2->sit_cartonlayer) -
                        $box3->bo_order_qty_sum/$box3->bo_pack_qty);
                        // echo $boxs4[0]->sit_cartonlayer - $pallet_frag;
                        }else{
                        $check_zero = 0;
                        echo 0;
                        }

                        @endphp

                    </td>
                    <td class="text-center">{{  number_format($boxs4_2->sit_netweight ,3,".",",") }}
                    </td>
                    <td class="text-center">{{  number_format($boxs4_2->sit_cartonwidth ,4,".",",") }}
                    </td>
                    <td class="text-center">{{  number_format($boxs4_2->sit_cartonlenght ,4,".",",") }}
                    </td>
                    <td class="text-center">
                        @if (($box3->bo_order_qty_sum/$box3->bo_pack_qty) / $boxs4_2->sit_cartonlayer < 1) 
                            {{ number_format($boxs4_2->sit_cartonheigh ,2,".",",") * 1 }} 
                        @else
                            {{ number_format($boxs4_2->sit_cartonheigh ,2,".",",") * floor(($box3->bo_order_qty_sum/$box3->bo_pack_qty) /  $boxs4_2->sit_cartonlayer)   }}
                        @endif 
                    </td> 
                    <td class="text-center">
                        {{ $boxs4_2->sit_cartonwidth *  $boxs4_2->sit_cartonlenght * $boxs4_2->sit_cartonheigh  }}
                    </td>
                    <td class="text-center"> 
                        {{ $boxs4_2->sit_cartonlayer }} x {{ $boxs4_2->sit_cartonperlayer }} = {{ $boxs4_2->sit_palletvolume }}</td>
                    <td class="text-center">
                        @if ($box3->bo_order_qty_sum/$box3->bo_pack_qty != 0)
                            @if ($check_zero == 0)
                                <span class="text-success">ON</span>
                            @else
                                <span class="text-danger">OFF</span>
                            @endif
                        @else
                            <span class="text-danger">OFF</span>
                        @endif
                       
                    </td>
                    <td class="text-center">{{  $box3->bo_size_mm }}</td>
                    <td class="text-center">
                        <h5>
                            @if (Session::has('layer_sel'))
                                @if ($box3->bo_order_qty_sum/$box3->bo_pack_qty == 0)
                                    <i class="fa fa-times-circle text-danger" aria-hidden="true"></i>
                                @else
                                    <i class="fas fa-arrow-circle-right load_pallet text-success" id="load_pallet" atr4="{{$key3}}" atr3="{{$box3->bo_order_qty_sum/$box3->bo_pack_qty}}" atr2="{{ $check_zero == 0 ? 1 : 0 }}" atr="{{$box3->bo_id}}" value-box="{{$box3->bo_id}}"></i>
                                @endif
                            @else 
                                <i class="fa fa-times-circle text-danger" aria-hidden="true"></i>
                            @endif
                        </h5>
                    </td>


                    @php
                    $sum_box1 += ceil($box3->bo_order_qty_sum/$box3->bo_pack_qty);
                    $sum_box2 += $box3->bo_order_qty_sum;
                    $key_check += 1;
                    @endphp
                </tr>
                @endforeach

                @php
                $count_key++;
                @endphp
                @endforeach
                {{-- {{ print_r($height_pallet) }} --}}
                <tr>
                    <td class="text-left" colspan='3'></td>
                    <td class="text-center">{{ $sum_box1 }}</td>
                    <td class="text-center">{{ $sum_box2 }}</td>
                    <td class="text-center" colspan='10'></td>
                </tr>
            </tbody>
        </table>
    </div>
</form>

<div class="modal" id="myModal">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
        
                <!-- Modal Header -->
                <div class="modal-header"> 
                <h4 class="modal-title">Overview Pallet</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <form action="{{ url('loadpalletman') }}" method="POST">
                        @csrf
                <!-- Modal body -->
                <div class="modal-body">
                    <h5><b>Pallet Size : </b> {{ $boxs[0]->tp_width.'x'.$boxs[0]->tp_length.'x'.$boxs[0]->tp_hieght }} ({{ $boxs[0]->tp_weight.'kgs/PLT' }})</h5>
                    <br>
                    <table class="table table-bordered table-hover" id="product1">
                            <thead>
                                <tr class="text-center">
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
                                </tr>
                                {{-- {{dd(Session::get('cart'))}} --}}
                                @php
                                    $total_pallet = 0;
                                    $sum_height = 0;
                                @endphp
                                @if (Session::get('cart') != null)
                                    
                                
                                    @foreach (Session::get('cart') as $key => $item)
                                    @php

                                    $boxs2 = DB::table('tb_boxs')
                                        ->select('tb_boxs.*','tb_typepalate.*','tb_subitems.*','tb_items.*')
                                        // ->select('tb_boxs.bo_item')
                                        ->leftjoin('tb_items','tb_boxs.bo_item','=','tb_items.it_name')
                                        ->leftjoin('tb_subitems','tb_items.it_id','=','tb_subitems.sit_it_id')
                                        ->leftjoin('tb_typepalate','tb_subitems.sit_pallet','=','tb_typepalate.tp_id')
                                        ->where('bo_id',  $item['id'])
                                        // ->whereIn('bo_id', explode(',',$comma_separated))
                                        // ->groupBy('bo_id')
                                        // // ->distinct('bo_item')
                                        // ->where('bo_pd_id','=',$id)
                                        // ->where('tp_id',$box->tp_id)
                                        // ->orderBy('bo_so','asc')
                                        ->first();
                                    $sum_height += $boxs2->sit_cartonheigh;
                                    @endphp
                                    <tr class="text-center">
                                        <td class="text-center"><input type="checkbox" name="mix_box[]"></td>
                                        <td class="text-center">
                                            {{$boxs2->bo_so}}  ({{ substr($boxs2->bo_fullfill_from,0,1) }})
                                            <br>
                                            <span class="text-success">{{ $boxs2->sit_cartonperlayer }} ชั้น x {{
                                                            $boxs2->sit_cartonlayer }} =
                                                {{ $boxs2->sit_palletvolume }}
                                            </span>
                                        </td>
                                        <td class="text-center"> 
                                            {{ $boxs2->bo_item }} ({{ $boxs2->bo_revision }})
                                        </td>
                        
                                        <td class="text-center">{{$boxs2->bo_cus_item}}</td>
                                        <td class="text-center">
                                        {{ $boxs2->bo_size_mm }}
                                        <br>
                                        <span class="text-success">
                                        [{{number_format($boxs2->sit_netweight/$boxs2->bo_pack_qty,4,'.',',') }}- {{ number_format($boxs2->sit_grossweight/$boxs2->bo_pack_qty,4,'.',',')}}]</span>
                                        </td>
                                        <td class="text-center">{{ $boxs2->bo_cus_spec }}
                                                <br>
                                                <span class="text-success"> [{{
                                                                number_format($boxs2->sit_netweight,2,'.',',') }} - {{
                                                                number_format($boxs2->sit_grossweight,2,'.',',') }}]</span></td>
                                        <td class="text-center">{{ $boxs2->bo_pack_qty }}</td>
                                        <td class="text-center">{{ $item['qty']/$boxs2->bo_pack_qty }}</td>
                                        <td class="text-center">
                                            
                                                @if ($key == 0)
                                                {{ "1 - ".$item['qty']/$boxs2->bo_pack_qty }}
                                                @else
                                                @php
                                                $value_max =(int)$total_pallet+(int)$item['qty']/$boxs2->bo_pack_qty;
                                                $value_min = $total_pallet+1;
                                                @endphp
                                                {{ $value_min.' - '.$value_max }}
                                                @endif
                                        </td>
                                        <td class="text-center">{{ $item['qty'] }}</td>
                                        <td class="text-center">{{ number_format(($item['qty']/$boxs2->bo_pack_qty)*$boxs2->sit_netweight,2,".",",") }}</td>
                                        <td class="text-center">{{number_format(($item['qty']/$boxs2->bo_pack_qty)*$boxs2->sit_grossweight,2,".",",") }}</td>
                                        </tr>
                                        @php
                                        $total_pallet += $item['qty']/$boxs2->bo_pack_qty;
                                        @endphp
                                    @endforeach
                                      
                                @endif
                            </thead>
                    </table>
                </div>
                {{-- {{dd()}} --}}
                <!-- Modal footer -->
                <div class="modal-footer">
                    <input type="hidden" name="id" id="id" value="{{$boxs[0]->bo_pd_id}}">
                    <input type="hidden" name="sel_location" id="sel_location2">
                    <input type="hidden" name="pallet_layer" id="pallet_layer2">
                    <input type="hidden" name="total_pallet" id="total_pallet" value="{{$total_pallet}}">
                    <input type="hidden" name="total_height" value="{{$sum_height+15}}">
                    <button type="submit" class="btn btn-primary" >Load Pallet</button>
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                </div>
                </form>
            </div>
        </div>
    </div>

<script>
    $('.load_pallet').click(function () {
            var id          = $(this).attr('atr');
            var type        = $(this).attr('atr2');
            var qty         = $(this).attr('atr3');
            var key         = $(this).attr('atr4');
            var token       = $('input[name=_token]').val();
            var old_value   = $('#sum_box'+key).val();
            var changevalue   = $('#qty'+key).val();
            var qty2        = 0;
           
            if(changevalue != ""){
                if( parseInt(changevalue) >  parseInt(old_value)){
                    qty2 = old_value;
                }else{
                    qty2 = changevalue;
                }
                   

            }else{
               
                    qty2 = qty;
            }
            
               
         
            // console.log(qty2);
           

            $.ajax({
                url: "{{url('calculate')}}" + '/' + id + '/' + type + '/' + qty2,
                type: "post",
                data: {
                    _method: 'post',
                    _token: token,
                    id: id
                }
            }).done(function (data) {
                // console.log(data);
                $.ajax({
                url: "{{url('showpallet')}}" ,
                type: "get",
                }).done(function (data) {
                    $("#showpallet").html(data);
                });
                var comma_separated = $("input[name=comma_separated]").val();
                var id = $("input[name=id]").val();
                $.ajax({
                    url: "{{url('showtable')}}/"+ comma_separated + '/' + id,
                    type: "get",
                }).done(function (data) {
                  
                    $("#showtable").html(data);
                });
            });
    });
</script>