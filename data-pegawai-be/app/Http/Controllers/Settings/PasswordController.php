<?php

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PasswordController extends Controller
{
    /**
     * Update the user's password.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $user = $request->user();

        $accepted_body = $request->only([
            'password', 
        ]);

        $validator = Validator::make($accepted_body, [
            'password' => 'required|confirmed|min:6'
        ]);

        if ($validator->fails()) {
            return response()->json([
                "status" => "fail",
                "message" => "Password Gagal Terganti!",
                "error" => $validator->errors(),
            ], 403);
        }
        
        $model = User::findOrFail($user->id);
        $model->password = bcrypt($request->password);
        $model->save();

        return response()->json([
            'status' => true,
            'message' => 'Password Terganti!'
        ], 200);

    }
}
