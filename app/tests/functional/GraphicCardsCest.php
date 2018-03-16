<?php

namespace App\Tests;

/**
 * Class GraphicCardsCest
 * @package App\Tests
 *
 * @group GraphicCardsCest
 */
class GraphicCardsCest
{
    public function checkIndex (FunctionalTester $I) : void
    {
        $I->amOnPage('/graphic-cards');
        $I->see('Mining Graphic Cards Rating', 'h1');
        $I->canSeeElement('article.graphic-card a');
        $I->click('article.graphic-card a');
        $I->seeInCurrentRoute('graphic_card');
        $I->seeElement('article.graphic-card h1');
    }
}
