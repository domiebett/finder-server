<?php

namespace App\Api;

use Illuminate\Http\UploadedFile;

class DriveApi {

    private $client, $service;
    private $scopes = array(
        "https://www.googleapis.com/auth/drive.file",
        "https://www.googleapis.com/auth/drive"
    );

    public function __construct()
    {
        $this->client = new \Google_Client();
        $this->client->useApplicationDefaultCredentials();
        $this->client->setScopes($this->scopes);
        $this->service = new \Google_Service_Drive($this->client);
    }

    /**
     * Retrieves list of files from drive api
     *
     * @return array|string
     */
    public function getFiles() {
        $optParams = array(
            'pageSize' => 10,
            'fields' => 'nextPageToken, files(id, name, webViewLink)'
        );
        $results = $this->service->files->listFiles($optParams);

        if (count($results->getFiles()) == 0) {
            return "No files found.";
        } else {
            $files = [];
            print "Files:\n";
            foreach ($results->getFiles() as $file) {
                printf("%s (%s)\n", $file->getName(), $file->getId());
                $files[$file->getId()] = $file->getWebViewLink();
            }

            return $files;
        }
    }

    /**
     * Adds a file to drive api
     *
     * @param UploadedFile $file
     * @return \Google_Service_Drive_DriveFile|UploadedFile
     */
    public function addFile(UploadedFile $file) {
        $fileMetadata = new \Google_Service_Drive_DriveFile(array(
            'name' => $file->getClientOriginalName()));

        $file = $this->service->files->create($fileMetadata, array(
            'data' => file_get_contents($file->getRealPath()),
            'mimeType' => $file->getMimeType(),
            'uploadType' => 'multipart',
            'fields' => 'id, name, webContentLink'
        ));

        $permission = new \Google_Service_Drive_Permission();
        $permission->setType('anyone');
        $permission->setRole('reader');

        $this->service->permissions->create(
            $file->id,
            $permission,
            array('fields' => 'id')
        );

        return $file;
    }
}
