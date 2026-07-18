<?php
/**
 * Vercel PHP entry point for Typecho
 */
// Change to project root so relative paths work
chdir(dirname(__DIR__));

// Set server vars for Typecho
$_SERVER['SCRIPT_FILENAME'] = dirname(__DIR__) . '/index.php';
$_SERVER['SCRIPT_NAME'] = '/index.php';

// Boot Typecho
require_once dirname(__DIR__) . '/index.php';
