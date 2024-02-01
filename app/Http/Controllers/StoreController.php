<?php

namespace App\Http\Controllers;

use App\Models\Store;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class StoreController extends Controller
{
    //
    public function index(Request $request)
    {
        try {
            $stores = Store::all();
            return response()->json($stores);
        } catch (\Throwable $th) {
        }
    }
    public function oneStore(Request $request, $id)
    {
        try {
            $stores = Store::find($id);;
            return response()->json($stores);
        } catch (\Throwable $th) {
        }
    }

    //
    public function create(Request $request)
    {


        try {
            $validator = Validator::make($request->all(), [
                'nameEn' => 'required|string|unique:stores',
                'nameAr' => 'required|string|unique:stores',
                'type' => 'required|string',
                'managers' => 'required',
                'user_id' => 'required|string',

            ]);
            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()], 422);
            }
            $tenantId = $request->header("bussniesid");

            $store = Store::create([
                ...$request->only("nameEn", "nameAr", "type", "user_id"),
                "tenant_id" => $tenantId
            ]);;
            // Set other fields as needed
            $store->save();
            $managers = User::find($request->managers);
            // dd($managers);
            // Check if all managers with the given IDs exist
            if (count($managers) !== count($request->managers)) {
                return response()->json(['error' => 'One or more managers not found'], 404);
            }

            $store->managers()->attach($managers);

            return response()->json(['message' => 'Store created successfully'], 201);
        } catch (ValidationException $e) {
            // Handle validation errors
            return response()->json(['errors' => $e->errors()], 422);
        }
    }
    public function Delete(Request $request, $id)
    {
        try {
            $stores = Store::find($id);;
            if (!$stores) {
                // Handle the case where the user is not found (e.g., return a 404 response)
                return response()->json(['error' => 'store not found'], 404);
            }
            $stores->delete();

            return response()->json(['message' => 'store deleted successfully']);
        } catch (\Throwable $th) {
        }
    }
}
