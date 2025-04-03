<?php

namespace App\Tests\Entity;

use App\Entity\User;
use PHPUnit\Framework\TestCase;

class UserTest extends TestCase
{
    public function testGettersAndSetters(): void
    {
        $user = new User();
        
        $this->assertNull($user->getId());

        $email = "test@example.com";
        $user->setEmail($email);
        $this->assertSame($email, $user->getEmail());

        $password = "hashed_password";
        $user->setPassword($password);
        $this->assertSame($password, $user->getPassword());

        $plainPassword = "plain_password";
        $user->setPlainPassword($plainPassword);
        $this->assertSame($plainPassword, $user->getPlainPassword());
        
        $user->eraseCredentials();
        $this->assertNull($user->getPlainPassword());

        $this->assertEquals(["ROLE_USER"], $user->getRoles()); // ROLE_USER est ajouté par défaut
        $user->setRoles(["ROLE_ADMIN"]);
        $this->assertSame(["ROLE_ADMIN", "ROLE_USER"], $user->getRoles()); 
    }
}
