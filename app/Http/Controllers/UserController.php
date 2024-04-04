<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\UserDetail;
use Carbon\Carbon;
use Illuminate\Http\Request;
use DataTables;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    /**
     * Yajra Datatables
     */
    public function dataTable($request)
    {
        $users = User::with('user_details');

        // Apply global search
        if (!empty($request->search['value'])) {
            $searchValue = $request->search['value'];
            $users = $users->where(function ($query) use ($searchValue) {
                $query->where('name', 'LIKE', "%$searchValue%")
                      ->orWhere('email', 'LIKE', "%$searchValue%")
                      ->orWhereHas('user_details', function ($query) use ($searchValue) {
                        $query->where('street', 'LIKE', "%$searchValue%")
                        ->orWhere('state_city', 'LIKE', "%$searchValue%")
                        ->orWhere('country', 'LIKE', "%$searchValue%");
                      });
                // Add more columns for search as needed
            });
        }

        $totalRecords = $users->count(); // Get the total number of records for pagination

        $data = $users->skip($request->start)
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
            ->addColumn('email', function ($row) {
                return $row->email;
            })
            ->addColumn('phone_number', function ($row) {
                return (isset($row->user_details)) ? $row->user_details->country_code.$row->user_details->phone_number : '';
            })
            ->addColumn('address', function ($row) {
                return (isset($row->user_details)) ? strtoupper($row->user_details->street.', '.$row->user_details->state_city.', '.$row->user_details->country) : '';
            })
            ->addColumn('role', function ($row) {
                $html = '';
                if ($row->user_type == 'admin') {
                    $html .= '<span class="badge bg-white text-success px-2">'.ucfirst($row->user_type).'</span>';
                }
                if ($row->user_type == 'manager') {
                    $html .= '<span class="badge bg-white text-primary px-2">'.ucfirst($row->user_type).'</span>';
                }
                if ($row->user_type == 'seller') {
                    $html .= '<span class="badge bg-white text-danger px-2">'.ucfirst($row->user_type).'</span>';
                }
                if ($row->user_type == 'supplier') {
                    $html .= '<span class="badge bg-white text-info px-2">'.ucfirst($row->user_type).'</span>';
                }
                if ($row->user_type == 'customer') {
                    $html .= '<span class="badge bg-white text-secondary px-2">'.ucfirst($row->user_type).'</span>';
                }
                return $html;
            })
            ->addcolumn('active_status', function ($row) {
                if ($row->active_status == 'active') {
                    return '<span class="badge2 px-1 text-success border-success"><i class="align-middle text-success" data-feather="user-check">
                    </i></span>';
                } else {
                    return '<span class="badge2 px-1 text-danger border-danger"><i class="align-middle text-danger" data-feather="user-x">
                    </i></span>';
                }
            })
            ->addColumn('actions', function ($row) {
                $btns = '
                <a class="badge2 text-dark" href="#" data-bs-toggle="dropdown">
                    <i class="align-middle text-center" data-feather="more-vertical"></i>
                </a>
                <div class="dropdown-menu dropdown-menu-end">
                    <a class="dropdown-item" href="users/' . $row->id . '/edit">
                        <i class="align-middle me-1 text-primary" data-feather="edit">
                        </i>
                        Edit Details
                    </a>
                    <a class="dropdown-item" href="users/' . $row->id . '/delete">
                        <i class="align-middle me-1 text-danger" data-feather="trash-2">
                        </i>
                        Delete User
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
        return view('users.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        try {
            $roles = Role::get();
            return view('users.create', compact('roles'));
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Something went wrong',
            ]);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        try {
            $this->validateUser($request);
            $user = new User();
            $this->saveUser($request, $user);
            return response()->json([
                'status' => true,
                'message' => 'User saved successfully'
            ]);
        } catch (\Exception $e) {
            dd($e->getMessage());
            return response()->json([
                'status' => false,
                'message' => 'Something went wrong',
            ]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
        try {
            $user = User::with('user_details')->findOrFail($id);
            $roles = Role::get();
            return view('users.edit', compact('user', 'roles'));
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Something went wrong',
            ]);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
        try {
            $this->validateUser($request);
            $user = User::with('user_details')->where('id', $id)->first();
            if (isset($user)) {
                $this->saveUser($request, $user);
                return response()->json([
                    'status' => true,
                    'message' => 'Updated successfully'
                ]);
            } else {
                return response()->json([
                    'status' => false,
                    'message' => 'No user found',
                ]);
            }
        } catch (\Exception $e) {
            dd($e->getMessage());
            return response()->json([
                'status' => false,
                'message' => 'Something went wrong',
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    // validate user
    public function validateUser($request)
    {
        $this->validate($request, [
            'name' => 'required',
            'email' => 'required',
            'country_code' => 'required',
            'phone_number' => 'required',
            'street' => 'required',
            'state_city' => 'required',
            'country' => 'required',
            // 'image' => 'required',
        ]);
    }

    // Save user record
    public function saveUser($request, $user)
    {
        $user->name = $request->name;
        $user->email = $request->email;
        if (Auth::user()->hasRole('admin')) {
            if (!empty($request->password)) {
                $user->password = Hash::make($request->password);
            }
        }
        $user->user_type = !empty($request->user_type) ? $request->user_type : (!empty($user->user_type) ? $user->user_type : 'customer');
        $user->active_status = !is_null($request->active_status) ? 'active' : 'in_active';
        $user->save();
        $role = Role::where('name', $request->user_type)->first();
        if ($user && $role) {
            $user->assignRole($role);
        }
        $this->saveUserDetail($request, $user);
    }

    // Save user details
    public function saveUserDetail($request, $user)
    {
        $userDetails = UserDetail::where('user_id', $user->id)->first();
        if (!isset($userDetails)) {
            $userDetails = new UserDetail();
            $userDetails->user_id = $user->id;
        }
        $userDetails->phone_number = $request->phone_number;
        $userDetails->country_code = $request->country_code;
        $userDetails->street = $request->street;
        $userDetails->state_city = $request->state_city;
        $userDetails->country = $request->country;
        if ($request->hasFile('image')) {
            $imageName = Carbon::now()->format('Ymdhis') . '.' . $request->file('image')->getClientOriginalExtension();
            $imagePath = $request->file('image')->move(public_path('img/photos'), $imageName);
            // Store the image filename in the database
            $userDetails->image = $imageName;
        }
        $userDetails->save();
        
    }
}
