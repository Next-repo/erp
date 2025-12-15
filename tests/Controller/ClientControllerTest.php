<?php

namespace App\Tests\Controller;

use App\Entity\Client;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

final class ClientControllerTest extends WebTestCase
{
    private KernelBrowser $client;
    private EntityManagerInterface $manager;
    private EntityRepository $clientRepository;
    private string $path = '/client/';

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->manager = static::getContainer()->get('doctrine')->getManager();
        $this->clientRepository = $this->manager->getRepository(Client::class);

        foreach ($this->clientRepository->findAll() as $object) {
            $this->manager->remove($object);
        }

        $this->manager->flush();
    }

    public function testIndex(): void
    {
        $this->client->followRedirects();
        $crawler = $this->client->request('GET', $this->path);

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Client index');

        // Use the $crawler to perform additional assertions e.g.
        // self::assertSame('Some text on the page', $crawler->filter('.p')->first()->text());
    }

    public function testNew(): void
    {
        $this->markTestIncomplete();
        $this->client->request('GET', sprintf('%snew', $this->path));

        self::assertResponseStatusCodeSame(200);

        $this->client->submitForm('Save', [
            'client[nom]' => 'Testing',
            'client[type]' => 'Testing',
            'client[email]' => 'Testing',
            'client[telephone]' => 'Testing',
            'client[created]' => 'Testing',
            'client[updated]' => 'Testing',
            'client[createdBy]' => 'Testing',
        ]);

        self::assertResponseRedirects($this->path);

        self::assertSame(1, $this->clientRepository->count([]));
    }

    public function testShow(): void
    {
        $this->markTestIncomplete();
        $fixture = new Client();
        $fixture->setNom('My Title');
        $fixture->setType('My Title');
        $fixture->setEmail('My Title');
        $fixture->setTelephone('My Title');
        $fixture->setCreated('My Title');
        $fixture->setUpdated('My Title');
        $fixture->setCreatedBy('My Title');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Client');

        // Use assertions to check that the properties are properly displayed.
    }

    public function testEdit(): void
    {
        $this->markTestIncomplete();
        $fixture = new Client();
        $fixture->setNom('Value');
        $fixture->setType('Value');
        $fixture->setEmail('Value');
        $fixture->setTelephone('Value');
        $fixture->setCreated('Value');
        $fixture->setUpdated('Value');
        $fixture->setCreatedBy('Value');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s/edit', $this->path, $fixture->getId()));

        $this->client->submitForm('Update', [
            'client[nom]' => 'Something New',
            'client[type]' => 'Something New',
            'client[email]' => 'Something New',
            'client[telephone]' => 'Something New',
            'client[created]' => 'Something New',
            'client[updated]' => 'Something New',
            'client[createdBy]' => 'Something New',
        ]);

        self::assertResponseRedirects('/client/');

        $fixture = $this->clientRepository->findAll();

        self::assertSame('Something New', $fixture[0]->getNom());
        self::assertSame('Something New', $fixture[0]->getType());
        self::assertSame('Something New', $fixture[0]->getEmail());
        self::assertSame('Something New', $fixture[0]->getTelephone());
        self::assertSame('Something New', $fixture[0]->getCreated());
        self::assertSame('Something New', $fixture[0]->getUpdated());
        self::assertSame('Something New', $fixture[0]->getCreatedBy());
    }

    public function testRemove(): void
    {
        $this->markTestIncomplete();
        $fixture = new Client();
        $fixture->setNom('Value');
        $fixture->setType('Value');
        $fixture->setEmail('Value');
        $fixture->setTelephone('Value');
        $fixture->setCreated('Value');
        $fixture->setUpdated('Value');
        $fixture->setCreatedBy('Value');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));
        $this->client->submitForm('Delete');

        self::assertResponseRedirects('/client/');
        self::assertSame(0, $this->clientRepository->count([]));
    }
}
