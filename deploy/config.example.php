<?php 

define('SPONSOR_LIST_KEY', 'YOUR_GOOGLE_SPREADSHEET_API_KEY_WHICH_HAS_SPONSORS');
define('PROGRAM_LIST_KEY', 'YOUR_GOOGLE_SPREADSHEET_API_KEY_WHICH_HAS_PROGRAM_LIST');

define('MARKSITE_PATH', 'marksite/');
define('THEME_PATH', '../theme/');
define('SRC_PATH', '../src/');
define('TMP_PATH', 'tmp/');
define('WEBSITE_PATH', '../../2013-beta/');  // Final output

define('RUNNING_USER', 'www-data');  // http running user, remember to change all files' ownership to this user.

$sponsors_output = array(
	"sidebar" => array(
		"zh-tw" => "../src/blocks/sponsors-zh-tw.html",
		"zh-cn" => "../src/blocks/sponsors-zh-cn.html",
		"en" => "../src/blocks/sponsors-en.html"
	),
	"mobile-sidebar" => array(
		"zh-tw" => "../src/blocks/sponsors-mobile.html"
	),
	"page" => array(
		"zh-tw" => "../src/zh-tw/sponsors/index.md",
		"zh-cn" => "../src/zh-cn/sponsors/index.md",
		"en" => "../src/en/sponsors/index.md"
	)
);

$program_list_output = array(
  "program" => array (
    "zh-tw" => "../src/zh-tw/program/index.html",
    "zh-cn" => "../src/zh-cn/program/index.html",
    "en" => "../src/en/program/index.html"
  )
);

$json_output = array(
	"menu" => "tmp/api/menu/menu.json.js",
	"sponsors" => "tmp/api/sponsors/sponsors.json.js",
	"program" => "tmp/api/program/program.json.js"
);

