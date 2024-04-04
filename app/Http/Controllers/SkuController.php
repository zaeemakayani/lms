<?php

namespace App\Http\Controllers;

use App\Models\Sku;
use App\Models\User;
use Illuminate\Http\Request;
use DataTables;

class SkuController extends Controller
{
    /**
     * Yajra Datatables
     */
    public function dataTable($request)
    {
        $skus = Sku::with('seller');

        // Apply global search
        if (!empty($request->search['value'])) {
            $searchValue = $request->search['value'];
            $skus = $skus->where(function ($query) use ($searchValue) {
                $query->where('name', 'LIKE', "%$searchValue%");
            });
        }

        $totalRecords = $skus->count(); // Get the total number of records for pagination

        $data = $skus->skip($request->start)
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
            ->addColumn('sku_name_id', function ($row) {
                return $row->sku_name_id;
            })
            ->addColumn('seller', function ($row) {
                return isset($row->seller) ? $row->seller->name : '';
            })
            ->addColumn('selling_cost', function ($row) {
                return $row->selling_cost;
            })
            ->addcolumn('active_status', function ($row) {
                if ($row->active_status == 'active') {
                    return '<span class="badge bg-green d-flex justify-content-center">Active</span>';
                } else {
                    return '<span class="badge bg-red d-flex justify-content-center">In active</span>';
                }
            })
            ->addcolumn('created_by', function ($row) {
                return ucfirst($row->created_by);
            })
            ->addColumn('actions', function ($row) {
                $btns = '
                <a class="badge2 text-dark" href="#" data-bs-toggle="dropdown">
                    <i class="align-middle text-center" data-feather="more-vertical"></i>
                </a>
                <div class="dropdown-menu dropdown-menu-end">
                    <a class="dropdown-item" href="skus/' . $row->id . '/edit">
                        <i class="align-middle me-1 text-primary" data-feather="edit">
                        </i>
                        Edit SKU
                    </a>
                    <a class="dropdown-item" href="skus/' . $row->id . '/delete">
                        <i class="align-middle me-1 text-danger" data-feather="trash-2">
                        </i>
                        Delete SKU
                    </a>
                </div>';
                return $btns;
            })
            ->rawColumns(['active_status', 'actions', 'role'])
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
        return view('skus.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        $sellers = User::where('user_type', 'seller')->get();
        return view('skus.create', compact('sellers'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Request $request, $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, $id)
    {
        //
    }
}
