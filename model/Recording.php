<?php
	namespace permag\model;

	/**
	 * Store Recording objects in object array
	 */
	class RecordingArray {
		private $m_recordings = array();

		public function add(Recording $recording) {
			$this->m_recordings[] = $recording;
		}

		public function get() {
			return $this->m_recordings;
		}
	}

	/**
	 * Stores recordings data from DB in object
	 */
	class Recording {
		private $m_recordingId;
		private $m_filename;
		private $m_timestamp;
		private $m_memberId;
		private $m_username;
		private $m_new;
		private $m_toMemberId;


		public function __construct($recordingId, $filename, $timestamp, $memberId, $username, $new, $toMemberId) {
			$this->m_recordingId = $recordingId;
			$this->m_filename = $filename;
			$this->m_timestamp = $timestamp;
			$this->m_memberId = $memberId;
			$this->m_username = $username;
			$this->m_new = $new;
			$this->m_toMemberId = $toMemberId;
		}

		public function getRecordingId() {
			return $this->m_recordingId;
		}
		public function getFilename() {
			return $this->m_filename;
		}
		public function getTimestamp() {
			return $this->m_timestamp;
		}
		public function getMemberId() {
			return $this->m_memberId;
		}
		public function getUsername() {
			return $this->m_username;
		}
		public function getNew() {
			return $this->m_new;
		}
		public function getToMemberId() {
			return $this->m_toMemberId;
		}
	}