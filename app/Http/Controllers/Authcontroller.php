<?php

namespace App\Http\Controllers;

use App\Http\Requests\ForgotPasswordRequest;
use App\Http\Requests\LoginUserRequest;
use App\Http\Requests\RestorePasswordRequest;
use App\Http\Requests\StoreBusinessRequest;
use App\Http\Requests\StoreUserRequest;
use App\Models\Address;
use App\Models\PasswordReset;
use App\Models\ServiceCategory;
use App\Models\WorkDay;
use App\Traits\HttpResponses;
use App\Models\User;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Testing\Fluent\Concerns\Has;

class Authcontroller extends Controller
{
    use HttpResponses;

    public function login(LoginUserRequest $request)
    {
        $request->validated($request->all());
        if (!Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            return $this->error('', $request, 401);
        }
        $user = User::where('email', $request->email)->first();
        return $this->success([
            'user' => $user,
            'token' => $user->createToken('API of ' . $user->email)->plainTextToken
        ]);
    }

    public function register(StoreUserRequest $request)
    {
        $request->validated($request->all());
        $user = User::create([
            'phoneNumber' => $request->phoneNumber,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);
        $customer = $user->customer()->create([
            'name' => '',
            'birth_day' => $request->birth_day,
            'sex' => '',
            'user_id' => $user->id
        ]);
        return $this->success([
            'user' => $user,
            'customer' => $customer,
            'token' => $user->createToken('API of ' . $user->email)->plainTextToken
        ]);
    }

    public function registerBusiness(StoreBusinessRequest $request)
    {
        $request->validated($request->all());

        $user = User::create([
            'phoneNumber' => $request->phoneNumber,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role_id' => 2
        ]);

        $address = Address::create([
            'city' => '',
            'street' => '',
            'number' => null,
            'floor' => null,
            'description' => '',
            'postal_code' => null
        ]);

        $business = $user->business()->create([
            'name' => $request->name,
            'eik' => $request->eik,
        ]);

        $business->address()->associate($address);
        $business->save();

        $business_id = $business->id;

        $openHours = [];
        if (!$business || !$business->id) {
            return response()->json(['error' => 'Invalid business'], 400);
        }

        for ($i = 1; $i <= 5; $i++) {
            $workDay = new WorkDay();
            $workDay->business_id = $business_id;
            $workDay->weekday_id = $i;
            $workDay->is_off = false;
            $workDay->start_time = '08:00';
            $workDay->end_time = '17:00';
            $workDay->pause_start = '12:00';
            $workDay->pause_end = '13:00';
            $workDay->save();
            $openHours[] = $workDay;
        }

        for ($i = 6; $i <= 7; $i++) {
            $workDay = new WorkDay();
            $workDay->business_id = $business_id;
            $workDay->weekday_id = $i;
            $workDay->is_off = true;
            $workDay->start_time = '00:00';
            $workDay->end_time = '00:00';
            $workDay->pause_start = null;
            $workDay->pause_end = null;
            $workDay->save();
            $openHours[] = $workDay;
        }

        $serviceCategory = ServiceCategory::create([
            'title' => 'Моята категория',
            'business_id' => $business_id,
        ]);

        return $this->success([
            'user' => $user,
            'business' => $business,
            'open_hours' => $openHours,
            'service_category' => $serviceCategory,
            'address' => $address,
            'token' => $user->createToken('API of ' . $user->email)->plainTextToken
        ]);
    }

    public function logout()
    {
        Auth::user()->currentAccessToken()->delete();
        return $this->success([
            'message' => 'You have successfully been logged out-!'
        ]);
    }

    public function forgotPassword(ForgotPasswordRequest $request)
    {

        $user = User::where('email', $request->email)->firstOrFail();
        if (!$user) {
            return $this->success('','Ако данните са същестуващи ще получите имейл с код за възстановяване на вашата парола.');
        } else {
            $resetPasswordToken = str_pad(random_int(1, 9999), 4, 0, STR_PAD_LEFT);

            if (!$userReset = PasswordReset::where('email', $user->email)->first()) {
                PasswordReset::create([
                    'email' => $user->email,
                    'token' => $resetPasswordToken
                ]);
            } else {
                $userReset->update([
                    'email' => $user->email,
                    'token' => $resetPasswordToken
                ]);
            }
            $user->notify(new ResetPassword($resetPasswordToken));
            return $this->success('','Ако данните са същестуващи ще получите имейл с код за възстановяване на вашата парола.');
        }

    }
    public function resetPassword(RestorePasswordRequest $request){
        $request->validated($request->all());
        $user = User::where('email',$request->email)->first();
        if(!$user){
            return $this->error('','Невалидни данни, опитай отново.',404);
        }

        $reset = PasswordReset::where('email', $user->email)->first();
        if(!$reset || $reset->token != $request->token){
            return $this->error('','Невалидни данни, опитай отново.',404);
        }
        $user->update([
           'password' => Hash::make($request->password)
        ]);
        $reset->delete();
        $user->tokens()->delete();
        return $this->success([
            'user' => $user,
            'token' => $user->createToken('API of ' . $user->email)->plainTextToken
        ]);
    }
}
