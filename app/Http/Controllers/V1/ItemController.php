<?php

namespace App\Http\Controllers\V1;

use App\Models\LostItem;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Database\QueryException;

class ItemController extends Controller
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
     * Gets lost items that match filter parameters
     *
     * @param Request $request - Request object
     *
     * @return object $response - Response object
     */
    public function getItems(Request $request)
    {
        $limit = $request->get("limit") ?
            intval($request->get("limit")) : 20;

        $params = buildGetItemParams($request);

        $lostItems = LostItem::buildItemsQuery($params)
            ->orderBy("created_at", "desc")
            ->paginate($limit);

        $response = [];
        $response["items"] = formatItems($lostItems);
        $response["pagination"] = formatPagination($lostItems);

        return $this->respond($response, 200);
    }

    /**
     * Adds a lost item reported by user.
     *
     * @param Request $request - Request object
     *
     * @return LostItem - item that is lost
     *
     * @throws UnauthorizedException
     */
    public function addItems(Request $request)
    {
        $this->validate($request, LostItem::$addItemRules);

        $currentUser = $request->user();
        if (!$currentUser) {
            throw new UnauthorizedException("You must be logged in to add an item");
        }

        $currentUser->phone = $request->get("phone");

        $item = new LostItem;
        $item->name = $request->get("name");
        $item->description = $request->get("description");
        $item->category = $request->get("category");
        $item->found = false;

        if ($request->get("reporter") === "owner") {
            $item->owner = $currentUser->id;
        } else if ($request->get("reporter") === "finder") {
            $item->finder = $currentUser->id;
        }

        $currentUser->save();
        $item->save();

        return $this->respond($item, 201);
    }
}
