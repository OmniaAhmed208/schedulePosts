<?php

namespace App\Http\Controllers;

use App\Models\Api;
use App\Models\User;
use App\Mail\VerifyEmail;
use App\Models\settingsApi;
use Illuminate\Support\Str;
use App\Mail\ForgetPassword;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class UserController extends Controller
{
    public function index()
    {
        $allUsers = User::all();
        $roles = Role::all();
        $user_roles = DB::table('user_has_roles')->get();
        return view('main.users.index',compact('allUsers','roles','user_roles'));
    }

    public function register(Request $request)
    {
        try{
            $validator =$request->validate([
                'name' => 'required',
                'email' => 'required|email|unique:users,email',
                'password' => ['required','nullable','confirmed','min:8', // confirmed ===> password_confirmation
                    'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]+$/',
                ],
            ]);

            $token = random_int(100000, 999999);

            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password), // bcrypt($request->password)
                'verification_token' => $token,
            ]);

            $cc = User::where('email', $request->email)->first();
            $bcc = User::where('email', $request->email)->first();
            Mail::to($request->user())
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
            // ValidationException $e
            return response()->json([
                'message' => $th->getMessage(),
                'status' => false,
            ],500);

            // $errors = $e->validator->errors()->toArray();

            // if (isset($errors['password'])) {
            //     return response()->json([
            //     'message' => 'Password must be minimum 8 and contains at least (capital letter, number, special character) ', 
            //     'status' => false]);
            // }

            // return response()->json([
            //     'message' => $e->getMessage(),
            //     'status' => false,
            // ],500);

        }
    }

    public function email_verification(Request $request)
    {
        try{
            $validator = $request->validate([
                'token' => 'required|max:6',
            ]);

            // $user = User::where('email', $request->user()->email)->first();
            $user = User::where('verification_token', $request->token)->first();

            if (!$user) {
                return response()->json([
                    'message' => 'Code not correct',
                    'status' => false,
                ], 404);
            }

            // Optionally, you can check if the user is already verified
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
        catch (\Throwable $th) {
            return response()->json([
                'message' => $th->getMessage(),
                'status' => false,
            ], 500);
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

    public function show($userId) // profile
    {
        $user = User::find($userId);

        $apiAccounts = Api::all()->where('creator_id', Auth::user()->id);
        $userApps = settingsApi::all(); // all App on website

        return view('main.users.show',compact('user','apiAccounts','userApps'));
    }

    public function update(Request $request, $id)
    {
        $user = User::find($id);
        if($user == null){
            return back()->with('error','User not found');
        }

        $validator = $request->validate([
            'name' => 'required',
            'image' => 'mimes:jpg,jpeg,png',
        ]);

        $storageImage = $user->image;
        if ($request->hasFile('image'))
        {
            $userFolder = 'user'.Auth::user()->id;
            if($user->image != null){
                $rm_urlPath = parse_url($user->image, PHP_URL_PATH);
                $path = Str::replace('/storage/', '', $rm_urlPath);
                $filePath = storage_path('app/public/'. $path);
                if (file_exists($filePath)) {
                    unlink($filePath);
                }
            }
            $image = $request->file('image');
            $filename = time() . '_' . $image->getClientOriginalName();
            $image->storeAs('public/'.$userFolder.'/'.'profile_images', $filename);
            $storageImage = url('storage/'.$userFolder.'/'.'profile_images/'. $filename);
        }
        if ($request->reset_image == 1) {
            $storageImage = null;
        }

        $user->update([
            'name' => $request->name,
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
                ],422);
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
            ],200);

        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => $e->getMessage(),
            ],500);
        }
    }

    public function passwordCode(Request $request)
    {
        $validator = $request->validate([
            'token' => 'required|max:6',
        ]);

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
