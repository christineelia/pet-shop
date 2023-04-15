<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
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
