<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class UserControllerTest extends WebTestCase
{
    public function testLoginPageLoads()
    {
        $client = static::createClient();
        $client->request('GET', '/login');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertStringContainsString('Login', $client->getResponse()->getContent());
    }

    public function testRegisterUser()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/register');

        $form = $crawler->selectButton('Register')->form();
        $form['registration_form[username]'] = 'neuerUser';
        $form['registration_form[email]']    = 'user@example.com';
        $form['registration_form[password]'] = 'sicheresPasswort';

        $client->submit($form);

        $this->assertTrue($client->getResponse()->isRedirect(), 'Nach der Registrierung sollte umgeleitet werden');
    }
}
