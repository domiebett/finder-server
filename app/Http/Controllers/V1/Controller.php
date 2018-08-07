<?php

namespace App\Http\Controllers\V1;

use App\Api\DriveApi;
use App\Models\ItemFile;
use App\Models\LostItem;
use Illuminate\Http\Response;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Laravel\Lumen\Routing\Controller as BaseController;

class Controller extends BaseController
{

    protected $driveApi;

    private const SIGNIN_RULES = [
        'email'    => 'required|email|max:255',
        'password' => 'required',
    ];

    private const SIGNUP_RULES = [
        "username" => "required|max:100",
        "email"    => "required|email|max:255",
        "password" => "required",
    ];

    private const ADD_ITEM_RULES = [
        "name" => "required|string",
        "description" => "required|string",
        "reporter" => "required|string|in:finder,owner",
        "category" => "required|integer|exists:categories,id"
    ];

    private const ADD_CATEGORY_RULES = [
        "name" => "required|string|unique:categories,name"
    ];

    private const VALIDATION_RULES = [
        "login" => self::SIGNIN_RULES,
        "signup" => self::SIGNUP_RULES,
        "addLostItem" => self::ADD_ITEM_RULES,
        "addCategory" => self::ADD_CATEGORY_RULES
    ];


    private const ADD_ITEM_MESSAGES = [
        "reporter.in" => "Reporter should be 'finder' or 'owner'",
        "category.exists" => "The selected category doesn't exist"
    ];

    private const ADD_CATEGORY_MESSAGES = [
        "name.unique" => "The category name is already in use"
    ];

    private const VALIDATION_MESSAGES = [
        "addLostItem" => self::ADD_ITEM_MESSAGES,
        "addCategory" => self::ADD_CATEGORY_MESSAGES
    ];

    public function __construct() {
    }

    /**
     * Validates requests depending on the rule set selected
     *
     * @param String $ruleSet
     * @param Request $request
     * @param bool $messages
     */
    protected function validates(String $ruleSet, Request $request, $messages = false) {
        if ($messages) {
            $this->validate(
                $request, self::VALIDATION_RULES["$ruleSet"],
                self::VALIDATION_MESSAGES["$ruleSet"]
            );
        } else {
            $this->validate($request, self::VALIDATION_RULES["$ruleSet"]);
        }
    }

    /**
     * Creates a new response with the content and http content
     *
     * @param $response
     * @param $httpStatus
     *
     * @return Response
     */
    protected function respond($response, $httpStatus) {
        return new Response($response, $httpStatus);
    }

    /**
     * Creates a message to be returned as a response.
     *
     * @param String $message
     * @param int $httpStatus
     * @return Response
     */
    protected function message(String $message, $httpStatus = 200) {
        return new Response([
            "message" => $message,
        ], $httpStatus);
    }

    /**
     * Save files belonging to items
     *
     * @param LostItem $item
     * @param UploadedFile $file
     */
    protected function saveItemFile(LostItem $item, UploadedFile $file) {
        $this->driveApi = new DriveApi();
        $driveFile = $this->driveApi->addFile($file);

        $file = new ItemFile();
        $file->name = $driveFile->name;
        $file->identity = $driveFile->id;
        $file->url = $driveFile->webContentLink;
        $file->item = $item->id;

        $file->save();
    }
}
