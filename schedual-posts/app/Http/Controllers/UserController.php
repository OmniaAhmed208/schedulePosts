<?php

namespace App\Http\Controllers;

use App\Models\Api;
use App\Models\User;
use App\Models\PublishPost;
use App\Models\settingsApi;
use App\Notifications\ResetPassword;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Storage;
use Ichtrojan\Otp\Otp;
class UserController extends Controller
{
    private $otp;

    public function __construct(){
        $this->otp = new Otp;
    }

    public function index()
    {
        $allUsers = User::all();
        $roles = Role::all();
        $user_roles = DB::table('user_has_roles')->get();
        return view('main.users.index',compact('allUsers','roles','user_roles'));
    }

    public function show($userId) // profile
    {
        $user = User::find($userId);

        $apiAccounts = Api::all()->where('creator_id', Auth::user()->id);
        $userApps = settingsApi::all(); // all App on website

        return view('main.users.show',compact('user','apiAccounts','userApps'));
    }

    public function update(Request $request, $id)
    {
        // dd($request);
        $user = User::find($id);
        if($user == null){
            return back()->with('error','User not found');
        }

        $validator = $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'image' => 'mimes:jpg,jpeg,png',
        ]);

        // $password = $user->password;

        // if ($request->filled('old_password') && $request->filled('new_password')) {
        //     if (!Hash::check($request->old_password, $user->password)) {
        //         return redirect()->back()->with('error', 'Old password does not match.');
        //     }
        //     else{
        //         $request->validate([
        //             'new_password' => ['nullable','min:8','regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]+$/'],
        //         ]);
        //         $password = Hash::make($request->new_password);
        //     }
        // }

        $storageImage = $user->image;
        if ($request->hasFile('image'))
        {
            $image = $request->file('image');
            $filename = time() . '_' . $image->getClientOriginalName();
            $image->storeAs('public/profile_images', $filename);
            $storageImage = Storage::url('profile_images/'. $filename);
        }
        if ($request->reset_image == 1) {
            $storageImage = 'tools/dist/img/user.png';
        }

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'image' => $storageImage
        ]);

        // $user->addMediaFromRequest('image')->toMediaCollection('profile_images');

        return back()->with('success','User updated successfully');
    }


    public function updatePassword(Request $request, $id)
    {
        $user = User::find($id);

        $validator = $request->validate([
            'old_password' => 'required',
            'new_password' => ['required','min:8','regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]+$/'],
        ]);

        $password = $user->password;

        if ($request->filled('old_password') && $request->filled('new_password'))
        {
            if (!Hash::check($request->old_password, $user->password))
            {
                return response()->json([
                    'message' => 'Old password does not match.',
                    'status' => false
                ],404);
            }
            else{
                $password = Hash::make($request->new_password);
            }
        }

        $user->update([
            'password' => $password,
        ]);

        return back()->with('success','Password updated successfully');

    }

    public function forgetPassword(Request $request)
    {
        try{
            $validator = $request->validate([
                'email' => 'required'
            ]);

            $user = User::where('email',$request->email)->first();

            if ($user == null) {
                return response()->json([
                    'status' => false,
                    'message' => 'Email not valid!',
                ]);
            }

            $user->notify(new ResetPassword());

            return response()->json([
                'status' => true,
                'message' => 'Password reset email sent successfully!',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => $e->getMessage(),
            ]);
        }
    }

    public function verificationCode(Request $request)
    {
        $validator = $request->validate([
            'email' => 'required|exists:users',
            'code' => 'required|max:6',
        ]);

        $otpValidate = $this->otp->validate($request->email, $request->code);

        if(! $otpValidate->status){
            return response()->json([
                'status' => false,
                'message' => "The Code isn't valid!",
            ]);
        }

        return response()->json([
            'status' => true,
            'message' => 'Code is correct',
        ]);
    }

    public function resetPassword(Request $request)
    {
        $validator = $request->validate([
            'email' => 'required|exists:users',
            'code' => 'required|max:6',
            'password' => 'required'
        ]);

        $user = User::where('email',$request->email)->first();
        $user->update([
            'password' => Hash::make($request->password)
        ]);
        $user->tokens()->delete();

        // return response()->json([
        //     'status' => true,
        //     'message' => 'Password updated successfully',
        // ]);
        return view('welcome');
    }

    // public function destroy(string $id)
    // {
    //     $user = User::find($id);

    //     if($user == null){
    //         return back()->with('error','User not found');
    //     }

    //     $user->delete();

    //     return back()->with('error','User deleted successfully');
    // }
}
