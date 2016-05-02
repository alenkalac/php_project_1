<?php 
$I = new AcceptanceTester($scenario);
$I->wantTo('I want to login as a student');
$I->amOnPage("/");
$I->click("Login");
$I->fillField("username", "sample");
$I->fillField("password", "sample");
$I->click("login", "input");
$I->canSeeInTitle("Student Page");
$I->canSee("Welcome");