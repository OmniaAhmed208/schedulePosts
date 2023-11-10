<?php

namespace App\Http\Controllers;

use App\Models\Subscriber;
use Illuminate\Http\Request;
use App\Models\SubscriberRequest;
use Illuminate\Auth\Events\Validated;

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

        return view('AdminSocialMedia.subscribers',compact('subscribers'));
    }


    public function store(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        if($request->has('service_name')){
            $request->validate(['service_name' => 'required']);
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

        return back()->with('success','your request accepted');
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
