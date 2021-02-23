<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Models\Account;
use App\Models\Lead;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use Illuminate\Http\Request;


/**
 * Class UserController
 * @package App\Http\Controllers\API
 */
class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users = User::with('userable')->get();
        return response([ 'users' => UserResource::collection($users), 'message' => 'Retrieved successfully'], 200);

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

        $user = User::create($data);
        if($request->has('lead')){
            $lead = Lead::create($data['lead']);
            $user->userable_id = $lead->id;
            $user->userable_type = 'lead';
            $user->save();
        } elseif($request->has('account')){
            $account = Account::create($data['account']);
            $user->userable_id = $account->id;
            $user->userable_type = 'account';
            $user->save();
        } elseif($request->has('organisation')){
            $organisation = Organisation::create($data['organisation']);
            $user->userable_id = $organisation->id;
            $user->userable_type = 'organisation';
            $user->save();
        }


        return response(['user' => new UserResource($user), 'message' => 'Created successfully'], 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        $user['userable'] = ($user->userable?get_class($user->userable):null);
        return response(['user' => new UserResource($user), 'message' => 'Retrieved successfully'], 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {
        $user->update($request->all());

        return response(['user' => new UserResource($user), 'message' => 'Update successfully'], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     * @throws \Exception
     */
    public function destroy(User $user)
    {
        $userable = $user->userable();
        if($user->delete()) {
            $userable->delete();
        }

        return response(['message' => 'Deleted']);
    }
}
