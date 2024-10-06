<?php

if(function_exists('res_data12')){
    function res_data12($data,$message='',int $status=200){
        return response([
            'result'=>empty( $data)?null:$data,
            'message'=>$message,
            'status'=>$status,

        ],$status);
    }
}
