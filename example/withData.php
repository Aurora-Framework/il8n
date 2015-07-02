<?php
include "../vendor/autoload.php";

$JsonAdapter = new Aurora\Config\Adapter\JSON();
$json = '{
	"user": {
		"hello": ":name",
		"message": {
			"new": "So connect :#user.hello",
			"old": "You missed :count old messages"
		}
	}
}';


$il8n = new Aurora\il8n($JsonAdapter);

$start = microtime();
$il8n->setLocale("en_US");

$il8n->withData($json);

echo $il8n->get("user.hello", "Bye Bitch!", "Dan").PHP_EOL;
echo $il8n->get("user.message.new", "Bye Bitch!", "Dan").PHP_EOL;
echo $il8n->get("user.message.old", "Bye Bitch!", 4).PHP_EOL;

echo (microtime() - $start)*0.000006;
