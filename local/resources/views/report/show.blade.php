@extends('layouts.master')
@section('title')
Report
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
<div class="breadcrumb">
    <div class="breadcrumb-element">
        <a class="breadcrumb-item " href="{{ url('dashboard') }}"><i class="lnr lnr-home mr3"></i></a>
        <a class="breadcrumb-item " href="#">Report</a>
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">Report</div>
            <div class="card-body">
                @if (\Session::has('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <i class="fas fa-check-circle pr5"></i>สำเร็จ!</strong> {!! \Session::get('success') !!}
                </div>
                @endif
                <div class="row">
                    <div class="col-6">
                        
                    </div>
                    <div class="col-6 text-right">
                        <a target="_blank" href="{{route('reportcontainer',$id)}}" class="btn btn-success"><i class="fas fa-sticky-note"></i> Report PDF</a>
                    </div>
                </div>
                <hr>
                @foreach ($containers as $key => $container)
                <br>
                Container No. {{++$key}}
                <table class="table table-bordered table-striped table-hover">
                    <thead>
                        <tr>
                            <th class="text-center">Pallet No.</th>
                            <th class="text-center">F/G ID </th>
                            <th class="text-center">Part no.</th>
                            <th class="text-center">DESCRIPTION</th>
                            <th class="text-center">SPEC</th>
                            <th class="text-center">PCS/CTN</th>
                            <th class="text-center">L</th>
                            <th class="text-center">Check</th>
                            <th class="text-center">C/NO. 1-UP</th>
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
                    {{--
                    @php
                        $containers2 = DB::table('tb_containers')
                                    ->leftjoin('tb_container_detail','tb_container_detail.ctnd_ctn_id','=','tb_containers.ctn_id')
                                    ->where('ctn_date',$date)
                                    ->where('ctnd_ctn_id',$container->ctnd_ctn_id)
                                    ->get();
                    @endphp      --}}
                        {{-- @foreach ($containers2 as $key => $container2)
                        @php
                            $containers2 = DB::table('tb_containers')->where('ctnd_mp_id',$container2->ctnd_mp_id)->get();
                        @endphp --}}
                        {{-- <tr>
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
                            <td class="text-center"></td>
                            <td class="text-center"></td>
                        </tr> --}}
                        {{-- @endforeach --}}
                    {{-- @endforeach --}}
                    </tbody>
                </table>


                @endforeach
            </div>
        </div>
    </div>
</div>
<div id="ShowProducts"></div>
@endsection
@section('js')
@csrf
<script>
    $(document).ready(function () {
        $("[rel='tooltip']").tooltip();
    });

    $(document).ready(function () {
        $('#product1').DataTable();
        $('#product2').DataTable();
    });

    function viewShow(id) {
            $.ajax({
                url: "{{ url('box') }}/" + id,
                data: {id: id},
                type: 'GET',
                success: function (data) {
                    // console.log(data);
                    $('#ShowProducts').html(data);
                    $("#exampleModal").modal('show');
                    $('#exampleModal').modal({backdrop: 'static', keyboard: false});
                }
            });
        }
</script>

@endsection
