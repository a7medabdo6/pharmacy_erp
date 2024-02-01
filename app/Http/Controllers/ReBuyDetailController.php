<?php

namespace App\Http\Controllers;

use App\Models\Bill;
use App\Models\BillItem;
use App\Models\ItemOperation;
use App\Models\Product;
use App\Models\ProductExtraDetail;
use App\Models\SalePoint;
use App\Models\SalePointOperation;
use App\Models\Supplier;
use App\Models\SupplierOperation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class ReBuyDetailController extends Controller
{
    //

    public function ReBuy(Request $request)
    {



        try {
            $validator = Validator::make($request->all(), [
                'money_wanted' => 'required',
                'money_paid' => 'required',

                'money_remain' => 'required',
                'type_of_bill' => 'required',
                'total_buy' => 'required',
                'total_gomla' => 'required',
                'notes' => 'required',

                'store_id' => 'required',
                'supplier_id' => 'required',
                'user_id' => 'required',
                'bill_id' => 'required',
                'sale_point_id' => "required",

                'items_details.*.count' => 'required',
                'items_details.*.bill_item_id' => 'required',
                'items_details.*.product_id' => 'required',
                'items_details.*.unit_id' => 'required',







            ]);
            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()], 422);
            }
            DB::beginTransaction();

            $data = $request->all();

            $tenantId = $request->header("bussniesid");




            $rebuyItems = collect($data['items_details'])->map(function ($data) use ($tenantId,  $request) {
                $BillItem = BillItem::where('id', $data['bill_item_id'])->first();
                // dd($data);
                $BillItem->status = 'rebuy';

                // $BillItem->re_buy_detail_id = $data["bill_item_id"];


                $BillItem->save();
                $Products = ProductExtraDetail::where('product_id', $data['product_id'])->get();
                $ProductInfo = Product::where('id', $data['product_id'])->first();

                $filteredProducts = $Products->filter(function ($product) use ($data) {
                    return $product->expire_date === $data['expire_date'];
                });


                $choosenUnit = $data['unit_id'];
                $enterdQty = null;
                if ($ProductInfo['large_unit_id'] == $choosenUnit) {
                    // $matchedchoosenUnit = 'large_unit_id';
                    $enterdQty = $data['count'];
                } elseif ($ProductInfo['medium_unit_id'] == $choosenUnit) {
                    // $matchedchoosenUnit = 'medium_unit_id';
                    $enterdQty = $data['count'] / $ProductInfo['medium_unit_no'];
                } elseif ($ProductInfo['small_unit_id'] == $choosenUnit) {
                    // $matchedchoosenUnit = 'small_unit_id';
                    $enterdQty = $data['count'] / $ProductInfo['small_unit_no'];
                }

                $Product = ProductExtraDetail::find($filteredProducts[0]->id);

                $qty = $filteredProducts[0]->qty - $enterdQty;
                $Product->update(["qty" => $qty]);


                ItemOperation::create([
                    "product_id" => $data['product_id'],
                    "tenant_id" => $tenantId,
                    "bill_id" => $request->bill_id,
                    "bill_type" => "rebuy_bill",
                    "bill_item_id" => $BillItem->id,


                ]);

                return $BillItem;
            })->all();





            // foreach ($billitems as $bill_item) {
            //     $bill_item->bills()->attach($Billes->id,);
            // }
            $supplier = Supplier::where("id", $request->supplier_id)->first();
            SupplierOperation::create([
                "tenant_id" => $tenantId,
                "user_id" => $request->user_id,

                "bill_id" => $request->bill_id,
                "type" => "rebuy bill",
                "madeen" => $request->money_wanted,
                "charge" => $supplier->charge - $request->money_paid

            ]);
            $supplier->update([
                'charge' => $supplier->charge - $request->money_paid,
            ]);

            $SalePoint = SalePoint::where("id", $request->sale_point_id)->first();


            $balance = intval($SalePoint['balance']) + intval($request->money_paid);

            SalePointOperation::create([
                "tenant_id" => $tenantId,
                "user_id" => $request->user_id,
                "bill_id" => $request->bill_id,
                "bill_type" => " مرتجع فاتورة شرا",

                "type" =>  "توريد رصيد",

                "balance" =>  $balance,
                "amount_of_money" => $request->money_paid

            ]);
            $SalePoint->update([
                'balance' => $balance,
            ]);


            DB::commit();

            return response()->json(['message' => 'Bill created successfully'], 201);
        } catch (ValidationException $e) {
            // Handle validation errors
            DB::rollBack();

            return response()->json(['errors' => $e->errors()], 422);
        }
    }
}
