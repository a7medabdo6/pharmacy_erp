<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class CategoryController extends Controller
{
    //
    //
    public function index(Request $request)
    {
        try {
            $Category = Category::all();;
            return response()->json($Category);
        } catch (\Throwable $th) {
        }
    }
    public function oneStore(Request $request, $id)
    {
        try {
            $Category = Category::find($id);;
            return response()->json($Category);
        } catch (\Throwable $th) {
        }
    }

    //
    public function create(Request $request)
    {


        try {
            $validator = Validator::make($request->all(), [
                'nameEn' => 'required|string|unique:categories',
                'nameAr' => 'required|string|unique:categories',


            ]);
            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()], 422);
            }

            $categories = Category::create($request->all());;
            // Set other fields as needed
            $categories->save();
            // dd($managers);



            return response()->json(['message' => 'category created successfully'], 201);
        } catch (ValidationException $e) {
            // Handle validation errors
            return response()->json(['errors' => $e->errors()], 422);
        }
    }
    public function Delete(Request $request, $id)
    {
        try {
            $category = Category::find($id);;
            if (!$category) {
                // Handle the case where the user is not found (e.g., return a 404 response)
                return response()->json(['error' => 'category not found'], 404);
            }
            $category->delete();

            return response()->json(['message' => 'category deleted successfully']);
        } catch (\Throwable $th) {
        }
    }
}
