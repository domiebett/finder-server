<?php

namespace Test\App\Http\Controllers\V2;

use TestCase;

class ItemControllerTest extends TestCase
{
    public function setUp()
    {
        parent::setUp();
    }

    /**
     * Test that items are gotten succesfully
     */
    public function testGetLostItemsSuccess()
    {
        $this->get("api/v1/items");

        $response = json_decode($this->response->getContent());

        $this->assertResponseStatus(200);
        $this->assertEquals(count($response->items), 20);

        $singleItem = $response->items[0];

        $this->assertObjectHasAttribute("id", $singleItem);
        $this->assertObjectHasAttribute("name", $singleItem);
        $this->assertObjectHasAttribute("category", $singleItem);
        $this->assertObjectHasAttribute("finder", $singleItem);
        $this->assertObjectHasAttribute("owner", $singleItem);
        $this->assertObjectHasAttribute("found", $singleItem);
        $this->assertObjectHasAttribute("dateCreated", $singleItem);
        $this->assertObjectHasAttribute("dateUpdated", $singleItem);
    }

    /**
     * Test that wrong params fail test
     */
    public function testGetLostItemsFailureInvalidReporter()
    {
        $this->get("api/v1/items?reporter=wrongFinder");
        $this->assertResponseStatus(400);
    }
}