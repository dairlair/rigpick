<?php

namespace App\Tests;

use App\Entity\Rig;
use App\Tests\Helper\FunctionalLoginTrait;

class DashboardRigsAddCest
{
    use FunctionalLoginTrait;


    public function tryAddSuccessful (FunctionalTester $I) : void
    {
        $this->login($I, 'user0@gmail.com', 'pass_1234');
        $I->amOnPage('/dashboard/rigs/add');
        $I->see('Add rig', 'h3');
        $I->fillField(['name' => 'rig[name]'], 'Rig #1');
        $I->fillField(['name' => 'rig[description]'], 'Rig number one');
        $I->fillField(['name' => 'rig[power]'], '750');
        $I->click('', '#rig_submit');
        $I->seeCurrentUrlEquals('/dashboard');
        $I->canSeeInRepository(Rig::class, [
            'name' => 'Rig #1',
        ]);
    }
}
