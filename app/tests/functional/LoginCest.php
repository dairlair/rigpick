<?php

namespace App\Tests;

class LoginCest
{
    public function tryLogin (FunctionalTester $I)
    {
        $I->amOnPage('/');
        $I->click('Log in');
        $I->fillField(['name' => 'login[email]'], 'user0@gmail.com');
        $I->fillField(['name' => 'login[plainPassword]'], 'pass_1234');
        $I->click('Log in');
        $I->seeCurrentUrlEquals('/profile');
        $I->see('Profile', 'main');
    }
}