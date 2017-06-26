<?php

namespace Brightfox\Http\Controllers\Api;

use Brightfox\Models\Meetup;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Brightfox\Http\Controllers\Controller;
use Brightfox\Http\Transformers\UserTransformer;
use Brightfox\Http\Transformers\MeetupTransformer;

class MeetupsController extends ApiController
{
    private $user;
    private $transformer;

    /**
     * UsersController constructor.
     *
     * @param \Brightfox\Http\Transformers\MeetupTransformer $transformer
     */
    public function __construct(MeetupTransformer $transformer)
    {
        $this->transformer = $transformer;
        $this->middleware(function ($request, $next) {
            $this->user = Auth::user();
            return $next($request);
        });
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function all()
    {
        $meetups = Meetup::forUser($this->user)->forWeek()->get();
        return $this->respond($this->transformer->transformCollection($meetups));
    }

}
