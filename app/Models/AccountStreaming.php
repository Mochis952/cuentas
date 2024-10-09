<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AccountStreaming extends Model
{
    use HasFactory;
    public function customerAccounts()
    {
        return $this->hasMany(CustomerAccount::class, 'account_id');
    }
}
