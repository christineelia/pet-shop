<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

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
        //
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
        $user = User::where([['uuid', '=' , $uuid], ['is_admin','!=', 1]])
                    ->whereNull('deleted_at')
                    ->first();

        if($user) {
            if($request->has('first_name')) {
                $user->first_name       = $request->get('first_name');
            }

            if($request->has('last_name')) {
                $user->last_name        = $request->get('last_name');
            }

            if($request->has('email')) {
                $user->email            = $request->get('email');
            }

            if($request->has('address')) {
                $user->address          = $request->get('address');
            }

            if($request->has('phone_number')) {
                $user->phone_number     = $request->get('phone_number');
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