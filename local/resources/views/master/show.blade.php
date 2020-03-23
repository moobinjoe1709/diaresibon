@extends('layouts.master')
@section('title')
สร้าง Master Products
@endsection
@section('css')
<link rel="stylesheet" href="{{ asset('assets/css/app.css') }}">
@endsection
@section('content')
<div class="breadcrumb">
    <div class="breadcrumb-element">
        <a class="breadcrumb-item " href="{{ url('dashboard') }}"><i class="lnr lnr-home mr3"></i></a>
        <a class="breadcrumb-item " href="{{ url('master') }}">ตารางาน Master Products</a>
        <a class="breadcrumb-item " href="#">สร้าง Master Products</a>
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <div class="row">
                    <div class="col-6 col-md-6 text-left">
                        สร้าง Master Products
                    </div>
                    <div class="col-6 col-md-6 text-right">
                        <a class="btn btn-secondary" href="{{ url('master') }}">กลับ</a>
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
                {{-- <div class="text-right">
                    <a class="btn btn-info" style="color:white;" href="{{ route('palate.create') }}"><i class="fas fa-plus"></i>
                        สร้างรายการ Pallet</a>
                    <a class="btn btn-info" style="color:white;" href="{{ url('ShowCustomer') }}"><i class="fas fa-plus"></i>
                        สร้างรายการ Customer</a>
                </div> --}}
                <form action="{{ url('master') }}" method="post" enctype="multipart/form-data">
                    @csrf

                    <h5 class="text-primary">MASTER INFORMATION</h5>
                    <hr>
                    <table class="table table-bordered bg-primary text-white" id="">
                        <tbody>
                            <tr class="text-center">
                                <th class="text-center">CCN : <strong>{{ $customers->ct_sales_ccn }}</strong></th>
                                <th class="text-center"> Customer : <strong>{{ $customers->ct_name }}</strong></th>
                                <th class="text-center">Location : <strong>{{ $customers->ct_cus_ship_loc }}</strong></th>
                            </tr>

                        </tbody>
                    </table>
                    <div class="row">
                        <div class="col-12 col-md-6">

                        </div>
                    </div>
                    <div class="row">
                        <div class="col-4 col-md-4">
                            <div class="form-group">
                                <label for="fg_id">ITEM</label>
                                <select name="fg_id" id="fg_id" class="form-control">
                                    <option value="0">------- กรุณาเลือก ITEM -------</option>
                                    @foreach ($items as $item)
                                    <option value="{{ $item->it_id }}">{{ $item->it_name }}</option>
                                    @endforeach
                                </select>
                                @if ($errors->has('fg_id'))
                                <div class="mx-sm-3 mb-2">
                                    <span class="text-danger" role="alert">
                                        <strong>{{ $errors->first('fg_id') }}</strong>
                                    </span>
                                </div>
                                @endif
                                {{-- <input type="text" class="form-control" id="fg_id" name="fg_id" placeholder="ITEM"
                                    value="{{ old('fg_id') }}">
                                @if ($errors->has('fg_id'))
                                <div class="mx-sm-3 mb-2">
                                    <span class="text-danger" role="alert">
                                        <strong>{{ $errors->first('fg_id') }}</strong>
                                    </span>
                                </div>
                                @endif --}}
                            </div>
                        </div>
                    </div>

                    <div class="text-right">
                        <input type="hidden" name="customer_id" id="customer_id" value="{{ $customers->ct_id }}">
                        <button type="submit" class="btn btn-primary">บันทึก</button>
                    </div>
                </form>
                <br>

                {{-- <h5 class="text-primary">DIAMENSION BOX</h5>
                <hr> 
                <div class="row">
                    <div class="col-4 col-md-4">
                        <div class="form-group">
                            <label for="width_cm">WIDTH (CM)</label>
                            <input type="text" class="form-control" id="width_cm" name="width_cm" placeholder="WIDTH (CM)"
                                value="" readonly>
                            @if ($errors->has('width_cm'))
                            <div class="mx-sm-3 mb-2">
                                <span class="text-danger" role="alert">
                                    <strong>{{ $errors->first('width_cm') }}</strong>
                                </span>
                            </div>
                            @endif
                        </div>
                    </div>
                    <div class="col-4 col-md-4">
                        <div class="form-group">
                            <label for="length_cm">LENGTH (CM)</label>
                            <input type="text" class="form-control" id="length_cm" name="length_cm" placeholder="LENGTH (CM)"
                                value="" readonly>
                            @if ($errors->has('length_cm'))
                            <div class="mx-sm-3 mb-2">
                                <span class="text-danger" role="alert">
                                    <strong>{{ $errors->first('length_cm') }}</strong>
                                </span>
                            </div>
                            @endif
                        </div>
                    </div>
                    <div class="col-4 col-md-4">
                        <div class="form-group">
                            <label for="highth_cm">HIGHTH (CM)</label>
                            <input type="text" class="form-control" id="highth_cm" name="highth_cm" placeholder="HIGHTH (CM)"
                                value="" readonly>
                            @if ($errors->has('highth_cm'))
                            <div class="mx-sm-3 mb-2">
                                <span class="text-danger" role="alert">
                                    <strong>{{ $errors->first('highth_cm') }}</strong>
                                </span>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-4 col-md-4">
                        <div class="form-group">
                            <label for="cbm">CBM (M<sup>2</sup>)</label>
                            <input type="text" class="form-control" id="cbm" name="cbm" placeholder="CBM (M2)" value=""
                                readonly>
                            @if ($errors->has('cbm'))
                            <div class="mx-sm-3 mb-2">
                                <span class="text-danger" role="alert">
                                    <strong>{{ $errors->first('cbm') }}</strong>
                                </span>
                            </div>
                            @endif
                        </div>
                    </div>
                    <div class="col-4 col-md-4">
                        <div class="form-group">
                            <label for="net_weight">Net Weight (Kg<sub>s</sub>)</label>
                            <input type="text" class="form-control" id="net_weight" name="net_weight" placeholder="Net Weight (Kgs)"
                                value="" readonly>
                            @if ($errors->has('net_weight'))
                            <div class="mx-sm-3 mb-2">
                                <span class="text-danger" role="alert">
                                    <strong>{{ $errors->first('net_weight') }}</strong>
                                </span>
                            </div>
                            @endif
                        </div>
                    </div>
                    <div class="col-4 col-md-4">
                        <div class="form-group">
                            <label for="gross_weight">Gross Weight (Kg<sub>s</sub>)</label>
                            <input type="text" class="form-control" id="gross_weight" name="gross_weight" placeholder="Gross  Weight (Kgs)"
                                value="" readonly>
                            @if ($errors->has('gross_weight'))
                            <div class="mx-sm-3 mb-2">
                                <span class="text-danger" role="alert">
                                    <strong>{{ $errors->first('gross_weight') }}</strong>
                                </span>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-4 col-md-4">
                        <div class="form-group">
                            <label for="pallet_volume">Pallet Volume</label>
                            <input type="number" class="form-control" id="pallet_volume" name="pallet_volume"
                                placeholder="Pallet Volume" readonly>
                            @if ($errors->has('pallet_volume'))
                            <div class="mx-sm-3 mb-2">
                                <span class="text-danger" role="alert">
                                    <strong>{{ $errors->first('pallet_volume') }}</strong>
                                </span>
                            </div>
                            @endif
                        </div>
                    </div>
                    <div class="col-4 col-md-4">
                        <div class="form-group">
                            <label for="pallet_layer">Pallet Layer</label>
                            <input type="number" class="form-control" id="pallet_layer" name="pallet_layer" placeholder="Pallet Layer"
                                value="{{ old('pallet_layer') }}" readonly>
                            @if ($errors->has('pallet_layer'))
                            <div class="mx-sm-3 mb-2">
                                <span class="text-danger" role="alert">
                                    <strong>{{ $errors->first('pallet_layer') }}</strong>
                                </span>
                            </div>
                            @endif
                        </div>
                    </div>
                    <div class="col-4 col-md-4">
                        <div class="form-group">
                            <label for="pallet_per_layer">Pallet Per Layer</label>
                            <input type="number" class="form-control" id="pallet_per_layer" name="pallet_per_layer"
                                placeholder="Pallet Per Layer" value="{{ old('pallet_per_layer') }}" readonly>
                            @if ($errors->has('pallet_per_layer'))
                            <div class="mx-sm-3 mb-2">
                                <span class="text-danger" role="alert">
                                    <strong>{{ $errors->first('pallet_per_layer') }}</strong>
                                </span>
                            </div>
                            @endif
                        </div>
                    </div>
                </div> --}}
                <br>
                <br>
                <h5 class="text-primary">Master List</h5>
                <hr>
                <div class="rov">

                    <div class="table-responsive">
                        <table class="table table-bordered table-hover table-striped" id="product1">
                            <thead>
                                <tr class="text-center">
                                    <th width="5%" >#</th>
                                    <th>Item</th>
                                    <th>Manage</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($masters as $key => $master)
                                <tr>
                                    <td class="text-center">{{ ++$key }}</td>
                                    <td class="text-center">{{ $master->it_name }}</td>
                                    <td class="text-center" width="15%">
                                        {{-- <a href="{{ route('master.show',$customer->ct_id)}}" class="btn btn-info edit_custo"
                                            id="edit_custo" style="color:white;" atr="{{ $customer->ct_id }}"><i class="fas fa-plus"></i></a>
                                        <a href="{{ route('master.edit',$customer->ct_id)}}" class="btn btn-success edit_custo"
                                            id="edit_custo" style="color:white;" atr="{{ $customer->ct_id }}"><i class="far fa-edit"></i></a>
                                        --}}
                                        <a class="btn btn-danger del_master" id="del_master" atr="{{ $master->mt_id }}"
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

    $("#fg_id").change(function () {
        var token = $("input[name=_token]").val();
        var id = $("#fg_id").val();
        $.ajax({
            url: "{{ url('FindItems') }}" + "/" + id,
            type: "post",
            data: {
                id: id,
                _token: token
            }
        }).done(function (data) {
            // console.log(data);
            $("#width_cm").val(data.sit_cartonwidth);
            $("#length_cm").val(data.sit_cartonlenght);
            $("#highth_cm").val(data.sit_cartonheigh);
            $("#cbm").val(data.sit_cbm);
            $("#net_weight").val(data.sit_netweight);
            $("#gross_weight").val(data.sit_grossweight);
            $("#pallet_layer").val(data.sit_cartonlayer);
            $("#pallet_per_layer").val(data.sit_cartonperlayer);
            $("#pallet_volume").val(data.sit_palletvolume);
        });
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
                            _method: "DELETE",
                            _token: token,
                            id: id

                        }
                    }).done(function (data) {
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
