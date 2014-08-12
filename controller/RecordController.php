<?php
	namespace permag\controller;

	require_once('model/RecordModel.php');
	require_once('view/RecordView.php');
	require_once('view/ShareView.php');
	require_once('model/MemberModel.php');
	require_once('model/RecordingList.php');
	require_once('view/RecordingListNavigationView.php');

	/**
	 * Controls the Recorder box
	 * record sound and saves file to disk and in DB
	 */
	class RecordController {

		private $m_db = null;
		private $m_lh = null;
		private $m_shareView = null;
		private $m_recModel = null;
		private $m_recView = null;

		public function __construct(\permag\model\LoginHandler $lh, \permag\database\Database $db) {
			$this->m_db = $db;
			$this->m_lh = $lh;
			$this->m_shareView = new \permag\view\ShareView();
			$this->m_recModel = new \permag\model\RecordModel($db);
			$this->m_recView = new \permag\view\RecordView();
		}

		/**
		 * Recorder box
		 * @return string HTML
		 */
		public function doControlBox() {
			$outputHTML = '';

			// display recorder
			$outputHTML .= $this->m_recView->doRecorderBox();
			// display username share
			$outputHTML .= $this->m_shareView->doShareToUsername();

			return $outputHTML;;
		}

		/**
		 * Ajax call from RecordCall.php. called when pressed SEND RECORDING
		 * saves recording
		 */
		public function doControlRecord() {

			if ($this->m_lh->isLoggedIn()) {

				$filename = '';
				$memberId = $this->m_lh->getLoggedInMemberId();

				// get memberId from session set in ajax/ShareGetMemberIdFromUsername
				// memberId for username in send recording input field
				$toMemberId = $this->m_recModel->getMemberIdFromSession();
				
				if ($toMemberId != null || $toMemberId != '') {


					$filename = $this->m_recModel->setRecordingFilename($memberId);
					if ($filename == true) {
						// save recording to disk
						$filenameAndExt = $this->m_recModel->saveRecordingToFile($filename);

						// if rec not null
						if ($filenameAndExt != null) {


							// save recording in DB and get inserted id in return
							$recordingId = $this->m_recModel->insertRecording($memberId, $toMemberId, $filenameAndExt);
						}
					} 
				}
			}
		}
	}