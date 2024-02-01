<?php

namespace App\Http\Controllers;

use App\Models\BillItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class BillItemController extends Controller
{
    //


    public function index(Request $request)
    {
        try {
            $BillItem = BillItem::all();;
            return response()->json($BillItem);
        } catch (\Throwable $th) {
        }
    }
    public function oneStore(Request $request, $id)
    {
        try {
            $BillItem = BillItem::find($id);;
            return response()->json($BillItem);
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

            $BillItemes = BillItem::create([...$data, "tenant_id" => $tenantId]);;
            // Set other fields as needed
            $BillItemes->save();



            return response()->json(['message' => 'BillItem created successfully'], 201);
        } catch (ValidationException $e) {
            // Handle validation errors
            return response()->json(['errors' => $e->errors()], 422);
        }
    }
    public function Delete(Request $request, $id)
    {
        try {
            $BillItem = BillItem::find($id);;
            if (!$BillItem) {
                // Handle the case where the user is not found (e.g., return a 404 response)
                return response()->json(['error' => 'BillItem not found'], 404);
            }
            $BillItem->delete();

            return response()->json(['message' => 'BillItem deleted successfully']);
        } catch (\Throwable $th) {
        }
    }
}
