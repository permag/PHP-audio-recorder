<?php
	namespace permag\ajax;
	session_start();

	require_once('../database/Database.php');
	require_once('../database/DBConfig.php');
	require_once('../model/MemberModel.php');

	/**
	 * Called from ajax javascript,
	 * to get memberId from username input field on share recording,
	 * and save memberId as SESSION to retrieve it from the RecordModel
	 * and save recording in DB.
	 */
	class ShareGetMemberIdFromUsername {

		private static $m_ajaxMemberIdToShare = 'ajax_memberIdToShare';

		public function __construct() {
			$this->init();
		}
		
		/**
		 * Get memberId from username, store memberId in SESSION.
		 * @return int memberId of member to receive recording
		 */
		public function init() {
			$shareToUsername = ShareGetMemberIdFromUsernameView::getUsername(); // get username from ajax parameter

			if (isset($shareToUsername)) {
				$dbConfig = new \permag\database\DBConfig();
				$db = new \permag\database\Database($dbConfig);
				$db->connect();
				$memberModel = new \permag\model\MemberModel($db);
				$memberId = $memberModel->getMemberIdFromUsername($shareToUsername);
				$db = null;

				if ($memberId != null || $memberId != '') {
					// write session
					$_SESSION[self::$m_ajaxMemberIdToShare] = $memberId;

					return $memberId;

				} else {
					return null;
				}
			}
		}
	}

	/**
	 * View
	 */
	class ShareGetMemberIdFromUsernameView {

		private static $m_username = 'username';

		/**
		 * Get username from ajax parameter
		 * @return string username or null
		 */
		public static function getUsername() {
			$username = $_GET[self::$m_username];

			if (isset($username)) {
				return $username;
			} else {
				return null;
			}
		}	
	}

	// ajax call return memberId
	$shareGetMemberId = new \permag\ajax\ShareGetMemberIdFromUsername();
	$memberId = $shareGetMemberId->init();

	// echo memberId to ajax callback
	echo json_encode($memberId);

