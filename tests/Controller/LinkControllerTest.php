<?php

namespace App\Tests\Controller;

use App\Entity\Link;
use App\Repository\LinkRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Request;

class LinkControllerTest extends WebTestCase
{
    public function testCanGenerateLinkFromValidURL(): void
    {

        $client = static::createClient();

        $client->request(Request::METHOD_POST, '/create', [
            'link' => 'http://yandex.ru'
        ]);

        $this->assertResponseIsSuccessful();
    }
    public function testCannotGenerateLinkFromInvalidURL(): void
    {

        $client = static::createClient();

        $client->request(Request::METHOD_POST, '/create', [
            'link' => 'agag'
        ]);

        $this->assertResponseIsUnprocessable();
    }

    public function testCanGetNotExpiredLink(): void
    {

        $client = static::createClient();

        $crawler = $client->request(Request::METHOD_GET, '/se23EWD');

        $this->assertResponseStatusCodeSame(302);
    }

    public function testCannotGetExpiredLink(): void
    {

        $client = static::createClient();

        $crawler = $client->request(Request::METHOD_GET, '/wewew2D');

        $this->assertResponseStatusCodeSame(404);
    }
}