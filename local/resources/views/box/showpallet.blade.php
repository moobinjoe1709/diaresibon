<hr>
<form action="{{url('loadpalletman')}}" method="POST">
    @csrf
    <div class="row justify-content-center">
        <div class="col-6">
            <select  id="sel_location" name="sel_location" class="form-control" required>
                <option value="">----- โปรดเลือก Location -----</option>
                <option value="1">ROJ</option>
                <option value="2">PIN</option>
            </select>
        </div>
        <div class="col-6">
            <select  id="pallet_layer" name="pallet_layer"  class="form-control" required>
                <option value="">----- โปรดเลือก Layer Pallet -----</option>
                <option value="1">Max Layer</option>
                <option value="2">Min Layer</option>
            </select>
        </div>
    </div>
    <br>
    <div class="row justify-content-center">
        <div class="col-6 text-center">
            <select id="condition_load" name="condition_load" class="form-control" required>
                <option value="">---- กรุณาเลือกชั้น Pallet -----</option>
                <option value="1" {{  (Session::has('layer_sel') ? (Session::get('layer_sel') == 1 ? 'selected' : ''  ) : '' ) }}>จัดการชั้นที่ 1</option>
                <option value="2" {{  (Session::has('layer_sel') ? (Session::get('layer_sel') == 2 ? 'selected' : ''  ) : '' ) }}>จัดการชั้นที่ 2</option>
                <option value="3" {{  (Session::has('layer_sel') ? (Session::get('layer_sel') == 3 ? 'selected' : ''  ) : '' ) }}>จัดการชั้นที่ 3</option>
                <option value="4" {{  (Session::has('layer_sel') ? (Session::get('layer_sel') == 4 ? 'selected' : ''  ) : '' ) }}>จัดการชั้นที่ 4</option>
                <option value="5" {{  (Session::has('layer_sel') ? (Session::get('layer_sel') == 5 ? 'selected' : ''  ) : '' ) }}>จัดการชั้นที่ 5</option>
                <option value="6" {{  (Session::has('layer_sel') ? (Session::get('layer_sel') == 6 ? 'selected' : ''  ) : '' ) }}>จัดการชั้นที่ 6</option>
            </select>
            <br>
            <button type="button" class="btn btn-success" data-toggle="modal" data-target="#exampleModal">ดู Pallet</button>
            {{-- <button type="button" class="btn btn-success select_layer">เลือกชั้น </button> --}}
        </div>
    </div>
    <br>
    @php
      
        $total_box = 0;
        $total_item = 0;
        $total_grossweight = 0;
        $total_netweight = 0;
    @endphp
    @for ($i = 1; $i <= 6 ; $i++)
    <div class="row">
        <div class="col-12">
            <div class="row">
                <div class="col-6">
                    @if (Session::has('layer_sel'))
                        <h5>จัดการ Pallet ชั้นที่ {{$i}}</h5>
                        
                    @endif
                </div>
            </div>
            <hr>
            
                @if (Session::get('cart'.$i) != null )
                <div class="table-responsive">
                    <table class="table table-bordered table-hover" id="product1">
                        <thead>
                            <tr class="text-center">
                                <th class="text-center" width="15%">#</th>
                                <th class="text-center">Item</th>
                                <th class="text-center">Weight</th>
                                <th class="text-center">Width</th>
                                <th class="text-center">Length</th>
                                <th class="text-center">Height</th>
                                <th class="text-center">Area</th>
                                <th class="text-center">Standard</th>
                                <th class="text-center">Size</th>
                                <th class="text-center">Qty</th>
                                <th class="text-center" width="5%">Delete</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php

                            $count = 0;
                            $sum_count = 0;
                            $sum_count2 = 0;
                            $sum_height = 0;
                            $sum_grossweight = 0;
                            $sum_netweight = 0;
                            // dd(Session::get('cart'));
                            @endphp
                            @foreach (Session::get('cart'.$i) as $key => $item)
                            @php

                            $boxs2 = App\Boxs::where('bo_id',$item['id'])->first();
                            $item2 = App\Items::where('it_name',$boxs2->bo_item)->leftjoin('tb_subitems','tb_items.it_id','=','tb_subitems.sit_it_id')->first();
                            $sum_count += ceil($item['qty']/$boxs2->bo_pack_qty);
                            $sum_count2 += $item['qty'];
                            $sum_height += $item2->sit_cartonheigh;
                            // $sum_grossweight += ($item['qty']/$boxs2->bo_pack_qty)*$item2->sit_grossweight;
                            $sum_grossweight += (ceil($item['qty'])) * ($item2->sit_grossweight/$boxs2->bo_pack_qty);
                            // $sum_netweight += ($item['qty']/$boxs2->bo_pack_qty)*$item2->sit_netweight;
                            $sum_netweight += (ceil($item['qty'])) * ($item2->sit_netweight/$boxs2->bo_pack_qty);
                            @endphp
                            <tr class="text-center">
                                <td class="text-center">{{++$count}}</td>
                                <td class="text-left">
                                    {{$boxs2->bo_item}}
                                    <br>
                                    pcs/ctn : {{ $boxs2->bo_pack_qty }}
                                </td>    
                                <td class="text-center">{{  number_format($item2->sit_netweight ,3,".",",") }}</td>
                                <td class="text-center">{{  number_format($item2->sit_cartonwidth ,4,".",",") }}</td>
                                <td class="text-center">{{  number_format($item2->sit_cartonlenght ,4,".",",") }}</td>
                                <td class="text-center">
                                    @if (($boxs2->bo_order_qty_sum/$boxs2->bo_pack_qty) / $item2->sit_cartonlayer < 1) 
                                        {{ number_format($item2->sit_cartonheigh ,2,".",",") * 1 }} 
                                    @else
                                        {{ number_format($item2->sit_cartonheigh ,2,".",",") * floor(($boxs2->bo_order_qty_sum/$boxs2->bo_pack_qty) /  $item2->sit_cartonlayer)   }}
                                    @endif 
                                </td> 
                                <td> {{ $item2->sit_cartonwidth *  $item2->sit_cartonlenght * $item2->sit_cartonheigh  }}</td>
                                <td>{{ $item2->sit_cartonlayer }} x {{ $item2->sit_cartonperlayer }} = {{ $item2->sit_palletvolume }}</td></td>
                                <td>{{  $boxs2->bo_size_mm }}</td>
                                <td class="text-center">{{ceil($item['qty']/$boxs2->bo_pack_qty)}}</td>
                                <td class="text-center">
                                    <h5><i class="fa fa-times text-danger del_pallet" aria-hidden="true" atr="{{$key}}"
                                        atr2="{{$item['qty']}}" atr3="{{$item['id']}}" atr4="{{$i}}"></i></h5>
                                </td>
                            </tr>
                            @endforeach
                            <tr class="text-center">
                                <td class="text-right" colspan="9">จำนวนกล่อง </td>
                                <td class="text-center">{{$sum_count}} </td>
                                <td class="text-center">กล่อง</td>
                            </tr>
                            <tr class="text-center">
                                <td class="text-right" colspan="9">จำนวนชิ้น</td>
                                <td class="text-center">{{$sum_count2}} </td>
                                <td class="text-center">ชิ้น</td>
                            </tr>
                            @php
                                $total_box += $sum_count;
                                $total_item += $sum_count2;
                                $total_grossweight += $sum_grossweight;
                                $total_netweight += $sum_netweight;
                            @endphp
                            
                         
                           
                            <input type="hidden" name="id" id="id" value="{{$boxs2->bo_pd_id}}">
                        </tbody>

                    </table>
                </div>

              


                @else

                <p class="text-center"><b>ยังไม่มีการเลือก Items</b></p>
                @endif
           
        </div>
    </div>
    @endfor
    
   <hr>
    <div class="row">
        <div class="col-12 text-right">
            <table class="table table-bordered">
                <tr class="text-center">
                    <td width="90%" class="text-right" colspan="9">จำนวนกล่อง </td>
                    <td width="5%"class="text-center">{{$total_box}} </td>
                    <td width="5%"  class="text-center">กล่อง</td>
                </tr>
                <tr class="text-center">
                    <td class="text-right" colspan="9">จำนวนชิ้น</td>
                    <td class="text-center">{{$total_item}} </td>
                    <td class="text-center">ชิ้น</td>
                </tr>
              
                <tr class="text-center">
                    <td class="text-right" colspan="9">Net Weight</td>
                    <td class="text-center">{{$total_netweight}} </td>
                    <td class="text-center">KK</td>
                </tr>
                <tr class="text-center">
                    <td class="text-right" colspan="9">Gross Weight</td>
                    <td class="text-center">{{$total_grossweight}}</td>
                    <td class="text-center">KK</td>
                </tr>
                <tr class="text-center">
                    <td class="text-right" colspan="9">ความสูงทั้งหมดของ Pallet </td>
                    <td class="text-center"><input type="text" name="total_height" value="" required> </td>
                    <td class="text-center">Cm</td>
                </tr>
            </table>
            <input type="hidden" name="total_pallet" value="{{$total_box}}">

            @if (Session::has('layer_sel'))
                <button type="submit" class="btn btn-primary"
                
                        {{ Session::has('layer_sel') ? '' : 'disabled' }}>
                        Load Pallet
                </button>
            @endif
        </div>
    </div>
</form>

<!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">รายการ Items</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
            <div class="table-responsive">
                @for ($i = 0; $i <= 8; $i++)
                    @if (Session::has('cart'.$i))
                        @if (count(Session::get('cart'.$i)) > 0)
                        <p>ชั้นที่ {{$i}}</p>
                        <table class="table table-bordered table-hover" id="product1">
                            <thead>
                                <tr class="text-center">
                                    <th class="text-center" width="5%">#</th>
                                    <th class="text-center">Item</th>
                                    <th class="text-center">Weight</th>
                                    <th class="text-center">Width</th>
                                    <th class="text-center">Length</th>
                                    <th class="text-center">Height</th>
                                    <th class="text-center">Area</th>
                                    <th class="text-center">Standard</th>
                                    <th class="text-center">Size</th>
                                    <th class="text-center">Qty</th>
                                    <th class="text-center"></th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                $sum_count = 0;
                                $sum_count2 = 0;
                                $sum_height = 0;
                                $sum_grossweight = 0;
                                $sum_netweight = 0;
                                // dd(Session::get('cart'));
                                @endphp
                               
                                    
                               
                                @foreach (Session::get('cart'.$i) as $key => $item)
                                    @php
                                        $boxs2 = App\Boxs::where('bo_id',$item['id'])->first();
                                        $item2 = App\Items::where('it_name',$boxs2->bo_item)->leftjoin('tb_subitems','tb_items.it_id','=','tb_subitems.sit_it_id')->first();
                                        $sum_count += ceil($item['qty']/$boxs2->bo_pack_qty);
                                        $sum_count2 += $item['qty'];
                                        $sum_height += $item2->sit_cartonheigh;
                                        // $sum_grossweight += ($item['qty']/$boxs2->bo_pack_qty)*$item2->sit_grossweight;
                                        $sum_grossweight += (ceil($item['qty'])) * ($item2->sit_grossweight/$boxs2->bo_pack_qty);
                                        // $sum_netweight += ($item['qty']/$boxs2->bo_pack_qty)*$item2->sit_netweight;
                                        $sum_netweight += (ceil($item['qty'])) * ($item2->sit_netweight/$boxs2->bo_pack_qty);
                                    @endphp
                                        <tr class="text-center">
                                            <td class="text-center">{{++$key}}</td>
                                            <td class="text-center">{{$boxs2->bo_item}}</td>    
                                            <td class="text-center">{{  number_format($item2->sit_netweight ,3,".",",") }}</td>
                                            <td class="text-center">{{  number_format($item2->sit_cartonwidth ,4,".",",") }}</td>
                                            <td class="text-center">{{  number_format($item2->sit_cartonlenght ,4,".",",") }}</td>
                                            <td class="text-center">
                                                @if (($boxs2->bo_order_qty_sum/$boxs2->bo_pack_qty) / $item2->sit_cartonlayer < 1) 
                                                    {{ number_format($item2->sit_cartonheigh ,2,".",",") * 1 }} 
                                                @else
                                                    {{ number_format($item2->sit_cartonheigh ,2,".",",") * floor(($boxs2->bo_order_qty_sum/$boxs2->bo_pack_qty) /  $item2->sit_cartonlayer)   }}
                                                @endif 
                                            </td> 
                                            <td> {{ $item2->sit_cartonwidth *  $item2->sit_cartonlenght * $item2->sit_cartonheigh  }}</td>
                                            <td>{{ $item2->sit_cartonlayer }} x {{ $item2->sit_cartonperlayer }} = {{ $item2->sit_palletvolume }}</td></td>
                                            <td>{{  $boxs2->bo_size_mm }}</td>
                                            <td class="text-center">{{ceil($item['qty']/$boxs2->bo_pack_qty)}}</td>
                                            <td></td>
                                        </tr>
                                    @endforeach
                                    <tr class="text-center">
                                        <td class="text-right" colspan="9">จำนวนกล่อง </td>
                                        <td class="text-center">{{$sum_count}} </td>
                                        <td class="text-center">กล่อง</td>
                                    </tr>
                                    <tr class="text-center">
                                        <td class="text-right" colspan="9">จำนวนชิ้น</td>
                                        <td class="text-center">{{$sum_count2}} </td>
                                        <td class="text-center">ชิ้น</td>
                                    </tr>
                                    <tr class="text-center">
                                        <td class="text-right" colspan="9">Net Weight</td>
                                        <td class="text-center">{{$sum_netweight}} </td>
                                        <td class="text-center">KK</td>
                                    </tr>
                                    <tr class="text-center">
                                        <td class="text-right" colspan="9">Gross Weight</td>
                                        <td class="text-center">{{$sum_grossweight}}</td>
                                        <td class="text-center">KK</td>
                                    </tr>
                                    
                            </tbody>
                        </table>
                        @endif
                    @endif
               @endfor
                
            </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        </div>
      </div>
    </div>
  </div>

  <script>
    $('.del_pallet').click(function () {
        var id = $(this).attr('atr');
        var qty = $(this).attr('atr2');
        var box = $(this).attr('atr3');
        var key = $(this).attr('atr4');
        $.ajax({
            url: "{{url('deletepallet')}}" + '/' + id + '/' + qty + '/' + box+ '/' + key,
            type: "get",
            data: {
                _method: 'get',
                id: id
            }
        }).done(function (data) {
            $.ajax({
                url: "{{url('showpallet')}}",
                type: "get",
            }).done(function (data) {
                $("#showpallet").html(data);
            });

            var comma_separated = $("input[name=comma_separated]").val();
            var id = $("input[name=id]").val();
            $.ajax({
                url: "{{url('showtable')}}/" + comma_separated + '/' + id,
                type: "get",
            }).done(function (data) {
                $("#showtable").html(data);
            });
        });
    });



    $('.show_overview').click(function () {
        var sel_location = $("#sel_location").val();
        var pallet_layer = $("#pallet_layer").val();
        if (sel_location == "") {
            swal('กรุณาเลือก Location')
            return false;
        }

        if (pallet_layer == "") {
            swal('กรุณาเลือก Pallet Layer')
            return false;
        }
        $('input[name=sel_location]').val(sel_location);
        $('input[name=pallet_layer]').val(pallet_layer);
        $('#myModal').modal('show');
    });

    $("#condition_load").change(function(){
        var value = $(this).val();
        // if(value == ""){
        //     value = 0;
        // }
        $.ajax({
            url: "{{url('select_layer')}}/"+value,
            type: "get",
        }).done(function (data) {
                window.location.reload();
        });
    });
</script>