<?php 
$I = new AcceptanceTester($scenario);
$I->wantTo('Check if homepage works');
$I->amOnPage("/");
$I->seeInTitle("Home Page");
