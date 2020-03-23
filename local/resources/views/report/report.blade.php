<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <title>Packing Calculation v2.0</title>
        <meta name='subject' content='Diaresibon.'>
        <meta name="description" content="Diaresibon." />
        <meta name='copyright' content='Diaresibon'>
        <meta name='robots' content='index,follow'>
        <meta name='author' content='Diaresibon'>
        <link rel="icon" type="image/jpg" href="{{ asset('logo.jpg') }}">
        {{-- <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous"> --}}
    </head>
    <style>
        body{ 
            padding: 5px; 
            border: 1px solid black; 
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

        .pull-left {float:left;}
        .pull-right {float:right;}
        .text-center {text-align:center;}
    </style>
<body>
    <div class="text-center">
        <span class="pull-left" >.</span>
        <span>Packing Detail</span>
        <span class="pull-right">CONT{{$containers[0]->ctn_type}}'X1 REV.01</span>
        </div>
    @foreach ($containers as $key => $container)
                <br>
                Container No. {{++$key}}
                <table class="table table-bordered table-striped table-hover">
                    <thead>
                        <tr>
                            <th class="text-center" width="10%">Pallet No.</th>
                            <th class="text-center" width="10%">F/G ID </th>
                            <th class="text-center">Part no.</th>
                            <th class="text-center">DESCRIPTION</th>
                            <th class="text-center">SPEC</th>
                            <th class="text-center">PCS/CTN</th>
                            <th class="text-center">L</th>
                            <th class="text-center">Check</th>
                            <th class="text-center" width="10%">C/NO. 1-UP</th>
                            <th class="text-center">TOTAL</th>
                            <th class="text-center">NET WEIGH</th>
                            <th class="text-center">GROSS WEIGH</th>
                            <th class="text-center">M<sup>3</sup></th>
                        </tr>
                    </thead>
                    <tbody>
                    @php
                        $container_details = DB::table('tb_container_detail')
                        ->leftjoin('tb_mainpallet','tb_mainpallet.mp_id','=','tb_container_detail.ctnd_mp_id')
                        ->leftjoin('tb_pallet','tb_pallet.tpl_mp_id','=','tb_mainpallet.mp_id')
                        ->leftjoin('tb_boxs','tb_boxs.bo_id','=','tb_pallet.tpl_bo_id')
                        ->leftjoin('tb_items','tb_items.it_name','=','tb_boxs.bo_item')
                        ->leftjoin('tb_subitems','tb_subitems.sit_it_id','=','tb_items.it_id')
                        ->leftjoin('tb_typepalate','tb_typepalate.tp_id','=','tb_subitems.sit_pallet')
                        ->where('ctnd_ctn_id',$container->ctn_id)
                        ->groupBy('ctnd_mp_id')
                        ->get();
                      
                        // print_r($container_details)
                        $checl_total = 0;
                    @endphp
                     @foreach ($container_details as $key => $container_detail)
                       
                        <tr>
                            <td class="text-center">
                                @if ($key == 0)
                                    {{1}} - {{$container_detail->mp_pallet_qty}}
                                @else 
                                    {{$checl_total+1}} - {{$checl_total+$container_detail->mp_pallet_qty}}
                                @endif

                            </td>
                            <td class="text-center">{{$container_detail->tp_width.'x'.$container_detail->tp_length.'x'.$container_detail->tp_hieght.' CM'}}</td>
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
                        $checl_total += $container_detail->mp_pallet_qty;

                            $collection = DB::table('tb_mainpallet')
                                                ->leftjoin('tb_pallet','tb_pallet.tpl_mp_id','=','tb_mainpallet.mp_id')
                                                ->leftjoin('tb_boxs','tb_boxs.bo_id','=','tb_pallet.tpl_bo_id')
                                                ->leftjoin('tb_items','tb_items.it_name','=','tb_boxs.bo_item')
                                                ->leftjoin('tb_subitems','tb_subitems.sit_it_id','=','tb_items.it_id')
                                                ->where('mp_id',$container_detail->ctnd_mp_id)->get();
                        @endphp
                        @foreach ($collection as $item)
                            <tr>
                                <td class="text-left">{{$item->bo_so}}</td>
                                <td class="text-left">{{$item->bo_item}}</td>
                                <td class="text-left">{{$item->bo_cus_item}}</td>
                                <td class="text-left">{{$item->bo_size_mm}}</td>
                                <td class="text-left">{{$item->bo_cus_spec}}</td>
                                <td class="text-center">{{$item->bo_pack_qty}}</td>
                                <td class="text-center">{{$item->tpl_qty}}</td>
                                <td class="text-center"></td>
                                <td class="text-center"></td>
                                <td class="text-right">{{$item->tpl_sum_qty}}</td>
                                <td class="text-right">{{number_format($item->tpl_sum_qty*($item->sit_netweight/$item->sit_palletvolume),2,'.',',')}}</td>
                                <td class="text-right">{{number_format($item->tpl_sum_qty*($item->sit_grossweight/$item->sit_palletvolume),2,'.',',')}}</td>
                                <td class="text-right">{{number_format($item->tpl_sum_qty*($item->sit_cbm/$item->sit_palletvolume),2,'.',',')}}</td>
                            </tr>
                        @endforeach
                    @endforeach 
                    </tbody>
                </table>


                @endforeach
</body>
</html>