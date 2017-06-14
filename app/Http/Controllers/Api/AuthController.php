<?php

namespace Brightfox\Http\Controllers\Api;

use Illuminate\Http\Request;

use JWTAuth;
use Illuminate\Support\Facades\Password;
use \Tymon\JWTAuth\Exceptions\JWTException;
use Illuminate\Foundation\Auth\ResetsPasswords;
use \Tymon\JWTAuth\Exceptions\TokenExpiredException;
use \Tymon\JWTAuth\Exceptions\TokenInvalidException;

class AuthController extends ApiController
{
    use ResetsPasswords;

    /**
     *  API Login, on success return JWT Auth token
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function authenticate(Request $request)
    {
        $credentials = $request->only('email', 'password');
        try {
            if (!$token = JWTAuth::attempt($credentials)) {
                return $this->setStatusCode(401)->respondWithError('invalid_credentials');
            }
        } catch (JWTException $e) {
            return $this->respondInternalError('could_not_create_token');
        }
        return $this->respond(['token' => $token]);
    }

    /**
     * Log out
     * Invalidate the token, so user cannot use it anymore
     * They have to relogin to get a new token
     *
     * @param Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout(Request $request)
    {
        if ($request->input('token')) {
            try {
                JWTAuth::invalidate($request->input('token'));
                return $this->respond('logged_out_correctly');
            } catch (TokenInvalidException $e) {
                return $this->setStatusCode($e->getStatusCode())->respondWithError('token_invalid');
            }
        }
        return $this->respondNotFound('token_not_in_request');
    }

    /**
     * Returns the authenticated user
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function authenticatedUser()
    {
        try {

            if (!$user = JWTAuth::parseToken()->authenticate()) {
                return $this->respondNotFound('user_not_found');
            }

        } catch (TokenExpiredException $e) {
            return $this->setStatusCode($e->getStatusCode())->respondWithError('token_expired');

        } catch (TokenInvalidException $e) {
            return $this->setStatusCode($e->getStatusCode())->respondWithError('token_invalid');

        } catch (JWTException $e) {
            return $this->setStatusCode($e->getStatusCode())->respondWithError('token_absent');
        }

        return response()->json(compact('user'));
    }

    /**
     * Refresh the token
     *
     * @return mixed
     */
    public function getToken()
    {
        $token = JWTAuth::getToken();
        if (!$token) {
            return $this->respondInternalError('token_not_provided');
        }
        try {
            $refreshedToken = JWTAuth::refresh($token);
        } catch (JWTException $e) {
            return $this->respondInternalError('unable_to_refresh_token');
        }
        return $this->respond(['token' => $refreshedToken]);
    }

    /**
     * @param \Illuminate\Http\Request $request
     *
     * @return mixed
     */
    public function sendResetLinkEmail(Request $request)
    {
        $this->validateSendResetLinkEmail($request);

        $broker = $this->getBroker();

        $response = Password::broker($broker)->sendResetLink(
            $this->getSendResetLinkEmailCredentials($request),
            $this->resetEmailBuilder()
        );

        switch ($response) {
            case Password::RESET_LINK_SENT:
                return $this->respond(['message' => 'recover_password_mail_sent']);
//                return $this->getSendResetLinkEmailSuccessResponse($response);
            case Password::INVALID_USER:
            default:
                return $this->setStatusCode(404)->respondWithError('email_not_found');
//                return $this->getSendResetLinkEmailFailureResponse($response);
        }
    }
}
