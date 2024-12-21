<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomerAccount extends Model
{
    use HasFactory;
    protected $table = 'customers_account';

    protected $fillable = [
        'customers_id',
        'account_id',
        'profile',
        'date_acquisition',
        'status',
        'date_expiration'
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function accountStreaming()
    {
        return $this->belongsTo(AccountStreaming::class, 'account_id');
    }
}
