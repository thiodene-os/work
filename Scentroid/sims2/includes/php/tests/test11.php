<?php

require_once($_SERVER['DOCUMENT_ROOT'] . '/includes/php/common.php');
//require_once(TEMPLATES_PATH . "/template_main.php");

// For admin login always keep that password and change it to test123 if not known
echo md5("test123") ; // cc03e747a6afbbcbf8be7668acfebee5

?>