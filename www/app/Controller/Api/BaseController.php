<?php


namespace Controller\Api;


use Http\Router;

/**
 * Class BaseController
 * @package Controller\Api
 */
class BaseController extends Controller
{
    /**
     * @var mixed
     */
    protected $request;
    /**
     * @var mixed
     */
    protected $method;

    /**
     * BaseController constructor.
     */
    public function __construct()
    {
        $this->method = Router::getMethod();
        $this->request = Router::getParamsPost();
    }

    /**
     * @param $result
     * @param $message
     */
    public function sendResponse($result, $message)
    {
        $response =
            [
                'success' => true,
                'data' => $result,
                'message' => $message
            ];
        return $this->jsonSendRequest($response, 200);
    }

    /**
     * @param $error
     * @param array $errorMessage
     * @param int $code
     */
    public function sendError($error, $errorMessage = [], $code = 404)
    {
        $response =
            [
                'success' => true,
                'data' => '',
                'message' => $error
            ];
        if (!empty($errorMessage))
            $response['data'] = $errorMessage;

        return $this->jsonSendRequest($response, $code);
    }

    /**
     * @param $response
     * @param $status
     */
    public function jsonSendRequest($response, $status)
    {
        Router::getHeader($response['message'], $status);
        echo json_encode($response);
    }

}