<?php

//From: http://css-tricks.com/snippets/php/time-ago-function/
function ago($time) {
  $periods = array("second", "minute", "hour", "day", "week", "month", "year", "decade");
  $lengths = array("60","60","24","7","4.35","12","10");
  $now = time();
  $difference = $now - $time;
  $tense = "ago";

  for($j = 0; $difference >= $lengths[$j] && $j < count($lengths)-1; $j++) {
    $difference /= $lengths[$j];
  }

  $difference = round($difference);

  if($difference != 1) {
    $periods[$j].= "s";
  }

  return "$difference $periods[$j] ago ";
}

//From: http://sites.google.com/site/chrelad/notes-1/pluraltosingularwithphp
function depluralize($word){
    // List of rules for plural to singular suffixes
    $rules = array( 
        'ss' => false, //Don't touch words that end with 'ss'
        'os' => 'o', 
        'ies' => 'y', 
        'xes' => 'x', 
        'oes' => 'o', 
        'ies' => 'y', 
        'ves' => 'f', 
        's' => '');
    // Loop through all the rules and do the replacement. 
    foreach(array_keys($rules) as $key){
        // If the end of the word doesn't match the key,
        // it's not a candidate for replacement. Move on
        // to the next plural ending. 
        if(substr($word, (strlen($key) * -1)) != $key) 
            continue;
        // If the value of the key is false, stop looping
        // and return the original version of the word. 
        if($key === false) 
            return $word;
        // We've made it this far, so we can do the replacement. 
        return substr($word, 0, strlen($word) - strlen($key)) . $rules[$key]; 
    }
    return $word;
}

?>
