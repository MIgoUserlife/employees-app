@extends('layouts.app')

@section('title', 'Positions list')

@section('content_header')
    <div class="d-flex justify-content-between">
        <h1>Positions</h1>
        <a
            class="btn btn-primary"
            href="{{ route('positions.create') }}"
        >
            Add position
        </a>
    </div>
@stop

@section('content')
    <h4>Positions list</h4>
    {{ $html->table() }}
@stop

@section('plugins.Datatables', true)

@section('js')
    {{ $html->scripts() }}
@stop

