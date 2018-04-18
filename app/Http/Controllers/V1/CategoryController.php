<?php

namespace App\Http\Controllers\V1;

use App\Models\Category;

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
    public function getCategories() {
        $categories = Category::all();
        $response["categories"] = $categories;
        return $this->respond($response, 200);
    }
}
