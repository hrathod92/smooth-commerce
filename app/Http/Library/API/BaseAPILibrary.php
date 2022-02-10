<?php

namespace App\Http\Library\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response as HttpResponse;
use Illuminate\Support\Facades\Validator;

class BaseAPILibrary extends Controller
{

    /**
     * Default Pagination
     *
     * @var int
     */
    public $defaultPagination = 50;

    /**
     * Default Status Code
     *
     * @var int
     */
    protected $statusCode = HttpResponse::HTTP_OK;

    /**
     * Create Product Validation Rules
     *
     * @var array
     */
    public $createProductRules = [
        'name'        => 'required|unique:products',
        'category_id' => 'required|exists:categories,id',
        'sku'         => 'required|unique:products',
        'price'       => 'required|numeric'
    ];

    /**
     * Create Product Validation Messages
     *
     * @var array
     */
    public $createProductMessages = [
        'name.required'        => 'Please enter a name.',
        'category_id.required' => 'Please enter a category.',
        'sku.required'         => 'Please enter a SKU.',
        'price.required'       => 'Please enter a Price.'
    ];

    /**
     * Apply Pagination
     *
     * @param $model
     * @param Request $request
     * @return mixed
     */
    public function applyPagination($model, Request $request)
    {
        // Validate Reqest
        $this->validateCustom($request->all(), [
            'per_page' => 'numeric',
            'page'     => 'numeric'
        ], []);

        // Pagination Details
        $perPage    = $request->get('per_page', $this->defaultPagination);
        $page       = $request->get('page', 1);
        $offset     = 0;
        $take       = $perPage ? $perPage : 50;
        $page       = (int) $page;

        if ($page && $page > 1) {
            $offset = ($take * $page) - $take;
        }

        return $model->offset($offset)->limit($take)->get();
    }

    /**
     * Get Success Response
     *
     * @param array|mixed $items
     * @param string $key
     * @param array $addedArray
     * @return \Illuminate\Http\JsonResponse
     */
    public function setSuccessResponse($items = array(), $key = 'items', $addedArray = [])
    {
        return response()->json((object) array_merge([
            'status'    => true,
            $key        => $items
        ], $addedArray), $this->getStatusCode());
    }

    /**
     * Set Failure Response
     *
     * @param null $errorCode
     * @param null $message
     * @param bool $showErrorCode
     * @param int $requestStatus
     * @return \Illuminate\Http\JsonResponse
     */
    public function setFailureResponse($errorCode = null, $message = null, $showErrorCode = true, $requestStatus = HttpResponse::HTTP_OK)
    {
        $response = [
            'status'    => false,
            'errorCode' => 'NO_ITEMS',
            'message'   => $message
        ];

        if ($errorCode == null && isset($response['errorCode'])) {
            unset($response['errorCode']);
        }

        if ($showErrorCode && $errorCode && $errorCode != '') {
            $response['errorCode'] = $errorCode;
        }

        return $this->setStatusCode($requestStatus)->respond($response);
    }

    /**
     * Respond
     *
     * @param  array $response
     * @return \Illuminate\Http\JsonResponse
     */
    public function respond($response)
    {
        return response()->json((object) $response, $this->getStatusCode());
    }

    /**
     * Get Status Code
     *
     * @return int
     */
    public function getStatusCode()
    {
        return $this->statusCode;
    }

    /**
     * Set Status Code
     *
     * @param $statusCode
     * @return $this
     */
    public function setStatusCode($statusCode)
    {
        $this->statusCode = $statusCode;
        return $this;
    }

    /**
     * Validate Custom
     *
     * @param array $input
     * @param array $rules
     * @param array $messages
     * @return mixed
     */
    public function validateCustom($input, $rules, $messages = array())
    {
        $validator = Validator::make($input, $rules, $messages);

        // Return response of validation failure with associated messages
        if ($validator->fails()) {
            $this->send([
                'status'        => false,
                'errorCode'     => 'VALIDATION_EXCEPTION',
                'messages'      => $validator->messages()
            ], true, HttpResponse::HTTP_BAD_REQUEST);
        }

        return $validator;
    }

    /**
     * Send Response Immediately
     *
     * @param array|mixed $data
     * @param bool $json
     * @param int $code
     */
    function send($data, $json = true, $code = HttpResponse::HTTP_OK)
    {
        if ($json == true) {
            response()->json($data, $code)->send();
        } else {
            response(var_export($data, true))->send();
        }
        exit();
    }
}
