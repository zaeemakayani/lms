<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use DataTables;
use Illuminate\Support\Facades\Auth;

class CategoryController extends Controller
{
    /**
     * Yajra Datatables
     */
    public function dataTable($request)
    {
        $categories = Category::with('products');

        // Apply global search
        if (!empty($request->search['value'])) {
            $searchValue = $request->search['value'];
            $categories = $categories->where(function ($query) use ($searchValue) {
                $query->where('name', 'LIKE', "%$searchValue%");
            });
        }

        $totalRecords = $categories->count(); // Get the total number of records for pagination

        $data = $categories->skip($request->start)
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
            ->addColumn('products_count', function ($row) {
                return $row->products->count();
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
                    <a class="dropdown-item" href="categories/' . $row->id . '/edit">
                        <i class="align-middle me-1 text-primary" data-feather="edit">
                        </i>
                        Edit Category
                    </a>
                    <a class="dropdown-item" href="categories/' . $row->id . '/delete">
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
        return view('categories.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        return view('categories.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        try {
            $this->validate($request, [
                'name' => 'required'
            ]);
            $category = new Category();
            $category->name = $request->name;
            $category->active_status = !empty($request->active_status) ? 'active' : 'in_active';
            $category->created_by = Auth::user()->name;
            $category->save();
            return response()->json([
                'status' => true,
                'message' => 'Successfully created'
            ]);
        } catch (\Exception $e) {
            dd($e);
            return response()->json([
                'status' => false,
                'message' => 'Something went wrong'
            ]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Category $category)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Request $request, $id)
    {
        //
        $category = Category::find($id);
        return view('categories.edit', compact('category', 'id'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        //
        try {
            $this->validate($request, [
                'name' => 'required'
            ]);
            $category = Category::find($id);
            if ($category) {
                $category->name = $request->name;
                $category->active_status = !empty($request->active_status) ? 'active' : 'in_active';
                // $category->created_by = Auth::user()->name;
                $category->save();
                return response()->json([
                    'status' => true,
                    'message' => 'Successfully updated'
                ]);
            } else {
                return response()->json([
                    'status' => false,
                    'message' => 'Category not found'
                ]);
            }
        } catch (\Exception $e) {
            dd($e);
            return response()->json([
                'status' => false,
                'message' => 'Something went wrong'
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Category $category)
    {
        //
    }
}
