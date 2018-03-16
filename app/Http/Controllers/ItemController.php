<?php

namespace App\Http\Controllers;

use App\Exceptions\UnauthorizedException;
use App\Models\Category;
use App\Models\LostItem;
use App\Models\User;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

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
        try {
            $limit = $request->get("limit") ?
                intval($request->get("limit")) : 20;
            $params = [];

            if ($searchQuery = $request->get("q")) {
                $params["searchQuery"] = $searchQuery;
            }
            if ($reporter = $request->get("reporter")) {
                $params["reporter"] = $reporter;
            }
            if($categories = $request->get("categories")) {
                $params["categories"] = explode(",", $categories);
            }

            $lostItems = LostItem::buildItemsQuery($params)
                ->orderBy("created_at", "desc")
                ->paginate($limit);

            $response["items"] = $this->formatItemData($lostItems);
            $response["pagination"] = [
                "totalCount" => $lostItems->total(),
                "pageSize" => $lostItems->perPage(),
                "lastPage" => $lostItems->lastPage(),
                "currentPage" => $lostItems->currentPage(),
            ];

            return $response;
        } catch(QueryException $exception) {
            $response = [
                "message" => $exception->getMessage()
            ];
            return $this->respond($response, 400);
        }
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
    public function addItem(Request $request) {
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

        return $item;
    }

    /**
     * Format request data
     * extracts returned request queries to match data on client side
     *
     * @param array $items - collection of items
     *
     * @return array - array of formatted items
     */
    private function formatItemData($items)
    {
        $formattedItems = [];
        foreach ($items as $item) {
            $formattedItem = (object) [
                "id" => $item->id,
                "name" => $item->name,
                "description" => $item->description,
                "category" => Category::find($item->category)->name,
                "finder" => $item->finder ? User::find($item->finder) : null,
                "owner" => $item->owner ? User::find($item->owner) : null,
                "found" => $item->found,
                "dateCreated" => $this->formatTime($item->created_at),
                "dateUpdated" => $this->formatTime($item->updated_at)
            ];
            $formattedItems[] = $formattedItem;
        }
        return $formattedItems;
    }

    /**
     * Checks if the given time is null and returns null else it returns
     * the time in the date format
     *
     * @param string $time - the time in the date format
     *
     * @return mixed null|string
     */
    private function formatTime($time)
    {
        return $time === null ? null : date("Y-m-d H:i:s", $time->getTimestamp());
    }
}
