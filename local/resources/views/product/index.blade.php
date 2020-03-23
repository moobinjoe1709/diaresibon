@extends('layouts.master')
@section('title')
    Import File สินค้า
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
        <a class="breadcrumb-item " href="#"> Import File สินค้า</a>
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">เพิ่ม Import File</div>
            <div class="card-body">
                @if (\Session::has('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <i class="fas fa-check-circle pr5"></i><strong>สำเร็จ!</strong> {!! \Session::get('success') !!}
                </div>
                @endif
                @if (\Session::has('danger'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <i class="fas fa-times-circle pr5"></i><strong>คำเตือน!</strong> {!! \Session::get('danger') !!}
                </div>
                @endif
                <form action="{{ url('productimport') }}" method="post" enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                        <div class="col-6">
                            <div class="form-group">
                                <label for="upload">Import File input</label>
                                <input type="file" class="form-control-file" name="upload" id="upload" value="">
                                @if ($errors->has('upload'))
                                    <span class="text-danger" role="alert">
                                        <strong>{{ $errors->first('upload') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-6 col-md-6 text-right">
                            <button type="submit" class="btn btn-primary">บันทึก</button>
                        </div>
                    </div>

                </form>
                <hr>
                <h4>ตาราง Shipment</h4>
                <div class="table-responsive">
                    <table class="table table-bordered table-hover table-striped" id="product1">
                        <thead>
                            <tr class="text-center">
                                <th class="text-center" width="5%">#</th>
                                <th class="text-center">Sale CCN</th>
                                <th class="text-center">Mas Loc</th>
                                <th class="text-center">Promise Date</th>
                                <th class="text-center">Ship Via</th>
                                <th class="text-center">Customer</th>
                                <th class="text-center">Cus Ship Loc</th>
                                {{-- <th class="text-center">Extra Box</th> --}}
                                <th class="text-center" width="15%">View</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($tb_products as $key => $tb_product)
                            @php
                                    $im_boxs = DB::table('tb_boxs')->select('bo_id')->where('bo_pd_id',$tb_product->pd_id)->get();
                                    $implode_box =   $im_boxs->implode('bo_id',',');

                                    $check_pallet = DB::table('tb_pallet')->whereIn('tpl_bo_id',explode(',',$implode_box))->get();
                                   
                            @endphp
                          
                            <tr>
                                <td class="text-center">{{ ++$key }}</td>
                                <td class="text-center">{{ $tb_product->sales_ccn }}</td>
                                <td class="text-center">{{ $tb_product->mas_loc }}</td>
                                <td class="text-center">{{ $tb_product->promise_date }}</td>
                                <td class="text-center">{{ $tb_product->ship_via }}</td>
                                <td class="text-center">{{ $tb_product->customer }}</td>
                                <td class="text-center">{{ $tb_product->cus_ship_loc }}</td>
                                {{-- <td class="text-right">@php echo $box->bo_frangment != "" ? 1 : '-' @endphp</td>
                                --}}
                                <td class="text-center">
                                    <button class="btn btn-info show_products" onclick="viewShow({{  $tb_product->pd_id }})"  atr="{{  $tb_product->pd_id }}"><i class="fas fa-eye"></i></button>
                                    <a class="btn btn-success" href="{{ route('productimport.edit',$tb_product->pd_id) }}" ><i class="fas fa-file-alt"></i></a>
                                    <button class="btn btn-danger del_shipment" atr="{{$tb_product->pd_id}}" {{count($check_pallet) > 0 ? 'disabled' : ''}}><i class="fas fa-trash"></i></button>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<div id="ShowProducts"></div>
@endsection
@section('js')
<script>
    $(document).ready(function () {
        $("[rel='tooltip']").tooltip();
    });

    $(document).ready(function () {
        $('#product1').DataTable();
    });


    function viewShow(id) {
        $.ajax({
             url: "{{ url('productimport') }}/" + id,
            data: {id: id},
            type: 'GET',
            success: function (data) {
                $('#ShowProducts').html(data);
                $("#exampleModal").modal('show');
                $('#exampleModal').modal({backdrop: 'static', keyboard: false});
            }
        });
    }

    

    $(".del_shipment").click(function () {
        var id = $(this).attr('atr');
        var token = $('input[name="_token"]').val();
        swal({
                title: "คำเตือน!!",
                text: "คุณจะทำการ ลบข้อมูล Shipment หรือไม่!",
                icon: "warning",
                buttons: true,
                dangerMode: true,
            })
            .then((willDelete) => {
                if (willDelete) {
                    $.ajax({
                        url: "{{ url('deleteshipment')}}/"+id,
                        method: "get",
                    }).done(function (data) {
                        if (data == 1) {
                            swal({
                                title: "เรียบร้อย?",
                                text: "คุณทำการลบข้อมูล Shipment เรียบร้อย!",
                                icon: "success",
                            }).then((willDelete) => {
                                window.location.reload();
                            });
                        }
                    });
                } else {
                    swal("ยกเลิก!", "คุณยกเลิกการลบข้อมูล Shipment!", "error");
                }
            });

    });
</script>
@endsection
