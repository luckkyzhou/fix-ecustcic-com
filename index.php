<?php
define('InSign', TRUE);

//Debug
ini_set('display_errors', 1);
error_reporting(E_ALL);

require_once 'config.php';
date_default_timezone_set('PRC');

$submit = $success = false;
if (isset($_POST['submit'])) {
	if (empty($_POST['building']) /*|| empty($_POST['stuid'])*/ || empty($_POST['brand']) || empty($_POST['system'])
		|| empty($_POST['description']) || empty($_POST['name']) || empty($_POST['contact'])) {
		die('data error!');
	}
	$submit = true;
	$success = save();
}

require_once 'home.template.html';

function save() {
	try {
		$conn = new mysqli(db_host, db_user, db_pw, db_name);
		$conn->query('set names utf8'); 
		$stmt = $conn->prepare('INSERT INTO fix (building,brand,brandtype,system,description,note,name,contact,ip,addtime) VALUES (?,?,?,?,?,?,?,?,?,?)');
		$t = time();
		$stmt->bind_param('sssssssssi', $_POST['building'], $_POST['brand'], $_POST['brandtype'], $_POST['system'], $_POST['description'], $_POST['note'], $_POST['name'], $_POST['contact'], $_SERVER['REMOTE_ADDR'], $t);
		$stmt->execute();
		$stmt->close();
		$id = $conn->insert_id;
		if (!$id || $id <= 0) {
			throw new Exception('insert failed');
		}
		return true;
	} catch (Exception $e) {
		return false;
	}
}