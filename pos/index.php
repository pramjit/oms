<?php 

$opencart_path = realpath(dirname(__FILE__) . '/../') . '/';

require_once $opencart_path.'config.php';

header('Location: '.HTTPS_SERVER.'admin/index.php?route=pos/pos'); 

?>
