<?php

namespace Test\Unit\App\Helpers;

use TestCase;
use App\Models\LostItem;
use App\Models\User;

class FormatHelperTest extends TestCase
{
    /**
     * Test formatItems function
     *
     * @return void
     */
    public function testFormatItems() {
        $lostItem = LostItem::find(1)->first();
        $formattedItem =  formatItem($lostItem);

        $this->assertArrayHasKey("id", $formattedItem);
        $this->assertArrayHasKey("name", $formattedItem);
        $this->assertArrayHasKey("description", $formattedItem);
        $this->assertArrayHasKey("category", $formattedItem);
        $this->assertArrayHasKey("finder", $formattedItem);
        $this->assertArrayHasKey("owner", $formattedItem);
        $this->assertArrayHasKey("found", $formattedItem);
        $this->assertArrayHasKey("dateCreated", $formattedItem);
        $this->assertArrayHasKey("dateUpdated", $formattedItem);
        $this->assertArrayHasKey("images", $formattedItem);
    }

    /**
     * Test formatUser function
     *
     * @return void
     */
    public function testFormatUser() {
        $user = User::find(1)->first();
        $formattedUser = formatUser($user);

        $this->assertArrayHasKey("id", $formattedUser);
        $this->assertArrayHasKey("username", $formattedUser);
        $this->assertArrayHasKey("email", $formattedUser);
        $this->assertArrayHasKey("location", $formattedUser);
    }
}
