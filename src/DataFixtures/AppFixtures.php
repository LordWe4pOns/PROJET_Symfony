<?php

namespace App\DataFixtures;

use App\Entity\Country;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    private ?UserPasswordHasherInterface $passwordHasher = null;

    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }

    public function load(ObjectManager $manager): void
    {
        // alimentation country
        $country1 = new Country();
        $country1
            ->setName('France')
            ->setCode('FR');
        $manager->persist($country1);

        $country2 = new Country();
        $country2
            ->setName('Allemagne')
            ->setCode('DE');
        $manager->persist($country2);

        $country3 = new Country();
        $country3
            ->setName('Espagne')
            ->setCode('ES');
        $manager->persist($country3);

        $country4 = new Country();
        $country4
            ->setName('Angleterre')
            ->setCode('EN');
        $manager->persist($country4);

        $country5 = new Country();
        $country5
            ->setName('Suisse')
            ->setCode('SW');
        $manager->persist($country5);

        $country6 = new Country();
        $country6
            ->setName('Belgique')
            ->setCode('BE');
        $manager->persist($country6);

        $country7 = new Country();
        $country7
            ->setName('Italie')
            ->setCode('IT');
        $manager->persist($country7);

        $country8 = new Country();
        $country8
            ->setName('Portugal')
            ->setCode('PT');
        $manager->persist($country8);

        $country9 = new Country();
        $country9
            ->setName('Bordeciel')
            ->setCode('BO');
        $manager->persist($country9);

        $country10 = new Country();
        $country10
            ->setName('Kalimdor')
            ->setCode('KD');
        $manager->persist($country10);

        $country11 = new Country();
        $country11
            ->setName('Royaumes de l\'Est')
            ->setCode('RE');
        $manager->persist($country11);

        $country12 = new Country();
        $country12
            ->setName('Norfendre')
            ->setCode('NF');
        $manager->persist($country12);

        $country13 = new Country();
        $country13
            ->setName('Drangleic')
            ->setCode('DL');
        $manager->persist($country13);

        $country14 = new Country();
        $country14
            ->setName('Lothric')
            ->setCode('LO');
        $manager->persist($country14);

        $country15 = new Country();
        $country15
            ->setName('Lordran')
            ->setCode('LR');
        $manager->persist($country15);

        // alimentation user

        $user1 = new User();
        $user1
            ->setLogin('sadmin')
            ->setPassword($this->passwordHasher->hashPassword($user1, 'nimdas'))
            ->setName('Spectre')
            ->setSurname('Supreme')
            ->setBirthday(date_create("1970-01-01"))
            ->setRoles(['ROLE_SUPER_ADMIN'])
            ->setCountry($country1);
        $manager->persist($user1);

        $user2 = new User();
        $user2
            ->setLogin('gilles')
            ->setPassword($this->passwordHasher->hashPassword($user2, 'sellig'))
            ->setName('Gilles')
            ->setSurname('Subrenat')
            ->setRoles(['ROLE_ADMIN'])
            ->setCountry($country1);
        $manager->persist($user2);

        $user3 = new User();
        $user3
            ->setLogin('rita')
            ->setPassword($this->passwordHasher->hashPassword($user2,'atir'))
            ->setName('Rita')
            ->setSurname('Zrour')
            ->setRoles(['ROLE_USER'])
            ->setCountry($country1);
        $manager->persist($user3);

        $user4 = new User();
        $user4
            ->setLogin('boumediene')
            ->setPassword($this->passwordHasher->hashPassword($user2,'eneidemuob'))
            ->setName('Boumediene')
            ->setSurname('Saidi')
            ->setRoles(['ROLE_USER'])
            ->setCountry($country1);
        $manager->persist($user4);

        $user5 = new User();
        $user5
            ->setLogin('simon')
            ->setPassword($this->passwordHasher->hashPassword($user2,'nomis'))
            ->setName('Simon')
            ->setSurname('Cossais')
            ->setBirthday(date_create("2002-11-22"))
            ->setRoles(['ROLE_ADMIN'])
            ->setCountry($country9);
        $manager->persist($user5);

        $user6 = new User();
        $user6
            ->setLogin('paul')
            ->setPassword($this->passwordHasher->hashPassword($user2,'luap'))
            ->setName('Paul')
            ->setSurname('Sarazin')
            ->setBirthday(date_create("2003-12-01")) // je sais plus quel jour dsl
            ->setRoles(['ROLE_ADMIN'])
            ->setCountry($country1);
        $manager->persist($user6);

        $manager->flush();
    }
}
