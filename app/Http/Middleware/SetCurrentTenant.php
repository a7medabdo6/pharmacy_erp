<?php

namespace App\Http\Middleware;

use App\Models\Tenant;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use PhpParser\Node\Stmt\TryCatch;
use Symfony\Component\HttpFoundation\Response;


class SetCurrentTenant
{
    public function handle($request, Closure $next, $currentTenantId = null)
    {
        // Your logic to determine the current tenant
        // dd($request->headers);
        $tenantId = $request->header("bussniesid");

        try {


            $tenant = Tenant::find($tenantId);
            if (empty($tenant)) {
                return response()->json(['message' => 'no Tenant present '], 404);
            }
            // Set other fields as needed
            // dd($managers);



            return $next($request);
        } catch (ValidationException $e) {
            // Handle validation errors
            return response()->json(['errors' => $e->errors()], 422);
        }
        // dd($tenant);
        // Set the current tenant
        // app('tenant')->setTenant($currentTenantId);

    }
}
