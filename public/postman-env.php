<?php
# This file allow automation of postman tests
# USAGE: newman run tests-postman.json -e http://localhost/postman-env.php
# You can also store the file as json and use it in GUI client as environment file

echo json_encode([
	'id' => bin2hex(random_bytes(36)),
	'values' => [
		[
			'id' => '23793e69-d7b7-4265-ba95-8e84fdf8d88a',
			'key' => 'host',
			'value' => 'http://' . $_SERVER['HTTP_HOST'],
			'description' => 'server address',
		],
	],
	"_postman_variable_scope" => "environment",
	"_postman_exported_at" => (new DateTime())->format(DateTime::ATOM),
]);
