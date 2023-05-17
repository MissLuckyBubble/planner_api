<?php

namespace App\Http\Controllers;

use App\Traits\HttpResponses;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    use HttpResponses;
    public function editPassword(Request $request)
    {
        $user = Auth::user();

        $this->validate($request,
            ['old_password' => ['required'],
                'new_password' => ['required','min:8', 'confirmed']],
            ['required' => 'Всички полета са задължителни.',]);

        if (!Hash::check($request->old_password, $user->password)){
            return $this->error('','Старата парола е невалидна',401);
        }
        if (Hash::check($request->new_password, $user->password)){
            return $this->error('','Новата парола не може да бъде същата като старата.',400);
        }
        $user->password = Hash::make($request->input('new_password'));
        $user->save();
        return $this->success($user,'Паролата е сменена успешно.', 200);
    }

    public function editEmailPhone(Request $request){
        $user = Auth::user();
        $this->validate($request,[
            'password' => ['required'],
            'email' => ['required', 'email:rfc,dns', 'max:255', Rule::unique('users')->ignore($user->id)],
            'phoneNumber' => ['required', 'string', 'size:9', Rule::unique('users')->ignore($user->id)],
        ], [
            'email.required' => 'Полето email е задължително.',
            'password.required' => 'Полето парола е задължително.',
            'email' => 'Невалиден или същестуващ email.',
            'phoneNumber.required' => 'Полето телефонен номер е задължително.',
            'phoneNumber' => 'Невалиден или същестуващ телефонен номер.',
        ]);
        if($user->email == $request->email && $user->phoneNumber == $request->phoneNumber){
            return $this->error('','Нищо не сте променили',400);
        }
        if (!Hash::check($request->password, $user->password)){
            return $this->error('','Вашата парола е невалидна',401);
        }

        $user->emazil = $request->email;
        $user->phoneNumber = $request->phoneNumber;
        $user->save();

        return $this->success($user,'Вашите данни са е променени успешно.', 200);
    }
}
