@extends('layouts.master')
@section('title')
ตารางาน Master Products
@endsection
@section('css')
<link rel="stylesheet" href="{{ asset('assets/css/app.css') }}">
@endsection
@section('content')
<div class="breadcrumb">
    <div class="breadcrumb-element">
        <a class="breadcrumb-item " href="{{ url('dashboard') }}"><i class="lnr lnr-home mr3"></i></a>
        <a class="breadcrumb-item " href="#">ตารางาน Master Products</a>
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">ตารางาน Master Products</div>
            <div class="card-body">
                @if (\Session::has('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <i class="fas fa-check-circle pr5"></i>สำเร็จ!</strong> {!! \Session::get('success') !!}
                </div>
                @endif
                {{-- <p class="text-right"><a href="{{ route('master.create') }}" class="btn btn-info" style="color:white;"><i class="fas fa-plus"></i> สร้างรายการ Master</a></p> --}}
                <hr>
                <div class="table-responsive">
                    <table class="table table-bordered table-hover table-striped" id="product1">
                        <thead>
                            <tr class="text-center">
                                <th>#</th>
                                <th>Customer Id</th>
                                <th>Type</th>
                                <th>F/G ID</th>
                                <th>Manage</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($customers as $key => $customer)
                            <tr>
                                <td class="text-center">{{ ++$key }}</td>
                                <td class="text-center">{{ $customer->ct_sales_ccn }}</td>
                                <td class="text-center">{{ $customer->ct_name }}</td>
                                <td class="text-center">{{ $customer->ct_cus_ship_loc }}</td>
                                <td class="text-center" width="15%">
                                    <a href="{{ route('master.show',$customer->ct_id)}}" class="btn btn-info edit_custo"
                                        id="edit_custo" style="color:white;" atr="{{ $customer->ct_id }}"><i class="fas fa-plus"></i></a>
                                    {{-- <a href="{{ route('master.edit',$customer->ct_id)}}" class="btn btn-success edit_custo"
                                        id="edit_custo" style="color:white;" atr="{{ $customer->ct_id }}"><i class="far fa-edit"></i></a> --}}
                                    {{-- <a class="btn btn-danger del_master" id="del_master" atr="{{ $customer->mt_id }}"
                                        style="color:white;"><i class="far fa-trash-alt"></i></a> --}}
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

@endsection
@section('js')
@csrf
<script>
    $(document).ready(function () {
        $("[rel='tooltip']").tooltip();
    });

    $(document).ready(function () {
        $('#product1').DataTable();
    });

    $(".del_master").click(function () {
        var id = $(this).attr('atr');
        var token = $('input[name="_token"]').val();
        swal({
                title: "คำเตือน!!",
                text: "คุณจะทำการ ลบข้อมูล Master หรือไม่!",
                icon: "warning",
                buttons: true,
                dangerMode: true,
            })
            .then((willDelete) => {
                if (willDelete) {
                    $.ajax({
                        url: "{{ url('master')}}/" + id,
                        method: "post",
                        data: {
                            _token: token,
                            id: id,
                            _method: "DELETE",
                        }
                    }).done(function (data) {
                        console.log(data);
                        if (data == 1) {
                            swal({
                                title: "เรียบร้อย?",
                                text: "คุณทำการลบข้อมูล รายการ Master เรียบร้อย!",
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
