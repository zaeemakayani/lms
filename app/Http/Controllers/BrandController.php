<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\User;
use Illuminate\Http\Request;
use DataTables;
use Illuminate\Support\Facades\Auth;

class BrandController extends Controller
{
    /**
     * Yajra Datatables
     */
    public function dataTable($request)
    {
        $brands = Brand::with('seller');

        // Apply global search
        if (!empty($request->search['value'])) {
            $searchValue = $request->search['value'];
            $brands = $brands->where(function ($query) use ($searchValue) {
                $query->where('name', 'LIKE', "%$searchValue%");
            });
        }

        $totalRecords = $brands->count(); // Get the total number of records for pagination

        $data = $brands->skip($request->start)
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
            ->addColumn('seller', function ($row) {
                return isset($row->seller) ? $row->seller->name : '';
            })
            ->addcolumn('active_status', function ($row) {
                if ($row->active_status == 'active') {
                    return '<span class="badge bg-green d-flex justify-content-center">Active</span>';
                } else {
                    return '<span class="badge bg-red d-flex justify-content-center">In active</span>';
                }
            })
            ->addColumn('created_by', function ($row) {
                return ucfirst($row->created_by);
            })
            ->addColumn('actions', function ($row) {
                $btns = '
                <a class="badge2 text-dark" href="#" data-bs-toggle="dropdown">
                    <i class="align-middle text-center" data-feather="more-vertical"></i>
                </a>
                <div class="dropdown-menu dropdown-menu-end">
                    <a class="dropdown-item" href="brands/' . $row->id . '/products">
                        <i class="align-middle me-1 text-primary" data-feather="database">
                        </i>
                        Brand Products
                    </a>
                    <a class="dropdown-item" href="brands/' . $row->id . '/edit">
                        <i class="align-middle me-1 text-primary" data-feather="edit">
                        </i>
                        Edit Category
                    </a>
                    <a class="dropdown-item" href="brands/' . $row->id . '/delete">
                        <i class="align-middle me-1 text-danger" data-feather="trash-2">
                        </i>
                        Delete Category
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
        return view('brands.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        $sellers = User::where('user_type', 'seller')->get();
        return view('brands.create', compact('sellers'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        try {
            $this->validateBrand($request);
            $brand = new Brand();
            $request['created_by'] = Auth::user()->name;
            $this->saveBrand($request, $brand);
            return response()->json([
                'status' => true,
                'message' => 'Brand created successfully'
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
    public function show(Brand $brand)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Request $request, $id)
    {
        //
        $brand = Brand::find($id);
        $sellers = User::where('user_type', 'seller')->get();
        return view('brands.edit', compact('brand', 'sellers'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        //
        try {
            $this->validateBrand($request);
            $brand = Brand::find($id);
            $request['created_by'] = Auth::user()->name;
            $this->saveBrand($request, $brand);
            return response()->json([
                'status' => true,
                'message' => 'Brand updated successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Something went wrong'
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Brand $brand)
    {
        //
    }

    // validate brand
    public function validateBrand($request)
    {
        $this->validate($request, [
            'name' => 'required',
            'seller_id' => 'required'
        ]);
    }

    public function saveBrand($request, $brand)
    {
        $brand->name = $request->name;
        $brand->seller_id = $request->seller_id;
        $brand->created_by = $request->created_by;
        $brand->active_status = !empty($request->active_status) ? 'active' : 'in_active';
        $brand->save();
        return $brand;
    }

    public function sellerBrands(Request $request)
    {
        try {
            $brands = Brand::where('seller_id', $request->seller_id)->get();
            return response()->json([
                'status' => true,
                'message' => 'Data found successfully',
                'data' => $brands
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Something went wrong',
                'data' => []
            ]);
        }
    }
}
