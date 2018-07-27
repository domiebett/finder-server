<?php

namespace App\Http\Controllers\V1;

use App\Models\LostItem;
use Illuminate\Http\Request;
use App\Exceptions\UnauthorizedException;
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
     * @return Response $response - Response object
     * @throws \App\Exceptions\BadRequestException
     */
    public function index(Request $request)
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
     * @return Response - item that is lost
     */
    public function store(Request $request)
    {
        $this->validates("addLostItem", $request, true);

        $currentUser = $request->user();
        $currentUser->phone = $request->get("phone");

        $item = new LostItem($request->only("name", "description", "category"));

        if ($request->reporter === "owner") {
            $item->owner = $currentUser->id;
        } else if ($request->reporter === "finder") {
            $item->finder = $currentUser->id;
        }

        $currentUser->save();
        $item->save();

        return $this->respond($item, 201);
    }
}
