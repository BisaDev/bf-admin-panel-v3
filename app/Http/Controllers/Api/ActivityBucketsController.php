<?php

namespace Brightfox\Http\Controllers\Api;



use Brightfox\Models\Meetup;
use Illuminate\Http\Request;
use Brightfox\Models\ActivityBucket;
use Illuminate\Support\Facades\Auth;
use Brightfox\Http\Controllers\Controller;
use Brightfox\Http\Transformers\ActivityBucketDetailsTransformer;

class ActivityBucketsController extends ApiController
{
    private $transformer;

    /**
     * UsersController constructor.
     *
     * @param \Brightfox\Http\Transformers\ActivityBucketDetailsTransformer $transformer
     */
    public function __construct(ActivityBucketDetailsTransformer $transformer)
    {
        $this->transformer = $transformer;
    }


    public function show($id)
    {
        $activityBucket = ActivityBucket::find($id);
        if(is_null($activityBucket)){
            return $this->respondWithError('Activity Bucket not found');
        }
        return $this->respond($this->transformer->transform($activityBucket));
    }

}
