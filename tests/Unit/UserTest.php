<?php

namespace App\Tests\Unit;

use App\Entity\User;
use PHPUnit\Framework\TestCase;

class UserTest extends TestCase
{
    public function testUserCreation(): void
    {
        $user = new User();
        $user->setFirstName('John')
            ->setLastName('Doe')
            ->setEmail('john.doe@example.com')
            ->setPassword('password')
            ->setEstArchive(0);

        $this->assertEquals('John', $user->getFirstName());
        $this->assertEquals('Doe', $user->getLastName());
        $this->assertEquals('john.doe@example.com', $user->getEmail());
        $this->assertEquals(['ROLE_USER'], $user->getRoles());
        $this->assertEquals(0, $user->getEstArchive());
        $this->assertFalse($user->isVerified());
    }

    public function testInvalidEmailThrowsException(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid email address');

        $user = new User();
        $user->setEmail('invalid-email');
    }

    public function testPasswordHashing(): void
    {
        $user = new User();
        $user->setPassword('plainpassword');

        $this->assertNotEquals('plainpassword', $user->getPassword());
        $this->assertTrue(password_verify('plainpassword', $user->getPassword()));
    }

    public function testDefaultRoleIsUser(): void
    {
        $user = new User();
        $this->assertEquals(['ROLE_USER'], $user->getRoles());
    }

    public function testSetAndGetRoles(): void
    {
        $user = new User();
        $user->setRoles(['ROLE_ADMIN', 'ROLE_MANAGER']);

        $this->assertEquals(['ROLE_ADMIN', 'ROLE_MANAGER'], $user->getRoles());
    }

    public function testSetEstArchive(): void
    {
        $user = new User();
        $user->setEstArchive(1);

        $this->assertEquals(1, $user->getEstArchive());
    }

    public function testVerificationStatus(): void
    {
        $user = new User();
        $this->assertFalse($user->isVerified());

        $user->setIsVerified(true);
        $this->assertTrue($user->isVerified());
    }
}