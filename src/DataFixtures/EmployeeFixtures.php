<?php

namespace App\DataFixtures;

use App\Entity\Employee;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class EmployeeFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        // create 2 Employees with predefined passwords

            $employee = new Employee();
            $employee->setEmail('employee@mail.com');
            $employee->setRoles(array('ROLE_EDITOR'));
            $employee->setPassword('$2y$13$DTwFLXYOn6NU5FiajKSvfuDbQa4.4cY5F8Be6NPydVp8fHDQP4L4O');
            $manager->persist($employee);

            $boss = new Employee();
            $boss->setEmail('boss@mail.com');
            $boss->setRoles(array('ROLE_EDITOR'));
            $boss->setPassword('$2y$13$DTwFLXYOn6NU5FiajKSvfuDbQa4.4cY5F8Be6NPydVp8fHDQP4L4O');
            $manager->persist($boss);


        $manager->flush();
    }
}
