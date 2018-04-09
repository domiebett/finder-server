<?php

use Laravel\Lumen\Testing\DatabaseMigrations;
use Laravel\Lumen\Testing\DatabaseTransactions;

class AuthControllerTest extends TestCase
{
    private $user = [
        "name" => "Dominic Bett",
        "email" => "dominic@example.com",
        "password" => "password"
    ];

    /**
     * Test user registration success
     */
    public function testRegisterUserSuccess() {
        $this->post("api/v1/auth/register", $this->user);
        $response = json_decode($this->response->getContent(), true);

        $this->assertResponseStatus(200);
        $this->assertArrayHasKey("token", $response);
        $this->assertArrayHasKey("user", $response);

        $this->assertEquals($response["user"]["email"], "dominic@example.com");
    }

    /**
     * Test existent user registration failure
     */
    public function testRegisterExistentUserFailure() {
        $this->post("api/v1/auth/register", $this->user);
        $this->post("api/v1/auth/register", $this->user);

        $this->assertResponseStatus(400);
    }

    /**
     * Test user login success
     */
    public function testLoginUserSuccess() {
        $this->post("api/v1/auth/register", $this->user);
        $this->post("api/v1/auth/login", $this->user);
        $response = json_decode($this->response->getContent(), true);

        $this->assertResponseStatus(200);
        $this->assertArrayHasKey("token", $response);
        $this->assertArrayHasKey("user", $response);

        $this->assertEquals($response["user"]["email"], "dominic@example.com");
    }

    /**
     * Test unregistered user login failure
     */
    public function testLoginUnregisteredFailure() {
        $this->post("api/v1/auth/login", $this->user);
        $response = json_decode($this->response->getContent(), true);

        print_r($response);

        $this->assertResponseStatus(404);
    }
}
