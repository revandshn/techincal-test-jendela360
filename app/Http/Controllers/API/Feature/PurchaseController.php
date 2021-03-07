<?php

namespace App\Http\Controllers\API\Feature;

use App\Http\Controllers\Controller;
use App\Models\PurchaseLog;
use Carbon\Carbon;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PurchaseController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // Role check
        $user = Auth::guard('api')->user();
        if (!Auth::guard('api')->check())
            return $this->errorResponse("User unauthenticated or Invalid Token",  401);
        if ($user->role !== 1)
            return $this->errorResponse("You are not allowed",  401);

        try {
            $carbonNow = Carbon::now()->toDateString();

            $purchaseOfToday = PurchaseLog::where('created_at', 'like', $carbonNow . '%')->with("car")->get();

            $mostSoldCar = PurchaseLog::select('car_id')
                ->where('created_at', 'like', $carbonNow . '%')
                ->groupBy('car_id')
                ->orderByRaw('COUNT(*) DESC')
                ->with("car")
                ->first();
            $carName = $mostSoldCar->car->name;

            $totalPurchase = 0;
            foreach ($purchaseOfToday as $item) {
                $totalPurchase += $item->car->price;
            }

            if (!$purchaseOfToday)
                return $this->errorResponse('Purchase not found', 404);

            if (!$mostSoldCar)
                return $this->errorResponse('Purchase not found', 404);

            $data = [
                'most_sold_car' => $carName,
                'purchase_of_today' => $purchaseOfToday->count(),
                'total_purchase' => $totalPurchase
            ];

            return $this->successResponse($data, 'Purchase today', 200);
        } catch (QueryException $e) {
            return $this->errorResponse($e, 400);
        }
    }

    public function index7days()
    {
        // Role check
        $user = Auth::guard('api')->user();
        if (!Auth::guard('api')->check())
            return $this->errorResponse("User unauthenticated or Invalid Token",  401);
        if ($user->role !== 1)
            return $this->errorResponse("You are not allowed",  401);


        try {
            $carbonNow = Carbon::now()->toDateString();

            $purchaseOfToday = PurchaseLog::where('created_at', 'like', $carbonNow . '%')->with("car")->get();

            $mostSoldCar = PurchaseLog::select('car_id')
                ->where('created_at', 'like', $carbonNow . '%')
                ->groupBy('car_id')
                ->orderByRaw('COUNT(*) DESC')
                ->with("car")
                ->first();
            $carName = $mostSoldCar->car->name;

            $totalPurchase = 0;
            foreach ($purchaseOfToday as $item) {
                $totalPurchase += $item->car->price;
            }

            if (!$purchaseOfToday)
                return $this->errorResponse('Purchase not found', 404);

            if (!$mostSoldCar)
                return $this->errorResponse('Purchase not found', 404);

            $data = [
                'most_sold_car' => $carName,
                'purchase_of_today' => $purchaseOfToday->count(),
                'total_purchase' => $totalPurchase
            ];

            return $this->successResponse($data, 'Purchase today', 200);
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
    public function store(Request $request, $id)
    {
        // Role check
        $user = Auth::guard('api')->user();
        if (!Auth::guard('api')->check())
            return $this->errorResponse("User unauthenticated or Invalid Token",  401);
        if ($user->role !== 0)
            return $this->errorResponse("You are not allowed",  401);

        try {
            $purchase = PurchaseLog::create([
                'user_id' => $user->id,
                'car_id' => $id
            ]);

            if (!$purchase)
                return $this->errorResponse('Can\'t create purchase car', 404);

            return $this->successResponse($purchase, 'Purchase success', 201);
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
        $user = Auth::guard('api')->user();
        if (!Auth::guard('api')->check())
            return $this->errorResponse("User unauthenticated or Invalid Token",  401);
        if ($user->role > 1)
            return $this->errorResponse("You are not allowed",  401);

        try {
            $purchase = PurchaseLog::find($id);

            if (!$purchase)
                return $this->errorResponse('Purchase not found', 404);

            return $this->successResponse($purchase, 'This purchase', 200);
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
