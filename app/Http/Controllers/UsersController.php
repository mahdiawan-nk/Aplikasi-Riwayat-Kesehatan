<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class UsersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $limit = $request->limit ? $request->limit : 10;
        $query = User::with('roles');
        if ($request->has('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
            $query->orWhere('email', 'like', '%' . $request->search . '%');
        }

        $users = $query->latest()->paginate($limit);
        $no = $users->firstItem();
        foreach ($users->items() as $index => $item) {
            $item->no = $no;
            $no++;
        }

        $response = [
            'success' => true,
            'message' => 'List users',
            'data' => [
                'data' => $users->items(),
                'pagination' => [
                    'total' => $users->total(),
                    'per_page' => $users->perPage(),
                    'current_page' => $users->currentPage(),
                    'last_page' => $users->lastPage(),
                    'from' => $users->firstItem(),
                    'to' => $users->lastItem(),
                ]
            ]
        ];
        return response()->json($response, 200);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // Validation
        $validatedData = $request->validate(
            [
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users,email',
                'password' => 'string|nullable',
                'roles' => 'required',
                'phone' => 'string|nullable',
            ],
            [
                'name.required' => 'Name is required',
                'email.required' => 'Email is required',
                'email.unique' => 'Email already exists',
                'password.required' => 'Password is required',
                'roles.required' => 'Role is required',
            ]
        );
        $password = $validatedData['password'] ? '123456' : $validatedData['password'];
        // Insert new user into the 'users' table
        $user = User::create([
            'name' => $validatedData['name'],
            'email' => $validatedData['email'],
            'password' => Hash::make($password), // Hashing the password
            'phone' => $validatedData['phone'],
        ]);

        $role = Role::findOrFail($validatedData['roles']);
        $user->assignRole($role->name);
        // Return success response
        return response()->json([
            'success' => true,
            'message' => 'User created successfully',
            'data' => $user,
        ], 201);
    }


    /**
     * Display the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        $dataUsers = User::with('roles')->find($user->id);
        $success['data'] = $dataUsers;
        return $this->sendResponse($success, 'User data retrieved successfully.');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {
        $user->email = $request->email;
        $user->name = $request->name;
        $user->phone = $request->phone;
        if ($request->password != null) {
            $user->password = Hash::make($request->password);
        }
        $user->save();

        // Find the role by ID and get its name
        $roleName = Role::where('id', $request->roles)->pluck('name')->first();

        // Sync the user's role
        $user->syncRoles($roleName);
        $response = [
            'success' => true,
            'message' => 'User updated',
            'data' => $user
        ];
        return response()->json($response, 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        $user->delete();
        $response = [
            'success' => true,
            'message' => 'User deleted',
            'data' => $user
        ];
        return response()->json($response, 200);
    }
}
