<?php

namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class CharacterControllerTest extends WebTestCase
{
    private $content; // Contenu de la réponse
    private static $identifier; // Identifier du Character
    private $client;
    public function setUp(): void
    {
        $this->client = static::createClient();
    }
    public function testCreate(): void
    {
        $this->client->request(
            'POST',
            '/characters/',
            array(),// Parameters
            array(),// Files
            array('CONTENT_TYPE' => 'application/json'),
            <<<JSON
            {
                "kind": "Dame",
                "name": "Maeglin",
                "surname": "Oeil vif",
                "caste": "Archer",
                "knowledge": "Nombres",
                "intelligence": 120,
                "strength": 120,
                "image": "/dames/maeglin.webp"
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
        $this->client->request('GET', '/characters/' . self::$identifier);
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
        $this->client->request('GET', '/characters/');
        $this->assertResponseCode(200);
        $this->assertJsonResponse();

//        // Tests with page
//        $this->client->request('GET', '/characters/?page=1');
//        $this->assertResponseCode(200);
//        $this->assertJsonResponse();
//
//        // Tests with page and size
//        $this->client->request('GET', '/characters/?page=1&size=1');
//        $this->assertResponseCode(200);
//        $this->assertJsonResponse();
//
//        // Tests with size
//        $this->client->request('GET', '/characters/?size=1');
//        $this->assertResponseCode(200);
//        $this->assertJsonResponse()
    }

    public function testBadIdentifier(): void
    {
        $this->client->request('GET', '/characters/badIdentifier');
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
        $this->client->request('GET', '/characters/8f74f20597c5cf99dd42cd31331b7e6e2aeerror');
        $this->assertError404();
    }

    public function assertIdentifier(): void
    {
        $this->assertArrayHasKey('identifier', $this->content);
    }
    // Defines identifier
    public function defineIdentifier(): void
    {
        self::$identifier = $this->content['identifier'];
    }

    public function testUpdate(): void
    {
        $this->client->request(
            'PUT',
        '/characters/' . self::$identifier,
            array(),
            array(),
            array('CONTENT_TYPE' => 'application/json'),
            <<<JSON
            {
                "kind": "Seigneur",
                "name": "Gorthol"
            }
            JSON
        );

        $this->assertResponseCode(204);
    }
    // Asserts that Response code is equal to $code
    public function assertResponseCode(int $code): void
    {
        $response = $this->client->getResponse();
        $this->assertEquals($code, $response->getStatusCode());
    }

    public function testDelete(): void
    {
        $this->client->request('DELETE', '/characters/' . self::$identifier);
        $this->assertResponseCode(204);
    }

    // Tests images
    public function testImages()
    {
        //Tests without kind
        $this->client->request('GET', '/characters/images');
        $this->assertJsonResponse();
        $this->client->request('GET', '/characters/images/3');
        $this->assertJsonResponse();
    }

    // Tests images
    public function testImagesByKind()
    {
        //Tests without kind
        $this->client->request('GET', '/characters/images/dames');
        $this->assertJsonResponse();
        $this->client->request('GET', '/characters/images/dames/3');
        $this->assertJsonResponse();
    }
}