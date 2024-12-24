<?php

namespace App\Http\Controllers;
use App\Models\AccountStreaming;

use Illuminate\Http\Request;

class AccountStreamingController extends Controller
{
    function get_account_streaming_available($name_service){
        $accountStreaming = AccountStreaming::where('name_service', $name_service)
                ->whereColumn('user_active', '<', 'user_max')
                ->where('status', 'active')
                ->get();
        return $accountStreaming;
    }
}
