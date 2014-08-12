<?php
	namespace permag\model;
	require_once('model/Recording.php');

	/**
	 * Collects data from multiple tables to create inbox/outbox of recordings data
	 */
	class RecordingList {

		// DB table
		private static $m_tableMember = 'member'; 
		private static $m_tableRecoridng = 'recording';
		
		private static $m_memberId = ':memberId';
		private $m_db = null;
		
		public function __construct(\permag\database\Database $db) {
			$this->m_db = $db;
		}
		/**
		 * Get data for inbox rows as object
		 * @param  int $memberId 
		 * @return RecordingArray object
		 */
		public function getInboxRecordings($memberId) {
			$sql = "SELECT ".self::$m_tableMember.".username, ".self::$m_tableMember.".memberId, 
							".self::$m_tableRecoridng.".toMemberId, ".self::$m_tableRecoridng.".recordingId, 
							".self::$m_tableRecoridng.".filename, ".self::$m_tableRecoridng.".timestamp, ".self::$m_tableRecoridng.".new
					FROM ".self::$m_tableMember."
					INNER JOIN ".self::$m_tableRecoridng."
					ON ".self::$m_tableMember.".memberId = ".self::$m_tableRecoridng.".memberId
					WHERE ".self::$m_tableRecoridng.".toMemberId = :memberId
					ORDER BY ".self::$m_tableRecoridng.".timestamp DESC";
			$param = array(self::$m_memberId => $memberId);

			$stmt = $this->m_db->selectReturnSTMT($sql, $param);

			$ret = new RecordingArray();

			while ($obj = $stmt->fetch(\PDO::FETCH_OBJ)) {
				$recording = new Recording(
										$obj->recordingId, 
										$obj->filename, 
										$obj->timestamp, 
										$obj->memberId, 
										$obj->username,
										$obj->new,
										$obj->toMemberId);
				$ret->add($recording);
			}
			$stmt = null;

			return $ret;
		}

		/**
		 * Remove "unheard" marking from recording
		 * @param  int $memberId memberId of user
		 * @return bool true if OK else false
		 */
		public function removeNewMarking($memberId) {
			$sql = "UPDATE ".self::$m_tableRecoridng."
					SET new = :old
					WHERE toMemberId = :memberId
					AND new = :new";
			$param = array(':old' => 0, self::$m_memberId => $memberId, ':new' => 1);

			$stmt = $this->m_db->insertUpdateDelete($sql, $param);
			if ($stmt > 0) {
				return true;
			} else {
				return false;
			}
		}

		/**
		 * Get data for outbox rows as object
		 * @param  int $memberId 
		 * @return RecordingArray object
		 */
		public function getOutboxRecordings($memberId) {
			$sql = "SELECT ".self::$m_tableMember.".username, ".self::$m_tableMember.".memberId, 
							".self::$m_tableRecoridng.".toMemberId, ".self::$m_tableRecoridng.".recordingId, 
							".self::$m_tableRecoridng.".filename, ".self::$m_tableRecoridng.".timestamp, ".self::$m_tableRecoridng.".new
					FROM ".self::$m_tableMember."
					INNER JOIN ".self::$m_tableRecoridng."
					ON ".self::$m_tableMember.".memberId = ".self::$m_tableRecoridng.".toMemberId
					WHERE ".self::$m_tableRecoridng.".memberId = :memberId
					ORDER BY ".self::$m_tableRecoridng.".timestamp DESC";
			$param = array(self::$m_memberId => $memberId);

			$stmt = $this->m_db->selectReturnSTMT($sql, $param);

			$ret = new RecordingArray();

			while ($obj = $stmt->fetch(\PDO::FETCH_OBJ)) {
				$recording = new Recording(
										$obj->recordingId, 
										$obj->filename, 
										$obj->timestamp, 
										$obj->memberId, 
										$obj->username,
										$obj->new,
										$obj->toMemberId);
				$ret->add($recording);
			}
			$stmt = null;

			return $ret;
		}


		// TEST
		public function test() {
			// test tables
			self::$m_tableMember = 'test_member';
			self::$m_tableRecoridng = 'test_recording';

			// check returned instance object
			if ($this->getInboxRecordings(1) instanceof \permag\model\RecordingArray == false) {
				echo 'FEL! getInboxRecordings returnerar inte RecordingArray.';
				return false;	
			}

			// check inbox if memberId 3 has 1 object in RecordingArray
			if (count($this->getInboxRecordings(3)->get()) != 1) {
				echo 'FEL! getInboxRecordings returnerar inte 3 med count.';
				return false;	
			}

			// check outbox if memberId 3 has 1 object in RecordingArray
			if (count($this->getOutboxRecordings(3)->get()) != 1) {
				echo 'FEL! getOutboxRecordings returnerar inte 3 med count.';
				return false;	
			}

			return true;
		}

	}
