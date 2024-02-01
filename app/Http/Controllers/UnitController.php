<?php

namespace App\Http\Controllers;

use App\Models\Unit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class UnitController extends Controller
{
    //

    public function index(Request $request)
    {
        try {
            $Unit = Unit::all();;
            return response()->json($Unit);
        } catch (\Throwable $th) {
        }
    }
    public function oneStore(Request $request, $id)
    {
        try {
            $Unit = Unit::find($id);;
            return response()->json($Unit);
        } catch (\Throwable $th) {
        }
    }

    //
    public function create(Request $request)
    {


        try {
            $validator = Validator::make($request->all(), [
                'nameEn' => 'required|string|unique:units',
                'nameAr' => 'required|string|unique:units',


            ]);
            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()], 422);
            }
            $tenantId = $request->header("bussniesid");

            $Unit = Unit::create($request->all());;
            // Set other fields as needed
            $Unit->save();
            // dd($managers);



            return response()->json(['message' => 'Unit created successfully'], 201);
        } catch (ValidationException $e) {
            // Handle validation errors
            return response()->json(['errors' => $e->errors()], 422);
        }
    }
    public function Delete(Request $request, $id)
    {
        try {
            $Unit = Unit::find($id);;
            if (!$Unit) {
                // Handle the case where the user is not found (e.g., return a 404 response)
                return response()->json(['error' => 'Unit not found'], 404);
            }
            $Unit->delete();

            return response()->json(['message' => 'Unit deleted successfully']);
        } catch (\Throwable $th) {
        }
    }
}
