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

    public function update(Request $request, $id)
    {
        try {
            $account = AccountStreaming::findOrFail($id);

            $account->email = $request->input('email');
            $account->password = $request->input('password');
            $account->prices = $request->input('prices');
            $account->type_account = $request->input('type_account');
            $account->type_payment = $request->input('type_payment');
            $account->date_payment = $request->input('date_payment');
            $account->status = $request->input('status');
            
            $account->save();

            return response()->json(['message' => 'Cuenta actualizada exitosamente.']);

        } catch (\Exception $e) {
            // Log::error('Error al actualizar la cuenta: ' . $e->getMessage());
            return response()->json(['message' => 'Ocurrió un error al actualizar la cuenta.'], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $account = AccountStreaming::findOrFail($id);
            $account->delete();

            return response()->json(['message' => 'Cuenta eliminada exitosamente.']);

        } catch (\Exception $e) {
            // Log::error('Error al eliminar la cuenta: ' . $e->getMessage());
            return response()->json(['message' => 'Ocurrió un error al eliminar la cuenta.'], 500);
        }
    }
}
