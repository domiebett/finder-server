<?php

namespace App\Http\Controllers\V1;

use App\Api\BoxApi;
use GuzzleHttp\Psr7\UploadedFile;
use Illuminate\Http\Request;
use App\Exceptions\BadRequestException;

class FileController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Get all files
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        
    }

    /**
     * Add a file
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     * @throws BadRequestException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function store(Request $request) {
        $file = $request->file('file');
        $fileName = $request->get("name");

        if ($file->getSize() > 1000000) {
            throw new BadRequestException(
                "Your file size is too big. Should be less that 1 mb."
            );
        }

        $file->move(base_path('storage/csv'),$file->getClientOriginalName());

        $boxApi = new BoxApi();
        $response = $boxApi->uploadFile($file, $fileName, "0");

        return $this->respond($response, 201);
    }
}
