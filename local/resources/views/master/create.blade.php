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
                <div class="text-right">
                    <a class="btn btn-info" style="color:white;" href="{{ route('palate.create') }}"><i class="fas fa-plus"></i>
                        สร้างรายการ Pallet</a>
                    <a class="btn btn-info" style="color:white;" href="{{ url('ShowCustomer') }}"><i class="fas fa-plus"></i>
                        สร้างรายการ Customer</a>
                </div>
                <form action="{{ url('master') }}" method="post" enctype="multipart/form-data">
                    @csrf

                    <h5 class="text-primary">PRODUCT INFORMATION</h5>
                    <hr>
                    <div class="row">
                        <div class="col-12 col-md-6">
                            <div class="form-group">

                                <label for="customer_name">CUSTOMER NAME</label>
                                <select class="form-control" id="customer_name" name="customer_name">
                                    <option value="0">------ Plaese Select Customer ------</option>
                                    @foreach ($customers as $customer)
                                    <option value="{{ $customer->ct_id }}" @if(old('customer_name')==$customer->ct_id )
                                        {{ 'selected' }} @endif>{{ $customer->ct_name }}</option>
                                    @endforeach
                                </select>
                                @if ($errors->has('customer_name'))
                                <div class="mx-sm-3 mb-2">
                                    <span class="text-danger" role="alert">
                                        <strong>{{ $errors->first('customer_name') }}</strong>
                                    </span>
                                </div>
                                @endif
                                <small id="emailHelp" class="form-text text-muted">We'll never share your email
                                    with anyoneelse.</small>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12 col-md-6">
                            
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-4 col-md-4">
                            <div class="form-group">
                                <label for="fg_id">TYPE</label>
                                <input type="text" class="form-control" id="type" name="type" placeholder="TYPE" value="{{ old('type') }}">
                                @if ($errors->has('type'))
                                <div class="mx-sm-3 mb-2">
                                    <span class="text-danger" role="alert">
                                        <strong>{{ $errors->first('type') }}</strong>
                                    </span>
                                </div>
                                @endif
                            </div>
                        </div>
                        <div class="col-4 col-md-4">
                            <div class="form-group">
                                <label for="fg_id">FG/ID</label>
                                <input type="text" class="form-control" id="fg_id" name="fg_id" placeholder="FG/ID"
                                    value="{{ old('fg_id') }}">
                                @if ($errors->has('fg_id'))
                                <div class="mx-sm-3 mb-2">
                                    <span class="text-danger" role="alert">
                                        <strong>{{ $errors->first('fg_id') }}</strong>
                                    </span>
                                </div>
                                @endif
                            </div>
                        </div>
                        <div class="col-4 col-md-4">
                            <div class="form-group">
                                <label for="part_no">PART NO. / STOCK NO.</label>
                                <input type="text" class="form-control" id="part_no" name="part_no" placeholder="PART NO. / STOCK NO."
                                    value="{{ old('part_no') }}">
                                @if ($errors->has('part_no'))
                                <div class="mx-sm-3 mb-2">
                                    <span class="text-danger" role="alert">
                                        <strong>{{ $errors->first('part_no') }}</strong>
                                    </span>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-6 col-md-6">
                            <div class="form-group">
                                <label for="size_wheel_mm">SIZE WHEEL (MM)</label>
                                <input type="text" class="form-control" id="size_wheel_mm" name="size_wheel_mm"
                                    placeholder="SIZE WHEEL (MM)" value="{{ old('size_wheel_mm') }}">
                                @if ($errors->has('size_wheel_mm'))
                                <div class="mx-sm-3 mb-2">
                                    <span class="text-danger" role="alert">
                                        <strong>{{ $errors->first('size_wheel_mm') }}</strong>
                                    </span>
                                </div>
                                @endif
                            </div>
                        </div>
                        <div class="col-6 col-md-6">
                            <div class="form-group">
                                <label for="size_wheel_inch">SIZE WHEEL (INCH)</label>
                                <input type="text" class="form-control" id="size_wheel_inch" name="size_wheel_inch"
                                    placeholder="SIZE WHEEL (INCH)" value="{{ old('size_wheel_inch') }}">
                                @if ($errors->has('size_wheel_inch'))
                                <div class="mx-sm-3 mb-2">
                                    <span class="text-danger" role="alert">
                                        <strong>{{ $errors->first('size_wheel_inch') }}</strong>
                                    </span>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="spec">SPEC</label>
                                <input type="text" class="form-control" id="spec" name="spec" placeholder="SPEC" value="{{ old('spec') }}">
                                @if ($errors->has('spec'))
                                <div class="mx-sm-3 mb-2">
                                    <span class="text-danger" role="alert">
                                        <strong>{{ $errors->first('spec') }}</strong>
                                    </span>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <br>
                    <h5 class="text-primary">PACKING QTY</h5>
                    <hr>
                    <div class="row">
                        <div class="col-4 col-md-4">
                            <div class="form-group">
                                <label for="inner">INNER (PCS)</label>
                                <input type="text" class="form-control" id="inner" name="inner" placeholder="INNER (PCS)"
                                    value="{{ old('inner') }}">
                                @if ($errors->has('inner'))
                                <div class="mx-sm-3 mb-2">
                                    <span class="text-danger" role="alert">
                                        <strong>{{ $errors->first('inner') }}</strong>
                                    </span>
                                </div>
                                @endif
                            </div>
                        </div>
                        <div class="col-4 col-md-4">
                            <div class="form-group">
                                <label for="outer">OUTER (PCS)</label>
                                <input type="text" class="form-control" id="outer" name="outer" placeholder="OUTER (PCS)"
                                    value="{{ old('outer') }}">
                                @if ($errors->has('outer'))
                                <div class="mx-sm-3 mb-2">
                                    <span class="text-danger" role="alert">
                                        <strong>{{ $errors->first('outer') }}</strong>
                                    </span>
                                </div>
                                @endif
                            </div>
                        </div>
                        <div class="col-4 col-md-4">
                            <div class="form-group">
                                <label for="over_outer">OVER OUTER (PCS)</label>
                                <input type="text" class="form-control" id="over_outer" name="over_outer" placeholder="OUTER (PCS)"
                                    value="{{ old('over_outer') }}">
                                @if ($errors->has('over_outer'))
                                <div class="mx-sm-3 mb-2">
                                    <span class="text-danger" role="alert">
                                        <strong>{{ $errors->first('over_outer') }}</strong>
                                    </span>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                    <br>
                    <h5 class="text-primary">OUTER DIAMENSION BOX</h5>
                    <hr>
                    <div class="row">
                        <div class="col-4 col-md-4">
                            <div class="form-group">
                                <label for="width_cm">WIDTH (CM)</label>
                                <input type="text" class="form-control" id="width_cm" name="width_cm" placeholder="WIDTH (CM)"
                                    value="{{ old('width_cm') }}">
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
                                    value="{{ old('length_cm') }}">
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
                                    value="{{ old('highth_cm') }}">
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
                                <input type="text" class="form-control" id="cbm" name="cbm" placeholder="CBM (M2)"
                                    value="{{ old('cbm') }}">
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
                                    value="{{ old('net_weight') }}">
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
                                <input type="text" class="form-control" id="gross_weight" name="gross_weight"
                                    placeholder="Gross  Weight (Kgs)" value="{{ old('gross_weight') }}">
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
                    <br>
                    <h5 class="text-primary">Quantity / PALLET </h5>
                    <hr>
                    <div class="row">
                        <div class="col-6 col-md-3">
                            <div class="form-group">
                                <label for="pallet_size1">PALLET NO.1</label>
                                <br>
                                <select class="form-control" id="pallet_size1" name="pallet_size1">
                                    <option value="0">---- Plaese Select Pallet NO.1 ----</option>
                                    @foreach ($typepalates as $typepalate)
                                    <option value="{{ $typepalate->tp_id }}" @if (old('pallet_size1')==$typepalate->tp_id)
                                        {{ 'selected' }} @endif>{{ $typepalate->tp_width.'x'.$typepalate->tp_length.' x
                                        '.$typepalate->tp_hieght.' CM-'.$typepalate->tp_weight.'kgs/PLT' }}</option>
                                    @endforeach
                                </select>
                                @if ($errors->has('pallet_size1'))
                                <div class="mx-sm-3 mb-2">
                                    <span class="text-danger" role="alert">
                                        <strong>{{ $errors->first('pallet_size1') }}</strong>
                                    </span>
                                </div>
                                @endif
                                {{-- <small id="emailHelp" class="form-text text-muted">We'll never share your email
                                    with anyoneelse.</small> --}}
                            </div>
                        </div>
                        <div class="col-6 col-md-3">
                            <div class="form-group">
                                <label for="pallet_size_1">PALLET SIZE NO.1</label>
                                <input type="text" class="form-control" id="pallet_size_1" name="pallet_size_1"
                                    placeholder="PALLET SIZE NO.1" value="{{ old('pallet_size_1') }}">
                                <br>
                                @if ($errors->has('pallet_size_1'))
                                <div class="mx-sm-3 mb-2">
                                    <span class="text-danger" role="alert">
                                        <strong>{{ $errors->first('pallet_size_1') }}</strong>
                                    </span>
                                </div>
                                @endif
                                {{-- <small id="emailHelp" class="form-text text-muted">We'll never share your email
                                    with anyoneelse.</small> --}}
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-6 col-md-3">
                            <div class="form-group">
                                <label for="pallet_size2">PALLET NO.2</label>
                                <br>
                                <select class="form-control" id="pallet_size2" name="pallet_size2">
                                    <option value="0">---- Plaese Select Pallet NO.2 ----</option>
                                    @foreach ($typepalates as $typepalate)
                                    <option value="{{ $typepalate->tp_id }}" @if (old('pallet_size2')==$typepalate->tp_id)
                                        {{ 'selected' }} @endif >{{ $typepalate->tp_width.'x'.$typepalate->tp_length.'
                                        x '.$typepalate->tp_hieght.' CM-'.$typepalate->tp_weight.'kgs/PLT' }}</option>
                                    @endforeach
                                </select>
                                @if ($errors->has('pallet_size2'))
                                <div class="mx-sm-3 mb-2">
                                    <span class="text-danger" role="alert">
                                        <strong>{{ $errors->first('pallet_size2') }}</strong>
                                    </span>
                                </div>
                                @endif
                                {{-- <small id="emailHelp" class="form-text text-muted">We'll never share your email
                                    with anyoneelse.</small> --}}
                            </div>
                        </div>
                        <div class="col-6 col-md-3">
                            <div class="form-group">
                                <label for="pallet_size_2">PALLET SIZE NO.2</label>
                                <input type="text" class="form-control" id="pallet_size_2" name="pallet_size_2"
                                    placeholder="PALLET SIZE NO.2" value="{{ old('pallet_size_2') }}">
                                <br>
                                @if ($errors->has('pallet_size_2'))
                                <div class="mx-sm-3 mb-2">
                                    <span class="text-danger" role="alert">
                                        <strong>{{ $errors->first('pallet_size_2') }}</strong>
                                    </span>
                                </div>
                                @endif
                                {{-- <small id="emailHelp" class="form-text text-muted">We'll never share your email
                                    with anyoneelse.</small> --}}
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-6 col-md-3">
                            <div class="form-group">
                                <label for="pallet_size3">PALLET SIZE NO.3</label>
                                <br>
                                <select class="form-control" id="pallet_size3" name="pallet_size3">
                                    <option value="0">---- Plaese Select Pallet NO.3 ----</option>
                                    @foreach ($typepalates as $typepalate)
                                    <option value="{{ $typepalate->tp_id }}" @if (old('pallet_size3')==$typepalate->tp_id)
                                        {{ 'selected' }} @endif>{{ $typepalate->tp_width.'x'.$typepalate->tp_length.' x
                                        '.$typepalate->tp_hieght.' CM-'.$typepalate->tp_weight.'kgs/PLT' }}</option>
                                    @endforeach
                                </select>
                                @if ($errors->has('pallet_size3'))
                                <div class="mx-sm-3 mb-2">
                                    <span class="text-danger" role="alert">
                                        <strong>{{ $errors->first('pallet_size3') }}</strong>
                                    </span>
                                </div>
                                @endif
                            </div>
                        </div>
                        <div class="col-6 col-md-3">
                            <div class="form-group">
                                <label for="pallet_size_3">PALLET SIZE NO.3</label>
                                <input type="text" class="form-control" id="pallet_size_3" name="pallet_size_3"
                                    placeholder="PALLET SIZE NO.3" value="{{ old('pallet_size_3') }}">
                                <br>
                                @if ($errors->has('pallet_size_3'))
                                <div class="mx-sm-3 mb-2">
                                    <span class="text-danger" role="alert">
                                        <strong>{{ $errors->first('pallet_size_3') }}</strong>
                                    </span>
                                </div>
                                @endif
                                {{-- <small id="emailHelp" class="form-text text-muted">We'll never share your email
                                    with anyoneelse.</small> --}}
                            </div>
                        </div>
                    </div>

                    <div class="text-right"><button type="submit" class="btn btn-primary">บันทึก</button></div>
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

    $(document).ready(function () {
        $('#product1').DataTable();
    });

</script>
@endsection
