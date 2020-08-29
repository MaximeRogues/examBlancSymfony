<?php

namespace App\DataFixtures;

use App\Entity\Employee;
use App\Repository\SecteurRepository;
use Doctrine\Bundle\FixturesBundle\Fixture;
use App\DataFixtures\SectorFixtures;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;


class EmployeeFixtures extends Fixture implements DependentFixtureInterface
{

    private $sectorRepository;

    public function __construct(SecteurRepository $sectorRepository, UserPasswordEncoderInterface $encoder)
    {
        $this->sectorRepository = $sectorRepository;
        $this->encoder = $encoder;

    }

    public function getDependencies()
    {
        return [
            SectorFixtures::class
        ];
    }
     
    public function load(ObjectManager $manager)
    {

        $sectors = $this->sectorRepository->findAll();

        $firstNames = ['Camille', 'Coralie', 'Tarik', 'Jonathan', 'Ferdi', 'Maxime', 'Amélie', 'Yves-Marie', 'Pierre-Henri', 'Sohaib', 'William', 'Nathan', 'Thibault', 'Astrid', 'Laurie', 'Roland', 'Quentin', 'Aurélien'];
        $lastNames = ['Mathieu', 'Faure', 'Louatah', 'Lopez', 'Celik', 'Rogues', 'Marduel', 'Chorel', 'Laurency', 'Zeghouani', 'Boulhol', 'Krewcun', 'Renouat', 'Soulier', 'Villeneuve', 'Cornet', 'Chevalier', 'Delorme'];

        // ADMIN
        $admin = new Employee();
        $admin->setEmail('admin@deloitte.com');
        $admin->setFirstName('Je suis');
        $admin->setLastName('l\'admin');
        $admin->setRedefinedPassword(true);
        $admin->setSector($sectors[3]);
        $admin->setPicture('photo.jpg');
        $admin->setRoles(['ROLE_ADMIN', 'IS_ANONYMOUS']);

        $password = $this->encoder->encodePassword($admin, 'admin123@');
        $admin->setPassword($password);
        
        $manager->persist($admin);

        $manager->flush();

        // EMPLOYEES
        for ($i = 0; $i < 50; $i++) {
            $employee = new Employee();
            $employee->setFirstName($firstNames[rand(0, 17)]);
            $employee->setLastName($lastNames[rand(0, 17)]);
            $employee->setEmail(rand(1,99999) . '@deloitte.com');
            $employee->setRedefinedPassword(false);
            $employee->setSector($sectors[rand(0, 3)]);
            $employee->setPicture('photo.jpg');
            $employee->setRoles(['ROLE_USER', 'IS_ANONYMOUS']);

            $password = $this->encoder->encodePassword($employee, 'Severus');
            $employee->setPassword($password);

            $manager->persist($employee);
        }
        $manager->flush();

        
    }

    
}
