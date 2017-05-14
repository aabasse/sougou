<?php

namespace ESSABA\AnnonceBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class VoteControllerTest extends WebTestCase
{
    public function testVoterbon()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/voterBon');
    }

}
