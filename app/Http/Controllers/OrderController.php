<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;

// use App\Http\Controllers\Validator;
class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {
            $msg = 'All orders are right here';
            $data = Order::with(['users', 'products'])->get();
            return $this->successResponse($data, $msg);
        } catch (\Exception $ex) {
            return $this->errorResponse($ex->getMessage(), 500);
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
        $validator = \Validator::make(
            $request->all(),
            [
                'user_id' => 'required|exists:users,id'
            ]
        );
        if ($validator->fails()) {
            return $this->errorResponse($validator->errors(), 422);
        }
        try {
            $order = Order::create([
                'userId' => $request->user_id
            ]);
            $data = $order;
            $msg = 'Order is created successfully';
            return $this->successResponse($data, $msg, 201);
        } catch (\Exception $ex) {
            return $this->errorResponse($ex->getMessage(), 500);
        }
    }
    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function show(Order $order)
    {
        try {
            if (!$order)
                return $this->errorResponse('No order with such id', 404);
            $msg = 'Got you the order you are looking for';
            return $this->successResponse($order, $msg);
        } catch (\Exception $ex) {
            return $this->errorResponse($ex->getMessage(), 500);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $order)
    {
        try {
            if (!$order) {
                return $this->errorResponse('No order with such id', 404);
            }
            $order->userId = $request->input('userId');
            $order->save();

            if ($request->has('products')) {
                $products = $request->input('products');
                $quantities = $request->input('quantities');
                $order->products()->sync($products, $quantities);
            }

            $msg = 'The order is updated successfully';
            return $this->successResponse($order, $msg);
        } catch (\Exception $ex) {
            return $this->errorResponse($ex->getMessage(), 500);
        }
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function destroy(Order $order)
    {

        try {
            if (!$order) {
                return $this->errorResponse('No order with such id', 404);
            }
            $order->delete();
            $msg = 'The order is deleted successfully';
            return $this->successResponse($order, $msg);
        } catch (\Exception $ex) {
            return $this->errorResponse($ex->getMessage(), 500);
        }

    }
}