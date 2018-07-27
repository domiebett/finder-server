<?php

namespace Test\Functional\App\Http\Controllers\V1;

use TestCase;

class CategoryControllerTest extends TestCase
{
    private $category = [
        "name" => "Category"
    ];

    public function setUp()
    {
        parent::setUp();
    }

    /**
     * Tests get all categories
     */
    public function testGetAllCategoriesSuccess()
    {
        $this->get("api/v1/categories");
        $response = json_decode($this->response->getContent(), true);

        $singleCategory = $response["categories"][0];
        $this->assertResponseStatus(200);
        $this->assertTrue(count($response["categories"]) > 0);
        $this->assertArrayHasKey("id", $singleCategory);
        $this->assertArrayHasKey("name", $singleCategory);
        $this->assertArrayHasKey("dateCreated", $singleCategory);
    }

    public function testAddCategorySuccess()
    {
        $this->makeAdmin(11);

        $this->post("api/v1/categories", $this->category);
        $response = json_decode($this->response->getContent(), true);

        $this->assertResponseStatus(201);
        $this->assertEquals($response["name"], "Category");
    }

    public function testAddCategoryFailureNonAdmin()
    {
        $this->makeUser(11);

        $this->post("api/v1/categories", $this->category);
        $response = json_decode($this->response->getContent(), true);

        $this->assertResponseStatus(401);
        $message = "You do not have permission to access this route";
        $this->assertEquals($response["message"], $message);
    }
}
