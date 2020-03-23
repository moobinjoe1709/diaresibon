@extends('layouts.master')
@section('title')
สร้าง Customer
@endsection
@section('css')
<link rel="stylesheet" href="{{ asset('assets/css/app.css') }}">
@endsection
@section('content')
<div class="breadcrumb">
    <div class="breadcrumb-element">
        <a class="breadcrumb-item " href="{{ url('dashboard') }}"><i class="lnr lnr-home mr3"></i></a>
        <a class="breadcrumb-item " href="#">สร้าง Customer</a>
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <div class="row">
                    <div class="col-6 col-md-6 text-left">
                        สร้าง Customer
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
                <div class="form-add-customer">
                    <h5>แบบฟอร์มสร้าง Customer</h5>
                    <form action="{{ url('CreateCustomer') }}" method="post" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-4 col-md-4">
                                <div class="form-group">
                                    <label for="sales_ccn">SALES CCN</label>
                                    <input type="text" class="form-control" id="sales_ccn" name="sales_ccn" placeholder="SALES CCN"
                                        value="{{ old('sales_ccn') }}">
                                    @if ($errors->has('sales_ccn'))
                                    <div class="mx-sm-3 mb-2">
                                        <span class="text-danger" role="alert">
                                            <strong>{{ $errors->first('sales_ccn') }}</strong>
                                        </span>
                                    </div>
                                    @endif
                                </div>
                            </div>
                            <div class="col-4 col-md-4">
                                <div class="form-group">
                                    <label for="customer">CUSTOMER</label>
                                    <input type="text" class="form-control" id="customer" name="customer" placeholder="CUSTOMER"
                                        value="{{ old('customer') }}">
                                    @if ($errors->has('customer'))
                                    <div class="mx-sm-3 mb-2">
                                        <span class="text-danger" role="alert">
                                            <strong>{{ $errors->first('customer') }}</strong>
                                        </span>
                                    </div>
                                    @endif
                                </div>
                            </div>
                            <div class="col-4 col-md-4">
                                <div class="form-group">
                                    <label for="cus_ship_loc">CUS SHIP LOC</label>
                                    <input type="text" class="form-control" id="cus_ship_loc" name="cus_ship_loc"
                                        placeholder="CUS SHIP LOC" value="{{ old('cus_ship_loc') }}">
                                    @if ($errors->has('cus_ship_loc'))
                                    <div class="mx-sm-3 mb-2">
                                        <span class="text-danger" role="alert">
                                            <strong>{{ $errors->first('cus_ship_loc') }}</strong>
                                        </span>
                                    </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="text-right"> <button type="submit" class="btn btn-primary mb-2">บันทึก</button></div>

                    </form>
                </div>
                <hr>
                <div class="table-responsive">
                    <table class="table table-bordered table-hover table-striped" id="product1">
                        <thead>
                            <tr class="text-center">
                                <th width="10%">#</th>
                                <th>SALES CCN</th>
                                <th>CUSTOMER</th>
                                <th>CUS SHIP LOC</th>
                                <th width="25%">Manage</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($customers as $key => $customer)
                            <tr>
                                <td class="text-center">{{ ++$key }}</td>
                                <td class="text-center">{{ $customer->ct_sales_ccn }}</td>
                                <td class="text-center">{{ $customer->ct_name }}</td>
                                <td class="text-center">{{ $customer->ct_cus_ship_loc }}</td>
                                <td class="text-center">
                                    <a class="btn btn-success edit_custo" id="edit_custo" style="color:white;" atr="{{ $customer->ct_id }}"
                                        data-toggle="modal" data-target="#exampleModal"><i class="far fa-edit"></i></a>
                                    <a class="btn btn-danger del_custo" id="del_custo" atr="{{ $customer->ct_id }}"
                                        style="color:white;"><i class="far fa-trash-alt"></i></a>
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

<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">แบบฟอร์มแก้ไข Customer</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ url('UpdateCustomer') }}" method="post" id="form-edit-customer">
                @csrf
                <div class="modal-body">
                    <div class="row">
                        <div class="col-4 col-md-4">
                            <div class="form-group">
                                <label for="sales_ccn2">SALES CCN</label>
                                <input type="text" class="form-control" id="sales_ccn2" name="sales_ccn2" placeholder="SALES CCN"
                                    value="{{ old('sales_ccn2') }}">
                                @if ($errors->has('sales_ccn2'))
                                <div class="mx-sm-3 mb-2">
                                    <span class="text-danger" role="alert">
                                        <strong>{{ $errors->first('sales_ccn2') }}</strong>
                                    </span>
                                </div>
                                @endif
                            </div>
                        </div>
                        <div class="col-4 col-md-4">
                            <div class="form-group">
                                <label for="customer2">CUSTOMER</label>
                                <input type="text" class="form-control" id="customer2" name="customer2" placeholder="CUSTOMER"
                                    value="{{ old('customer2') }}">
                                @if ($errors->has('customer2'))
                                <div class="mx-sm-3 mb-2">
                                    <span class="text-danger" role="alert">
                                        <strong>{{ $errors->first('customer2') }}</strong>
                                    </span>
                                </div>
                                @endif
                            </div>
                        </div>
                        <div class="col-4 col-md-4">
                            <div class="form-group">
                                <label for="cus_ship_loc2">CUS SHIP LOC</label>
                                <input type="text" class="form-control" id="cus_ship_loc2" name="cus_ship_loc2"
                                    placeholder="CUS SHIP LOC" value="{{ old('cus_ship_loc2') }}">
                                @if ($errors->has('cus_ship_loc2'))
                                <div class="mx-sm-3 mb-2">
                                    <span class="text-danger" role="alert">
                                        <strong>{{ $errors->first('cus_ship_loc2') }}</strong>
                                    </span>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                    <input type="hidden" class="form-control" id="id_customer_edit" name="id_customer_edit">
                    {{-- <button type="submit" class="btn btn-primary mb-2">แก้ไข</button> --}}
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary update_custo">แก้ไข</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">ปิด</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
@section('js')
<script>
    $(document).ready(function () {
        $("[rel='tooltip']").tooltip();
    });

    $(document).ready(function () {
        $('#product1').DataTable();
    });

</script>
<script>
    $(".edit_custo").click(function () {
        var id = $(this).attr('atr');
        var token = $('input[name="_token"]').val();

        $.ajax({
            url: "{{ url('EditCustomer')}}/" + id,
            method: "post",
            data: {
                _token: token,
                id: id
            }
        }).done(function (data) {
            console.log(data);
            $("#sales_ccn2").val(data.customer['ct_sales_ccn']);
            $("#customer2").val(data.customer['ct_name']);
            $("#cus_ship_loc2").val(data.customer['ct_cus_ship_loc']);
            $("#id_customer_edit").val(data.customer['ct_id']);
        });
    });

    $(".update_custo").click(function () {

        var token = $('input[name="_token"]').val();
        var sales_ccn = $("#sales_ccn2").val();
        var customer = $("#customer2").val();
        var cus_ship_loc = $("#cus_ship_loc2").val();
        var id = $("#id_customer_edit").val();
        $.ajax({
            url: "{{ url('UpdateCustomer')}}/" + id,
            method: "post",
            data: {
                _method: "PUT",
                _token: token,
                id: id,
                customer: customer,
                sales_ccn: sales_ccn,
                cus_ship_loc: cus_ship_loc,
            }
        }).done(function (data) {
            // console.log(data);
            if (data == 1) {
                $('#exampleModal').modal('hide');
                swal({
                    title: "เรียบร้อย?",
                    text: "คุณทำการแก้ไขข้อมูล รายการ Customer เรียบร้อย!",
                    icon: "success",
                }).then((willDelete) => {

                    window.location.reload();
                });
            } else {
                console.log(data.errors);
            }
        });
    });

    $(".del_custo").click(function () {
        var id = $(this).attr('atr');
        var token = $('input[name="_token"]').val();
        swal({
                title: "คำเตือน!!",
                text: "คุณจะทำการ ลบข้อมูล Customer หรือไม่!",
                icon: "warning",
                buttons: true,
                dangerMode: true,
            })
            .then((willDelete) => {
                if (willDelete) {
                    $.ajax({
                        url: "{{ url('DelCustomer')}}/" + id,
                        method: "post",
                        data: {
                            _token: token,
                            id: id

                        }
                    }).done(function (data) {
                        if (data == 1) {
                            swal({
                                title: "เรียบร้อย?",
                                text: "คุณทำการลบข้อมูล รายการ Customer เรียบร้อย!",
                                icon: "success",
                            }).then((willDelete) => {
                                window.location.reload();
                            });
                        }
                    });
                } else {
                    swal("ยกเลิก!", "คุณยกเลิกการลบข้อมูล!", "error");
                }
            });

    });

</script>
@endsection
