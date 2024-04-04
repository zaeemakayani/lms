<?php

namespace App\Http\Controllers;

use App\Models\Unit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use DataTables;

class UnitController extends Controller
{
    /**
     * Yajra Datatables
     */
    public function dataTable($request)
    {
        $units = Unit::query();

        // Apply global search
        if (!empty($request->search['value'])) {
            $searchValue = $request->search['value'];
            $units = $units->where(function ($query) use ($searchValue) {
                $query->where('name', 'LIKE', "%$searchValue%");
            });
        }

        $totalRecords = $units->count(); // Get the total number of records for pagination

        $data = $units->skip($request->start)
            ->take($request->length)
            ->get();

        return DataTables::of($data)
            ->addIndexColumn()
            ->editColumn('sr_no', function ($row) {
                return '1';
            })
            ->addColumn('name', function ($row) {
                return $row->name;
            })
            ->addColumn('weight', function ($row) {
                return $row->weight;
            })
            ->addcolumn('active_status', function ($row) {
                if ($row->active_status == 'active') {
                    return '<span class="badge bg-green d-flex justify-content-center">Active</span>';
                } else {
                    return '<span class="badge bg-red d-flex justify-content-center">In active</span>';
                }
            })
            ->addColumn('actions', function ($row) {
                $btns = '
                <a class="badge2 text-dark" href="#" data-bs-toggle="dropdown">
                    <i class="align-middle text-center" data-feather="more-vertical"></i>
                </a>
                <div class="dropdown-menu dropdown-menu-end">
                    <a class="dropdown-item" href="units/' . $row->id . '/edit">
                        <i class="align-middle me-1 text-primary" data-feather="edit">
                        </i>
                        Edit Unit
                    </a>
                    <a class="dropdown-item" href="units/' . $row->id . '/delete">
                        <i class="align-middle me-1 text-danger" data-feather="trash-2">
                        </i>
                        Delete Unit
                    </a>
                </div>';
                return $btns;
            })
            ->rawColumns(['active_status', 'actions'])
            ->setTotalRecords($totalRecords)
            ->setFilteredRecords($totalRecords) // For simplicity, same as totalRecords
            ->skipPaging()
            ->make(true);
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        //
        if ($request->ajax()) {
            return $this->dataTable($request);
        }
        return view('units.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        return view('units.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        try {
            $this->validate($request, [
                'name' => 'required',
                'weight' => 'required',
            ]);
            $unit = new Unit();
            $unit->name = $request->name;
            $unit->weight = $request->weight;
            $unit->active_status = !empty($request->active_status) ? 'active' : 'in_active';
            $unit->created_by = Auth::user()->name;
            $unit->save();
            return response()->json([
                'status' => true,
                'message' => 'Unit created successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Something went wrong'
            ]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Unit $unit)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Request $request, $id)
    {
        //
        $unit = Unit::find($id);
        return view('units.edit', compact('unit', 'id'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        //
        try {
            $this->validate($request, [
                'name' => 'required',
                'weight' => 'required',
            ]);
            $unit = Unit::find($id);
            if ($unit) {
                $unit->name = $request->name;
                $unit->weight = $request->weight;
                $unit->active_status = !empty($request->active_status) ? 'active' : 'in_active';
                $unit->save();
                return response()->json([
                    'status' => true,
                    'message' => 'Updated successfully',
                ]);
            } else {
                return response()->json([
                    'status' => false,
                    'message' => 'Unit not found',
                ]);
            }
        } catch (\Exception $e) {
            dd($e);
            return response()->json([
                'status' => false,
                'message' => 'Something went wrong',
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Unit $unit)
    {
        //
    }
}
