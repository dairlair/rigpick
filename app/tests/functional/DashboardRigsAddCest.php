<?php

namespace App\Tests;

use App\Entity\Rig;
use App\Tests\Helper\FunctionalLoginTrait;
use Codeception\Example;

class DashboardRigsAddCest
{
    use FunctionalLoginTrait;

    /**
     * @param FunctionalTester $I
     * @param Example $example
     *
     * @example ["Rig #1", "Rig number one", "750"]
     * @example ["Rig #2", "Rig number two", null]
     */
    public function tryAddSuccessful (FunctionalTester $I, Example $example = null) : void
    {
        [$name, $description, $power] = $example;
        $this->login($I, 'user0@gmail.com', 'pass_1234');
        $I->amOnPage('/dashboard/rigs/add');
        $I->see('Add rig', 'h3');

        // Check fields
        $I->seeElement('input', ['name' => 'rig[name]', 'required' => 'required']);
        $I->seeElement('input', ['name' => 'rig[description]', 'required' => 'required']);
        $I->seeElement('input', ['name' => 'rig[power]', 'required' => null]);

        // Fill fields
        $I->fillField(['name' => 'rig[name]'], $name);
        $I->fillField(['name' => 'rig[description]'], $description);
        $I->fillField(['name' => 'rig[power]'], $power);
        $I->click('', '#rig_submit');
        $I->seeCurrentUrlEquals('/dashboard');
        $I->canSeeInRepository(Rig::class, [
            'name' => $name,
        ]);
    }
}
