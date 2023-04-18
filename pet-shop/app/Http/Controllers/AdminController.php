<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;


class AdminController extends Controller
{
    /**
     * @OA\Get(
     *     tags={"Admin"},
     *     path="/admin/user-listing",
     *     summary="Get list of non admin users",
     *     description="Returns information about the current user if the request is authenticated",
     *     @OA\Parameter(
    *         name="Authorization",
    *         description="Bearer token for authentication",
    *         in="header",
    *         required=true
    *     ),
    *     @OA\Parameter(
    *         name="page",
    *         description="Page number",
    *         in="query",
    *         required=false
    *     ),
    *     @OA\Parameter(
    *         name="limit",
    *         description="Item per page",
    *         in="query",
    *         required=false
    *     ),
        *     @OA\Parameter(
    *         name="sortBy",
    *         description="Name of column",
    *         in="query",
    *         required=false
    *     ),
    *     @OA\Parameter(
    *         name="desc",
    *         description="1 or 0",
    *         in="query",
    *         required=false
    *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Success"
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="Unauthorised"
     *     )
     * )
    */
    public function index(Request $request)
    {
        $users = User::where('is_admin','!=', 1)
                     ->whereNull('deleted_at');

        $sortBy     = $request->input('sortBy', 'id');
        $sortOrder  = $request->input('desc', '0');
        if ($sortOrder == 1) {
            $sortOrder = 'desc';
        } else {
            $sortOrder = 'asc';
        }
        $users->orderBy($sortBy, $sortOrder);

        // Paginate results
        $page       = $request->input('page', 1);
        $perPage    = $request->input('limit', 10);
        $users      = $users->paginate($perPage, ['*'], 'page', $page);

        return response()->json(['success' => 'true' , 'users' => $users]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {

        $validatedData = $request->validate([
            'first_name' => 'required|string',
            'last_name' => 'required|string',
            'email' => ['required', 'email', Rule::unique('users')],
            'password' => 'required|string|min:8|confirmed',
            'avatar' => 'nullable|string',
            'address' => 'nullable|string',
            'phone_number' => 'nullable|string',
            'marketing' => 'nullable|string',
        ]);
        
            $user               = new User();
            $user->uuid         = Str::uuid()->toString();
            $user->first_name   = $validatedData['first_name'];
            $user->last_name    = $validatedData['last_name'];
            $user->email        = $validatedData['email'];
            $user->password     = bcrypt($validatedData['password']);
            $user->avatar       = $validatedData['avatar'];
            $user->address      = $validatedData['address'];
            $user->phone_number = $validatedData['phone_number'];
            $user->is_marketing = $validatedData['marketing'];
            $user->is_admin     = true;
            $user->save();

        } catch (\Throwable $e) {
            return response()->json(['message' => $e->getMessage()], 422);
        }
       
        return response()->json(['message' => 'Admin account created successfully'], 200);
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
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $uuid)
    {
        $rules = [
            'first_name'    => 'required|string',
            'last_name'     => 'required|string',
            'email'         => [
                'required',
                'email',
                Rule::unique('users')->ignore($uuid, 'uuid'),
            ],
            'password'      => 'sometimes|required|string|confirmed|min:8',
            'address'       => 'required|string',
            'phone_number'  => 'required|string',
            'is_marketing'  => 'sometimes|string'
        ];
    
        $validator = Validator::make($request->all(), $rules);
    
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }
        $user = User::where([['uuid', '=' , $uuid], ['is_admin','!=', 1]])
                    ->whereNull('deleted_at')
                    ->first();

        if($user) {
            $user->first_name       = $request->get('first_name');
            $user->last_name        = $request->get('last_name');
            $user->email            = $request->get('email');
            $user->address          = $request->get('address');
            if($request->has('password')) {
                $user->password     = bcrypt($request->get('password'));
            }
            if($request->has('avatar')) {
                $user->avatar     = bcrypt($request->get('avatar'));
            }
            $user->save();

            return response()->json(['success' => 'true' , 'user' => $user , 'message' => 'Updated Successfully']);

        }
        return response()->json(['success' => 'true' , 'user' => [] , 'message' => 'Not Authorised']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $uuid)
    {
        $user = User::where([['uuid', '=' , $uuid], ['is_admin','!=', 1]])
                    ->whereNull('deleted_at')
                    ->first();
        
        if($user) {
            $user->delete();
            return response()->json(['success' => 'true' , 'message' => 'User deleted successfully!']);
        }

        return response()->json(['success' => 'true' , 'message' => 'Not Authorised']);
    }
}
