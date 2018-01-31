<?php

namespace App\Tests;

class AuthCest
{
    public function tryLogin (FunctionalTester $I) : void
    {
        $I->amOnPage('/');
        $I->click('Log in');
        $I->fillField(['name' => 'user[email]'], 'user0@gmail.com');
        $I->fillField(['name' => 'user[plainPassword]'], 'pass_1234');
        $I->click('', '#user_submit');
        $I->seeCurrentUrlEquals('/dashboard');
        $I->see('Dashboard', 'h1');
    }

    public function trySignUp (FunctionalTester $I) : void
    {
        $I->amOnPage('/');
        $I->click('Join now');
        $I->fillField(['name' => 'user[email]'], 'test.sign.up.1@gmail.com');
        $I->fillField(['name' => 'user[plainPassword]'], 'qwerty');
        $I->click('', '#user_submit');
        $I->seeCurrentUrlEquals('/dashboard');
        $I->see('Dashboard', 'h1');
    }
}
