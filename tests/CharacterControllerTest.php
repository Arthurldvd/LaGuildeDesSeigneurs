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
    public function testCreate()
    {
        $this->client->request(
                       'POST',
                       '/characters/',
                       array(),// Parameters
                       array(),// Files
                       array('CONTENT_TYPE' => 'application/json'),// Server
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
        // $client->request('POST', '/characters/');
        $this->client->request('GET', '/characters/' . self::$identifier);
        $this->assertResponseCode(200);
        $this->assertJsonResponse();
        $this->assertIdentifier();

        $this->assertJsonResponse($this->client->getResponse());
    }

    public function assertJsonResponse()
    {
        $response = $this->client->getResponse();
        $this->content = json_decode($response->getContent(), true, 50);
        $this->assertResponseIsSuccessful();
        $this->assertTrue($response->headers->contains('Content-Type', 'application/json'), $response->headers);
    }

    // public function testIndex()
    // {
    //  $this->client->request('GET', '/buildings/');
    //     $this->assertResponseCode(200);
    //     $this->assertJsonResponse();
    // }

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
        $this->client->request(
                      'PUT',
                      '/characters/' . self::$identifier,
                      array(),// Parameters
                      array(),// Files
                      array('CONTENT_TYPE' => 'application/json'),// Server
                      <<<JSON
                      {
                          "kind": "Seigneur",
                          "name": "Gorthol",
                          "surname": "Heaume de terreur",
                          "caste": "Chevalier",
                          "knowledge": "Diplomatie",
                          "intelligence": 140,
                          "strength": 140,
                          "image": "/seigneurs/gorthol.jpg"
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
        $this->client->request('DELETE', '/characters/' . self::$identifier);
        $this->assertResponseCode(204);
    }
}