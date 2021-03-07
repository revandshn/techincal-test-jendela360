<?php

namespace App\Http\Controllers\API\Feature;

use App\Http\Controllers\Controller;
use App\Models\Car;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class CarController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // Role check
        // Administrator only
        $user = Auth::guard('api')->user();
        if (!Auth::guard('api')->check())
            return $this->errorResponse("User unauthenticated or Invalid Token",  401);
        if ($user->role !== 1)
            return $this->errorResponse("You are not allowed",  401);

        try {
            $car = Car::paginate(10);

            if (!$car)
                return $this->errorResponse('Car not found', 404);

            return $this->successResponse($car, 'List of cars', 200);
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
        // Role check
        // Administrator only
        $user = Auth::guard('api')->user();
        if (!Auth::guard('api')->check())
            return $this->errorResponse("User unauthenticated or Invalid Token",  401);
        if ($user->role !== 1)
            return $this->errorResponse("You are not allowed",  401);

        // Validate
        $valid = Validator::make($request->all(), [
            'name' => 'required|string',
            'price' => 'required|integer',
            'stock' => 'required|integer'
        ]);

        if ($valid->fails())
            return $this->errorResponse($valid->errors(), 400);

        try {
            $car = Car::create([
                'name' => $request->name,
                'price' => $request->price,
                'stock' => $request->stock
            ]);

            if (!$car)
                return $this->errorResponse('Can\'t create car object', 404);

            return $this->successResponse($car, 'Car created', 201);
        } catch (QueryException $e) {
            return $this->errorResponse($e, 400);
        }
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

        try {
            $car = Car::find($id);
            if (!$car)
                return $this->errorResponse('Car not found', 404);

            return $this->successResponse($car, 'This car', 200);
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
        // Role check
        // Administrator only
        $user = Auth::guard('api')->user();
        if (!Auth::guard('api')->check())
            return $this->errorResponse("User unauthenticated or Invalid Token",  401);
        if ($user->role !== 1)
            return $this->errorResponse("You are not allowed",  401);

        // Validate
        $valid = Validator::make($request->all(), [
            'name' => 'required|string',
            'price' => 'required|integer',
            'stock' => 'required|integer'
        ]);

        if ($valid->fails())
            return $this->errorResponse($valid->errors(), 400);

        try {
            $car = Car::find($id);

            if (!$car)
                return $this->errorResponse('Car not found', 404);

            $car->name = $request->name;
            $car->price = $request->price;
            $car->stock = $request->stock;

            if (!$car->save())
                return $this->errorResponse('Car update failed', 404);

            return $this->successResponse($car, 'Car updated', 200);
        } catch (QueryException $e) {
            return $this->errorResponse($e, 400);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        // Role check
        // Administrator only
        $user = Auth::guard('api')->user();
        if (!Auth::guard('api')->check())
            return $this->errorResponse("User unauthenticated or Invalid Token",  401);
        if ($user->role !== 1)
            return $this->errorResponse("You are not allowed",  401);

        try {
            $car = Car::find($id);
            if (!$car)
                return $this->errorResponse('Car not found', 404);

            $car->delete();

            if (!$car->trashed())
                return $this->errorResponse('Car delete failed', 404);

            return $this->successResponse($car, 'Car deleted', 200);
        } catch (QueryException $e) {
            return $this->errorResponse($e, 400);
        }
    }
}
