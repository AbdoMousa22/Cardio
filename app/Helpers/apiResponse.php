<?php

namespace App\Helpers ;

class apiResponse {
         static function sendResponse($code=200,$mes=null,$data=null){
            $response=[
                "status" =>$code,
                "mes"    =>$mes,
                "data"    =>$data,

            ];
            return response()->json($response,$code);
         }

}

