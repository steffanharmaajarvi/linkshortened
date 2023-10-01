<?php

namespace App\DataFixtures;


use App\Entity\Link;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class LinkFixture extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $link = new Link();
        $link->setUrl('http://yandex.ru');
        $link->setToken('se23EWD');
        $link->setCreatedAt(new \DateTime('2029-01-01'));
        $manager->persist($link);

        $link = new Link();
        $link->setUrl('http://yandex.ru');
        $link->setToken('wewew2D');
        $link->setCreatedAt(new \DateTime('2019-01-01'));
        $manager->persist($link);

        $manager->flush();
    }
}