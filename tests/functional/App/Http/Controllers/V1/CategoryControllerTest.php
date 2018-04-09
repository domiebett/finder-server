<?php

use Laravel\Lumen\Testing\DatabaseMigrations;
use Laravel\Lumen\Testing\DatabaseTransactions;

class CategoryControllerTest extends TestCase
{
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
