<?php

namespace App\Http\Controllers\api;

use App\Models\Api;
use App\Models\User;
use App\Mail\VerifyEmail;
use App\Models\PublishPost;
use App\Models\settingsApi;
use App\Mail\ForgetPassword;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
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
                ],422);
            }

            $token = rand(100000,999999);

            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'verification_token' => $token,
                'password' => Hash::make($request->password), // bcrypt($request->password)
            ]);

            $cc = User::where('email', $request->email)->first();
            $bcc = User::where('email', $request->email)->first();
            Mail::to($request->email)
            ->cc($cc)
            ->bcc($bcc)
            ->send(new VerifyEmail($token));

            return response()->json([
                'message' => 'User created successfully',
                'status' => true,
                'user' => $user,
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

    public function email_verification(Request $request)
    {
        try{
            $validator = Validator::make($request->all(), [
                'token' => 'required|max:6',
            ]);

            if($validator->fails()){
                return response()->json([
                    'message' => 'Validation error',
                    'errors' => $validator->errors(),
                    'status' => false
                ],422);
            }

            $user = User::where('verification_token', $request->token)->first();

            if (!$user) {
                return response()->json([
                    'message' => 'Code not correct',
                    'status' => false,
                ], 404);
            }

            if ($user->email_verified_at) {
                return response()->json([
                    'message' => 'User is already verified',
                    'status' => false,
                ], 400);
            }

            $user->update(['email_verified_at' => now()]);

            $role = Role::where('name', 'user')->first();
            DB::table('user_has_roles')
                ->insert([
                    'role_id' => $role->id,
                    'user_id' => $user->id,
                ]);

            return response()->json([
                'message' => 'email verified successfully',
                'user' => $user,
                'status' => true,
            ], 200);
        }
        catch(\Throwable $th){
            return response()->json([
                'message' => $th->getMessage(),
                'status' => false,
            ],500);
        }
    }

    public function code_verification_from_profile(Request $request)
    {
        try{
            $user = $request->user();

            if ($user->email_verified_at) {
                return response()->json([
                    'message' => 'User is already verified',
                    'status' => false,
                ], 400);
            }

            $token = rand(100000,999999);

            $user->update(['verification_token' => $token]);

            $cc = User::where('email', $request->email)->first();
            $bcc = User::where('email', $request->email)->first();
            Mail::to($request->email)
            ->cc($cc)
            ->bcc($bcc)
            ->send(new VerifyEmail($token));

            return response()->json([
                'message' => 'code sent',
                'status' => true,
            ], 200);

            //after code send it to email_verification method
        }
        catch (\Throwable $th) {
            return response()->json([
                'message' => $th->getMessage(),
                'status' => false,
            ], 500);
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
                ],422);
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
                ],422);
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
                ],422);
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
                ],422);
            }

            $user = User::where('email',$request->email)->first();

            if ($user == null) {
                return response()->json([
                    'status' => false,
                    'message' => 'Email not valid!',
                ]);
            }

            $token = rand(100000,999999);

            $user->update(['verification_token' => $token]);

            $cc = User::where('email', $request->email)->first();
            $bcc = User::where('email', $request->email)->first();
            Mail::to($request->email)
            ->cc($cc)
            ->bcc($bcc)
            ->send(new ForgetPassword($token));

            return response()->json([
                'status' => true,
                'message' => 'Code sent',
                'token' => $user->createToken("API TOKEN")->plainTextToken
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => $e->getMessage(),
            ]);
        }
    }

    public function passwordCode(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'token' => 'required|max:6',
        ]);

        if($validator->fails()){
            return response()->json([
                'message' => 'Validation error',
                'errors' => $validator->errors(),
                'status' => false
            ],422);
        }

        $user = User::where('verification_token', $request->token)->first();

        if (!$user) {
            return response()->json([
                'message' => 'Code not correct',
                'status' => false,
            ], 404);
        }

        return response()->json([
            'message' => 'Code correct',
            'status' => true,
        ], 200);
    }

    public function resetPassword(Request $request)
    {
        $validator = $request->validate([
            'password' => ['required','nullable','confirmed','min:8', // confirmed ===> password_confirmation
                'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]+$/',
            ],
        ]);

        $user = $request->user();

        if (!$user) {
            return response()->json([
                'message' => 'User not authenticated',
                'status' => false,
            ], 401);
        }

        $user->update([
            'password' => Hash::make($request->password)
        ]);

        $user->tokens()->delete();

        return response()->json([
            'status' => true,
            'message' => 'Password updated successfully',
        ],200);
    }



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
