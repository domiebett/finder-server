<?php

namespace Test\Functional\App\Http\Controllers\V1;

use TestCase;

class AuthControllerTest extends TestCase
{
    private $user = [
        "user_name" => "Dominic Bett",
        "email" => "dominic@example.com",
        "password" => "password"
    ];

    public function setUp() {
        parent::setUp();
    }

    /**
     * Test user registration success
     */
    public function testRegisterUserSuccess() {
        $this->post("api/v1/auth/signup", $this->user);
        $response = json_decode($this->response->getContent(), true);

        $this->assertResponseOk();

        $this->assertEquals($response["message"], "Successful sign up. Please login");
    }

    /**
     * Test existent user registration failure
     */
    public function testRegisterExistentUserFailure() {
        $this->post("api/v1/auth/signup", $this->user);
        $this->post("api/v1/auth/signup", $this->user);

        $this->assertResponseStatus(400);
    }

    /**
     * Test user login success
     */
    public function testLoginUserSuccess() {
        $this->post("api/v1/auth/signup", $this->user);
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

        $this->assertResponseStatus(401);
        $this->assertEquals("The email or password is wrong", $response["message"]);
    }
}
