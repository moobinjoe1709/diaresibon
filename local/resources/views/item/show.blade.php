@extends('layouts.master')
@section('title')
สร้าง Sub Items
@endsection
@section('css')
<link rel="stylesheet" href="{{ asset('assets/css/app.css') }}">
@endsection
@section('content')
<div class="breadcrumb">
    <div class="breadcrumb-element">
        <a class="breadcrumb-item " href="{{ url('dashboard') }}"><i class="lnr lnr-home mr3"></i></a>
        <a class="breadcrumb-item " href="#">สร้าง Sub Items</a>
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <div class="row">
                    <div class="col-6 col-md-6 text-left">
                        สร้าง Sub Items
                    </div>
                    <div class="col-6 col-md-6 text-right">
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
                <div class="form-add-customer">
                    <h5>แบบฟอร์มสร้าง Sub Items</h5>
                    <form action="{{ url('subitem') }}" method="post" enctype="multipart/form-data">
                        @csrf
                        {{-- {{ method_field('PUT') }} --}}
                        <div class="row">
                            <div class="col-4 col-md-4">
                                <div class="form-group">
                                    <label for="item">Items</label>
                                    <input type="text" class="form-control" id="item" name="item" placeholder="Items"
                                        value="{{ $items2->it_name != "" ? $items2->it_name : '' }}" readonly>
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
                                        placeholder="Net Weight" value="@if(isset($items[0]->sit_netweight))@if($items[0]->sit_netweight != "" ){{ $items[0]->sit_netweight }} @endif  @else{{old('net_weight')}}@endif">
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
                                        placeholder="Gross Weight" value="@if(isset($items[0]->sit_grossweight))@if($items[0]->sit_grossweight != "" ){{ $items[0]->sit_grossweight }}  @endif  @else{{old('gross_weight')}}@endif">
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
                                    <input type="text" class="form-control" id="cbm" name="cbm" placeholder="CBM" value="@if(isset($items[0]->sit_cbm))@if($items[0]->sit_cbm != "" ){{ $items[0]->sit_cbm }} @endif @else{{ old('cbm')}}@endif">
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
                                        placeholder="Carton Width" value="@if(isset($items[0]->sit_cartonwidth))@if($items[0]->sit_cartonwidth != "" ){{ $items[0]->sit_cartonwidth }}  @endif @else{{old('carton_width') }}@endif">
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
                                        placeholder="Carton Length" value="@if(isset($items[0]->sit_cartonlenght))@if($items[0]->sit_cartonlenght != "" ){{ $items[0]->sit_cartonlenght }}@endif @else{{old('carton_length')}}@endif">
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
                                        placeholder="Carton Height" value="@if(isset($items[0]->sit_cartonheigh))@if($items[0]->sit_cartonheigh != "" ){{ $items[0]->sit_cartonheigh }}@endif @else{{old('carton_height')}}@endif">
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
                                        placeholder="Pallet Volume" value="{{ old('pallet_volume') }}" readonly>
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
                                    <label for="pallet_layer">Pallet Layer (กล่อง)</label>
                                    <input type="number" class="form-control" id="pallet_layer" name="pallet_layer"
                                        placeholder="Pallet Layer" value="{{ old('pallet_layer') }}">
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
                                    <label for="pallet_per_layer">Pallet Per Layer (ชั้น)</label>
                                    <input type="number" class="form-control" id="pallet_per_layer" name="pallet_per_layer"
                                        placeholder="Pallet Per Layer" value="{{ old('pallet_per_layer') }}">
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
                                        <option value="0" {{ old('pallet') == 0  ? 'selected' : ''  }}>----- โปรดเลือก Type Pallet -----</option>
                                        @foreach ($typepalates as $typepalate)
                                        <option value="{{$typepalate->tp_id}}" {{   $typepalate->tp_id ==  old('pallet') ? 'selected' : '' }}>
                                            {{ $typepalate->tp_width.'x'.$typepalate->tp_length.'x'.$typepalate->tp_hieght }} ({{ $typepalate->tp_weight.'  kgs/PLT' }}) </option>
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
                                        <option value="0" @if(old('type_pallet')==0 ) {{ 'selected' }} @endif>-----
                                            โปรดเลือก Type Pallet -----</option>
                                        <option value="2" @if(old('type_pallet')==2 ) {{ 'selected' }} @endif>MAX Layer</option>
                                        <option value="1" @if(old('type_pallet')==1 ) {{ 'selected' }} @endif>MIN Layer</option>
                                    </select>
                                    @if ($errors->has('type_pallet'))
                                    <div class="mx-sm-3 mb-2">
                                        <span class="text-danger" role="alert">
                                            <strong>{{ $errors->first('type_pallet') }}</strong>
                                        </span>
                                    </div>
                                    @endif
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
                                            <option  value="{{ $customer->ct_id }}" {{   $customer->ct_id ==  old('c_id') ? 'selected' : '' }} >{{ $customer->ct_sales_ccn.' / '.$customer->ct_name.' / '.$customer->ct_cus_ship_loc }}</option>
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
                            <input type="hidden" class="form-control" id="id_item" name="id_item" value="{{ $items2->it_id }}">
                            <button type="submit" class="btn btn-primary mb-2">บันทึก</button>
                        </div>
                    </form>
                </div>
                <hr>
                <div class="table-responsive">
                    <table class="table table-bordered table-hover table-striped" id="product1">
                        <thead>
                            <tr class="text-center">
                                <th width="5%">#</th>
                                <th width="15%">Customer Name</th>
                                <th>Carton Width</th>
                                <th>Carton Length</th>
                                <th>Carton Height</th>
                                <th>Net Weight</th>
                                <th>Gross Weight</th>
                                <th>CBM</th>
                                <th>Pallet Volume</th>
                                <th>Pallet Type</th>
                                <th >Manage</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($items as $key => $item)
                            <tr>
                                <td class="text-center">{{ ++$key }}</td>
                                <td class="text-center">{{ $item->ct_sales_ccn.' / '.$item->ct_name.' / '.$item->ct_cus_ship_loc }}</td>
                                <td class="text-center">{{ $item->sit_cartonwidth }}</td>
                                <td class="text-center">{{ $item->sit_cartonlenght }}</td>
                                <td class="text-center">{{ $item->sit_cartonheigh }}</td>
                                <td class="text-center">{{ $item->sit_netweight }}</td>
                                <td class="text-center">{{ $item->sit_grossweight }}</td>
                                <td class="text-center">{{ $item->sit_cbm }}</td>
                                <td class="text-center">{{ $item->sit_cartonlayer.'x'.$item->sit_cartonperlayer }} {{
                                    '('.$item->sit_palletvolume.')' }}</td>
                                <td class="text-center">
                                    {{ $item->tp_width.'x'.$item->tp_length.'x'.$item->tp_hieght }} 
                                    {{-- @if($item->sit_typepallet == 1 ) 
                                        {{ $item->tp_width.'x'.$item->tp_length.'x'.$item->tp_hieght.'  (MIN LAYER)' }} 
                                    @else 
                                        {{ $item->tp_width.'x'.$item->tp_length.'x'.$item->tp_hieght.'  (MAX LAYER)' }} 
                                    @endif --}}
                                </td>
                                <td class="text-center">
                                    <a href="{{ url('subitem',$item->sit_id).'/edit' }}" class="btn btn-success edit_item"
                                        id="edit_item" style="color:white;" atr="{{ $item->sit_id }}"><i class="far fa-edit"></i></a>
                                    <a class="btn btn-danger del_subitem" id="del_subitem" atr="{{ $item->sit_id }}"
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

    $(".del_subitem").click(function () {
        var id = $(this).attr('atr');
        var token = $('input[name="_token"]').val();
        swal({
                title: "คำเตือน!!",
                text: "คุณจะทำการ ลบข้อมูล SubItem หรือไม่!",
                icon: "warning",
                buttons: true,
                dangerMode: true,
            })
            .then((willDelete) => {
                if (willDelete) {
                    $.ajax({
                        url: "{{ url('subitem')}}/" + id,
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
                                text: "คุณทำการลบข้อมูล รายการ SubItem เรียบร้อย!",
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
