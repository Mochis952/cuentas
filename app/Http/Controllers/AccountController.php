<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Models\AccountStreaming;


class AccountController extends Controller
{
    public function store(Request $request){
        log::info($request);
        $data_validate = $request->validate([
            "name_service" => "required|string|max:255",
            "date_payment" => "required|max:255",
            "prices" => "required|max:255",
            "type_payment" => "required|string|max:255",
            "user_max" => "required|string|max:255",
            "date_create" => "required|max:255"
        ]);
        $type_account = null;
        if($request->user_max == 1){
            $type_account = "Individual";
        }else if($request->user_max == 2){
            $type_account = "Duo";
        }else if($request->user_max >= 3){
            $type_account = "Familiar";
        }
        $data_validate['type_account'] = $type_account;
        try {
            $AccountStreaming = AccountStreaming::create($data_validate);
            return response()->json([
                'success' => true,
                'message' => 'Cliente creado exitosamente.',
                'data' => $AccountStreaming,
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error generado.',
                'error' => $e,
            ], 500);
        }
    }
    public function index(){
        $accounts = AccountStreaming::orderBy('name_service','asc')->get();
        return $accounts ;
    }
}
