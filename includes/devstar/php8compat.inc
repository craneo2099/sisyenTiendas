<?php 
function str_starts_with($string, $startString) { 
  $len = strlen($startString); 
  return (substr($string, 0, $len) === $startString); 
} 
function str_contains($string, $needle) { 
  return strpos($string, $needle) !== false;
} 