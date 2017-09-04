<?php

namespace App\Http\Controllers;

use App\Campaign;
use App\UserProfile;
use App\Withdraw;
use Illuminate\Http\Request;

class AdminWithdrawController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $withdraws = Withdraw::orderBy('id','desc')->get();
        return view('admin.withdraws',compact('withdraws'));
    }


    public function pending()
    {
        $withdraws = Withdraw::where('status','pending')->orderBy('id','desc')->get();
        return view('admin.withdrawspending',compact('withdraws'));
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $withdraw = Withdraw::findOrFail($id);
        return view('admin.withdrawdetails',compact('withdraw'));
    }

    public function accept($id)
    {

        $withdraw = Withdraw::findOrFail($id);
        $data['status'] = "completed";
        $withdraw->update($data);

        return redirect('admin/withdraws')->with('message','Withdraw Accepted Successfully');
    }

    public function reject($id)
    {
        $withdraw = Withdraw::findOrFail($id);

        $user = UserProfile::findOrFail($withdraw->userid->id);
        $data['acc_balance'] = $user->acc_balance + $withdraw->amount + $withdraw->fee;
        $user->update($data);

        $data['status'] = "rejected";
        $withdraw->update($data);
        return redirect('admin/withdraws')->with('message','Withdraw Rejected Successfully');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
