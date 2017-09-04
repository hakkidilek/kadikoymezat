<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $fillable = ['transaction', 'userid', 'auctionid', 'amount','address','city','zip', 'reason', 'method', 'payment_status', 'txn_id', 'charge_id', 'created_at', 'updated_at', 'status'];
}
