<?php 
$I = new TestGuy($scenario);

$I->wantTo('make the Create Post Form Validation fail.');
$I->wantToTest('if App::error() Exception Handling is working with Codeception.');
$I->expect('being redirected back with error messages.');

$I->amOnPage('/posts/create');
$I->see('Create Post');
$I->fillField('title', '');
$I->fillField('body', '');
$I->click('Submit');
$I->seeCurrentUrlEquals('/posts/create');
$I->see('There were validation errors.');
