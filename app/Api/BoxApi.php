<?php

namespace App\Api;

use GuzzleHttp\Client;
use Illuminate\Http\UploadedFile;

class BoxApi {

    private $client;
    public $client_id 		= '';
    public $client_secret 	= '';
    public $redirect_uri	= '';
    public $access_token	= '';
    public $refresh_token	= '';
    public $authorize_url 	= 'https://www.box.com/api/oauth2/authorize';
    public $token_url	 	= 'https://www.box.com/api/oauth2/token';
    public $apiUrl 		= 'https://api.box.com/2.0';
    public $uploadUrl 		= 'https://upload.box.com/api/2.0';
    public function __construct( $redirect_uri = '') {
        $this->client_id 		= getenv("BOX_CLIENT_ID");
        $this->client_secret	= getenv("BOX_CLIENT_SECRET");
        $this->redirect_uri		= $redirect_uri;
        $this->client = new Client();
    }


    /**
     * @param UploadedFile $file
     * @param $fileName
     * @param $parentId
     * @return mixed|\Psr\Http\Message\ResponseInterface
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function uploadFile(UploadedFile $file, $fileName, $parentId) {
        $path = base_path('storage/csv/').$file->getClientOriginalName();
        $url = $this->buildUrl("upload", "files/content");
        $attributes = json_encode([
            "name" => $fileName,
            "parent" => [
                "id" => $parentId
            ]
        ]);

        $multipart = [
            [
                "name" => "file",
                "contents" => fopen($path, "r"),
                "headers" => [
                    "Content-Type" => "application/x-object"
                ]
            ],
            [
                "name" => "attributes",
                "contents" => $attributes
            ]
        ];

        $headers = [
            "Authorization" => "Bearer ".getenv("BOX_CLIENT_AUTH_TOKEN"),
            "Content-Type" => "multipart/form-data",
            "enctype" => "multipart/form-data"
        ];

        $response = $this->client->request(
            "POST", $url,
            [
                "multipart" => $multipart,
                "headers" => $headers
            ]
        );

        return $response;
    }

    /* Builds the URL for the call */
    private function buildUrl($apiFunction = "api", $url = "") {
        if ($apiFunction == "upload") {
            $base = $this->uploadUrl;
        } else {
            $base = $this->apiUrl;
        }

        $builtUrl = $base."/".$url;
        return $builtUrl;
    }

    private function post($url, $file, $fileName, $parentId) {
        $cFile = curl_file_create($file);
        $params = json_encode([
            "attributes" => [
                "name" => $fileName,
                "parent" => [
                    "id" => $parentId
                ]
            ],
            "file" => $cFile
        ]);

        dd($cFile);

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, "https://upload.box.com/api/2.0/files/content");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $params);

        $headers = array();
        $headers[] = "Authorization: Bearer CGZa9LBll67xAGzJSFRAQkak9p6mWqBk";
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        $result = curl_exec($ch);
        if (curl_errno($ch)) {
            echo 'Error:' . curl_error($ch);
        }
        curl_close ($ch);

        return $result;
    }
}