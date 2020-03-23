@extends('layouts.master')
@section('title')
สร้าง Items
@endsection
@section('css')
<link rel="stylesheet" href="{{ asset('assets/css/app.css') }}">
@endsection
@section('content')
<div class="breadcrumb">
    <div class="breadcrumb-element">
        <a class="breadcrumb-item " href="{{ url('dashboard') }}"><i class="lnr lnr-home mr3"></i></a>
        <a class="breadcrumb-item " href="#">สร้าง Items</a>
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <div class="row">
                    <div class="col-6 col-md-6 text-left">
                        Import Master
                    </div>
                    <div class="col-6 col-md-6 text-right">
                        <a class="btn btn-success" style="color:white;" href="{{ url('loadmaster') }}">Master Templates</a>
                        <a class="btn btn-secondary" href="{{ url('item') }}">กลับ</a>
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
                <form action="{{ url('productimport') }}" method="post" enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                        <div class="col-6">
                            <div class="form-group">
                                <label for="upload">Import File Master</label>
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
            <form action="#" method="post" id="form-edit-customer">
                @csrf
                <div class="modal-body">
                    <div class="row">
                        <div class="col-4 col-md-4">
                            <div class="form-group">
                                <label for="sales_ccn">Items</label>
                                <input type="text" class="form-control" id="item2" name="item2" placeholder="Items" value="">
                                @if ($errors->has('item'))
                                <div class="mx-sm-3 mb-2">
                                    <span class="text-danger" role="alert">
                                        <strong>{{ $errors->first('item') }}</strong>
                                    </span>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                    <input type="hidden" class="form-control" id="id_item_edit" name="id_item_edit">
                    {{-- <button type="submit" class="btn btn-primary mb-2">แก้ไข</button> --}}
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary update_item">แก้ไข</button>
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
    
    $("#cus_id").change(function () {
        var id = $(this).val();
        $.ajax({
            url: "{{ url('SearchItem')}}/" + id,
            method: "get",
            data: {
                id: id
            }
        }).done(function (data) {
            // console.log(data);
           
        });
    });
    $(".edit_item").click(function () {
        var id = $(this).attr('atr');
        

        $.ajax({
            url: "{{ url('item')}}/" + id+"/edit",
            method: "get",
            data: {
                id: id
            }
        }).done(function (data) {
            // console.log(data);
            $("#item2").val(data.item['it_name']);
            $("#id_item_edit").val(data.item['it_id']);
        });
    });

    $(".update_item").click(function () {

        var token = $('input[name="_token"]').val();
        var item = $("#item2").val();
        var id = $("#id_item_edit").val();
        $.ajax({
            url: "{{ url('item')}}/" + id,
            method: "post",
            data: {
                _method: "PUT",
                _token: token,
                id: id,
                item: item,
            }
        }).done(function (data) {
            // console.log(data);
            if (data == 1) {
                $('#exampleModal').modal('hide');
                swal({
                    title: "เรียบร้อย?",
                    text: "คุณทำการแก้ไขข้อมูล รายการ Items เรียบร้อย!",
                    icon: "success",
                }).then((willDelete) => {
                    window.location.reload();
                });
            } else {
                console.log(data.errors);
            }
        });
    });

    $(".del_item").click(function () {
        var id = $(this).attr('atr');
        var token = $('input[name="_token"]').val();
        swal({
                title: "คำเตือน!!",
                text: "คุณจะทำการ ลบข้อมูล Items หรือไม่!",
                icon: "warning",
                buttons: true,
                dangerMode: true,
            })
            .then((willDelete) => {
                if (willDelete) {
                    $.ajax({
                        url: "{{ url('item')}}/" + id,
                        method: "post",
                        data: {
                            _method: "DELETE",
                            _token: token,
                            id: id

                        }
                    }).done(function (data) {
                        if (data == 1) {
                            swal({
                                title: "เรียบร้อย?",
                                text: "คุณทำการลบข้อมูล รายการ Items เรียบร้อย!",
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
