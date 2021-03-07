<?php

namespace App\Http\Controllers\API\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    public function profile()
    {
        // Role check
        // User id
        $user = Auth::guard('api')->user();
        if (!Auth::guard('api')->check())
            return $this->errorResponse("User unauthenticated or Invalid Token",  401);

        try {
            $user = User::find($user->id);

            if (!$user)
                return $this->errorResponse('User not found', 404);

            return $this->successResponse($user, 'This user', 200);
        } catch (QueryException $e) {
            return $this->errorResponse($e, 400);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        // Role check
        // Administrator only
        $user = Auth::guard('api')->user();
        if (!Auth::guard('api')->check())
            return $this->errorResponse("User unauthenticated or Invalid Token",  401);
        if ($user->role !== 1)
            return $this->errorResponse("You are not allowed",  401);

        try {
            $user = User::find($id);

            if (!$user)
                return $this->errorResponse('User not found', 404);

            return $this->successResponse($user, 'This user', 200);
        } catch (QueryException $e) {
            return $this->errorResponse($e, 400);
        }
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
