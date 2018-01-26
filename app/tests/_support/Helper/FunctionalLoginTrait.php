<?php

namespace App\Tests\Helper;

use App\Tests\FunctionalTester;

trait FunctionalLoginTrait
{
    protected function login (FunctionalTester $I, string $email, string $password = '') : void
    {
        $I->amOnPage('/login');
        $I->fillField(['name' => 'user[email]'], $email);
        $I->fillField(['name' => 'user[plainPassword]'], $password);
        $I->click('', '#user_submit');
        $I->seeCurrentUrlEquals('/dashboard');
        $I->see('Dashboard', 'h1');
    }

}
