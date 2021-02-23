<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\AccountResource;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use App\Models\Account;
use Illuminate\Http\Request;

/**
 * Class AccountController
 * @package App\Http\Controllers\API
 */
class AccountController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $accounts = Account::with('user')->get();
        return response([ 'accounts' => AccountResource::collection($accounts), 'message' => 'Retrieved successfully'], 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $request->all();

        $validator = Validator::make($data, [
            'name' => 'required|max:255',
        ]);

        if ($validator->fails()) {
            return response(['error' => $validator->errors(), 'Validation Error']);
        }

        $account = Account::create($data);
        if($request->has('user')){
            $user = User::create($data['user']);
            $account->user()->save($user);
        }

        return response(['account' => new AccountResource($account), 'message' => 'Created successfully'], 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Account  $account
     * @return \Illuminate\Http\Response
     */
    public function show(Account $account)
    {
        $account = Account::with('user')->find($account->id);
        return response(['account' => new AccountResource($account), 'message' => 'Retrieved successfully'], 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Account  $account
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Account $account)
    {
        $data = $request->all();
        $account->update($data);
        if($request->has('user')){
            $account->user()->update($data['user']);
        }

        return response(['account' => new AccountResource($account), 'message' => 'Update successfully'], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Account  $account
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     * @throws \Exception
     */
    public function destroy(Account $account)
    {
        $account->delete();

        return response(['message' => 'Deleted']);
    }
}
