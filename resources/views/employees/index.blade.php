@extends('layouts.app')

@section('title', 'Employees list')

@section('content_header')
    <div class="d-flex justify-content-between">
        <h1>Employees</h1>
        <a
            class="btn btn-primary"
            href="{{ route('employees.create') }}"
        >
            Add employee
        </a>
    </div>
@stop

@section('content')
    <h4>Employees list</h4>
    {{ $html->table() }}
@stop

@section('plugins.Datatables', true)

@section('js')
    {{ $html->scripts() }}
@stop

