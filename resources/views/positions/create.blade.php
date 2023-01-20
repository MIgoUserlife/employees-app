@extends('layouts.app')

@section('title', 'Position edit')

@section('content_header')
    <div class="d-flex justify-content-between">
        <h1>Positions</h1>
    </div>
@stop

@section('content')
    <h4>Add position</h4>

    <form
        action="{{ route('positions.store') }}"
        method="post"
    >
        @csrf

        <x-adminlte-input
            name="name"
            label="Name"
            value="{{ old('name') }}"
            disable-feedback
            fgroup-class="input-field-count"
            @class([
                'is-invalid' => $errors->first('name')
            ])
        >
            <x-slot name="bottomSlot">
                <div class="row">
                    <div class="col-6">
                        @error('name')
                            <span>{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="col-6 text-sm text-gray text-right">
                        <span data-count>{{ str()->length(old('name')) }}</span>
                        /
                        <span data-total>256</span>
                    </div>
                </div>
            </x-slot>
        </x-adminlte-input>

        <div class="text-right mt-4">
            <a href="{{ route('positions.index') }}" class="btn btn-flat btn-default w-25">Cancel</a>
            <button type="submit" class="btn btn-flat btn-primary w-25">Save</button>
        </div>

    </form>
@stop

@section('js')
    <x-scripts.input-fields-count></x-scripts.input-fields-count>
@stop
