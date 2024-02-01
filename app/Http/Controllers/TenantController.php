<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Tenant;
use App\Models\User;
use Dotenv\Exception\ValidationException;
use Illuminate\Support\Facades\Validator;

use function Laravel\Prompts\error;

class TenantController extends Controller
{

    public function create(Request $request)
    {
        // Validate the request data
        // dd($request->all());
        try {
            $validator = Validator::make($request->all(), [
                'name' => 'required|string',
                'email' => 'required|string',
                'password' => 'required|string',
                // Add any other required fields
            ]);
            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()], 422);
            }
            // Create a new tenant
            $tenant = Tenant::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => $request->password
            ]);
            $tenant->save();
            // dd($tenant);
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => $request->password,
                'role' => "admin",
                "tenant_id" => $tenant['id']

            ]);

            $user->save();


            return response()->json(['message' => 'Tenant created successfully'], 201);

            // return response()->json(['errors' => "test"], 422);
        } catch (ValidationException $e) {
            // Handle validation errors
            return response()->json(['errors' => $e->errors()], 422);
        }
    }
}
