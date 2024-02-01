<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class ProductController extends Controller
{
    //
    public function index(Request $request)
    {
        try {
            $Product = Product::all();;
            return response()->json($Product);
        } catch (\Throwable $th) {
        }
    }
    public function oneStore(Request $request, $id)
    {
        try {
            $Product = Product::find($id);;
            return response()->json($Product);
        } catch (\Throwable $th) {
        }
    }

    //
    public function create(Request $request)
    {


        try {
            $validator = Validator::make($request->all(), [
                'nameEn' => 'required|string',
                'nameAr' => 'required|string',
                'company' => 'required|string',
                'ac' => 'required|string',
                'category_id' => 'required|string',

                'large_unit_id' => 'required|string',
                // 'large_unit_no' => 'required|string',
                'large_unit_price' => 'required|string',
                'large_unit_qty' => 'required|string',

                'medium_unit_id' => 'required|string',
                'medium_unit_no' => 'required|string',
                // 'medium_unit_price' => 'required|string',
                'medium_unit_qty' => 'required|string',

                'small_unit_id' => 'required|string',
                'small_unit_no' => 'required|string',
                // 'small_unit_price' => 'required|string',
                'small_unit_qty' => 'required|string'


            ]);
            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()], 422);
            }
            $data = $request->all();
            if (empty($request->input('medium_unit_price'))) {
                $data['medium_unit_price'] = $request->large_unit_price / $request->medium_unit_no;
            }
            if (empty($request->input('small_unit_price'))) {
                $data['small_unit_price'] = $data['medium_unit_price'] / $request->small_unit_no;
            }

            $categories = Product::create($data);;
            // Set other fields as needed
            $categories->save();



            return response()->json(['message' => 'Product created successfully'], 201);
        } catch (ValidationException $e) {
            // Handle validation errors
            return response()->json(['errors' => $e->errors()], 422);
        }
    }
    public function Delete(Request $request, $id)
    {
        try {
            $Product = Product::find($id);;
            if (!$Product) {
                // Handle the case where the user is not found (e.g., return a 404 response)
                return response()->json(['error' => 'Product not found'], 404);
            }
            $Product->delete();

            return response()->json(['message' => 'Product deleted successfully']);
        } catch (\Throwable $th) {
        }
    }
}
