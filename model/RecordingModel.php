<?php
	namespace permag\model;

	/**
	 * Model for recordings data
	 */
	class RecordingModel {

		private static $m_filename = 'filename';

		// DB table
		private static $m_tableRecording = 'recording';
		
		private $m_db = null;

		public function __construct(\permag\database\Database $db) {
			$this->m_db = $db;
		}

		/**
		 * Delete recording if user is owner or receiver
		 * @param  int $recordingId id of recording
		 * @param  int $memberId  	member id of user
		 * @return bool true if OK else false
		 */
		public function deleteRecordingInDB($recordingId, $memberId) {
			$sql = "DELETE FROM ".self::$m_tableRecording." 
					WHERE ".self::$m_tableRecording.".recordingId = :recordingId
					AND (".self::$m_tableRecording.".memberId = :memberId
					OR ".self::$m_tableRecording.".toMemberId = :memberId)";

			$param = array(':recordingId' => $recordingId, ':memberId' => $memberId);

			if ($this->m_db->insertUpdateDelete($sql, $param) == 1) {
				return true;
			} else {
				return false;
			}
		}

		/**
		 * Get filename of recording from it's id
		 * @param  id $recordingId 
		 * @return string filename or null
		 */
		public function getFilenameToDelete($recordingId) {
			$sql = "SELECT ".self::$m_tableRecording.".filename
					FROM ".self::$m_tableRecording."
					WHERE ".self::$m_tableRecording.".recordingId = :recordingId";
			$param = array(':recordingId' => $recordingId);

			$ret = $this->m_db->selectParam($sql, $param);

			$filename = '';
			foreach ($ret as $r) {
				$filename = $r[self::$m_filename];
			}

			if ($filename != null || $filename != '') {
				return $filename;
			} else {
				return null;
			}
		}

		/**
		 * Delete recording file from disk
		 * @param  string $filename
		 * @return bool true on OK
		 */
		public function deleteRecordingFile($filename) {
			$file =  RecordModel::RECORDINGS_DIR .'/'. $filename;

			if (unlink($file)) {
				return true;
			} else {
				return false;
			}
		}


		// TEST
		public function test() {
			// table for test
			self::$m_tableRecording = 'test_recording';
			$test_filename = '1_20120101111111.wav';

			// insert into test_recording: to delete
			if ($this->m_db->insertUpdateDelete("INSERT INTO ".self::$m_tableRecording." (memberId, filename) VALUES (:memberId, :filename)", 
												array(':memberId' => 1, ':filename' => $test_filename)) != 1) {
				echo 'FEL! insertUpdateDelete i test-klassen returnerar inte 1.';
				return false; 
			}

			// last id
			$lastId = $this->m_db->lastInsertId();

			if ($this->getFilenameToDelete($lastId) == null) {
				echo 'FEL! getFilenameToDelete returnerar null.';
				return false;	
			}

			if ($this->getFilenameToDelete($lastId) != $test_filename ) {
				echo 'FEL! getFilenameToDelete returnerar null.';
				return false;	
			}

			if ($this->deleteRecordingInDB($lastId, 1) == false) {
				echo 'FEL! deleteRecordingInDB returnerar false.';
				return false;				
			}


			return true;
		}

	}