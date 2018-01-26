<?php

namespace App\Tests\Entity;

use App\Entity\Rig;
use App\Entity\User;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Validator\Validation;

class RigTest extends KernelTestCase
{
    /**
     *
     */
    public function testValidModelData()
    {
        $rig = new Rig();

        $rig->setName('Rig name');
        $rig->setDescription('Rig description');
        $rig->setUser(new User());
        $rig->setHash('qweQWE');
        $rig->setCreatedAt(new DateTime());

        $validator = Validation::createValidatorBuilder()->enableAnnotationMapping()->getValidator();
        $errors = $validator->validate($rig);
        $this->assertEquals(0, count($errors), 'Unexpected errors: ' . print_r($errors, true));
    }
}
