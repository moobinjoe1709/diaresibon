@extends('layouts.master')
@section('title')
Load Pallet
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
    @php
        $active = 'box';
    @endphp
    <select name="" id="" class="btn btn-success">
        <option value="1">1</option>
        <option value="2">2</option>
    </select>
@endsection
@section('js')
@csrf
<script>

</script>

@endsection