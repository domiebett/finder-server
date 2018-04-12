<?php

namespace Test\Functional\App\Http\Controllers\V1;

use TestCase;

class CategoryControllerTest extends TestCase
{
    public function setUp() {
        parent::setUp();
    }

    /**
     * Tests get all categories
     */
    public function testGetAllCategoriesSuccess() {
        $this->get("api/v1/categories");
        $response = json_decode($this->response->getContent(), true);

        $singleCategory = $response["categories"][0];
        $this->assertResponseStatus(200);
        $this->assertTrue(count($response["categories"]) > 0);
        $this->assertArrayHasKey("id", $singleCategory);
        $this->assertArrayHasKey("name", $singleCategory);
        $this->assertArrayHasKey("created_at", $singleCategory);
    }
}
