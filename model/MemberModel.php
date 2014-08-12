<?php
	namespace permag\model;

	/**
	 * Member
	 */
	class MemberModel {

		private static $m_usernameAutocompleteLimit = 27;
		private static $m_memberId = 'memberId';

		private $m_db = null;

		public function __construct(\permag\database\Database $db) {
			$this->m_db = $db;
		}

		/**
		 * Get memberId from username in share recording input field
		 * @param  string $username username
		 * @return int memberId
		 */
		public function getMemberIdFromUsername($username) {
			$theMemberId = '';
			$sql = "SELECT memberId FROM member WHERE username = :username";
			$param = array(':username' => $username);

			$ret = $this->m_db->selectParam($sql, $param);

			if ($ret != null || $ret != '') {
				foreach ($ret as $row) {
					$theMemberId = $row[self::$m_memberId];
				}
				return $theMemberId;
			} else {
				return null;
			}
		}

		/**
		 * Get username from input field autocomplete ajax suggestions search term
		 * @param  string $searchTerm part of username
		 * @return array assoc for json output to ajax callback
		 */
		public function getUsernameFromAjaxAutocomplete($searchTerm) {
			$usernames = array();
			$sql = "SELECT username FROM member WHERE username LIKE :username LIMIT ".self::$m_usernameAutocompleteLimit."";
			$param = array(':username' => '%'.$searchTerm.'%');

			$stmt = $this->m_db->selectReturnSTMT($sql, $param);
			$stmt->execute($param);
			$stmt->setFetchMode(\PDO::FETCH_ASSOC);

			while ($r = $stmt->fetch()) {
				array_push($usernames, $r);
			}
			return $usernames;
		}


		// TEST
		public function test() {
			if ($this->getMemberIdFromUsername('usernameTest') != 1) {
				echo 'FEL! getMemberIdFromUsername returnerar inte rätt memberId.';
				return false;
			}

			return true;
		}
	}