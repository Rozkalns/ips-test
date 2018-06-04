<?php

namespace App\Http\Responses;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Response;


class ApiResponse extends Response
{
    private $data;
    private $errors;
    private $exceptions;
    private $meta;

    const STATUS_OK = Response::HTTP_OK;
    const STATUS_CREATED = Response::HTTP_CREATED;
    const STATUS_DELETED = Response::HTTP_NO_CONTENT;
    const STATUS_UNAUTHORIZED = Response::HTTP_UNAUTHORIZED;
    const STATUS_FORBIDDEN = Response::HTTP_FORBIDDEN;
    const STATUS_NOT_FOUND = Response::HTTP_NOT_FOUND;

    const META_STATUS_CODE = 'status_code';
    const META_COMPLETED_AT = 'completed_at';
    const META_METHOD = 'method';
    const META_ENDPOINT = 'endpoint';

    public function __construct($content = null, $status = self::STATUS_OK, $headers = [])
    {
        parent::__construct('', $status, []);

        $this->setData($content);
    }

    public static function create($content = '', $status = self::STATUS_OK, $headers = [])
    {
        $res = new self($content, $status, $headers);
        return $res;
    }

    public function toCustomResponse()
    {
        $response = [
            'data' => $this->data,
            'meta' => $this->meta,
        ];

        if ($this->errors) {
            $response['errors'] = $this->errors;
        }

        $response = response()->json($response, $this->statusCode);
        $response->original = $this;

        return $response;
    }

    public function setCommonMetaFields(Request $request)
    {
        $this->setMeta(self::META_STATUS_CODE, $this->statusCode);
        $this->setMeta(self::META_COMPLETED_AT, Carbon::now()->toIso8601String());
        $this->setMeta(self::META_METHOD, $request->getMethod());
        $this->setMeta(self::META_ENDPOINT, $request->route()->uri());
    }

    public function setData($data)
    {
        $this->data = $data;
        return $this;
    }

    public function setMeta($key, $value)
    {
        $this->meta[$key] = $value;
        return $this;
    }

    public function setError($error)
    {
        $this->errors[] = $error;
        return $this;
    }
}
