<?php

namespace App\Http\Controllers;

use App\Models\Review;
use App\Models\User;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {
            $msg = 'All reviews are right here';
            $data = Review::with(['user', 'product'])->get();
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
        $validator = Validator::make(
            $request->all(),
            [
                'desc' => 'required|string',
                'star' => 'required|numeric',
                'user_id' => 'required|exists:users,id',
                'product_id' => 'required|exists:products,id'
            ]
        );
        if ($validator->fails()) {
            return $this->errorResponse($validator->errors(), 422);
        }
        try {
            $review = Review::create($request->all());
            $data = $review;
            $msg = 'Review is added successfully';
            return $this->successResponse($data, $msg, 201);
        } catch (\Exception $ex) {
            return $this->errorResponse($ex->getMessage(), 500);
        }
    }
    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Review  $review
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        try {
            $data = Review::find($id);
            if (!$data)
                return $this->errorResponse('No review with such id', 404);
            $msg = 'Got you the review you are looking for';
            return $this->successResponse($data, $msg);
        } catch (\Exception $ex) {
            return $this->errorResponse($ex->getMessage(), 500);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Review  $review
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Review $review)
    {

        try {
            //  $data = Review::find($review);
            if (!$review)
                return $this->errorResponse('No review with such id', 404);
            $review->update($request->all());
            $review->save();
            $msg = 'The review is updated successfully';
            return $this->successResponse($review, $msg);
        } catch (\Exception $ex) {
            return $this->errorResponse($ex->getMessage(), 500);
        }

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Review  $review
     * @return \Illuminate\Http\Response
     */
    public function destroy(Review $review)
    {

        try {
            if (!$review)
                return $this->errorResponse('No review with such id', 404);
            $review->delete();
            $msg = 'The review is deleted successfully';
            return $this->successResponse($review, $msg);
        } catch (\Exception $ex) {
            return $this->errorResponse($ex->getMessage(), 500);
        }

    }
    public function getUserReviews($userId)
    {
        try {
            $reviews = Review::where('userId', $userId)->get();
            $msg = 'Got reviews for user Successfully';
            return $this->successResponse($reviews, $msg);
        } catch (\Exception $ex) {
            return $this->errorResponse($ex->getMessage(), 500);
        }
    }
    public function getProductReviews($productId)
    {
        try {
            $reviews = Review::where('userId', $productId)->get();
            $msg = 'Got reviews for product Successfully';
            return $this->successResponse($reviews, $msg);
        } catch (\Exception $ex) {
            return $this->errorResponse($ex->getMessage(), 500);
        }
    }
    public function getMostActiveUsers()
    {
        try {
            $users = User::withCount('reviews')
                ->orderByDesc('reviews_count')
                ->get();
            if ($users->isEmpty()) {
                return $this->errorResponse('No users found', 404);
            }
            $msg = 'Got most active users Successfully';
            return $this->successResponse($users, $msg);
        } catch (\Exception $ex) {
            return $this->errorResponse($ex->getMessage(), 500);
        }
    }
}