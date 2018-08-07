<?php

use App\Models\User;
use App\Models\LostItem;

abstract class TestCase extends Laravel\Lumen\Testing\TestCase
{
    /**
     * Creates the application.
     *
     * @return \Laravel\Lumen\Application
     */
    public function createApplication()
    {
        return require __DIR__.'/../bootstrap/app.php';
    }

    public function setUp()
    {
        parent::setUp();

        $this->artisan("migrate:refresh");
        $this->artisan("db:seed");
    }

    public function makeUser($userId) {
        $this->be(
            factory(\App\Models\User::class)->make(
                [
                    "id" => $userId,
                    "username" => "Dominic Bett",
                    "email" => "dominic@example.com",
                    "location" => "Nairobi",
                    "password" => "password"
                ]
            )
        );
    }

    public function makeAdmin($userId) {
        $this->be(
            factory(\App\Models\User::class)->make(
                [
                    "id" => $userId,
                    "user_name" => "Admin",
                    "email" => "admin@example.com",
                    "password" => "password",
                    "admin" => true
                ]
            )
        );
    }

    /**
     * Delete data from tables
     */
    public function clearTables()
    {
        User::where("id", ">", 0)->forceDelete();
        LostItem::where("id", ">", 0)->forceDelete();
    }
}
