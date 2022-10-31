<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\ResetsPasswords;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ResetPasswordController extends Controller
{
    use ResetsPasswords;

    protected function sendResetResponse(Request $request, $response)
    {
        return response()->json([
            'code' => Response::HTTP_OK,
            'status' => trans($response)
        ], 200);
    }

    protected function sendResetFailedResponse(Request $request, $response)
    {
        $error = (object) [
            'email' => array(trans($response))
        ];
        return response()->json([
            'errors' => $error
        ], 422);
    }
    protected function rules()
    {
        return [
            'token' => 'required',
            'email' => 'required|email',
            'password' => ['required', 'confirmed'],
        ];
    }
}
