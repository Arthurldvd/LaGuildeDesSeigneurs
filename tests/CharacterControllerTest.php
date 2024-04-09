<?php

namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class CharacterControllerTest extends WebTestCase
{
    private $client;
    public function setUp(): void
    {
        $this->client = static::createClient();
    }
    public function testDisplay(): void
    {
        // $client->request('POST', '/characters/');
        $this->client->request('GET', '/characters/cc10d47dbcd360f1024c46fb23b93b350cce9469');

        $this->assertJsonResponse($this->client->getResponse());
    }

    public function assertJsonResponse()
    {
        $response = $this->client->getResponse();
        $this->assertResponseIsSuccessful();
        $this->assertTrue($response->headers->contains('Content-Type', 'application/json'), $response->headers);
    }

    public function testIndex()
    {
        $this->client->request('GET', '/characters/');
        $this->assertJsonResponse();
    }

    public function testBadIdentifier()
    {
        $this->client->request('GET', '/characters/badIdentifier');
        $this->assertError404();
    }
    // Asserts that Response returns 404
    public function assertError404()
    {
        $response = $this->client->getResponse();
        $this->assertEquals(404, $response->getStatusCode());
    }

    public function testInexistingIdentifier()
    {
        $this->client->request('GET', '/characters/8f74f20597c5cf99dd42cd31331b7e6e2aeerror');
        $this->assertError404();
    }

}