@extends('layouts.master')
@section('title')
รายการ Shipment
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
        <a class="breadcrumb-item " href="#">รายการ Adjust</a>
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">รายการ Adjust</div>
            <div class="card-body">
                @if (\Session::has('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        <i class="fas fa-check-circle pr5"></i>สำเร็จ!</strong> {!! \Session::get('success') !!}
                    </div>
                @elseif(\Session::has('danger'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        <i class="fas fa-exclamation-circle pr5"></i>คำเตือน!</strong> {!! \Session::get('danger') !!}
                    </div>
                @endif
                <table class="table table-bordered table-hover table-striped" id="product1">
                        <thead>
                            <tr class="text-center">
                                <th class="text-center" width="5%">#</th>
                                <th class="text-center">Sale CCN</th>
                                <th class="text-center">Mas Loc</th>
                                <th class="text-center">Promise Date</th>
                                <th class="text-center">Ship Via</th>
                                <th class="text-center">Customer</th>
                                <th class="text-center">Cus Ship Loc</th>
                                <th class="text-center" width="15%">Manage</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($boxs as $key => $box)
                            <tr>
                                <td class="text-center">{{ ++$key }}</td>
                                <td class="text-center">{{ $box->bo_sale_ccn }}</td>
                                <td class="text-center">{{ $box->bo_mas_loc }}</td>
                                <td class="text-center">{{ $box->bo_promise_date }}</td>
                                <td class="text-center">{{ $box->bo_ship_via }}</td>
                                <td class="text-center">{{ $box->bo_customer }}</td>
                                <td class="text-center">{{ $box->bo_cus_ship_loc }}</td>
                                <td class="text-center">
                                    <button class="btn btn-info" onclick="viewShow({{ $box->bo_pd_id }})"><i class="fas fa-eye"></i></button>
                                    <a class="btn btn-success" href="{{ route('adjust.show',$box->bo_pd_id) }}" ><i class="fas fa-pencil-alt"></i></a>
                                
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
            </div>
        </div>
    </div>
</div>
<div id="ShowProducts"></div>
@endsection
@section('js')
@csrf
<script>
    $(document).ready(function () {
        $("[rel='tooltip']").tooltip();
    });

    $(document).ready(function () {
        $('#product1').DataTable();
        $('#product2').DataTable();
    });

    function viewShow(id) {
            $.ajax({
                url: "{{ url('box') }}/" + id,
                data: {id: id},
                type: 'GET',
                success: function (data) {
                    // console.log(data);
                    $('#ShowProducts').html(data);
                    $("#exampleModal").modal('show');
                    $('#exampleModal').modal({backdrop: 'static', keyboard: false});
                }
            });
        }
</script>

@endsection
