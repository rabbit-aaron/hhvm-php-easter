<?php
require_once 'easter.php'

function test_php_easter_days(){
  for($i = 1000; $i < 3000; ++$i){
    assert(easter_days($i) == php_easter_days($i));
  }
}

function test_php_easter_date(){
  for($i = 1970; $i <= 2037; ++$i){
    assert(easter_date($i) == php_easter_date($i));
  }
}

function test_easter_datetime(){
  for($i = 1970; $i <= 2037; ++$i){
    $dt = new DateTime();
    $dt->setTimestamp(easter_date($i));
    assert($dt == easter_datetime($i));
  }
}

test_php_easter_days();
test_php_easter_date();
test_easter_datetime();

