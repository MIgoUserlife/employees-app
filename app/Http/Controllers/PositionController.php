<?php

namespace App\Http\Controllers;

use App\Http\Requests\PositionStoreRequest;
use App\Http\Requests\PositionUpdateRequest;
use App\Models\Position;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;
use Yajra\DataTables\Html\Builder;
use Yajra\DataTables\Html\Column;

class PositionController extends Controller
{
    public function index(Builder $builder)
    {
        if (request()->ajax())
        {
            $model = Position::query();

            return DataTables::eloquent($model)
                ->editColumn('updated_at', '{{ date("d.m.y") }}')
                ->addColumn('action', ' ')
                ->editColumn('action', function ($row) {
                    return view('components.datatables.actions', [
                        'model' => 'positions',
                        'modal_text' => 'position',
                        'row' => $row,
                    ]);
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
            Column::make('updated_at')
                ->title('Last update')
                ->searchable(false)
                ->width('20%'),
            Column::make('action')
                ->searchable(false)
                ->orderable(false)
                ->width('20%'),
        ]);

        return view('positions.index', compact('html'));
    }

    public function show(Position $position)
    {
        return view('positions.show', compact('position'));
    }

    public function create()
    {
        return view('positions.create');
    }

    public function store(PositionStoreRequest $request)
    {
        $data = $request->validated();

        $admin_id = Auth::id();

        $data['admin_created_id'] = $admin_id;
        $data['admin_updated_id'] = $admin_id;

        Position::create($data);

        return redirect()->route('positions.index');
    }

    public function update(PositionUpdateRequest $request, Position $position)
    {
        $data = $request->validated();
        $data['admin_updated_id'] = Auth::id();

        $position->update($data);

        return redirect()->route('positions.index');
    }

    public function destroy(Position $position)
    {
        $position->delete();

        return back();
    }

}
