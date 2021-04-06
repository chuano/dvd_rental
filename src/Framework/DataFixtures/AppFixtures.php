<?php

namespace App\Framework\DataFixtures;

use App\Modules\Administration\AdminUser\Domain\AdminUser;
use App\Modules\Administration\Customer\Domain\Customer;
use App\Modules\Administration\Sale\Domain\MovieTitle;
use App\Modules\Administration\Sale\Domain\Sale;
use App\Modules\Rental\User\Domain\User;
use App\Modules\Rental\User\Domain\UserPostalAddress;
use App\Shared\Domain\CompleteName;
use App\Shared\Domain\EmailAddress;
use App\Shared\Domain\Password;
use App\Shared\Domain\Uuid;
use App\Shared\Movie\Domain\Movie;
use App\Shared\Movie\Domain\MovieMetadata;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $this->createUsers($manager);
        $this->createMovies($manager);
        $this->createAdminUsers($manager);
        $manager->flush();
        $this->createSales($manager);
        $manager->flush();
    }

    private function createMovies(ObjectManager $manager): void
    {
        $movie = new Movie(
            Uuid::create(\Ramsey\Uuid\Uuid::uuid4()->toString()),
            new MovieMetadata('Pulp Fiction', 1994, 'Jules y Vincent, dos asesinos a sueldo con no demasiadas...'),
            2
        );
        $manager->persist($movie);
        $movie = new Movie(
            Uuid::create(\Ramsey\Uuid\Uuid::uuid4()->toString()),
            new MovieMetadata('Kill Bill', 2003, 'El día de su boda, una asesina profesional sufre el ataque de...'),
            1
        );
        $manager->persist($movie);
    }

    private function createUsers(ObjectManager $manager): void
    {
        $user = new User(
            Uuid::create(\Ramsey\Uuid\Uuid::uuid4()->toString()),
            CompleteName::create('Name', 'FirstSurname', 'SecondSurname'),
            UserPostalAddress::create('Address', '1', 'City', '03640', 'State'),
            EmailAddress::create('test@domain.com'),
            Password::create('12345678')
        );
        $manager->persist($user);

        // Copy user to customers if not testing. In test mode, the kernel client triggers the event listener
        if (isset($_ENV['APP_ENV']) && $_ENV['APP_ENV'] !== 'test') {
            $customer = new Customer($user->getId(), $user->getCompleteName());
            $manager->persist($customer);
        }
    }

    private function createAdminUsers(ObjectManager $manager): void
    {
        $user = new AdminUser(
            Uuid::create(\Ramsey\Uuid\Uuid::uuid4()->toString()),
            EmailAddress::create('testadmin@domain.com'),
            Password::create('12345678')
        );
        $manager->persist($user);
    }

    private function createSales(ObjectManager $manager): void
    {
        $movie = $manager->getRepository(Movie::class)->findBy([])[0];
        $user = $manager->getRepository(User::class)->findBy([])[0];
        $sale = new Sale(
            Uuid::create(\Ramsey\Uuid\Uuid::uuid4()->toString()),
            new MovieTitle('Kill Bill'),
            $movie->getId(),
            $user->getCompleteName(),
            $user->getId(),
            new \DateTimeImmutable()
        );
        $manager->persist($sale);
    }
}
