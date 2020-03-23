@extends('layouts.master')
@section('title')
แก้ไข Sub Items
@endsection
@section('css')
<link rel="stylesheet" href="{{ asset('assets/css/app.css') }}">
@endsection
@section('content')
<div class="breadcrumb">
    <div class="breadcrumb-element">
        <a class="breadcrumb-item " href="{{ url('dashboard') }}"><i class="lnr lnr-home mr3"></i></a>
        <a class="breadcrumb-item " href="#">แก้ไข Sub Items</a>
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <div class="row">
                    <div class="col-6 col-md-6 text-left">
                        แก้ไข Sub Items
                    </div>
                    <div class="col-6 col-md-6 text-right">
                        <a class="btn btn-secondary" href="{{ url('subitem',$items->sit_it_id) }}">กลับ</a>
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
                    <h5>แบบฟอร์มแก้ไข Sub Items</h5>
                    <form action="{{ url('subitem',$items->sit_id) }}" method="post" enctype="multipart/form-data">
                        @csrf
                        {{ method_field('PUT') }}
                        <div class="row">
                            <div class="col-4 col-md-4">
                                <div class="form-group">
                                    <label for="item">Items</label>
                                    <input type="text" class="form-control" id="item" name="item" placeholder="Items"
                                        value="@if($items->it_name != "" )  {{ $items->it_name }}@endif" readonly>
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
                        <div class="row">
                            <div class="col-4 col-md-4">
                                <div class="form-group">
                                    <label for="net_weight">Net Weight</label>
                                    <input type="text" class="form-control" id="net_weight" name="net_weight"
                                        placeholder="Net Weight" value="{{ $items->sit_netweight }}">
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
                                    <label for="gross_weight">Gross Weight</label>
                                    <input type="text" class="form-control" id="gross_weight" name="gross_weight"
                                        placeholder="Gross Weight" value="{{ $items->sit_grossweight }}">
                                    @if ($errors->has('gross_weight'))
                                    <div class="mx-sm-3 mb-2">
                                        <span class="text-danger" role="alert">
                                            <strong>{{ $errors->first('gross_weight') }}</strong>
                                        </span>
                                    </div>
                                    @endif
                                </div>
                            </div>
                            <div class="col-4 col-md-4">
                                <div class="form-group">
                                    <label for="cbm">CBM</label>
                                    <input type="text" class="form-control" id="cbm" name="cbm" placeholder="CBM" value="{{ $items->sit_cbm }}">
                                    @if ($errors->has('cbm'))
                                    <div class="mx-sm-3 mb-2">
                                        <span class="text-danger" role="alert">
                                            <strong>{{ $errors->first('cbm') }}</strong>
                                        </span>
                                    </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-4 col-md-4">
                                <div class="form-group">
                                    <label for="carton_width">Carton Width</label>
                                    <input type="text" class="form-control" id="carton_width" name="carton_width"
                                        placeholder="Carton Width" value="{{ $items->sit_cartonwidth }}">
                                    @if ($errors->has('carton_width'))
                                    <div class="mx-sm-3 mb-2">
                                        <span class="text-danger" role="alert">
                                            <strong>{{ $errors->first('carton_width') }}</strong>
                                        </span>
                                    </div>
                                    @endif
                                </div>
                            </div>
                            <div class="col-4 col-md-4">
                                <div class="form-group">
                                    <label for="carton_length">Carton Length</label>
                                    <input type="text" class="form-control" id="carton_length" name="carton_length"
                                        placeholder="Carton Length" value="{{ $items->sit_cartonlenght }}">
                                    @if ($errors->has('carton_length'))
                                    <div class="mx-sm-3 mb-2">
                                        <span class="text-danger" role="alert">
                                            <strong>{{ $errors->first('carton_length') }}</strong>
                                        </span>
                                    </div>
                                    @endif
                                </div>
                            </div>
                            <div class="col-4 col-md-4">
                                <div class="form-group">
                                    <label for="carton_height">Carton Height</label>
                                    <input type="text" class="form-control" id="carton_height" name="carton_height"
                                        placeholder="Carton Height" value="{{ $items->sit_cartonheigh }}">
                                    @if ($errors->has('carton_height'))
                                    <div class="mx-sm-3 mb-2">
                                        <span class="text-danger" role="alert">
                                            <strong>{{ $errors->first('carton_height') }}</strong>
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
                                        placeholder="Pallet Volume" readonly value="{{ $items->sit_palletvolume }}">
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
                                    <input type="number" class="form-control" id="pallet_layer" name="pallet_layer"
                                        placeholder="Pallet Layer" value="{{ $items->sit_cartonlayer }}">
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
                                        placeholder="Pallet Per Layer" value="{{ $items->sit_cartonperlayer }}">
                                    @if ($errors->has('pallet_per_layer'))
                                    <div class="mx-sm-3 mb-2">
                                        <span class="text-danger" role="alert">
                                            <strong>{{ $errors->first('pallet_per_layer') }}</strong>
                                        </span>
                                    </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-6 col-md-6">
                                <div class="form-group">
                                    <label for="pallet">Pallet Type</label>

                                    <select name="pallet" id="pallet" class="form-control">
                                        <option value="0" @if(old('pallet')== 0 ) {{ 'selected' }} @endif>-----
                                            โปรดเลือก Type Pallet -----</option>
                                        @foreach ($typepalates as $typepalate)
                                        <option value="{{ $typepalate->tp_id }}" @if($typepalate->tp_id ==
                                            $items->sit_pallet ) {{ 'selected' }}@endif>
                                            {{
                                            $typepalate->tp_width.'x'.$typepalate->tp_length.'x'.$typepalate->tp_hieght
                                            }} ({{ $typepalate->tp_weight.' kgs/PLT' }}) </option>
                                        @endforeach
                                    </select>
                                    @if ($errors->has('pallet'))
                                    <div class="mx-sm-3 mb-2">
                                        <span class="text-danger" role="alert">
                                            <strong>{{ $errors->first('pallet') }}</strong>
                                        </span>
                                    </div>
                                    @endif
                                    {{-- <small style="color:red;">*size pallet is kgs/PLT</small> --}}
                                </div>
                            </div>
                            {{-- <div class="col-6 col-md-6">
                                <div class="form-group">
                                    <label for="type_pallet">Pallet Type Min : Max</label>
                                    <select name="type_pallet" id="type_pallet" class="form-control">
                                        <option value="0" @if($items->sit_typepallet ==0 ) {{ 'selected' }} @endif>-----
                                            โปรดเลือก Type Pallet -----</option>
                                        <option value="2" @if($items->sit_typepallet == 2 ) {{ 'selected' }} @endif>MAX Layer</option>
                                        <option value="1" @if($items->sit_typepallet == 1 ) {{ 'selected' }} @endif>MIN Layer</option>
                                    </select>
                                    @if ($errors->has('type_pallet'))
                                    <div class="mx-sm-3 mb-2">
                                        <span class="text-danger" role="alert">
                                            <strong>{{ $errors->first('type_pallet') }}</strong>
                                        </span>
                                    </div>
                                    @endif
                                    {{-- <small style="color:red;">*size pallet is kgs/PLT</small> --}}
                                </div>
                            </div> --}}
                        </div>
                        <div class="row">
                            <div class="col-6 col-md-6">
                                <div class="form-group">
                                    <label for="c_id">Customer</label>
                                       
                                    <select name="c_id" id="c_id" class="form-control" required>
                                        <option value="0" @if(old('pallet') == 0 ) {{ 'selected' }} @endif>----- โปรดเลือก Customer -----</option>
                                        @foreach ($customers as $customer)
                                            <option  value="{{ $customer->ct_id }}" {{   $customer->ct_id ==  $items->ct_id ? 'selected' : '' }} >{{ $customer->ct_sales_ccn.' / '.$customer->ct_name.' / '.$customer->ct_cus_ship_loc }}</option>
                                        @endforeach
                                    </select>
                                    @if ($errors->has('c_id'))
                                    <div class="mx-sm-3 mb-2">
                                        <span class="text-danger" role="alert">
                                            <strong>{{ $errors->first('c_id') }}</strong>
                                        </span>
                                    </div>
                                    @endif
                                    {{-- <small style="color:red;">*size pallet is kgs/PLT</small> --}}
                                </div>
                            </div>
                        </div>
                        <div class="text-right">
                            <input type="hidden" class="form-control" id="id_item" name="id_item" value="{{ $items->sit_it_id }}">
                            <button type="submit" class="btn btn-primary mb-2">บันทึก</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">แบบฟอร์มแก้ไข Items</h5>
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
                                <input type="text" class="form-control" id="item2" name="item2" placeholder="Items"
                                    value="">
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

    $("#pallet_per_layer").keyup(function () {
        var pallet_layer = $("#pallet_layer").val();
        var pallet_per_layer = $("#pallet_per_layer").val();

        $("#pallet_volume").val(pallet_layer * pallet_per_layer);
    });

    $("#pallet_layer").keyup(function () {
        var pallet_layer = $("#pallet_layer").val();
        var pallet_per_layer = $("#pallet_per_layer").val();

        $("#pallet_volume").val(pallet_layer * pallet_per_layer);
    });

</script>
@endsection
