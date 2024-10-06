<?php

if (!function_exists('authApi')) {
    function authApi(){
        return auth()->guard('api');
    }
}

if (!function_exists('res_data')) {
    function res_data($data, $message = '', $status = 200)
    {
        $result=[
            'statusCode' => $status,
            'status' => in_array( $status,[200,201,202,203]),

        ];
        if(!empty( $data))
        $result['data']=$data;
    if(!empty( $message))
    $result['message']=$message;
    return response()->json( $result, $status);
        // return response()->json([
        //     'result' => empty( $data)?null:$data,
        //     'message' => $message,
        //     'status' => $status
        // ], $status);
    }
}
