<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ForgotPasswordController extends Controller
{
    use SendsPasswordResetEmails;

    protected function sendResetLinkResponse(Request $request, $response)
    {
        return response()->json([
            'code' => Response::HTTP_OK,
            'status' => trans($response)
        ], 200);
    }

    protected function sendResetLinkFailedResponse(Request $request, $response)
    {
        $error = (object) [
            'email' => array(trans($response))
        ];
        return response()->json([
            'errors' => $error
        ], 422);
    }
}
