<?php
	namespace permag\controller;

	require_once('model/RecordingModel.php');
	require_once('view/RecordingView.php');
	require_once('model/RecordingList.php');
	require_once('view/RecordingListNavigationView.php');

	/**
	 * Controls the Message inbox / outbox lists of recordings
	 */
	class RecordingController {

		private $m_db = null;
		private $m_lh = null;
		private $m_recListNavView = null;
		private $m_recordingModel = null;
		private $m_recordingView = null;

		/**
		 * @param \permag\model\LoginHandler               $lh             
		 * @param \permag\database\Database                $db             
		 * @param \permag\view\RecordingListNavigationView $recListNavView 
		 */
		public function __construct(\permag\model\LoginHandler $lh, \permag\database\Database $db, 
									\permag\view\RecordingListNavigationView $recListNavView) {
			$this->m_db = $db;
			$this->m_lh = $lh;
			$this->m_recListNavView = $recListNavView;
			$this->m_recordingModel = new \permag\model\RecordingModel($db);
			$this->m_recordingView = new \permag\view\RecordingView();
		}

		/**
		 * List of recordings INBOX / OUTBOX 
		 * @return string HTML
		 */
		public function doControlRecordingsList() {
			$recList = new \permag\model\RecordingList($this->m_db);
			$outputHTML = '';
			$message = '';
			$memberId = $this->m_lh->getLoggedInMemberId();

			switch ($this->m_recListNavView->getController()) {
				default:
				case \permag\view\RecordingListNavigationView::INBOX:
					// user clicked remove button
					if ($this->m_recordingView->userClickedDelete()) {
						// control deleting of recording
						if ($this->doControllDeleteRecording($memberId)) {
							$message .= \permag\view\RecordingView::MSG_DELETE_OK;
						} else {
							$message .= \permag\view\RecordingView::MSG_DELETE_NOT_OK;
						}
					}
					// get inbox recordings as object
					$recordings = $recList->getInboxRecordings($memberId);
					// show list of recordings. params: recordings object and reclistnavview instace for creating links					
					$outputHTML .= $this->m_recordingView->showInboxRecordings($recordings, $this->m_recListNavView);
					// remove "new marking" of new audios
					$recList->removeNewMarking($memberId);

					break;

				case \permag\view\RecordingListNavigationView::OUTBOX:
					// user clicked remove button
					if ($this->m_recordingView->userClickedDelete()) {
						// control deleting of recording
						if ($this->doControllDeleteRecording($memberId)) {
							$message .= \permag\view\RecordingView::MSG_DELETE_OK;
						} else {
							$message .= \permag\view\RecordingView::MSG_DELETE_NOT_OK;
						}
					}
					// get sent recordings as object
					$recordings = $recList->getOutboxRecordings($memberId);
					// show list of recordings. params: recordings object and reclistnavview instace for creating links
					$outputHTML .= $this->m_recordingView->showOutboxRecordings($recordings, $this->m_recListNavView);

					break;
			}
			$messageHTML = $this->m_recordingView->outputMessage($message);

			return $outputHTML . $messageHTML;
		}

		/**
		 * Delete recording
		 * @param  int $memberId memberId of user
		 * @return bool true on OK else false
		 */
		private function doControllDeleteRecording($memberId) { 
			// get id of record from hidden post field
			$deleteId = $this->m_recordingView->getIdOfRecordingToDelete();
			
			// get filename of file to delete
			$filename = $this->m_recordingModel->getFilenameToDelete($deleteId);

			// if javascript confirm true, delete recording
			if ($this->m_recordingModel->deleteRecordingInDB($deleteId, $memberId)) {
				// delete file
				if ($this->m_recordingModel->deleteRecordingFile($filename)) {
					return true;
				}
				return true;
			}
		}

	}