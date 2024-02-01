<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class SupplierController extends Controller
{
    //

    public function index(Request $request)
    {
        try {
            $Supplier = Supplier::all();;
            return response()->json($Supplier);
        } catch (\Throwable $th) {
        }
    }
    public function oneStore(Request $request, $id)
    {
        try {
            $Supplier = Supplier::find($id);;
            return response()->json($Supplier);
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
                'code' => 'required|string',




            ]);
            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()], 422);
            }
            $data = $request->all();
            // if (empty($request->input('medium_unit_price'))) {
            //     $data['medium_unit_price'] = $request->large_unit_price / $request->medium_unit_no;
            // }
            // if (empty($request->input('small_unit_price'))) {
            //     $data['small_unit_price'] = $data['medium_unit_price'] / $request->small_unit_no;
            // }
            $tenantId = $request->header("bussniesid");

            $Supplieres = Supplier::create([...$data, "tenant_id" => $tenantId]);;
            // Set other fields as needed
            $Supplieres->save();



            return response()->json(['message' => 'Supplier created successfully'], 201);
        } catch (ValidationException $e) {
            // Handle validation errors
            return response()->json(['errors' => $e->errors()], 422);
        }
    }
    public function Delete(Request $request, $id)
    {
        try {
            $Supplier = Supplier::find($id);;
            if (!$Supplier) {
                // Handle the case where the user is not found (e.g., return a 404 response)
                return response()->json(['error' => 'Supplier not found'], 404);
            }
            $Supplier->delete();

            return response()->json(['message' => 'Supplier deleted successfully']);
        } catch (\Throwable $th) {
        }
    }
}
