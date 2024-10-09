<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;
    public function customerAccount()
    {
        return $this->belongsTo(CustomerAccount::class, 'customers_account_id');
    }
}
