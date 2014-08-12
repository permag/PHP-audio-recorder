<?php
	namespace permag\ajax;
	session_start();

	require_once('database/DBConfig.php');
	require_once('database/Database.php');
	require_once('model/LoginHandler.php');
	require_once('controller/RecordController.php');

	/**
	 * Called from jRecorder API, JavaScript/Flash, called when clicked send recording
	 */
	class RecordCall {

		/**
		 * Call RecordController to save recording
		 */
		public function init() {
			$dbConfig = new \permag\database\DBConfig();
			$db = new \permag\database\Database($dbConfig);
			// DB-connect
			$db->connect();

			$loginHandler = new \permag\model\LoginHandler($db);

			$rec = new \permag\controller\RecordController($loginHandler, $db);
			$rec->doControlRecord();

			// kill DB-conn
			$db = null;
		}
	}

	$recordCall = new RecordCall();
	$recordCall->init();
