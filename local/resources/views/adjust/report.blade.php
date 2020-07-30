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
        @font-face {
            font-family: 'THSarabunNew';
            font-style: normal;
            font-weight: normal;
            src: url("{{ asset('fonts/THSarabunNew.ttf') }}") format('truetype');
        }
        @font-face {
            font-family: 'THSarabunNew';
            font-style: normal;
            font-weight: bold;
            src: url("{{ asset('fonts/THSarabunNew Bold.ttf') }}") format('truetype');
        }
        @font-face {
            font-family: 'THSarabunNew';
            font-style: italic;
            font-weight: normal;
            src: url("{{ asset('fonts/THSarabunNew Italic.ttf') }}") format('truetype');
        }
        @font-face {
            font-family: 'THSarabunNew';
            font-style: italic;
            font-weight: bold;
            src: url("{{ asset('fonts/THSarabunNew BoldItalic.ttf') }}") format('truetype');
        }
 

        body{ 
            font-family: "THSarabunNew";
            padding: 5px; 
            /* border: 1px solid black;  */
            font-size: 18px;
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
            font-size: 16px;
            border: 1px solid black;
        }

        thead {
            background-color: azure;
        }
        
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

        .column{
            -webkit-column-width: 20em;
            -webkit-column-gap: 2em;
            -webkit-column-rule: 1px solid #eee;
            -webkit-column-count: 2;
            -moz-column-width: 20em;
            -moz-column-gap: 2em;
            -moz-column-rule: 1px solid #eee;
            -moz-column-count: 2;
            -ms-column-width: 20em;
            -ms-column-gap: 2em;
            -ms-column-rule: 1px solid #eee;
            -ms-column-count: 2;
            column-width: 20em;
            column-gap: 2em;
            column-rule: 1px solid #eee;
            column-count: 2;
        }

        .col-container {
            display: table;
            width: 100%;
        }
        .col {
            display: table-cell;
            padding: 10px;
            border: #696969 1px solid;
        }
    </style>
<body>
    <div class="text-center">
        <span class="pull-left" >.</span>
        <span><b>Packing Detail</b></span>
        <span class="pull-right">CONT{{$containers[0]->ctn_size}}'X{{count($containers)}} REV.01</span>
    </div>
    <br>
    @php
        
        $date = date_create($containers[0]->ctn_date);
    @endphp
    <div class="text-center">
        <span class="pull-left" >So. No.  {{$report->rh_sono}}</span>
        <span>Customer Name:  {{$report->rh_customer}}</span>
        <span class="pull-right">Ex-FAC : {{ date_format($date,"d M y")}}</span>
    </div>
   
        @php
            $sumpallet = 0;
            $countlap  = 0;
            $sumbox_qty = 0;
        @endphp
    @foreach ($containers as $key => $container)
                <br>
Container No. {{++$key}}
                <table class="table table-bordered table-hover">
                    <thead>
                        <tr>
                            <th class="text-center" width="55">Pallet No.</th>
                            <th class="text-center" width="100">F/G ID </th>
                            <th class="text-center">Part no.</th>
                            <th class="text-center">DESCRIPTION</th>
                            <th class="text-center"width="50">SPEC</th>
                            <th class="text-center">PCS/CTN</th>
                            <th class="text-center">L</th>
                            <th class="text-center">Check</th>
                            <th class="text-center" width="50">C/NO. 1-UP</th>
                            <th class="text-center">TOTAL</th>
                            <th class="text-center">NET WEIGH</th>
                            <th class="text-center">GROSS WEIGH</th>
                            <th class="text-center">M<sup>3</sup></th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                        $tb_container_detail = DB::table('tb_container_detail')
                                                ->selectRaw('*,  count(ctnds_group) as count_pallets')
                                                ->leftjoin('tb_container_des','tb_container_des.ctnds_ctnd_id','=','tb_container_detail.ctnd_id')
                                                ->leftjoin('tb_pallet','tb_pallet.tpl_id','=','tb_container_des.ctnds_key')
                                                ->leftjoin('tb_boxs','tb_boxs.bo_id','=','tb_pallet.tpl_bo_id')
                                                ->leftjoin('tb_items','tb_boxs.bo_item','=','tb_items.it_name') 
                                                ->leftjoin('tb_subitems','tb_items.it_id','=','tb_subitems.sit_it_id')
                                                ->leftjoin('tb_typepalate','tb_subitems.sit_pallet','=','tb_typepalate.tp_id')
                                                ->whereIn('ctnd_key', explode(",",$container->pallet_id))
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
                                        {{$lastlap}} - {{$sumpallet}}
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

                            <tr style="border-color: #F5F5F5;">
                                <td class="text-left" style="font-size:14px;">{{$val->bo_so}}</td>
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
                                        $sum_up += ceil($box_qty*$val->bo_pack_qty);
                                    @endphp
                                    {{ceil($box_qty*$val->bo_pack_qty)}}
                                </td>
                                <td class="text-center">
                                    @php
                                        $sum_net += $box_qty*$val->sit_netweight;
                                    @endphp
                                    {{number_format($box_qty*$val->sit_netweight,2,'.',',')}}
                                </td>
                                <td class="text-center">
                                    @php
                                        $sum_gross += $box_qty*$val->sit_grossweight;
                                    @endphp
                                    {{number_format($box_qty*$val->sit_grossweight,2,'.',',')}}
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

    @endforeach
    <div class="page_break"></div>
    <div class="col-container">
        @foreach ($remarks as $remark)
            <div class="col" >
                {{$remark->re_remark}}
            </div>
        @endforeach
       
    </div>
</body>
</html>