<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $user_id = auth()->id();

        try {
            $user = User::find($user_id);
            return response()->json(['success' => 'true' , 'user' => $user]);

        }catch(\Throwable $e) {
            return response()->json(['success' => 'false' , 'user' => []]);
        }

        
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
      //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $uuid)
    {
        $user_id = auth()->id();
        $user = User::find($user_id);

        if($user) {
            $user->delete();
            return response()->json(['success' => 'true' , 'message' => 'User deleted successfully!']);
        }

        return response()->json(['success' => 'true' , 'message' => 'Not Authorised']);
    }
}
