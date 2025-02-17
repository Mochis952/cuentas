<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CustomerAccount;
use App\Models\Customer;
use App\Models\AccountStreaming;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CustomerAccountController extends Controller
{
    public function update_pay(Request $request){
        
        $customer = CustomerAccount::findOrFail($request->id);
        $customer->date_expiration = Carbon::parse($customer->date_expiration)->addMonth();
        $customer->save();
        return response()->json([
            'message' => 'Fecha de expiración actualizada correctamente',
            'customer' => $customer,
            'succes' => true
        ]);
    }
    public function delete($id)
    {
        DB::beginTransaction();

        try {
            $customerAccount = CustomerAccount::findOrFail($id);
            $customer = Customer::findOrFail($customerAccount->customers_id);
            $accountStreaming = AccountStreaming::findOrFail($customerAccount->account_id);

            $accountStreaming->user_active = max(0, $accountStreaming->user_active - 1);
            $accountStreaming->save();

            $customerAccount->delete();

            $customer->delete();

            DB::commit();

            return response()->json([
                "succes" => true,
                'message' => 'Cuenta eliminada correctamente.',
            ]);

        } catch (\Exception $e) {
            DB::rollBack();

            // Responder con un error
            return response()->json([
                "succes" => false,
                'message' => 'Ocurrió un error al intentar eliminar la cuenta.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
    public function update_profile(Request $request){
        log::info("request update_profile");
        log::info($request);
        $customer = CustomerAccount::findOrFail($request->id_user);
        $customer->profile = $request->profile;
        $customer->pin_profile = $request->pin_profile;
        $customer->save();
        return response()->json([
            "succes" => true,
            'message' => 'Cuenta actualizada.',
        ]);
    }
}
