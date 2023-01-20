@extends('layouts.app')

@section('title', 'Employee edit')

@section('content_header')
    <div class="d-flex justify-content-between">
        <h1>Employees</h1>
    </div>
@stop

@section('content')
    <h4>Employee edit</h4>

    <form
        action="{{ route('employees.update', $employee->id) }}"
        method="post"
    >
        @csrf
        @method('patch')

        <x-adminlte-input-file
            name="photo"
            label="Photo"
            legend="Upload photo"
            fgroup-class="d-inline-block"
            disable-feedback
        />

        <x-adminlte-input
            name="name"
            label="Name"
            value="{{ old('name') ?? $employee->name }}"
            disable-feedback
            fgroup-class="input-field-count"
            @class([
                'is-invalid' => $errors->first('name')
            ])
        >
            <x-slot name="bottomSlot">
                <div class="row">
                    <div class="col-6">
                        @error('name') <span>{{ $message }}</span> @enderror
                    </div>
                    <div class="col-6 text-sm text-gray text-right">
                        <span data-count>{{ str()->length(old('name') ?? $employee->name) }}</span>
                        /
                        <span data-total>256</span>
                    </div>
                </div>
            </x-slot>
        </x-adminlte-input>

        <x-adminlte-input
            name="phone_number"
            label="Phone number"
            value="{{ old('phone_number') ?? $employee->phone_number }}"
            data-phone-mask
            disable-feedback
            @class([
                'is-invalid' => $errors->first('phone_number')
            ])
        >
            <x-slot name="bottomSlot">
                <div class="row">
                    <div class="col-6">
                        @error('phone_number') <span>{{ $message }}</span> @enderror
                    </div>
                    <div class="col-6 text-sm text-gray text-right">
                        Required format +380 (xx) XXX XX XX
                    </div>
                </div>
            </x-slot>
        </x-adminlte-input>

        <x-adminlte-input
            name="email"
            label="Email"
            value="{{ old('email') ?? $employee->email }}"
            disable-feedback
            @class([
                'is-invalid' => $errors->first('email')
            ])
        >
            <x-slot name="bottomSlot">
                @error('email') <span>{{ $message }}</span> @enderror
            </x-slot>
        </x-adminlte-input>

        <x-adminlte-select2 name="position_id" label="Position">
            @foreach($positions as $position)
                <option
                    value="{{ $position->id }}"
                    @selected($employee->position_id === $position->id)
                >
                    {{ $position->name }}
                </option>
            @endforeach
        </x-adminlte-select2>

        <x-adminlte-input
            name="salary"
            label="Salary, $"
            value="{{ old('salary') ?? $employee->salary}}"
            data-money-mask
            disable-feedback
            @class([
                'is-invalid' => $errors->first('salary')
            ])
        >
            <x-slot name="bottomSlot">
                @error('salary') <span>{{ $message }}</span> @enderror
            </x-slot>
        </x-adminlte-input>

        @php
            $config = [
                'format' => 'DD.MM.YY',
                'dayViewHeaderFormat' => 'MMM YYYY',
                'maxDate' => "js:moment().endOf('day')",
            ];
        @endphp
        <x-adminlte-input-date
            name="date_of_employment"
            label="Date of employment"
            value="{{ old('date_of_employment') ?? $employee->date_of_employment }}"
            :config="$config"
            disable-feedback
            @class([
                'is-invalid' => $errors->first('date_of_employment')
            ])
        >
            <x-slot name="bottomSlot">
                @error('date_of_employment') <span>{{ $message }}</span> @enderror
            </x-slot>
        </x-adminlte-input-date>

        <div class="row">
            <div class="col-6 my-2">
                <span class="text-bold">Created at:</span> {{ date('d.m.y', $employee->created_at->timestamp) }}
            </div>
            <div class="col-6 my-2">
                <span class="text-bold">Admin created ID:</span> {{ $employee->admin_created_id }}
            </div>
            <div class="col-6 my-2">
                <span class="text-bold">Updated at:</span> {{ date('d.m.y', $employee->updated_at->timestamp) }}
            </div>
            <div class="col-6 my-2">
                <span class="text-bold">Admin updated ID:</span> {{ $employee->admin_updated_id }}
            </div>
        </div>

        <div class="text-right mt-4">
            <a href="{{ route('employees.index') }}" class="btn btn-flat btn-default w-25">Cancel</a>
            <button type="submit" class="btn btn-flat btn-primary w-25">Save</button>
        </div>

    </form>
@stop

@section('plugins.Select2', true)
@section('plugins.InputMask', true)
@section('plugins.TempusDominusBs4', true)
@section('plugins.BsCustomFileInput', true)

@section('js')
    <x-scripts.input-fields-mask></x-scripts.input-fields-mask>
    <x-scripts.input-fields-count></x-scripts.input-fields-count>
@stop


