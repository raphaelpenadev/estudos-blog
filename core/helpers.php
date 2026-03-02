<?php

function base_path()
{
  $scriptName = $_SERVER['SCRIPT_NAME'];
  return rtrim(str_replace('\\', '/', dirname($scriptName)), '/');
}

function url(string $path = '')
{
  return base_path() . $path;
}
