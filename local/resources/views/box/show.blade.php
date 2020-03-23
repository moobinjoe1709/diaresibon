@extends('layouts.master')
@section('title')
Manual Load Pallet
@endsection
@section('css')
<link rel="stylesheet" href="{{ asset('assets/css/app.css') }}">
<style>
     .modal-lg {
        max-width: 90%;
    }

</style>
@endsection
@section('content')
<style>
    input {
        text-align: center;
    }

    .load_pallet{
        cursor: pointer;
    }
    .del_pallet{
        cursor: pointer;
    }

    
</style>
@php
$active = 'box';
@endphp
<div class="breadcrumb">
    <div class="breadcrumb-element">
        <a class="breadcrumb-item " href="{{ url('dashboard') }}"><i class="lnr lnr-home mr3"></i></a>
        <a class="breadcrumb-item " href="#">Manual Load Pallet</a>
    </div>
</div>
{{-- {{dd(Session::get('cart'))}} --}}
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <div class="row">
                    <div class="col-6">
                        Manual Load Pallet
                    </div>
                    <div class="col-6  text-right">
                    <a href="{{route('box.edit',$id)}}" class="btn btn-secondary" >กลับ</a>
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
                <input type="hidden" name="comma_separated" value="{{$comma_separated}}">
                <input type="hidden" name="id" value="{{$id}}">
                <div class="row">
                    <div class="col-12">
                        <div id="showtable"></div>  
                        <input type="hidden" name="comma_separated" value="{{$comma_separated}}">
                        <input type="hidden" name="id" value="{{$id}}">
                    </div>
                </div>
                <div id="showpallet"></div>    
            </div>
        </div>
    </div>
    {{-- {{dd(Session::get('cart'))}} --}}
    @csrf
    @endsection

    @section('js')
    <script>
        $( document ).ready(function() {
            $.ajax({
                url: "{{url('showpallet')}}" ,
                type: "get",
            }).done(function (data) {
               $("#showpallet").html(data);
            });
        });

        $( document ).ready(function() {
            var comma_separated = $("input[name=comma_separated]").val();
            var id = $("input[name=id]").val();
            $.ajax({
                url: "{{url('showtable')}}/"+ comma_separated + '/' + id,
                type: "get",
            }).done(function (data) {
               $("#showtable").html(data);
            });
        });

       

        
        
    </script>
    @endsection
