<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $fillable = [
        'user_id',
        'asaas_payment_id',
        'amount',
        'billing_type',
        'status',
        'pix_qr_code',
        'pix_copy_paste',
        'due_date',
        'payment_date'
    ];

    protected $casts = [
        'due_date' => 'datetime',
        'payment_date' => 'datetime',
        'amount' => 'decimal:2'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
