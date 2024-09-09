<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $limit = $request->limit ? $request->limit : 10;
        $query = Role::withCount('users');
        if ($request->has('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }
        if ($request->has('all') && $request->all == 'true') {
            $users = $query->get();
            $data = [
                'data' => $users,
            ];
            $no = 1;
        } else {
            $users = $query->latest()->paginate($limit);
            $data = [
                'data' => $users->items(),
                'pagination' => [
                    'total' => $users->total(),
                    'per_page' => $users->perPage(),
                    'current_page' => $users->currentPage(),
                    'last_page' => $users->lastPage(),
                    'from' => $users->firstItem(),
                    'to' => $users->lastItem(),
                ]
            ];
            $no = $users->firstItem();
        }

        foreach ($users as $index => $item) {
            $item->no = $no;
            $no++;
        }

        $response = [
            'success' => true,
            'message' => 'List users',
            'data' => $data
        ];
        return response()->json($response, 200);
    }

    public function getPermissions(Request $request)
    {
        $permissions = Permission::all();
        $response = [
            'success' => true,
            'message' => 'List users',
            'data' => $permissions
        ];
        return response()->json($response, 200);
    }

    public function getRolePermissions(Role $role)
    {
        // $role = Role::find($request->id);
        $permissions = Permission::all();
        $rolePermissions = $role->permissions()->pluck('id')->toArray();
        $response = [
            'success' => true,
            'message' => 'List users',
            'data' => [
                'role' => $role,
                'permissions' => $permissions,
                'rolePermissions' => $rolePermissions
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
        $validated = $request->validate([
            'name' => 'required|string',
            'permission' => 'required|array',
            'permission.*' => 'integer|exists:permissions,id',
        ], [
            'name.required' => 'Role name is required',
            'permission.required' => 'Akses Menu Wajib dipilih',
        ]);

        // Create a new role
        $role = Role::create(['name' => $validated['name']]);

        // Assign the selected permissions to the new role
        if (!empty($validated['permission'])) {
            $permissions = Permission::whereIn('id', $validated['permission'])->get();
            $role->givePermissionTo($permissions);
        }

        return response()->json(['success' => true, 'message' => 'Role created successfully', 'data' => $request->all()], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $role = Role::find($id);
        $rolePermissions = $role->permissions()->pluck('id')->toArray();
        $response = [
            'success' => true,
            'message' => 'List users',
            'data' => [
                'role' => $role,
                'rolePermissions' => $rolePermissions
            ]
        ];
        return response()->json($response, 200);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        // Validate the incoming request
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'permission' => 'nullable|array',
            'permission.*' => 'exists:permissions,id'
        ]);

        // Find the role by ID
        $role = Role::findOrFail($id);

        // Update the role name
        $role->name = $validated['name'];

        // Assign the selected permissions to the role
        if (!empty($validated['permission'])) {
            // Detach all existing permissions
            $role->permissions()->detach();

            // Attach the new permissions
            $permissions = Permission::whereIn('id', $validated['permission'])->get();
            $role->givePermissionTo($permissions);
        }

        // Save the updated role
        $role->save();

        return response()->json([
            'success' => true,
            'message' => 'Role updated successfully',
            'data' => $role
        ], 200);
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            // Find the role by ID
            $role = Role::findOrFail($id);

            // Detach all permissions associated with this role, if necessary
            $role->permissions()->detach();

            // Delete the role
            $role->delete();

            return response()->json([
                'success' => true,
                'message' => 'Role deleted successfully'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error deleting role: ' . $e->getMessage()
            ], 500);
        }
    }
}
