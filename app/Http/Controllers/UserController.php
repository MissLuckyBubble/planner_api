<?php

namespace App\Http\Controllers;

use App\Traits\HttpResponses;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

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

        if (Hash::check($request->old_password, $user->password)){
            return $this->error('','Старата парола е невалидна',401);
        }
        $user->password = Hash::make($request->input('new_password'));
        $user->save();
        return $this->success($user,'Паролата е сменена успешно.', 200);
    }

    public function editEmail(Request $request){
        $user = Auth::user();
        $this->validate($request,[
            'password' => ['required'],
            'email' => ['required', 'email:rfc,dns', 'max:255', 'unique:users'],
        ], [
            'email.required' => 'Полето email е задължително.',
            'password.required' => 'Полето парола е задължително.',
            'email' => 'Невалиден или същестуващ email.',]);
        if (Hash::check($request->password, $user->password)){
            return $this->error('','Вашата парола е невалидна',401);
        }
        $user->email = $request->email;
        $user->save();
        return $this->success($user,'Имейлът е променен успешно.', 200);
    }
}
