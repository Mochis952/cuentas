<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Customer;
use Illuminate\Support\Facades\Log;


class CustomerController extends Controller
{
    public function store($request)
    {
        $validatedData = $request->validate([
            'customer_name' => 'required|string|min:3|max:50',
            'customer_phone_number' => 'required|digits:10',
        ]);
        $customer = new Customer();
        $customer->name = $validatedData['customer_name'];
        $customer->phone_number = $validatedData['customer_phone_number'];
        $customer->email = $request->input('email', null);
        $customer->save();

        return 'success Cliente registrado exitosamente.';
    }
    public function add_customer(Request $request){
        log::info("request add_customer");
        log::info($request);
        $response_store_customer = $this->store($request);
        log::info($response_store_customer);
        return "success";
    }
}
