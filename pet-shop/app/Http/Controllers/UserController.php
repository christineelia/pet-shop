<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Validation\Rule;
use App\Models\Order;
use Illuminate\Support\Facades\Validator;

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
    public function update(Request $request)
    {
        $user_id    = auth()->id();
        $user       = User::find($user_id);

        $rules = [
            'first_name'    => 'required|string',
            'last_name'     => 'required|string',
            'email'         => [
                'required',
                'email',
                Rule::unique('users')->ignore($user->uuid, 'uuid'),
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

    /**
     * Remove the specified resource from storage.
     */
    public function destroy()
    {
        $user_id    = auth()->id();
        $user       = User::find($user_id);

        if($user) {
            $user->delete();
            return response()->json(['success' => 'true' , 'message' => 'User deleted successfully!']);
        }

        return response()->json(['success' => 'true' , 'message' => 'Not Authorised']);
    }

    public function orders(Request $request)
    {
        $user = $request->user();

        try {

            $validator = $request->validate([
                'page' => 'nullable|integer|min:1',
                'limit' => 'nullable|integer|min:1|max:100',
                'desc' => 'nullable|boolean'
            ]);

            $page = $validator['page'] ?? 1;
            $limit = $validator['limit'] ?? 10;
            $sortBy = $validator['sortBy'] ?? 'created_at';
            $desc = $validator['desc'] ?? true;

            $query = Order::where('user_id', $user->id);

            if ($sortBy) {
                $direction = $desc ? 'desc' : 'asc';
                $query->orderBy($sortBy, $direction);
            }

            $orders = $query->paginate($limit, ['*'], 'page', $page);

            return response()->json(['success' => 'true' , 'orders' => $orders]);

        } catch (\Throwable $e) {
            return response()->json(['success' => 'false' , 'message' => $e->getMessage()], 422);
        }
    }
}
