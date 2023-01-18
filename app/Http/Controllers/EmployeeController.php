<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\Position;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;
use Yajra\DataTables\Html\Builder;
use Yajra\DataTables\Html\Column;

class EmployeeController extends Controller
{
    public function index(Builder $builder)
    {
        if (request()->ajax())
        {
            $query = DB::table('employees')
                ->join('positions', 'positions.id', '=', 'employees.position_id')
                ->select( 'employees.*', 'positions.name as position');

            return DataTables::of($query)
                ->editColumn('salary', '${{ number_format($salary, 2, ".", ",") }}')
                ->addColumn('actions', ' ')
                ->editColumn('actions', function ($row) {
                    return view('components.datatables.actions', compact('row'));
                })
                ->rawColumns(['actions'])
                ->toJson();
        }

        $builder->parameters([
            'searchDelay' => 1000,
            'buttons' => [],
        ]);

        $html = $builder->columns([
            Column::make('name'),
            Column::make(['data' => 'position', 'searchable' => false,]),
            Column::make(['data' => 'date_of_employment', 'searchable' => false,]),
            Column::make('phone_number'),
            Column::make('email'),
            Column::make(['data' => 'salary', 'searchable' => false,]),
            Column::make([
                'data'           => 'actions',
                'orderable'      => false,
                'searchable'     => false,
            ]),
        ]);

        return view('employees.index', compact('html'));
    }
}
