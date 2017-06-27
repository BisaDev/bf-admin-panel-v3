<?php

namespace Brightfox\Http\Controllers\Api;

use Brightfox\Http\Transformers\MeetupDetailsTransformer;
use Brightfox\Models\Meetup;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Brightfox\Http\Controllers\Controller;
use Brightfox\Http\Transformers\MeetupTransformer;

class MeetupsController extends ApiController
{
    private $user;
    private $transformer;
    private $detailsTransformer;

    /**
     * UsersController constructor.
     *
     * @param \Brightfox\Http\Transformers\MeetupTransformer        $transformer
     * @param \Brightfox\Http\Transformers\MeetupDetailsTransformer $detailsTransformer
     */
    public function __construct(MeetupTransformer $transformer, MeetupDetailsTransformer $detailsTransformer)
    {
        $this->transformer = $transformer;
        $this->detailsTransformer = $detailsTransformer;
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

    public function show($id)
    {
        $meetup = Meetup::find($id);
        if($meetup->checkOwner($this->user)){
            return $this->respond($this->detailsTransformer->transform($meetup));
        }else{
            return $this->respondWithError('You Do not have permission to view this meetup');
        }
    }

}
