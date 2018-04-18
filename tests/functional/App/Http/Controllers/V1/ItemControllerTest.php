<?php

namespace Test\Functional\App\Http\Controllers\V1;

use TestCase;

class ItemControllerTest extends TestCase
{
    private $lostItem = [
        "name" => "Lost Item",
        "description" => "This is an item I lost. Please help me",
        "category" => 5,
        "reporter" => "owner"
    ];

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
     * Test that pagination works
     */
    public function testGetLostItemsPaginationSuccess() {
        $this->get("api/v1/items?limit=5&page=2");
        $response = json_decode($this->response->getContent());

        $paginationResponse = $response->pagination;

        $this->assertEquals($paginationResponse->currentPage, 2);
        $this->assertEquals($paginationResponse->totalCount, 40);
        $this->assertEquals($paginationResponse->lastPage, 8);
        $this->assertEquals($paginationResponse->pageSize, 5);
    }

    /**
     * Test that wrong reporter params fail test
     */
    public function testGetLostItemsFailureInvalidReporter()
    {
        $this->get("api/v1/items?reporter=wrongFinder");

        $response = json_decode($this->response->getContent());

        $this->assertResponseStatus(404);

        $errorMessage = "You can only provide 'owner' or 'finder' as reporters";
        $this->assertEquals($errorMessage, $response->message);
    }

    /**
     * Test success when adding a lost item
     */
    public function testAddLostItemSuccess() {
        $this->makeUser(11);

        $this->post("api/v1/items", $this->lostItem);

        $response = json_decode($this->response->getContent(), true);

        $this->assertResponseStatus(201);
        $this->assertEquals($response["lost_item"]["id"], 41);
        $this->assertEquals($response["lost_item"]["name"], "Lost Item");
    }

    /**
     * Test failure if user adds item without logging in
     */
    public function testAddLostItemUnauthorizedFailure() {
        $this->post("api/v1/items", $this->lostItem);

        $response = json_decode($this->response->getContent(), true);
        print_r($this->response->getContent());

        $this->assertResponseStatus(401);

        $message = "You must be logged in to add an item";
        $this->assertEquals($response["message"], $message);
    }
}