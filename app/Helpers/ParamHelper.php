<?php

use App\Exceptions\BadRequestException;
use Illuminate\Http\Request;

/**
 * Helps to build parameter to be used to build query
 * for item.
 *
 * @param Request $request - Request Object
 *
 * @return void
 */
function buildGetItemParams(Request $request) {
    $params = [];

    if ($searchQuery = $request->get("q")) {
        $params["searchQuery"] = $searchQuery;
    }
    if ($reporter = $request->get("reporter")) {
        if(!in_array($reporter, ["owner", "finder"])) {
            $message = "You can only provide 'owner' or 'finder' as reporters";
            throw new BadRequestException($message);
        }
        $params["reporter"] = $reporter;
    }
    if ($categories = $request->get("categories")) {
        $params["categories"] = explode(",", $categories);
    }

    return $params;
}