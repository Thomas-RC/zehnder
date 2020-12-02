<?php


namespace Controller\Api;


use Http\Router;

class BaseController extends Controller
{
    protected $request;
    protected $method;

    public function __construct()
    {
        $this->method = Router::getMethod();
        $this->request = Router::getParamsPost();
    }

    public function sendResponse($result, $message)
    {
        $response =
            [
                'success'=>true,
                'data'=>$result,
                'message'=>$message
            ];
        return $this->jsonSendRequest($response, 200);
    }

    public function sendError($error, $errorMessage=[], $code=404)
    {
        $response =
            [
                'success'=>true,
                'data'=>'',
                'message'=>$error
            ];
        if(!empty($errorMessage))
            $response['data'] = $errorMessage;

        return $this->jsonSendRequest($response, $code);
    }

    public function jsonSendRequest($response, $status)
    {
        Router::getHeader($response['message'], $status);
        echo json_encode($response);
    }

}