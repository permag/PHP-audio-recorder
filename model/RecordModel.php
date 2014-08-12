<?php
	namespace permag\model;

	/**
	 * Model for recorder box
	 */
	class RecordModel {

		const RECORDINGS_DIR = 'recs';
		const FILE_EXTENSION = '.wav';
		
		// session to retireive memberId from "send rec to username"
		const MEMBERID_SHARE_SESSION = 'ajax_memberIdToShare';

		// time
		private static $m_timeZone = 'Europe/Berlin';
		private static $m_timeFormatFilename = 'YmdHis';

		// DB table
		private static $m_tableRecording = 'recording';

		private $m_db = null;

		public function __construct(\permag\database\Database $db) {
			$this->m_db = $db;
		}

		/**
		 * Generate filename with memberId prefix and date time 
		 * @param int $memberId from session
		 */
		public function setRecordingFilename($memberId) {
			$filename = '';
			date_default_timezone_set(self::$m_timeZone);
			$filename = $memberId . '_' . date(self::$m_timeFormatFilename);
			
			return $filename;
		}

		/**
		 * Save recording to file
		 * @param  string $filename generated filename with memberId prefix
		 * @return string full filename with extension
		 */
		public function saveRecordingToFile($filename) {

			$uploadPath = dirname(__FILE__) .'/../'. self::RECORDINGS_DIR;

			// create sound file
			$fp = fopen($uploadPath.'/'.$filename.self::FILE_EXTENSION, 'wb');
			fwrite($fp, file_get_contents('php://input'));
			fclose($fp);

			if (filesize($uploadPath.'/'.$filename.self::FILE_EXTENSION) > 1) {

				return $filename.self::FILE_EXTENSION; // return filename.ext

			} else {
				unlink(self::RECORDINGS_DIR.'/'.$filename.self::FILE_EXTENSION);
				return null;
			}
		}

		/**
		 * Save recording in database
		 * @param  int $memberId from session
		 * @param  string $filename generated filename with extension and memberId prefix
		 * @return int inserted recordingId or bool false
		 */
		public function insertRecording($memberId, $toMemberId, $filename) {
			$sql = "INSERT INTO ".self::$m_tableRecording." (filename, memberId, toMemberId)
					VALUES (:filename, :memberId, :toMemberId)";
			$param = array(':filename' => $filename, 
							':memberId' => $memberId, 
							':toMemberId' => $toMemberId);

			if ($this->m_db->insertUpdateDelete($sql, $param) == 1) {
				return $this->m_db->lastInsertId();
			} else {
				return false;
			}
		}

		/**
		 * Get memberId of username in sharebox
		 * the session is set in ajax/ShareGetUsername
		 * @return int memberId or null
		 */
		public function getMemberIdFromSession() {
			$memberId = $_SESSION[self::MEMBERID_SHARE_SESSION];
			if (isset($memberId)) {
				unset($_SESSION[self::MEMBERID_SHARE_SESSION]);
				return $memberId;
			} else {
				return null;
			}
		}

		
		// TEST
		public function test() {
			// change table for tests
			self::$m_tableRecording = 'test_recording';

			if ($this->insertRecording(1, 2, '1_20120101111111.wav') == false) {
				echo 'FEL! insertRecording returnerar false';
				return false;
			}

			if ($this->insertRecording(1, 2, '1_20120101111111.wav') != $this->m_db->lastInsertId()) {
				echo 'FEL! insertRecording returnerar fel ID';
				return false;
			}

			if ($this->getMemberIdFromSession() != $_SESSION[self::MEMBERID_SHARE_SESSION]) {
				echo 'FEL! getMemberIdFromSession returnerar fel ID';
				return false;
			}

			return true;
		}
	}
