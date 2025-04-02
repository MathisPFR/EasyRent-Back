<?php

namespace App\Tests\Entity;

use App\Entity\Bien;
use App\Entity\User;
use PHPUnit\Framework\TestCase;

class BienTest extends TestCase
{
    public function testGettersAndSetters(): void
    {
        $bien = new Bien();
        $user = new User();

        $bien->setTitre('Appartement T3');
        $this->assertEquals('Appartement T3', $bien->getTitre());

        $bien->setAdresse('10 Rue de la Paix');
        $this->assertEquals('10 Rue de la Paix', $bien->getAdresse());

        $bien->setSurface(75.5);
        $this->assertEquals(75.5, $bien->getSurface());

        $bien->setType('Appartement');
        $this->assertEquals('Appartement', $bien->getType());

        $bien->setLoyer(850.0);
        $this->assertEquals(850.0, $bien->getLoyer());

        $bien->setUsers($user);
        $this->assertEquals($user, $bien->getUsers());
    }

    public function testIdIsInitiallyNull(): void
    {
        $bien = new Bien();
        $this->assertNull($bien->getId());
    }

    public function testCanSetAndGetId(): void
    {
        $bien = new Bien();
        $reflection = new \ReflectionClass($bien);
        $property = $reflection->getProperty('id');
        $property->setAccessible(true);
        $property->setValue($bien, 123);

        $this->assertEquals(123, $bien->getId());
    }
}