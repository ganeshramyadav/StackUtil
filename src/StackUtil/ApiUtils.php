<?php

namespace StackUtil\Utils;

class ApiUtils
{
    public static function Request($method, $url, array $head, $body = null)
    {
        $curl = curl_init();

        switch ($method){
            case "POST":
                curl_setopt($curl, CURLOPT_POST, 1);
                if ($body)
                    curl_setopt($curl, CURLOPT_POSTFIELDS, $body);
                break;
            case "PUT":
                curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "PUT");
                if ($body)
                    curl_setopt($curl, CURLOPT_POSTFIELDS, $body);
                break;
            default:
                if ($body)
                    $url = sprintf("%s?%s", $url, http_build_query($body));
        }

        // OPTIONS:
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_HTTPHEADER, $head);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
        
        // EXECUTE:
        $result = curl_exec($curl);
        $httpcode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        if(!$result){
            return response()->json(['error' => 'Service Unavailable'], 503);
            /* die("Connection Failure"); */
        }
        curl_close($curl);
        if($httpcode == 200){
            return response()->json(['message' => 'Authorized'], 200);
        }else{
            return response()->json(['error' => 'Unauthorized'], 401);
        }
    }
    
}
