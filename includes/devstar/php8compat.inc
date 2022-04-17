<?php 
function str_starts_with($string, $startString) { 
  $len = strlen($startString); 
  return (substr($string, 0, $len) === $startString); 
} 
function str_ends_with($string, $endString) { 
  $length = strlen( $endString );
  if( !$length ) {
      return true;
  }
  return substr( $string, -$length ) === $endString;
} 
function str_contains($string, $needle) { 
  return strpos($string, $needle) !== false;
} 