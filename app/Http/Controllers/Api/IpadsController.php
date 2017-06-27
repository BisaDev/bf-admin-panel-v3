<?php

namespace Brightfox\Http\Controllers\Api;

use Brightfox\Http\Transformers\IpadTransformer;
use Brightfox\Models\Ipad;
use Illuminate\Http\Request;

class IpadsController extends ApiController
{
    private $ipadTransformer;

    /**
     * IpadsController constructor.
     *
     * @param $ipadTransformer
     */
    public function __construct(IpadTransformer $ipadTransformer)
    {
        $this->ipadTransformer = $ipadTransformer;
    }

    public function register(Request $request)
    {
        if ($request->has('macAddress')){
            $ipad = Ipad::firstOrCreate([
                'mac_address' => $request->get('macAddress')
            ]);

            return $this->respond($this->ipadTransformer->transform($ipad));

        }

        return $this->respondWithError('MAC Address field is required');
    }
}
