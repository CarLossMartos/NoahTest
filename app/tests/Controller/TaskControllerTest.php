<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class TaskControllerTest extends WebTestCase
{
    public function testIndexPageIsSuccessful()
    {
        $client = static::createClient();
        $client->request('GET', '/task');

        $this->assertEquals(200, $client->getResponse()->getStatusCode(), 'Die Task-Indexseite sollte erfolgreich geladen werden');
        $this->assertStringContainsString('Task', $client->getResponse()->getContent());
    }

    public function testShowTask()
    {
        $client = static::createClient();
        $client->request('GET', '/task/1');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertStringContainsString('Task Details', $client->getResponse()->getContent());
    }
}
