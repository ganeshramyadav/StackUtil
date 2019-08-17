<?php

namespace StackUtil\Utils;
use Exception;
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
        if(curl_errno($curl)){
            throw new Exception(curl_error($curl));
        }
        $httpcode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        curl_close($curl);

        $res = json_decode($result,true);
        if(empty($res) || $res === null)
        {
            $res = $result;
        }
        return response()->json($res, $httpcode);
    }

}
