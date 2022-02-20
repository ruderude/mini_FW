<?php

require 'config.php';

/**
 * エスケープ
 * @param string $string
 * @return string
 */
function h($string)
{
	return htmlspecialchars($string, ENT_QUOTES, 'utf-8');
}

/**
 * autoload
 * @param string $className
 */
function autoload($className)
{
	$filename = sprintf('%s.php', $className);
	require $filename;
}

spl_autoload_register('autoload');