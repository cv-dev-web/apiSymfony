<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    private $manager;
    private $faker;
    public function __construct()
    {
$this-> faker=Factory :: create("fr_fr");
    }
    public function load(ObjectManager $manager): void
    {
        // $product = new Product();
        // $manager->persist($product);
        $this->manager=$manager;
        $manager->flush();
    }
}
