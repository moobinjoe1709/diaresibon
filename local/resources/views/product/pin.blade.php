@extends('layouts.master')
@section('title')
รายการสินค้า (ปิ่นทอง จ.ชลบุรี)
@endsection
@section('css')
<link rel="stylesheet" href="{{ asset('assets/css/app.css') }}">
@endsection
@section('content')
<div class="breadcrumb">
    <div class="breadcrumb-element">
        <a class="breadcrumb-item " href="{{ url('dashboard') }}"><i class="lnr lnr-home mr3"></i></a>
        <a class="breadcrumb-item " href="#"> รายการสินค้า PIN</a>
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header"> PIN (ปิ่นทอง จ.ชลบุรี)</div>
            <div class="card-body">
                @if (\Session::has('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <i class="fas fa-check-circle pr5"></i><strong>สำเร็จ!</strong> {!! \Session::get('success') !!}
                </div>
                @endif
                <div class="table-responsive">
                    <table class="table table-bordered table-hover table-striped" id="product1">
                        <thead>
                            <tr class="text-center">
                                <th>#</th>
                                <th>sales_ccn</th>
                                <th>mas_loc</th>
                                <th>promise_date</th>
                                <th>ship_via</th>
                                <th>customer</th>
                                <th>cus_ship_loc</th>
                                <th>so</th>
                                <th>so_line</th>
                                <th>fullfill_from</th>
                                <th>ordered_qty</th>
                                <th>pack_qty</th>
                                {{-- <th>Manage</th> --}}
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($products2 as $key => $product2)
                            <tr>
                                <td>{{ ++$key }}</td>
                                <td>{{ $product2->sales_ccn }}</td>
                                <td>{{ $product2->mas_loc }}</td>
                                <td>{{ $product2->promise_date }}</td>
                                <td>{{ $product2->ship_via }}</td>
                                <td>{{ $product2->customer }}</td>
                                <td>{{ $product2->cus_ship_loc }}</td>
                                <td>{{ $product2->so }}</td>
                                <td>{{ $product2->so_line }}</td>
                                <td>{{ $product2->fullfill_from }}</td>
                                <td>{{ $product2->ordered_qty }}</td>
                                <td>{{ $product2->pack_qty}}</td>
                                {{-- <td><button class="btn btn-info"><i class="fas fa-eye"></i></button></td> --}}
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
    });

</script>
@endsection
