@extends('layouts.master')
@section('title')
สร้าง Pallet
@endsection
@section('css')
<link rel="stylesheet" href="{{ asset('assets/css/app.css') }}">
@endsection
@section('content')
<div class="breadcrumb">
    <div class="breadcrumb-element">
        <a class="breadcrumb-item " href="{{ url('dashboard') }}"><i class="lnr lnr-home mr3"></i></a>
        <a class="breadcrumb-item " href="#">สร้าง Pallet</a>
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <div class="row">
                    <div class="col-6 col-md-6 text-left">
                        สร้าง Pallet
                    </div>
                    <div class="col-6 col-md-6 text-right">
                        <a class="btn btn-secondary" href="{{ url('master/create') }}">กลับ</a>
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
                <form action="{{ url('palate') }}" method="post" enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                        <div class="col-4 col-md-4">
                            <div class="form-group">
                                <label for="width_cm">WIDTH (CM)</label>
                                <input type="number" class="form-control" id="width_cm" name="width_cm" placeholder="WIDTH (CM)"
                                    value="{{ old('width_cm') }}" step="any">
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
                                <input type="number" class="form-control" id="length_cm" name="length_cm" placeholder="LENGTH (CM)"
                                    value="{{ old('length_cm') }}" step="any">
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
                                <label for="hieght_cm">HIEGHT (CM)</label>
                                <input type="number" class="form-control" id="hieght_cm" name="hieght_cm" placeholder="HIEGHT (CM)"
                                    value="{{ old('hieght_cm') }}" step="any">
                                @if ($errors->has('hieght_cm'))
                                <div class="mx-sm-3 mb-2">
                                    <span class="text-danger" role="alert">
                                        <strong>{{ $errors->first('hieght_cm') }}</strong>
                                    </span>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-6 col-md-6">
                            <div class="form-group">
                                <label for="wieght_pallet">WEIGHT PALLET (kgs/PLT)</label>
                                <input type="number" class="form-control" id="wieght_pallet" name="wieght_pallet"
                                    placeholder="WEIGHT PALLET (kgs/PLT)" value="{{ old('wieght_pallet') }}" step="any">
                                @if ($errors->has('wieght_pallet'))
                                <div class="mx-sm-3 mb-2">
                                    <span class="text-danger" role="alert">
                                        <strong>{{ $errors->first('wieght_pallet') }}</strong>
                                    </span>
                                </div>
                                @endif
                                {{-- <small style="color:red;">*size pallet is kgs/PLT</small> --}}
                            </div>
                        </div>
                    </div>
                    <div class="text-right"><button type="submit" class="btn btn-primary">บันทึก</button></div>
                </form>
                <hr>
                <h5>ตารางรายการ Pallet</h5>
                <div class="table-responsive">
                    <table class="table table-bordered table-hover table-striped" id="product1">
                        <thead>
                            <tr class="text-center">
                                <th>#</th>
                                <th>Pallet Information</th>
                                <th>Pallet Weight</th>
                                <th>Manage</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($typepalates as $key => $typepalate)
                            <tr>
                                <td class="text-center">{{ ++$key }}</td>
                                <td class="text-center">{{
                                    $typepalate->tp_width.'x'.$typepalate->tp_length.'x'.$typepalate->tp_hieght.' CM'
                                    }}</td>
                                <td class="text-center">{{ $typepalate->tp_weight.'  (kgs/PLT)' }}</td>
                                <td class="text-center">
                                    <a href="{{ route('palate.edit',$typepalate->tp_id) }}" class="btn btn-success edit_pallet"
                                        id="edit_pallet" style="color:white;" atr="{{ $typepalate->tp_id }}"><i class="far fa-edit"></i></a>
                                    <a class="btn btn-danger del_pallet" id="del_pallet" atr="{{ $typepalate->tp_id }}"
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

@endsection
@section('js')
<script>
    $(document).ready(function () {
        $("[rel='tooltip']").tooltip();
    });

    $(document).ready(function () {
        $('#product1').DataTable();
        $('#product2').DataTable();
        $('#product3').DataTable();
    });

</script>
<script>
    $(".del_pallet").click(function () {
        var id = $(this).attr('atr');
        var token = $('input[name="_token"]').val();
        swal({
                title: "คำเตือน!!",
                text: "คุณจะทำการ ลบข้อมูล Pallet หรือไม่!",
                icon: "warning",
                buttons: true,
                dangerMode: true,
            })
            .then((willDelete) => {
                if (willDelete) {
                    $.ajax({
                        url: "{{ url('palate')}}/" + id,
                        method: "post",
                        data: {
                            _token: token,
                            id: id,
                            _method: "DELETE",
                        }
                    }).done(function (data) {
                        if (data == 1) {
                            swal({
                                title: "เรียบร้อย?",
                                text: "คุณทำการลบข้อมูล รายการ Pallet เรียบร้อย!",
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
