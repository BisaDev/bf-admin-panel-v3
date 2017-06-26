<?php

namespace Brightfox\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Brightfox\Http\Controllers\Controller;
use Brightfox\Http\Transformers\UserTransformer;

class UserController extends ApiController
{
    private $user;
    private $transformer;

    /**
     * UsersController constructor.
     *
     * @param \Brightfox\Http\Transformers\UserTransformer $transformer
     */
    public function __construct(UserTransformer $transformer)
    {
        $this->transformer = $transformer;
        $this->middleware(function ($request, $next) {
            $this->user = Auth::user();
            view()->share('user', $this->user);
            return $next($request);
        });
    }

    public function me()
    {
        return $this->respond($this->transformer->transform($this->user));
    }
}
