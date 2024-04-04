<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\BrandProduct;
use App\Models\Product;
use Illuminate\Http\Request;
use DataTables;
use Illuminate\Support\Facades\Auth;

class BrandProductController extends Controller
{
    /**
     * Yajra Datatables
     */
    public function dataTable($request, $id)
    {
        $brandProducts = BrandProduct::with('brand', 'product')->where('brand_id', $id);

        // Apply global search
        if (!empty($request->search['value'])) {
            $searchValue = $request->search['value'];
            $brandProducts = $brandProducts->where(function ($query) use ($searchValue) {
                $query->where('name', 'LIKE', "%$searchValue%");
            });
        }

        $totalRecords = $brandProducts->count(); // Get the total number of records for pagination

        $data = $brandProducts->skip($request->start)
            ->take($request->length)
            ->get();

        return DataTables::of($data)
            ->addIndexColumn()
            ->editColumn('sr_no', function ($row) {
                return '1';
            })
            ->addColumn('image', function ($row) {
                if (is_null($row->product->image)) {
                    return '<img class="zoomable-img border border-default" src="' . asset("public/img/products/no-image-available.jpg") . '" width="50px" height="50px">';
                } else {
                    return '<img class="zoomable-img border border-default" src="' . asset("public/img/products/".$row->product->image) . '" width="50px" height="50px">';
                }
            })
            ->addColumn('name', function ($row) {
                return $row->product->name;
            })
            ->addColumn('category', function ($row) {
                return isset($row->product->category) ? $row->product->category->name : '';
            })
            ->addColumn('weight', function ($row) {
                return $row->product->weight->weight.' '.$row->product->weight->name;
            })
            ->addColumn('unit_cost', function ($row) {
                return $row->product->unit_cost;
            })
            ->addColumn('shipping_cost', function ($row) {
                return $row->product->shipping_cost;
            })
            ->addColumn('total_cost', function ($row) {
                return $row->product->total_cost;
            })
            ->addColumn('selling_cost', function ($row) {
                return $row->product->selling_cost;
            })
            ->addColumn('qty', function ($row) {
                return $row->product->qty;
            })
            ->addcolumn('active_status', function ($row) {
                if ($row->product->active_status == 'active') {
                    return '<span class="badge bg-green d-flex justify-content-center">Active</span>';
                } else {
                    return '<span class="badge bg-red d-flex justify-content-center">In active</span>';
                }
            })
            ->addColumn('actions', function ($row) {
                $btns = '
                <a class="badge2 text-danger delete-brand-product" data-product-id="'.$row->product->id.'" href="#">
                    <i class="align-middle text-danger text-center" data-feather="trash-2"></i>
                </a>';
                return $btns;
            })
            ->rawColumns(['image', 'active_status', 'actions', 'role'])
            ->setTotalRecords($totalRecords)
            ->setFilteredRecords($totalRecords) // For simplicity, same as totalRecords
            ->skipPaging()
            ->make(true);
    }

    public function getBrandProducts($request, $id)
    {
        $brand = Brand::with('products')->where('id', $id)->first();
        $brandProductIds = $brand->products->pluck('product_id');
        $allProducts = Product::whereNotIn('id', $brandProductIds)->get();
        return ['brand' => $brand, 'products' => $allProducts];
    }
    
    public function brandProducts(Request $request, $id)
    {
        if ($request->ajax()) {
            return $this->dataTable($request, $id);
        }
        $brandProductsData = $this->getBrandProducts($request, $id);
        $brand = $brandProductsData['brand'];
        $allProducts = $brandProductsData['products'];
        return view('brands.products', compact('id', 'brand', 'allProducts'));
    }

    public function createBrandProducts(Request $request, $id)
    {
        try {
            $this->validate($request, [
                'product_id' => 'required',
            ]);
            $product = Product::where('id', $request->product_id)->first();
            if (isset($product)) {
                $brandProduct = new BrandProduct();
                $brandProduct->brand_id = $id;
                $brandProduct->product_id = $request->product_id;
                $brandProduct->product_details = $product;
                $brandProduct->created_by = Auth::user()->name;
                $brandProduct->active_status = 'active';
                $brandProduct->save();
                return response()->json([
                    'status' => true,
                    'message' => 'Added product successfully',
                ]);
            } else {
                return response()->json([
                    'status' => false,
                    'message' => 'No data found',
                ]);
            }
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Something went wrong',
            ]);
        }
    }

    public function deleteBrandProduct(Request $request, $id)
    {
        try {
            $this->validate($request, [
                'product_id' => 'required',
            ]);
            $brandProduct = BrandProduct::where('product_id', $request->product_id)->first();
            if (isset($brandProduct)) {
                $brandProduct->delete();
                return response()->json([
                    'status' => true,
                    'message' => 'Deleted product successfully',
                ]);
            } else {
                return response()->json([
                    'status' => false,
                    'message' => 'No data found',
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
}
