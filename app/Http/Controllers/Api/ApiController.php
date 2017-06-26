<?php

namespace Brightfox\Http\Controllers\Api;

use Illuminate\Http\Request;
use Brightfox\Http\Controllers\Controller;

class ApiController extends Controller
{
    public $statusCode = 200;

    /**
     * @return int
     */
    public function getStatusCode()
    {
        return $this->statusCode;
    }

    /**
     * @param int $statusCode
     *
     * @return $this
     */
    public function setStatusCode($statusCode)
    {
        $this->statusCode = $statusCode;
        return $this;
    }

    /**
     * @param string $message
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function respondNotFound($message = 'Not found')
    {
        return $this->setStatusCode(404)->respondWithError($message);
    }

    /**
     * @param string $message
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function respondInternalError($message = 'Internal Error')
    {
        return $this->setStatusCode(500)->respondWithError($message);
    }

    /**
     * @param $data
     * @param $headers
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function respond($data, $headers = [])
    {
        $dataArray = $this->createResponseArray($data);
        return response()->json($dataArray, $this->getStatusCode(), $headers);
    }

    /**
     * @param       $message
     * @param array $headers
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function respondWithError($message, $headers = [])
    {
        $dataArray = $this->createResponseArray(null, [
            'message' => $message,
            'status_code' => $this->getStatusCode()
        ]);
        return response()->json($dataArray, $this->getStatusCode(), $headers);
    }

    public function createResponseArray($data, $error = null)
    {
        return [
            'data' => $data,
            'error' => $error
        ];
    }
}
