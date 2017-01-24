<?php
/**
 * Created by PhpStorm.
 * User: Servidor
 * Date: 20/01/2017
 * Time: 16:35
 */

namespace Deskti\Curl;


use Deskti\Curl\Exceptions\DesktiCurlException;

class CurlRequest
{
    private $http_client;
    private $method;
    private $url;
    private $headers = Array();
    private $parameters =  Array();
    private $curl;
    private $params;

    public function __construct()
    {
        //$this->curl = $contract;
    }

    public function request($params = array())
    {
        $this->curl = curl_init();
        $this->headers = array();

        if(!array_key_exists('url',$params)) {
            throw new DesktiCurlException("You must set the URL to make a request.");
        } else {
            $this->url = $params["url"];
        }

        curl_setopt($this->curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($this->curl, CURLOPT_TIMEOUT, 60);

        if (array_key_exists('parameters',$params)) {
            $this->parameters = array_merge($this->parameters, $params["parameters"]);
        }

        if(array_key_exists('method',$params)) {
            $this->method = $params["method"];
        }

        if(array_key_exists('headers',$params)) {
            $this->headers = array_merge($this->headers, $params["headers"]);
        }

        if ($this->method){
            switch($this->method) {
                case 'post':
                case 'POST':
                    curl_setopt($this->curl, CURLOPT_POST, true);
                    curl_setopt($this->curl, CURLOPT_POSTFIELDS, http_build_query($this->parameters));
                    break;
                case 'get':
                case 'GET':
                    $this->url .= '?' . http_build_query($this->parameters);
                    break;
                case 'put':
                case 'PUT':
                    curl_setopt($this->curl, CURLOPT_CUSTOMREQUEST, 'PUT');
                    curl_setopt($this->curl, CURLOPT_POSTFIELDS, http_build_query($this->parameters));
                    break;
                case 'delete':
                case 'DELETE':
                    curl_setopt($this->curl, CURLOPT_CUSTOMREQUEST, 'DELETE');
                    curl_setopt($this->curl, CURLOPT_POSTFIELDS, http_build_query($this->parameters));
                    break;

            }
        }

        curl_setopt($this->curl, CURLOPT_URL, $this->url);
        curl_setopt($this->curl, CURLOPT_HTTPHEADER, $this->headers);
        curl_setopt($this->curl, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($this->curl, CURLOPT_SSL_VERIFYPEER, 0);
        //curl_setopt( $this->curl, CURLOPT_CAINFO, storage_path('certificates/ca-certificates.crt') );
    }

    public function run()
    {
        $response = curl_exec($this->curl);
        $error = curl_error($this->curl);

        if ($error) {
            throw new DesktiCurlException("error: ".$error);
        }

        $code = curl_getinfo($this->curl, CURLINFO_HTTP_CODE);

        curl_close($this->curl);

        return array("code" => ''.$code.'', "body" => $response);
    }
}