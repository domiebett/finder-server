<?php

namespace App\Http\Controllers\V1;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
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
     * Get all categories
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        $categories = Category::all();
        $response["categories"] = formatCategories($categories);

        return $this->respond($response, 200);
    }

    /**
     * Saves categories to the database.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) {
        $this->validates("addCategory", $request, true);

        $category = new Category($request->only("name"));
        $category->save();

        return $this->respond($category, 201);
    }
}
