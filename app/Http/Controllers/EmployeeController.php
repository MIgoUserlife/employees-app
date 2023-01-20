<?php

namespace App\Http\Controllers;

use App\Http\Requests\EmployeeStoreRequest;
use App\Http\Requests\EmployeeUpdateRequest;
use App\Models\Employee;
use App\Models\Position;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;
use Psy\Util\Str;
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
                ->editColumn('photo', function ($row) {
                    if (isset($row->photo))
                    {
                        $photoName = explode('.', $row->photo);
                        $thumbSrc = asset('images/employees/'. $row->id . '/300x300/' . $photoName[0] . '.jpg');
                        return "<img class='img-thumbnail bg-gray-light d-inline-block rounded-circle' src='{$thumbSrc}' alt='' width='60' height='60'/>";
                    }
                    else
                    {
                        return "<span class='img-thumbnail bg-gray-light d-inline-block rounded-circle lh-1' style='height:60px;width:60px;'></span>";
                    }
                })
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
                ->rawColumns(['action', 'photo'])
                ->toJson();
        }

        $builder->parameters([
            'searchDelay' => 1000,
            'buttons' => [],
        ]);

        $html = $builder->columns([
            Column::make('photo')
                ->searchable(false)
                ->orderable(false)
                ->width('75px'),
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

        if ($request->hasFile('photo')) {
            $file = $request->file('photo');
            $filename = $file->getClientOriginalName();
            $filetitle = explode('.', $filename);

            $data['photo'] = $filename;
            $employee = Employee::create($data);

            $directory = 'images/employees/' . $employee->id;

            $file->storeAs($directory, $filename);
            Storage::createDirectory($directory . '/300x300');

            $thumb = Image::make("{$directory}/{$filename}")
                ->orientate()
                ->encode('jpg')
                ->resize(600, 600, function ($constraint) {
                    $constraint->aspectRatio();
                    $constraint->upsize();
                })
                ->fit(300, 300);

            $thumb->save("{$directory}/300x300/{$filetitle[0]}.jpg", 80);

        }
        else
        {
            Employee::create($data);
        }

        return redirect()->route('employees.index');
    }

    public function update(EmployeeUpdateRequest $request, Employee $employee)
    {
        $data = $request->validated();
        $data['admin_updated_id'] = Auth::id();

        if ($request->hasFile('photo')) {
            $file = $request->file('photo');
            $filename = $file->getClientOriginalName();
            $filetitle = explode('.', $filename);

            $directory = 'images/employees/' . $employee->id;

            (isset($employee->photo)) && Storage::deleteDirectory($directory);

            $file->storeAs($directory, $filename);
            Storage::createDirectory($directory . '/300x300');

            $thumb = Image::make("{$directory}/{$filename}")
                ->orientate()
                ->encode('jpg')
                ->resize(600, 600, function ($constraint) {
                    $constraint->aspectRatio();
                    $constraint->upsize();
                })
                ->fit(300, 300);

            $thumb->save("{$directory}/300x300/{$filetitle[0]}.jpg", 80);

            $data['photo'] = $filename;
        }

        $employee->update($data);

        return redirect()->route('employees.index');
    }

    public function destroy(Employee $employee)
    {

        if (isset($employee->photo))
        {
            $directory = 'images/employees/' . $employee->id;
            Storage::deleteDirectory($directory);
        }

        $employee->delete();

        return back();
    }

}
