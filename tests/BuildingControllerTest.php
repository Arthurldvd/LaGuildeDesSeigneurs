<?php

namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class BuildingControllerTest extends WebTestCase
{
    private $content; // Contenu de la rÃ©ponse
    private static $identifier; // Identifier du Building
    private $client;
    public function setUp(): void
    {
        $this->client = static::createClient();
    }
    public function testCreate()
    {
         $this->client->request(
            'POST',
            '/buildings/',
            array(),// Parameters
            array(),// Files
            array('CONTENT_TYPE' => 'application/json'),// Server
            <<<JSON
            {
            "name": "Château Silken",
            "caste": "Archer",
            "image": "/buildings/chateau-silken.webp",
            "strength": 1200
            }
            JSON
            );
        $this->assertResponseCode(201);
        $this->assertJsonResponse();
        $this->defineIdentifier();
        $this->assertIdentifier();
    }

    public function testDisplay(): void
    {
        $this->client->request('GET', '/buildings/' . self::$identifier);
        $this->assertResponseCode(200);
        $this->assertJsonResponse();
        $this->assertIdentifier();

        $this->assertJsonResponse();
    }

    public function assertJsonResponse(): void
    {
        $response = $this->client->getResponse();
        $this->content = json_decode($response->getContent(), true, 50);
        $this->assertResponseIsSuccessful();
        $this->assertTrue($response->headers->contains('Content-Type', 'application/json'), $response->headers);
    }

    public function testIndex(): void
    {
         // Tests with default values
        $this->client->request('GET', '/buildings/');
        $this->assertResponseCode(200);
        $this->assertJsonResponse();
        // Tests with page
        $this->client->request('GET', '/buildings/?page=1');
        $this->assertResponseCode(200);
        $this->assertJsonResponse();
        // Tests with page and size
        $this->client->request('GET', '/buildings/?page=1&size=1');
        $this->assertResponseCode(200);
        $this->assertJsonResponse();
        // Tests with size
        $this->client->request('GET', '/buildings/?size=1');
        $this->assertResponseCode(200);
        $this->assertJsonResponse();
    }

    public function testBadIdentifier(): void
    {
        $this->client->request('GET', '/buildings/badIdentifier');
        $this->assertError404();
    }
    // Asserts that Response returns 404
    public function assertError404(): void
    {
        $response = $this->client->getResponse();
        $this->assertEquals(404, $response->getStatusCode());
    }

    public function testInexistingIdentifier(): void
    {
        $this->client->request('GET', '/buildings/8f74f20597c5cf99dd42cd31331b7e6e2aeerror');
        $this->assertError404();
    }

    public function assertIdentifier()
    {
        $this->assertArrayHasKey('identifier', $this->content);
    }
    // Defines identifier
    public function defineIdentifier()
    {
        self::$identifier = $this->content['identifier'];
    }

    public function testUpdate()
    {
       // Tests partial content
 $this->client->request(
    'PUT',
    '/buildings/' . self::$identifier,
    array(),// Parameters
    array(),// Files
    array('CONTENT_TYPE' => 'application/json'),// Server
    <<<JSON
    {
    "name": "Château Oakenfield",
    "caste": "Erudit"
    }
    JSON
    );
        $this->assertResponseCode(204);
    }
    // Asserts that Response code is equal to $code
    public function assertResponseCode(int $code)
    {
        $response = $this->client->getResponse();
        $this->assertEquals($code, $response->getStatusCode());
    }

    public function testDelete()
    {
        $this->client->request('DELETE', '/buildings/' . self::$identifier);
        $this->assertResponseCode(204);
    }
}