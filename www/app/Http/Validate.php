<?php


namespace Http;


class Validate
{

    public static function check($request, array $inputArr) : array
    {
        $requiredArray = [];

        if(!empty($request))
        {
            if(!(new Validate)->checkEmail($request))
            {
                $requiredArray['data']['email'] = 'Email is incorrect';
            }

            if(!(new Validate)->checkUserLength($request))
            {
                $requiredArray['data']['email'] = 'Maximum username length is 255';
            }
            foreach ((new Validate)->prepareFields($request, $inputArr) as $required)
            {
                if('count')
                {
                    $requiredArray['data']['args'] = 'Check you params request.';
                }

                $requiredArray['data'][$required][] = 'The '.$required.' field is required';
                unset($requiredArray['data']['count']);
            }
        }
        else
        {
            foreach ($inputArr as $required)
            {
                $requiredArray['data'][$required][] = 'The '.$required.' field is required';
            }
        }

        return $requiredArray;

    }

    private function checkUserLength($request)
    {
        if(strlen($request['email'])>255)
        {
            return false;
        }
        return true;
    }

    private function checkEmail($request)
    {
        if(!filter_var($request['email'], FILTER_VALIDATE_EMAIL))
        {
            return false;
        }
        return true;
    }

    private function prepareFields($request, array $inputArr) : array
    {
        $request = array_keys($request);
        $inputArr = array_values($inputArr);

        if(count($request) != count($inputArr))
        {
            $inputArr[] = 'count';

        }
        return array_merge(array_diff($inputArr, $request));;

    }

}