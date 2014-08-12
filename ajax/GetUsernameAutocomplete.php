<?php 
	namespace permag\ajax;

	require_once('../database/DBConfig.php');
	require_once('../database/Database.php');
	require_once('../model/MemberModel.php');

	/**
	 * Controlling ajax call to retrieve username from autocomplete
	 */
	class GetUsernameAutocomplete {

		/**
		 * Get username
		 * @return array for json output
		 */
		public function getUsername() {
			$dbConfig = new \permag\database\DBConfig();
			$db = new \permag\database\Database($dbConfig);
			$db->connect();
			$memberModel = new \permag\model\Membermodel($db);
			$getUsernameAutocompleteView = new GetUsernameAutocompleteView();

			// array for result in JSON.
			$usernames = array();

			// get search term from ajax autocomplete
			$searchTerm = $getUsernameAutocompleteView->getSearchTerm();
			
			// get username suggestions
			$usernames = $memberModel->getUsernameFromAjaxAutocomplete($searchTerm);

			// kill DB conn
			$db = null;

			// return array
			return $usernames;
		}
	}

	/**
	 * View for retrieving search term from ajax call 
	 */
	class GetUsernameAutocompleteView {

		private static $m_searchTerm = 'term';

		public function getSearchTerm() {
			return $_GET[self::$m_searchTerm];
		}
	}

	// output to ajax callback function
	$getUsername = new GetUsernameAutocomplete();
	$usernameJSON = $getUsername->getUsername();

	// echo JSON array with username suggestions
	echo json_encode($usernameJSON);

