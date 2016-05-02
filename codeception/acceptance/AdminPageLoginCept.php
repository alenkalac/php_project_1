<?php 
$I = new AcceptanceTester($scenario);
$I->wantTo('Check if admin page works');
$I->amOnPage("/");
$I->click('Login');
$I->canSee("Login Form");

$I->wantTo('I want to submit a login form with invalid details');
$I->fillField("username", "admin");
$I->fillField("password", "admin2");
$I->click("login", "input");
$I->canSee("Error!");

$I->wantTo('I want to submit a login form with valid details');
$I->fillField("username", "admin");
$I->fillField("password", "admin");
$I->click("login", "input");
$I->canSeeInTitle("Admin Page");