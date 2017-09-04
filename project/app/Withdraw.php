<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Withdraw extends Model
{
    protected $fillable = ['userid', 'method', 'acc_email', 'iban', 'country', 'acc_name', 'address', 'swift', 'reference', 'amount', 'fee', 'created_at', 'updated_at', 'status'];

    public static $withoutAppends = false;

    public function getUseridAttribute($userid)
    {
        if(self::$withoutAppends){
            return $userid;
        }
        return UserProfile::findOrFail($userid);
    }

    public static function campWithdraw($id)
    {
        $withdraws = Withdraw::where('campid',$id)->
        where('status','completed')->sum('amount');
        $charges = Withdraw::where('campid',$id)->
        where('status','completed')->sum('fee');
        $withdraw = $withdraws + $charges;
        return $withdraw;
    }

    public static function withdrawPending()
    {
        $pending = Withdraw::where('status','pending')->count();
        return $pending;
    }

    public static function pendWithdraw($id)
    {
        $withdraws = Withdraw::where('campid',$id)->
        where('status','pending')->sum('amount');
        $charges = Withdraw::where('campid',$id)->
        where('status','pending')->sum('fee');
        $withdraw = $withdraws + $charges;
        return $withdraw;
    }

}
