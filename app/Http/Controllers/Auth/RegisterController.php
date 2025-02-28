<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;
use App\Models\User;
use Carbon\Carbon;
use Hash;
use DB;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */

    /** regiter page */
    public function register()
    {
        return view('auth.register');
    }

    /** insert new users */
    public function storeUser(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name'      => 'required|string|max:255',
            'email'     => 'required|string|email|max:255|unique:users',
            'password'  => 'required|string|min:8|confirmed',
            'password_confirmation' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation errors',
                'errors'  => $validator->errors()
            ], 422);
        }

        try {
            DB::beginTransaction();

            $user = User::create([
                'name'      => $request->name,
                'email'     => $request->email,
                'join_date' => Carbon::now()->toDayDateTimeString(),
                'role_name' => 'User Normal',
                'status'    => 'Active',
                'password'  => Hash::make($request->password),
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'User registered successfully',
                'data'    => $user
            ], 201);
        } catch (\Exception $e) {
            DB::rollback();
            \Log::error('User Registration Error: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Something went wrong while registering user',
                'error'   => $e->getMessage()
            ], 500);
        }
    }
}
