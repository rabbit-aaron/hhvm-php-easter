<?php
// constants define for hhvm
if(!defined('CAL_EASTER_DEFAULT')){
  define('CAL_EASTER_DEFAULT',0);
}
if(!defined("CAL_EASTER_ROMAN")){
  define('CAL_EASTER_ROMAN',1);
}
if(!defined('CAL_EASTER_ALWAYS_GREGORIAN')){
  define('CAL_EASTER_ALWAYS_GREGORIAN',2);
}
if(!defined('CAL_EASTER_ALWAYS_JULIAN')){
  define('CAL_EASTER_ALWAYS_JULIAN',3);
}

function php_easter_days($year = NULL, $method = CAL_EASTER_DEFAULT)
{
  if($year == NULL){
     $year = (int)date('Y');
  }
  /* based on code by Simon Kershaw, <webmaster@ely.anglican.org> */
  $golden = ($year % 19) + 1;         /* the Golden number */

  if (($year <= 1582 && $method != CAL_EASTER_ALWAYS_GREGORIAN) ||
      ($year >= 1583 && $year <= 1752 && $method != CAL_EASTER_ROMAN && $method != CAL_EASTER_ALWAYS_GREGORIAN) ||
       $method == CAL_EASTER_ALWAYS_JULIAN) {    /* JULIAN CALENDAR */
       
    $dom = ($year + (int)($year/4) + 5) % 7;      /* the "Dominical number" - finding a Sunday */
    if ($dom < 0) {
      $dom += 7;
    }

    $pfm = (3 - (11*$golden) - 7) % 30;     /* uncorrected date of the Paschal full moon */
    if ($pfm < 0) {
      $pfm += 30;
    }
  } else {              /* GREGORIAN CALENDAR */
    $dom = ($year + (int)($year/4) - (int)($year/100) + (int)($year/400)) % 7;  /* the "Domincal number" */
    if ($dom < 0) {
      $dom += 7;
    }

    $solar = (int)(($year-1600)/100) - (int)(($year-1600)/400);    /* the $solar and $lunar corrections */
    $lunar = (int)((((int)(($year-1400) / 100)) * 8) / 25);

    $pfm = (3 - (11*$golden) + $solar - $lunar) % 30;   /* uncorrected date of the Paschal full moon */
    if ($pfm < 0) {
      $pfm += 30;
    }
  }

  if (($pfm == 29) || ($pfm == 28 && $golden > 11)) {    /* corrected date of the Paschal full moon */
    $pfm--;              /* - days after 21st March                 */
  }

  $tmp = (4-$pfm-$dom) % 7;
  if ($tmp < 0) {
    $tmp += 7;
  }
  $easter = $pfm + $tmp + 1;               /* Easter as the number of days after 21st March */
  return $easter;
}

function php_easter_date($year=NULL){
  if($year == NULL){
    $year = (int)date('Y');
  }
  if($year<1970 || $year>2037){
    trigger_error('This function is only valid for $years between 1970 and 2037 inclusive', E_USER_WARNING);
    return false;
  }
  $easter = php_easter_days($year);
  if ($easter < 11) {
      $month = 3; // different from c source code
      $mday = $easter + 21; // different from c source code
  } else {
      $month = 4;
      $mday = $easter-10;
  }
  return mktime(0,0,0,$month,$mday,$year);
}

function easter_datetime($year=NULL){
  if($year == NULL){
    $year = (int)date('Y');
  }
  $easter = php_easter_days($year);
  $easter_datetime = new DateTime(((string)$year).'-03-21');
  $easter_datetime->modify('+'.$easter.' day');
  return $easter_datetime;
}


