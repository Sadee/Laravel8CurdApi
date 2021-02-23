<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\LeadResource;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use App\Models\Lead;
use Illuminate\Http\Request;

/**
 * Class LeadController
 * @package App\Http\Controllers\API
 */
class LeadController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $leads = Lead::with('user')->get();
        return response([ 'leads' => LeadResource::collection($leads), 'message' => 'Retrieved successfully'], 200);

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

        $lead = Lead::create($data);
        if($request->has('user')){
            $user = User::create($data['user']);
            $lead->user()->save($user);
        }

        return response(['lead' => new LeadResource($lead), 'message' => 'Created successfully'], 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Lead  $lead
     * @return \Illuminate\Http\Response
     */
    public function show(Lead $lead)
    {
        $lead = Lead::with('user')->find($lead->id);
        return response(['lead' => new LeadResource($lead), 'message' => 'Retrieved successfully'], 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Lead  $lead
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Lead $lead)
    {
        $data = $request->all();
        $lead->update($data);
        if($request->has('user')){
            $lead->user()->update($data['user']);
        }
        return response(['lead' => new LeadResource($lead), 'message' => 'Update successfully'], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Lead  $lead
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     * @throws \Exception
     */
    public function destroy(Lead $lead)
    {
        $lead->delete();

        return response(['message' => 'Deleted']);
    }
}
