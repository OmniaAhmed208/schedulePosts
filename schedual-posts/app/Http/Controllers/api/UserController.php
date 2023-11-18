<?php

namespace App\Http\Controllers\api;

use App\Models\Api;
use App\Models\User;
use App\Models\PublishPost;
use App\Models\settingsApi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use App\Http\Controllers\Controller;
use App\Notifications\RegisterNotification;
use App\Notifications\ResetPassword;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Ichtrojan\Otp\Otp;
class UserController extends Controller
{
    private $otp;

    public function __construct(){
        $this->otp = new Otp; // for verification code after registration
    }

    public function index()
    {
        $users = User::all();

        return response()->json([
            'message' => count($users). ' users found',
            'data' => $users,
            'status' => true
        ],200);
    }

    public function register(Request $request)
    {
        try{
            $validator = Validator::make($request->all(), [
                'name' => 'required',
                'email' => 'required|email|unique:users,email',
                'password' => ['required','nullable','confirmed','min:8', // confirmed ===> password_confirmation
                    'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]+$/',
                ],
            ]);
            //  At least one lowercase letter, At least one uppercase letter, At least one digit, At least one special character

            if($validator->fails()){
                return response()->json([
                    'message' => 'Validation error',
                    'errors' => $validator->errors(),
                    'status' => false
                ],401);
            }

            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password), // bcrypt($request->password)
            ]);

            $role = Role::where('name','user')->first();
            DB::table('user_has_roles')
                ->insert([
                    'role_id' => $role->id,
                    'user_id' => $user->id,
                ]);

            $user->notify(new RegisterNotification());

            return response()->json([
                'message' => 'User created successfully',
                'status' => true,
                'token' => $user->createToken("API TOKEN")->plainTextToken
            ],200);

        }
        catch(\Throwable $th){
            return response()->json([
                'message' => $th->getMessage(),
                'status' => false,
            ],500);
        }
    }

    public function sendEmailVerification(Request $request)
    {
        $request->user()->notify(new RegisterNotification());
        return response()->json([
            'status'=>true
        ],200);

    }

    public function email_verification(Request $request)
    {
        try{
            $validator = Validator::make($request->all(), [
                'email' => 'required|email|exists:users',
                'otp' => 'required|max:6',
            ]);

            if($validator->fails()){
                return response()->json([
                    'message' => 'Validation error',
                    'errors' => $validator->errors(),
                    'status' => false
                ],401);
            }

            $otpEmail = $this->otp->validate($request->email,$request->otp);

            if(!$otpEmail->status){
                return response()->json([
                    'error' => $otpEmail,
                    'status' => false,
                ],401);
            }

            $user = User::where('email', $request->email)->first();
            $user->update(['email_verified_at' => now()]);
            return response()->json([
                'message' => 'email verified successfully',
                'status' => true,
            ],500);
        }
        catch(\Throwable $th){
            return response()->json([
                'message' => $th->getMessage(),
                'status' => false,
            ],500);
        }
    }

    public function login(Request $request)
    {
        try{
            $validator = Validator::make($request->all(), [
                'email' => 'required',
                'password' => 'required|string',
            ]);

            if($validator->fails()){
                return response()->json([
                    'message' => 'Validation error',
                    'errors' => $validator->errors(),
                    'status' => false
                ],401);
            }

            $credentials = $request->only(['email','password']);

            if(!Auth::attempt($credentials)){
                return response()->json([
                    'message' => "Email $ Password don't match with our record",
                    'status' => false
                ],401);
            }

            $user = User::where('email', $request->email)->first();

            return response()->json([
                'message' => 'User logged in successfully',
                'token' => $user->createToken("API TOKEN")->plainTextToken,
                'status' => true
            ],200);
        }
        catch(\Throwable $th){
            return response()->json([
                'message' => $th->getMessage(),
                'status' => false,
            ],500);
        }

    }

    public function logout(Request $request)
    {
        try {
            if (auth()->check())
            {
                auth()->user()->tokens->each(function ($token, $key) {
                    $token->delete();
                });

                return response()->json([
                    'message' => 'Logged out'
                ]);

            } else {
                return response()->json([
                    'message' => 'Unauthorized',
                    'status' => false,
                ], 401);
            }

        } catch (\Throwable $th) {
            return response()->json([
                'message' => $th->getMessage(),
                'status' => false,
            ], 500);
        }
    }

    public function show(string $id)
    {
        $user = User::find($id);

        $userAccounts = Api::all()->where('creator_id', Auth::user()->id);
        $services = settingsApi::all(); // all App on website

        if($user == null){
            return response()->json([
                'message' => 'User not found',
                'status' => false
            ],401);
        }

        return response()->json([
            'message' => 'User found',
            'data' => [
                'user'=> $user,
                'userAccounts' => $userAccounts,
                'services' =>  $services
            ],
            'status' => true
        ],200);
    }

    public function update(Request $request, $id)
    {
        try{
            $user = User::find($id);

            if($user == null){
                return response()->json([
                    'message' => 'User not found',
                    'status' => false
                ],404);
            }

            $validator = Validator::make($request->all(), [
                'name' => 'required',
                'email' => 'required|email|unique:users,email,' . $user->id,
                'image' => 'mimes:jpg,jpeg,png',
            ]);

            if($validator->fails()){
                return response()->json([
                    'message' => 'Validation error',
                    'errors' => $validator->errors(),
                    'status' => false
                ],401);
            }

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

            return response()->json([
                'message' => 'User updated successfully',
                'status' => true
            ],200);
        }
        catch(\Throwable $th){
            return response()->json([
                'message' => $th->getMessage(),
                'status' => false,
            ],500);
        }
    }

    public function updatePassword(Request $request, $id)
    {
        try{
            $user = User::find($id);

            if($user == null){
                return response()->json([
                    'message' => 'User not found',
                    'status' => false
                ],404);
            }

            $validator = Validator::make($request->all(), [
                'old_password' => 'required',
                'new_password' => ['required','min:8','regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]+$/'],
            ]);

            if($validator->fails()){
                return response()->json([
                    'message' => 'Validation error',
                    'errors' => $validator->errors(),
                    'status' => false
                ],401);
            }

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

            return response()->json([
                'message' => 'Password updated successfully',
                'status' => true
            ],200);

        }
        catch(\Throwable $th){
            return response()->json([
                'message' => $th->getMessage(),
                'status' => false,
            ],500);
        }
    }

    public function forgetPassword(Request $request)
    {
        try{
            $validator = Validator::make($request->all(),[
                'email' => 'required'
            ]);

            if($validator->fails()){
                return response()->json([
                    'message' => 'Validation error',
                    'errors' => $validator->errors(),
                    'status' => false
                ],401);
            }

            $user = User::where('email',$request->email)->first();

            if($user == null){
                return response()->json([
                    'message' => 'User not found',
                    'status' => false
                ],404);
            }

            $user->notify(new ResetPassword());

            return response()->json([
                'message' => 'success',
                'status' => true
            ],200);

        }
        catch(\Throwable $th){
            return response()->json([
                'message' => $th->getMessage(),
                'status' => false,
            ],500);
        }
    }

    public function verificationCode(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'email' => 'required|email',
            'code' => 'required|max:6',
        ]);

        if($validator->fails()){
            return response()->json([
                'message' => 'Validation error',
                'errors' => $validator->errors(),
                'status' => false
            ],401);
        }

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
        $validator = Validator::make($request->all(),[
            'email' => 'required|email',
            'code' => 'required|max:6',
            'password' => 'required'
        ]);

        if($validator->fails()){
            return response()->json([
                'message' => 'Validation error',
                'errors' => $validator->errors(),
                'status' => false
            ],401);
        }

        $user = User::where('email',$request->email)->first();
        $user->update([
            'password' => Hash::make($request->password)
        ]);
        $user->tokens()->delete();

        return response()->json([
            'status' => true,
            'message' => 'Password updated successfully',
        ]);
    }

    // public function resetPassword(Request $request)
    // {
    //     try{
    //         $validator = Validator::make($request->all(),[
    //             'email' => 'required|exists:users',
    //             'otp' => 'required|max:6',
    //             'password' => 'required'
    //         ]);

    //         if($validator->fails()){
    //             return response()->json([
    //                 'message' => 'Validation error',
    //                 'errors' => $validator->errors(),
    //                 'status' => false
    //             ],401);
    //         }

    //         $otpValidate = $this->otp->validate($request->email, $request->otp);
    //         if(! $otpValidate->status){
    //             return response()->json([
    //                 'error' => $otpValidate
    //             ],401);
    //         }
    //         $user = User::where('email',$request->email)->first();

    //         $user->update([
    //             'password' => Hash::make($request->password)
    //         ]);
    //         $user->tokens()->delete();

    //         $success['success'] = true;

    //         return response()->json([
    //             'message' =>'password updated successfully',
    //             'status' => true,
    //         ],200);

    //     }
    //     catch(\Throwable $th){
    //         return response()->json([
    //             'message' => $th->getMessage(),
    //             'status' => false,
    //         ],500);
    //     }
    // }

    public function destroy(string $id)
    {
        $user = User::find($id);

        if($user == null){
            return response()->json([
                'message' => 'User not found',
                'status' => false
            ],401);
        }

        $user->delete();

        return response()->json([
            'message' => 'User deleted successfully',
            'status' => true
        ],200);
    }

    public function search(Request $request, $name)
    {
        try{
            $user = User::where('name','like', '%'.$name.'%')->get();

            if($user->isEmpty()){
                return response()->json([
                    'message' => 'User not found',
                    'status' => false
                ],404);
            }

            return response()->json([
                'message' => 'User found',
                'data' => $user,
                'status' => true
            ],200);
        }
        catch(\Throwable $th)
        {
            return response()->json([
                'message' => 'User Unauthenticated',
                'status' => false
            ],401);
        }
    }
}
