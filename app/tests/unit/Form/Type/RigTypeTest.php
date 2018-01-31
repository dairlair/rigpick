<?php

namespace App\Tests\Form\Type;

use App\Form\RigType;
use App\Entity\Rig;
use Symfony\Component\Form\Test\TypeTestCase;

/**
 * Class RigTypeTest
 *
 * @group RigType
 */
class RigTypeTest extends TypeTestCase
{
    /**
     * @param array $formData
     *
     * @dataProvider validAttributesProvider
     */
    public function testSubmitValidData(array $formData)
    {
        $rig = new Rig();
        $form = $this->factory->create(RigType::class, $rig);
        $form->submit($formData);

        $this->assertTrue($form->isSynchronized());
        $this->assertTrue($form->isValid());
        $this->assertTrue($form->isSubmitted());
        $this->assertEquals($rig, $form->getData());

        // Validate view
        $view = $form->createView();
        $children = $view->children;
        foreach (array_keys($formData) as $key) {
            $this->assertArrayHasKey($key, $children);
        }
    }

    public function validAttributesProvider()
    {
        return [
            [[
                'name' => 'Rig name',
                'description' => 'Rig description',
            ]],
        ];
    }
}