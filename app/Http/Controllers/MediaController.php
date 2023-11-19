<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Madcoda\compat;

class MediaController extends Controller
{
    public function index()
    {
        $mediaImages = User::find(Auth::user()->id)->getMedia('profile_images');
        $images = [];
        foreach($mediaImages as $image){
            $images[] = $image->getUrl();
        }

        return view('main.media.index', compact('mediaImages'));
    }
}
