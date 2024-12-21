<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Customer;
use Illuminate\Support\Facades\Log;
use App\Models\AccountStreaming;
use App\Models\CustomerAccount;



class CustomerController extends Controller
{
    public function store($request)
    {
        $validatedData = $request->validate([
            'customer_name' => '',
            'customer_phone_number' => '',
            'contact_method'=> 'required',
            'name_customer_facebook'=> '',
        ]);
        $customer = Customer::create($validatedData);
        try {
            $customer = Customer::create($validatedData);
            return [
                'success' => true,
                'message' => 'Cliente creado exitosamente.',
                'data' => $customer,
            ];

        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'Error generado.',
                'error' => $e,
            ];
        }
    }
    public function add_customer(Request $request){
            log::info(now()->addMonths(($request->months_paid) ));

            $accountStreaming = AccountStreaming::where('name_service', $request->account_streaming)
                ->whereColumn('user_active', '<', 'user_max')
                ->where('status', 'active')
                ->first();
            log::info($accountStreaming);
            if (!$accountStreaming) {
                return [
                    'success'=> false,
                    'error' => 'No hay cuentas disponibles para este tipo.'];
            }
            $response_store_customer = $this->store($request);
            if ($response_store_customer['success'] == true) {
                CustomerAccount::create([
                    'customers_id' => $response_store_customer['data']['id'],
                    'account_id' => $accountStreaming->id,
                    'date_acquisition' => now()->toDateString(),
                    'date_expiration' => now()->addMonths($request->months_paid)->toDateString(),
                    'status' => 'active',
                    'profile' => '1',
                ]);
                $accountStreaming->increment('user_active');
                return response()->json([
                    'success'=> true,
                    "data_account" => [
                        "email" => $accountStreaming->email,
                        "password"=> $accountStreaming->password,
                        "profile" => "1"
                    ]
                ]);
            } else {
                return response()->json($response_store_customer);
            }
    }
}
