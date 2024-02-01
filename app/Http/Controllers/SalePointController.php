<?php

namespace App\Http\Controllers;

use App\Models\SalePoint;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class SalePointController extends Controller
{
    public function index(Request $request)
    {
        try {
            $SalePoint = SalePoint::all();;
            return response()->json($SalePoint);
        } catch (\Throwable $th) {
        }
    }
    public function oneStore(Request $request, $id)
    {
        try {
            $SalePoint = SalePoint::find($id);;
            return response()->json($SalePoint);
        } catch (\Throwable $th) {
        }
    }

    //
    public function create(Request $request)
    {


        try {
            $validator = Validator::make($request->all(), [
                'name' => 'required',
                'balance' => 'required',
                'user_id' => 'required',



            ]);
            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()], 422);
            }
            $tenantId = $request->header("bussniesid");

            $data = $request->all();
            $data["tenant_id"] = $tenantId;

            $SalePoint = SalePoint::create($data);;
            $SalePoint->save();



            return response()->json(['message' => 'SalePoint created successfully'], 201);
        } catch (ValidationException $e) {
            // Handle validation errors
            return response()->json(['errors' => $e->errors()], 422);
        }
    }
    public function Delete(Request $request, $id)
    {
        try {
            $SalePoint = SalePoint::find($id);;
            if (!$SalePoint) {
                // Handle the case where the user is not found (e.g., return a 404 response)
                return response()->json(['error' => 'SalePoint not found'], 404);
            }
            $SalePoint->delete();

            return response()->json(['message' => 'SalePoint deleted successfully']);
        } catch (\Throwable $th) {
        }
    }
    //
}
