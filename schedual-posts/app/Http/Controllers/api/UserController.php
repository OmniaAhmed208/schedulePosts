<?php

namespace App\Http\Controllers\api;

use App\Models\Api;
use App\Models\User;
use App\Models\PublishPost;
use App\Models\settingsApi;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
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

        $apiAccounts = Api::all()->where('creator_id', Auth::user()->id);
        $userApps = settingsApi::all(); // all App on website

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
                'apiAccounts' => $apiAccounts,
                'userApps' =>  $userApps
            ],
            'status' => true
        ],200);
    }

    public function update(Request $request, string $id)
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
                'password' => ['required','nullable','confirmed','min:8',
                    'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]+$/',
                ],
            ]);

            if($validator->fails()){
                return response()->json([
                    'message' => 'Validation error',
                    'errors' => $validator->errors(),
                    'status' => false
                ],401);
            }

            $user->update([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
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
