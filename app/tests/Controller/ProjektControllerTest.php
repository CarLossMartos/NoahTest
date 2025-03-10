<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ProjektControllerTest extends WebTestCase
{
    public function testIndexPageIsSuccessful()
    {
        $client = static::createClient();
        $client->request('GET', '/projekt');

        $this->assertEquals(200, $client->getResponse()->getStatusCode(), 'Die Projekt-Indexseite sollte erfolgreich geladen werden');
        $this->assertStringContainsString('Projekte', $client->getResponse()->getContent());
    }

    public function testShowProject()
    {
        $client = static::createClient();
        $client->request('GET', '/projekt/1');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertStringContainsString('Projekt Details', $client->getResponse()->getContent());
    }
}

