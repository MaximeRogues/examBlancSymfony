<?php

namespace App\DataFixtures;

use App\Entity\Admin;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AdminFixtures extends Fixture 
{

    public function __construct( UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;

    }

    public function load(ObjectManager $manager)
    {
        $admin = new Admin();
        $admin->setEmail('admin@deloitte.com');
        
        $password = $this->encoder->encodePassword($admin, 'admin123@');
        $admin->setPassword($password);
        
        $admin->setRoles(['ROLE_ADMIN', 'IS_ANONYMOUS']);

        $manager->persist($admin);
        $manager->flush();
    }
}