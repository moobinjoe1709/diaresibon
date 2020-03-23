@extends('layouts.master')
@section('title')
รายการ Container
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
        <a class="breadcrumb-item " href="#"> รายการ Container</a>
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header"> รายการ Container</div>
            <div class="card-body">
                @if (\Session::has('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <i class="fas fa-check-circle pr5"></i>สำเร็จ!</strong> {!! \Session::get('success') !!}
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
                            {{-- <th class="text-center">Extra Box</th> --}}
                            <th  width="15%"  class="text-center" >View</th>
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
                            {{-- <td class="text-right">@php echo $box->bo_frangment != "" ? 1 : '-' @endphp</td> --}}
                            <td class="text-center">
                                <a   href="{{ url('palletmanage').'/'.$box->bo_pd_id }}" class="btn btn-primary" name="button_load" value="auto_load"> Manage</a>
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
            data: {
                id: id
            },
            type: 'GET',
            success: function (data) {
                $('#ShowProducts').html(data);
                $("#exampleModal").modal('show');
                $('#exampleModal').modal({
                    backdrop: 'static',
                    keyboard: false
                });
            }
        });
    }

</script>
@endsection
