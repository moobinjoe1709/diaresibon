@extends('layouts.master')
@section('title')
แก้ไข Pallet
@endsection
@section('css')
<link rel="stylesheet" href="{{ asset('assets/css/app.css') }}">
@endsection
@section('content')
<div class="breadcrumb">
    <div class="breadcrumb-element">
        <a class="breadcrumb-item " href="{{ url('dashboard') }}"><i class="lnr lnr-home mr3"></i></a>
        <a class="breadcrumb-item " href="#">แก้ไข Pallet</a>
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <div class="row">
                    <div class="col-6 col-md-6 text-left">
                        แก้ไข Pallet
                    </div>
                    <div class="col-6 col-md-6 text-right">
                        <a class="btn btn-secondary" href="{{ url('palate/create') }}">กลับ</a>
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
                <form action="{{ url('palate',$typetpallet->tp_id) }}" method="post" enctype="multipart/form-data">
                    @csrf
                    {{ method_field('PUT') }}
                    <div class="row">
                        <div class="col-4 col-md-4">
                            <div class="form-group">
                                <label for="width_cm">WIDTH (CM)</label>
                                <input type="number" class="form-control" id="width_cm" name="width_cm" placeholder="WIDTH (CM)"
                                    value="{{ $typetpallet->tp_width }}" step="any">
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
                                    value="{{ $typetpallet->tp_length }}" step="any">
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
                                    value="{{ $typetpallet->tp_hieght }}" step="any">
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
                                    placeholder="WEIGHT PALLET (kgs/PLT)" value="{{ $typetpallet->tp_weight }}" step="any">
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
                    <div class="text-right"><button type="submit" class="btn btn-primary mb-2">แก้ไข</button></div>
                    {{-- <div class="text-right"><button type="submit" class="btn btn-primary">บันทึก</button></div>
                    --}}
                </form>

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

</script>
@endsection
