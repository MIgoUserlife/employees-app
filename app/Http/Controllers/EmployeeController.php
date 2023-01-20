<?php

namespace App\Http\Controllers;

use App\Http\Requests\EmployeeStoreRequest;
use App\Http\Requests\EmployeeUpdateRequest;
use App\Models\Employee;
use App\Models\Position;
use Illuminate\Support\Facades\Auth;
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
                ->editColumn('salary', '${{ number_format($salary, 0, ".", ",") }}')
                ->editColumn('phone_number', function ($row) {
                    $format = '+380 (%s) %s %s %s';
                    $code = substr($row->phone_number, 4, 2);
                    $group1 = substr($row->phone_number, -7, 3);
                    $group2 = substr($row->phone_number, -4, 2);
                    $group3 = substr($row->phone_number, -2);
                    return sprintf($format, $code, $group1, $group2, $group3);
                })
                ->addColumn('action', ' ')
                ->editColumn('action', function ($row) {
                    return view('components.datatables.actions', [
                        'model' => 'employees',
                        'modal_text' => 'employee',
                        'row' => $row,
                    ]);
                })
                ->rawColumns(['action'])
                ->toJson();
        }

        $builder->parameters([
            'searchDelay' => 1000,
            'buttons' => [],
        ]);

        $html = $builder->columns([
            Column::make('name'),
            Column::make('position')
                ->searchable(false),
            Column::make('date_of_employment')
                ->searchable(false),
            Column::make('phone_number'),
            Column::make('email'),
            Column::make('salary')
                ->searchable(false),
            Column::make('action')
                ->searchable(false)
                ->orderable(false),
        ]);

        return view('employees.index', compact('html'));
    }

    public function show(Employee $employee)
    {
        $positions = Position::all()->sortBy(['name', 'asc']);
        return view('employees.show', compact(['employee', 'positions']));
    }

    public function create()
    {
        $positions = Position::all()->sortBy(['name', 'asc']);
        return view('employees.create', compact('positions'));
    }

    public function store(EmployeeStoreRequest $request)
    {
        $data = $request->validated();

        $admin_id = Auth::id();

        $data['admin_created_id'] = $admin_id;
        $data['admin_updated_id'] = $admin_id;

        Employee::create($data);

        return redirect()->route('employees.index');
    }

    public function update(EmployeeUpdateRequest $request, Employee $employee)
    {
//        dd($request);

        $data = $request->validated();
        $data['admin_updated_id'] = Auth::id();

        $employee->update($data);

        return redirect()->route('employees.index');
    }

    public function destroy(Employee $employee)
    {
        $employee->delete();

        return back();
    }

}
