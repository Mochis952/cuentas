<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Customer;
use Illuminate\Support\Facades\Log;
use App\Models\AccountStreaming;
use App\Models\CustomerAccount;
use Carbon\Carbon;



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
    public function index(){
        $customers = Customer::select('customers.*')
    ->join('customers_account', 'customers.id', '=', 'customers_account.customers_id')
    ->with(['customerAccounts.accountStreaming'])
    ->orderBy('customers_account.date_expiration', 'asc')
    ->get();


        return $customers;
    }
    public function add_customer(Request $request){
        $accountStreaming = null;
        if($request->assigned_account == "-1"){
            $accountStreaming = AccountStreaming::where('name_service', $request->account_streaming)
                ->whereColumn('user_active', '<', 'user_max')
                ->where('status', 'active')
                ->first();
        }else{
            $accountStreaming = AccountStreaming::where('id', $request->assigned_account)
                ->whereColumn('user_active', '<', 'user_max')
                ->first();
        }

        log::info($accountStreaming);
        if (!$accountStreaming) {
            return [
                'success'=> false,
                'error' => 'No hay cuentas disponibles para este tipo.'];
        }
        $response_store_customer = $this->store($request);
        $date_expiration = Carbon::parse($request->date_acquisition)->addMonths($request->months_paid);
        if ($response_store_customer['success'] == true) {
            CustomerAccount::create([
                'customers_id' => $response_store_customer['data']['id'],
                'account_id' => $accountStreaming->id,
                'date_acquisition' => $request->date_acquisition,
                'date_expiration' => $date_expiration,
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
    function history_customer($customer_contact){

        info("contacto");
        info($customer_contact);
        info(gettype($customer_contact));
        $phone_number_final = "521" . preg_replace('/\D/', '', $customer_contact);
        info($phone_number_final);
        $data = ['phoneNumber' => $phone_number_final];
        $curl = curl_init();

        curl_setopt_array($curl, array(
        CURLOPT_URL => 'http://localhost:3000/whatsapp/get-chat-history',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS => json_encode($data),
        CURLOPT_HTTPHEADER => array(
            'Content-Type: application/json'
        ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        info($response);
        return json_decode($response, true);
    }
}
