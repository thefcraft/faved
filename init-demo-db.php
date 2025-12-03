<?php

function initDemoDB(): void
{
	if (!empty($_SERVER['DB_NAME'])) {
		return;
	}

	$cookie_name = 'faved_db_name';

	// Set DB name from URL
	$db_name = $_GET['db_name'] ?? null;
	if ($db_name) {
		setcookie($cookie_name, $db_name, time() + 3600 * 24 * 365, '/', '', isset($_SERVER['HTTPS']), true);
		$_SERVER['DB_NAME'] = $db_name;
		return;
	}

	// Set DB name from cookie
	$db_name = $_COOKIE[$cookie_name] ?? null;
	if ($db_name) {
		$_SERVER['DB_NAME'] = $db_name;
		return;
	}

	// Generate new DB name
	function generate_secure_random_string($length = 64)
	{
		$bytes = random_bytes($length / 2);
		return bin2hex($bytes);
	}

	$db_name = generate_secure_random_string();
	setcookie($cookie_name, $db_name, time() + 3600 * 24 * 365, '/', '', isset($_SERVER['HTTPS']), true);
	$_SERVER['DB_NAME'] = $db_name;

	// Older version with DB name prefixed in path
	// header("Location: /db{$db_name}/index.php?route=/");
	// exit();
}

initDemoDB();