<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Inventory;
use App\Models\Product;
use App\Models\Unit;
use Carbon\Carbon;
use Illuminate\Http\Request;
use DataTables;
use GuzzleHttp\Handler\Proxy;
use Illuminate\Support\Facades\Auth;

class ProductController extends Controller
{
    /**
     * Yajra Datatables
     */
    public function dataTable($request)
    {
        $products = Product::with('category');

        // Apply global search
        if (!empty($request->search['value'])) {
            $searchValue = $request->search['value'];
            $products = $products->where(function ($query) use ($searchValue) {
                $query->where('name', 'LIKE', "%$searchValue%");
            });
        }

        $totalRecords = $products->count(); // Get the total number of records for pagination

        $data = $products->skip($request->start)
            ->take($request->length)
            ->get();

        return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('sr_no', function ($row) {
                return '1';
            })
            ->addColumn('image', function ($row) {
                if (is_null($row->image)) {
                    return '<img class="zoomable-img border border-default" src="' . asset("public/img/products/no-image-available.jpg") . '" width="50px" height="50px">';
                } else {
                    return '<img class="zoomable-img border border-default" src="' . asset("public/img/products/".$row->image) . '" width="50px" height="50px">';
                }
            })
            ->addColumn('name', function ($row) {
                return $row->name;
            })
            ->addColumn('category', function ($row) {
                return isset($row->category) ? $row->category->name : '';
            })
            ->addColumn('weight', function ($row) {
                return $row->weight->weight.' '.$row->weight->name;
            })
            ->addColumn('unit_cost', function ($row) {
                return $row->unit_cost;
            })
            ->addColumn('shipping_cost', function ($row) {
                return $row->shipping_cost;
            })
            ->addColumn('total_cost', function ($row) {
                return $row->total_cost;
            })
            ->addColumn('selling_cost', function ($row) {
                return $row->selling_cost;
            })
            ->addColumn('qty', function ($row) {
                return $row->qty;
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
                    <a class="dropdown-item" href="products/' . $row->id . '/edit">
                        <i class="align-middle me-1 text-primary" data-feather="edit">
                        </i>
                        Edit Product
                    </a>
                    <a class="dropdown-item" href="products/' . $row->id . '/delete">
                        <i class="align-middle me-1 text-danger" data-feather="trash-2">
                        </i>
                        Delete Product
                    </a>
                </div>';
                return $btns;
            })
            ->rawColumns(['image', 'active_status', 'actions', 'role'])
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
        return view('products.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        $categories = Category::get();
        $units = Unit::get(); // weights
        return view('products.create', compact('categories', 'units'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        try {
            $this->validateProductDetails($request);
            $product = new Product();
            $this->saveProduct($request, $product);
            return response()->json([
                'status' => true,
                'message' => 'Product created successfully'
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
    public function show(Product $product)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Request $request, $id)
    {
        //
        $product = Product::find($id);
        $categories = Category::get();
        $units = Unit::get();
        return view('products.edit', compact('product', 'categories', 'units'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        //
        try {
            $this->validateProductDetails($request);
            $product = Product::find($id);
            if ($product) {
                $this->saveProduct($request, $product);
                return response()->json([
                    'status' => true,
                    'message' => 'Updated successfully'
                ]);
            } else {
                return response()->json([
                    'status' => false,
                    'message' => 'Product not found'
                ]);
            }
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
    public function destroy(Product $product)
    {
        //
    }

    // validate product details
    public function validateProductDetails($request)
    {
        $this->validate($request, [
            'name' => 'required',
            'category_id' => 'required',
            'unit_id' => 'required',
            'unit_cost' => 'required',
            'shipping_cost' => 'required',
            'total_cost' => 'required',
            'selling_cost' => 'required',
            'qty' => 'required',
        ]);
    }

    public function saveProduct($request, $product)
    {
        $product->name = $request->name;
        $product->category_id = $request->category_id;
        $product->unit_id = $request->unit_id;
        $product->unit_cost = $request->unit_cost;
        $product->shipping_cost = $request->shipping_cost;
        $product->total_cost = $request->total_cost;
        $product->selling_cost = $request->selling_cost;
        $product->qty = $request->qty;
        $product->active_status = !empty($request->active_status) ? 'active' : 'in_active';
        if ($request->hasFile('image')) {
            $imageName = Carbon::now()->format('Ymdhis') . '.' . $request->file('image')->getClientOriginalExtension();
            $imagePath = $request->file('image')->move(public_path('img/products'), $imageName);
            // Store the image filename in the database
            $product->image = $imageName;
        }
        $product->created_by = Auth::user()->name;
        $product->save();
        $this->saveInventory($product);
        return $product;
    }

    public function saveInventory($product)
    {
        $inventory = new Inventory();
        $inventory->product_id = $product->id;
        $inventory->qty = $product->qty;
        $inventory->created_by = $product->created_by;
        $inventory->save();
        return $inventory;
    }
}
