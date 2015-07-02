<?php
include "../vendor/autoload.php";

$JsonAdapter = new Aurora\Config\Adapter\JSON();
$JsonAdapter->setBasePath("lang");

$il8n = new Aurora\il8n($JsonAdapter);

$start = microtime();
$il8n->setLocale("en_US");
// /$il8n->withFile("user");
$il8n->withFiles([
	"user"
]);

echo $il8n->get("user.hello", "Bye Bitch!", "Dan", "Hello").PHP_EOL;
echo $il8n->get("user.message.new", "Bye Bitch!", 4, "Dan").PHP_EOL;
echo $il8n->get("user.message.old", "Bye Bitch!", 4).PHP_EOL;

echo (microtime() - $start)*0.000006;
