<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\OrganisationResource;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use App\Models\Organisation;
use Illuminate\Http\Request;

/**
 * Class OrganisationController
 * @package App\Http\Controllers\API
 */
class OrganisationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $organisations = Organisation::with('user')->get();
        return response([ 'organisations' => OrganisationResource::collection($organisations), 'message' => 'Retrieved successfully'], 200);
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

        $organisation = Organisation::create($data);
        if($request->has('user')){
            $user = User::create($data['user']);
            $organisation->user()->save($user);
        }

        return response(['organisation' => new OrganisationResource($organisation), 'message' => 'Created successfully'], 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Organisation  $organisation
     * @return \Illuminate\Http\Response
     */
    public function show(Organisation $organisation)
    {
        $organisation = Organisation::with('user')->find($organisation->id);
        return response(['organisation' => new OrganisationResource($organisation), 'message' => 'Retrieved successfully'], 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Organisation  $organisation
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Organisation $organisation)
    {
        $data = $request->all();
        $organisation->update($data);
        if($request->has('user')){
            $organisation->user()->update($data['user']);
        }
        return response(['organisation' => new OrganisationResource($organisation), 'message' => 'Update successfully'], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Organisation  $organisation
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     * @throws \Exception
     */
    public function destroy(Organisation $organisation)
    {
        $organisation->delete();

        return response(['message' => 'Deleted']);
    }
}
