<?php

namespace App\Tests;
use App\Entity\User;
use App\Repository\Database;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ApiPostTest extends WebTestCase {

    private User $testUser;

    public function __construct() {
        $user = new User();
        $user->setUsername('test');
        $user->setId(2);
        $this->testUser = $user;
    }

    /**
     * Le tearDown se relance à la fin de chacun des tests de la classe.
     * Ici on s'en sert pour relancer le fichier database.sql et donc remettre à 
     * zéro la base de données entre chaque test
     */
    public function tearDown(): void {
        Database::connect()->exec(
            file_get_contents(__DIR__. '/../database.sql')
        );
    }

    public function testGetAllPosts() {
        $client = static::createClient();
        $client->request('GET', '/api/post');

        $json = json_decode( $client->getResponse()->getContent(), true );
        
        $this->assertNotEmpty($json);
        $this->assertCount(10, $json);

        
        $this->assertIsNumeric($json[0]['id']);
        $this->assertIsString($json[0]['content']);
        
        $this->assertResponseIsSuccessful();
    }


    public function testGetAllPostsPage2() {
        $client = static::createClient();
        $client->request('GET', '/api/post?page=2');

        $json = json_decode( $client->getResponse()->getContent(), true );
        
        $this->assertNotEmpty($json);
        $this->assertCount(8, $json);

        
        $this->assertIsNumeric($json[0]['id']);
        $this->assertIsString($json[0]['content']);
        
        $this->assertResponseIsSuccessful();
    }



    public function testAddPost() {
        $client = static::createClient();

        // $client->loginUser();
        $client->request('POST', '/api/post', content: json_encode([
            'content' => 'Test add post'
        ]));
        
        $this->assertResponseIsSuccessful();
        $json = json_decode( $client->getResponse()->getContent(), true );
        

        
        $this->assertIsNumeric($json[0]['id']);
        
        
    }
}