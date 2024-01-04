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
        $subscribers = Subscriber::with('subscriberRequests')->get()
        ->map(function($query){
            $requests = $query->subscriberRequests->map(function($query){
                return [
                    "subscriber_id" => $query->subscriber_id,
                    "service_name" => $query->service_name,
                    "reason" => $query->reason,
                    "notes" => $query->notes,
                ];
            });

            return [
                "email" => $query->email,
                "subscriber_requests" => $requests
            ];
        });

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
            ],422);
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
}
