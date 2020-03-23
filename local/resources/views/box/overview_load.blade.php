@extends('layouts.master')
@section('title')
Auto Load Pallet
@endsection
@section('css')
<link rel="stylesheet" href="{{ asset('assets/css/app.css') }}">
<link href="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.11.4/jquery-ui.css" rel="stylesheet">
<style>
    .modal-lg{
        max-width:1200px;
    }
</style>
@endsection
@section('content')
<style>
    input{
text-align: center;
}
</style>
@php
$active = 'box';
@endphp
<div class="breadcrumb">
    <div class="breadcrumb-element">
        <a class="breadcrumb-item " href="{{ url('dashboard') }}"><i class="lnr lnr-home mr3"></i></a>
        <a class="breadcrumb-item " href="#">Overview Pallet</a>
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <div class="row">
                    <div class="col-6">
                        Overview Pallet
                    </div>
                    <div class="col-6  text-right">
                        <button type="button" class="btn btn-secondary" onclick="history.back()">กลับ</button>
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
               
            </div>
        </div>
    </div>
</div>

@endsection
@section('js')
<script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.11.4/jquery-ui.js"></script> 
@endsection
