@extends('layouts.master')
@section('title')
แก้ไข Import File สินค้า
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
        <a class="breadcrumb-item " href="#"> แก้ไข Import File สินค้า</a>
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <div class="row">
                    <div class="col-6">
                        แก้ไข Import File สินค้า
                    </div>
                    <div class="col-6  text-right">
                        <a class="btn btn-secondary" href="{{ url('productimport') }}">กลับ</a>
                    </div>
                </div>
            </div>
            <div class="card-body">
                @if (\Session::has('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <i class="fas fa-check-circle pr5"></i><strong>สำเร็จ!</strong> {!! \Session::get('success') !!}
                </div>
                @endif
                @php
                    $date = date_create($tb_products->promise_date);
                @endphp
                <div class="row">
                    <div class="col-12 text-right">
                        <div class="table-responsive">
                            <table style="width: 100%" class="table table-bordered bg-primary text-white" id="">
                                <tbody >
                                    <tr class="text-center">
                                        <th class="text-center">Ship From : <strong>{{ $tb_products->mas_loc }}</strong></th>
                                        <th class="text-center"> Customer : <strong>{{ $tb_products->customer }}</strong></th>
                                        <th class="text-center">Location : <strong>{{ $tb_products->cus_ship_loc }}</strong></th>
                                        <th class="text-center">Promise Date : <strong>{{ date_format($date, 'd-M-y')}}</strong></th>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <hr>
                <form action="{{ url('productimport',$tb_products->pd_id) }}" method="post" enctype="multipart/form-data">
                    @csrf
                    {{ method_field('PUT') }}
                    <div class="row">
                        <div class="col-12">

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
                        <div class="col-12 col-md-8 text-right">
                            <button type="submit" class="btn btn-primary">แก้ไข</button>
                        </div>
                    </div>

                </form>
                <hr>
                <div class="table-responsive">
                    <table class="table table-bordered table-hover table-striped" id="product2">
                        <thead>
                            <tr class="text-center">
                                <th>#</th>
                                <th>sales_ccn</th>
                                <th>mas_loc</th>
                                <th>promise_date</th>
                                <th>ship_via</th>
                                <th>customer</th>
                                <th>cus_ship_loc</th>
                                <th>so</th>
                                <th>so_line</th>
                                <th>fullfill_from</th>
                                <th>ordered_qty</th>
                                <th>pack_qty</th>
                                <th>จัดการ</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($boxs as $key => $box)
                            <tr>
                                <td>{{ ++$key }}</td>
                                <td>{{ $box->bo_sale_ccn }}</td>
                                <td>{{ $box->bo_mas_loc }}</td>
                                <td>{{ $box->bo_promise_date }}</td>
                                <td>{{ $box->bo_ship_via }}</td>
                                <td>{{ $box->bo_customer }}</td>
                                <td>{{ $box->bo_cus_ship_loc }}</td>
                                <td>{{ $box->bo_so }}</td>
                                <td>{{ $box->bo_so_line }}</td>
                                <td>{{ $box->bo_fullfill_from }}</td>
                                <td>{{ $box->bo_order_qty_sum }}</td>
                                <td>{{ $box->bo_pack_qty}}</td>
                                <td class="text-center"><button class="btn btn-danger del_product" {{$box->bo_order_qty_sum != $box->bo_total ? 'disabled' : ''}}  atr="{{ $box->bo_pdt_id }}"><i class="fas fa-trash-alt"></i></button></td>
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
@csrf
@endsection
@section('js')
@csrf
<script>
    $(document).ready(function () {
        $("[rel='tooltip']").tooltip();
    });

    $(document).ready(function () {
        $('#product2').DataTable();
    });

    function viewShow(id) {
        $.ajax({
            url: "{{ url('box') }}/" + id,
            data: {
                id: id
            },
            type: 'GET',
            success: function (data) {
                $('#ShowProducts').html(data);
                $("#exampleModal").modal('show');
                $('#exampleModal').modal({
                    backdrop: 'static',
                    keyboard: false
                });
            }
        });
    }

        $('.del_product').click(function(){
            var id = $(this).attr('atr');
            var token = $("input[name=_token]").val();
            swal({
                title: "คำเดือน",
                text: "คุณต้องการลบข้อมูล Product นี้ ใช่หรือไม่?",
                icon: "warning",
                buttons: true,
                dangerMode: true,
            }).then((willDelete) => {
                if (willDelete) {
                    $.ajax({
                        url: "{{url('productimport')}}/"+id,
                        method: "post",
                        data: {
                            _method: "delete",
                            _token: token,
                            id: id
                        }
                    }).done(function (data) {
                        console.log(data);
                        if (data == 1) {
                            swal({
                                title: "เรียบร้อย",
                                text: "ลบข้อมูล Product เรียบร้อย!!",
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
                        text: "ยกเลิกการลบข้อมูล Product",
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
@endsection
