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

        $this->assertObjectHasAttribute("id", $formattedItem);
        $this->assertObjectHasAttribute("name", $formattedItem);
        $this->assertObjectHasAttribute("description", $formattedItem);
        $this->assertObjectHasAttribute("category", $formattedItem);
        $this->assertObjectHasAttribute("finder", $formattedItem);
        $this->assertObjectHasAttribute("owner", $formattedItem);
        $this->assertObjectHasAttribute("found", $formattedItem);
        $this->assertObjectHasAttribute("dateCreated", $formattedItem);
        $this->assertObjectHasAttribute("dateUpdated", $formattedItem);
    }

    /**
     * Test formatUser function
     *
     * @return void
     */
    public function testFormatUser() {
        $user = User::find(1)->first();
        $formattedUser = formatUser($user);

        $this->assertObjectHasAttribute("id", $formattedUser);
        $this->assertObjectHasAttribute("userName", $formattedUser);
        $this->assertObjectHasAttribute("email", $formattedUser);
        $this->assertObjectHasAttribute("firstName", $formattedUser);
        $this->assertObjectHasAttribute("lastName", $formattedUser);
        $this->assertObjectHasAttribute("location", $formattedUser);
        $this->assertObjectHasAttribute("dateCreated", $formattedUser);
        $this->assertObjectHasAttribute("dateUpdated", $formattedUser);
    }
}
