<?php

namespace App\Http\Controllers\api;

use App\Models\Subscriber;
use Illuminate\Http\Request;
use App\Models\SubscriberRequest;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class SubscriberController extends Controller
{
    public function index()
    {
        $allSubscribers = Subscriber::all();
        $subscribers = '';

        foreach($allSubscribers as $subscriber){
            $user = Subscriber::find($subscriber->id)->with(['subscriberRequests'])->get();
            $subscribers = $user;
        }

        return response()->json([
            'data' => $subscribers,
            'status' => true
        ],200);
    }

    public function store(Request $request)
    {
        $validatedRules = ['email' => 'required|email',];

        if($request->has('service_name')){
            $validatedRules['service_name'] = 'required';
        }

        $validator = Validator::make($request->all(), $validatedRules);

        if($validator->fails()){
            return response()->json([
                'message' => 'Validation error',
                'errors' => $validator->errors(),
                'status' => false
            ],401);
        }

        $subscriberExist = Subscriber::where('email',$request->email)->first();
        if($subscriberExist)
        {
            if($request->has('service_name')){
                $this->subscriberRequest($request, $subscriberExist->id);
            }
        }
        else{
            $subscriber = Subscriber::create(['email' => $request->email]);

            if($request->has('service_name')){
                $this->subscriberRequest($request, $subscriber->id);
            }
        }

        return response()->json([
            'message' => 'your request accepted',
            'status' => true
        ],200);
    }

    public function subscriberRequest($request,$subscriber_id)
    {
        SubscriberRequest::create([
            'subscriber_id' => $subscriber_id,
            'service_name' => $request->service_name,
            'reason' => $request->reason,
            'notes' => $request->notes,
        ]);
    }

    public function show(string $id)
    {
        //
    }

    public function update(Request $request, string $id)
    {
        //
    }

    public function destroy(string $id)
    {
        //
    }
}
