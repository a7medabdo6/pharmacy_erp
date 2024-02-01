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

class BillController extends Controller
{
    //

    public function index(Request $request)
    {
        try {
            $Bill = Bill::all();;
            return response()->json($Bill);
        } catch (\Throwable $th) {
        }
    }
    public function oneStore(Request $request, $id)
    {
        try {
            $Bill = Bill::find($id);;
            return response()->json($Bill);
        } catch (\Throwable $th) {
        }
    }

    //
    public function create(Request $request)
    {


        try {
            $validator = Validator::make($request->all(), [
                'supplier_serial' => 'required',
                'sale_point_id' => "required",
                'bill_number' => 'required',
                'bill_date' => 'required',
                'number_of_products' => 'required',
                'type_of_bill' => 'required',
                'descount_percentage' => 'required',
                'tax' => 'required',
                'total_price_of_buy' => 'required',
                'total_price_of_sell' => 'required',
                'what_paid' => 'required',
                'what_remainning' => 'required',
                'expenses' => 'required',
                'store_id' => 'required',
                'supplier_id' => 'required',
                'user_id' => 'required',
                'items_details' => 'required|array',
                'items_details.*.name' => 'required|string',
                'items_details.*.qty' => 'required|string',
                'items_details.*.expire_date' => 'required|string',
                'items_details.*.bouns' => 'required|string',
                'items_details.*.current_qty' => 'required|string',
                'items_details.*.sell_price' => 'required|string',
                'items_details.*.buy_price' => 'required|string',
                'items_details.*.tax' => 'required|string',
                'items_details.*.discount' => 'required|string',
                'items_details.*.total_price_buy' => 'required|string',
                'items_details.*.total_price_sell' => 'required|string',
                'items_details.*.code' => 'required|string',
                'items_details.*.unit_id' => 'required|string',
                'items_details.*.product_id' => 'required|string',





            ]);
            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()], 422);
            }
            DB::beginTransaction();

            $data = $request->all();

            $tenantId = $request->header("bussniesid");
            // return dd($request->all());
            $Billes = Bill::create([
                ...$request->only(
                    'serial',
                    'supplier_serial',
                    'bill_number',
                    'bill_date',
                    'number_of_products',
                    'type_of_bill',
                    'descount_percentage',
                    'tax',
                    'total_price_of_buy',
                    'total_price_of_sell',
                    'what_paid',
                    'what_remainning',
                    'expenses',
                    'store_id',
                    'supplier_id',
                    'user_id',
                    "sale_point_id",
                ), "tenant_id" => $tenantId,
            ]);;


            $Billes->save();
            $tenantId = $request->header("bussniesid");

            $billitems = collect($data['items_details'])->map(function ($data) use ($tenantId, $Billes, $request) {
                $BillItem = BillItem::create($data);
                // dd($data);
                $Products = ProductExtraDetail::where('product_id', $data['product_id'])->first();
                $ProductInfo = Product::where('id', $data['product_id'])->first();
                // return dd($Products);
                $choosenUnit = $data['unit_id'];
                $enterdQty = null;
                if ($ProductInfo['large_unit_id'] == $choosenUnit) {
                    // $matchedchoosenUnit = 'large_unit_id';
                    $enterdQty = $data['qty'];
                } elseif ($ProductInfo['medium_unit_id'] == $choosenUnit) {
                    // $matchedchoosenUnit = 'medium_unit_id';
                    $enterdQty = $data['qty'] / $ProductInfo['medium_unit_no'];
                } elseif ($ProductInfo['small_unit_id'] == $choosenUnit) {
                    // $matchedchoosenUnit = 'small_unit_id';
                    $enterdQty = $data['qty'] / $ProductInfo['small_unit_no'];
                }
                if ($Products) {

                    $filteredProducts = $Products->filter(function ($product) use ($data) {
                        return $product->expire_date === $data['expire_date'];
                    });



                    if ($data['expire_date'] == $filteredProducts[0]->expire_date) {



                        $Product = ProductExtraDetail::find($filteredProducts[0]->id);

                        $qty = $enterdQty + $filteredProducts[0]->qty;
                        $Product->update(["qty" => $qty]);
                    } else {
                        ProductExtraDetail::create([
                            "qty" => $enterdQty,
                            "product_id" => $data['product_id'],
                            "expire_date" => $data['expire_date'],
                            "tenant_id" => $tenantId,
                            "bill_item_id" => $BillItem->id,


                        ]);
                    }
                } else {
                    ProductExtraDetail::create([
                        "qty" => $enterdQty,
                        "product_id" => $data['product_id'],
                        "expire_date" => $data['expire_date'],
                        "tenant_id" => $tenantId,
                        "bill_item_id" => $BillItem->id,


                    ]);
                }



                ItemOperation::create([
                    "product_id" => $data['product_id'],
                    "tenant_id" => $tenantId,
                    "bill_id" => $Billes->id,
                    "bill_type" => "buy_bill",
                    "bill_item_id" => $BillItem->id,


                ]);

                return $BillItem;
            })->all();





            foreach ($billitems as $bill_item) {
                $bill_item->bills()->attach($Billes->id,);
            }
            $supplier = Supplier::where("id", $request->supplier_id)->first();
            SupplierOperation::create([
                "tenant_id" => $tenantId,
                "user_id" => $request->user_id,

                "bill_id" => $Billes->id,
                "type" => "buy bill",
                "daen" => $request->what_remainning,
                "charge" => $supplier->charge + $request->what_remainning

            ]);
            $supplier->update([
                'charge' => $supplier->charge + $request->what_remainning,
            ]);

            $SalePoint = SalePoint::where("id", $request->sale_point_id)->first();

            if ($SalePoint['balance']  < $request->what_paid) {
                return response()->json(['error' => 'SalePoint blance is less than what you want to pay '], 404);
            } else {
                $balance = intval($SalePoint['balance']) - intval($request->what_paid);

                SalePointOperation::create([
                    "tenant_id" => $tenantId,
                    "user_id" => $request->user_id,
                    "bill_id" => $Billes->id,
                    "bill_type" => "فاتورة شرا",

                    "type" => 'سحب رصيد',

                    "balance" =>  $balance,
                    "amount_of_money" => $request->what_paid

                ]);
                $SalePoint->update([
                    'balance' => $balance,
                ]);
            }

            DB::commit();

            return response()->json(['message' => 'Bill created successfully'], 201);
        } catch (ValidationException $e) {
            // Handle validation errors
            DB::rollBack();

            return response()->json(['errors' => $e->errors()], 422);
        }
    }
    public function Delete(Request $request, $id)
    {
        try {
            $Bill = Bill::find($id);;
            if (!$Bill) {
                // Handle the case where the user is not found (e.g., return a 404 response)
                return response()->json(['error' => 'Bill not found'], 404);
            }
            $Bill->delete();

            return response()->json(['message' => 'Bill deleted successfully']);
        } catch (\Throwable $th) {
        }
    }
}
