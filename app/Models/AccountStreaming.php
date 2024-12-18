<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AccountStreaming extends Model
{
    use HasFactory;
    protected $table = 'account_streaming';

    protected $fillable = [
        'name_service',
        'type_account',
        'date_payment',
        'prices',
        'type_payment',
        'bank_name',
        'card_number',
        'date_create',
        'date_expire',
        'status',
        'account_pays',
        'user_max'
    ];
    public function customerAccounts()
    {
        return $this->hasMany(CustomerAccount::class, 'account_id');
    }
}
