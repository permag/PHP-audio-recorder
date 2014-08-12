<?php
	session_start();

	//länka in filer med funktioner som används
	require_once('database/DBConfig.php');
	require_once('database/Database.php');
	require_once('model/LoginHandler.php');
	require_once('model/RegisterHandler.php');
	require_once('model/MemberModel.php');
	require_once('model/RecordModel.php');
	require_once('model/RecordingModel.php');
	require_once('model/RecordingList.php');


	echo "<h1>Enhetstester</h1>";


	// Database test
	echo '<h2>DB-, PDO-test</h2>';
	$dbConfig = new \permag\database\DBConfig();
	$dbConn = new \permag\database\Database($dbConfig);
	if ($dbConn->test() == true) {
		echo '<p>DB-test OK.</p>';
	} else {
		echo '<p>DB-test fungerar ej.</p>';
	}

	// Login test
	echo "<h2>Login-test</h2>";
	$login = new \permag\model\LoginHandler($dbConn);
	if ($login->test() == true) {
		echo "<p>Login-test OK.</p>";
	} else {
		echo "<p>Login-test fungerar ej.</p>";
	}

	// Register test
	echo "<h2>Register-test</h2>";

	$register = new \permag\model\RegisterHandler($dbConn);
	if ($register->test() == true) {
		echo '<p>Register-test OK.</p>';
	} else {
		echo '<p>Register-test fungerar ej.</p>';
	}


	// Member model test
	echo "<h2>MemberModel-test</h2>"; 
	$memberModel = new \permag\model\MemberModel($dbConn);
	if ($memberModel->test() == true) {
		echo '<p>MemberModel-test OK.</p>';
	} else {
		echo '<p>MemberModel-test fungerar ej.</p>';
	}


	// Record model test
	echo "<h2>RecordModel-test</h2>"; 
	$recordModel = new \permag\model\RecordModel($dbConn);
	if ($recordModel->test() == true) {
		echo '<p>RecordModel-test OK.</p>';
	} else {
		echo '<p>RecordModel-test fungerar ej.</p>';
	}

	// Recording model test
	echo "<h2>RecordingModel-test</h2>"; 
	$recoringdModel = new \permag\model\RecordingModel($dbConn);
	if ($recoringdModel->test() == true) {
		echo '<p>RecordingModel-test OK.</p>';
	} else {
		echo '<p>RecordingModel-test fungerar ej.</p>';
	}


	// RecordingList test
	echo "<h2>RecordingList-test</h2>"; 
	$recordingList = new \permag\model\RecordingList($dbConn);
	if ($recordingList->test() == true) {
		echo '<p>RecordingList-test OK.</p>';
	} else {
		echo '<p>RecordingList-test fungerar ej.</p>';
	}


	// DB KILL
	$dbConn = null;
