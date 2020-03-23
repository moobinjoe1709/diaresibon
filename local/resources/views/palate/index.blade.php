@extends('layouts.master')
@section('title')
รายการ Pallet List
@endsection
@section('css')
<link rel="stylesheet" href="{{ asset('assets/css/app.css') }}">
@endsection
@section('content')
<div class="breadcrumb">
    <div class="breadcrumb-element">
        <a class="breadcrumb-item " href="{{ url('dashboard') }}"><i class="lnr lnr-home mr3"></i></a>
        <a class="breadcrumb-item " href="#">   รายการ Pallet List</a>
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <form action="{{ url('PalletOverview') }}" method="POST">
            @csrf
            <div class="card">
                <div class="card-header">
                    <div class="row">
                        <div class="col-6">
                            รายการ Pallet List
                        </div>
                        <div class="col-6 text-right">
                            {{-- <button type="submit" class="btn btn-primary"><i class="fas fa-upload"></i> </button> --}}
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
                        @php
                            // dd($tb_boxs);
                        @endphp
                       
                    </div>
                </div>
            </div>
        </form>
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
    @endsection
