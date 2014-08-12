<?php
	namespace permag\view;

	/**
	 * View for recordings inbox / outbox
	 */
	class RecordingView {

		const MSG_DELETE_OK = 'MSG_DELETE_OK';
		const MSG_DELETE_NOT_OK = 'MSG_DELETE_NOT_OK';

		// post
		private static $m_deleteRec = 'deleteRec';
		private static $m_deleteId = 'deleteId';

		//  css classes for css and javascript
		 private static $m_deleteRecInbox = 'deleteRecInbox';
		 private static $m_deleteRecOutbox = 'deleteRecOutbox';

		/**
		 * Show INBOX
		 * @param  \permag\model\RecordingArray             $recordings     
		 * @param  \permag\view\RecordingListNavigationView $recListNavView 
		 * @return string HTML                                                  
		 */
		public function showInboxRecordings(\permag\model\RecordingArray $recordings, \permag\view\RecordingListNavigationView $recListNavView) {
			$html = "<h3 id='audioListHeader'><a href='". $recListNavView->getInboxLink() ."'>Audio inbox</a></h3>
					<div id='recordingList'>";
			if (count($recordings->get()) > 0) {
				
				foreach ($recordings->get() as $r) {
					$fromMemberId = $r->getMemberId();
					$username = $r->getUsername();
					$filename = $r->getFilename();
					$time = $r->getTimestamp();
					$recordingId = $r->getRecordingId();
					$new = $r->getNew();

					$newRecoring = '';
					$newRecordingText = '';
					if ($new == 1) {
						$newRecoring = ' newRecording';
						$newRecordingText = "<p class='unheardMessage'>(New)</p>";
					}
				
					$html .= "<div class='recordingDiv$newRecoring'>
								<p class='from'>From: <a href='#' class='username'>$username</a></p>
								<span class='remove'>
								<form method='post'>
									<input type='hidden' name='".self::$m_deleteId."' value='$recordingId' />
									<input type='submit' name='".self::$m_deleteRec."' value='X' class='".self::$m_deleteRecInbox."' />
								</form>
								</span>$newRecordingText
								<p class='recordingTime'>$time</p>
								<div><audio src='/recs/$filename' controls></audio></div>
							</div>";
				}

			} else {
				$html .= '<p>You have no audio messages in your inbox.</p>';
			}
			$html .= "</div>";

			return $html;
		}

		/**
		 * Show OUTBOX
		 * @param  \permag\model\RecordingArray $recordings     
		 * @param  \permag\view\RecordingListNavigationView $recListNavView 
		 * @return string HTML
		 */
		public function showOutboxRecordings(\permag\model\RecordingArray $recordings, \permag\view\RecordingListNavigationView $recListNavView) {
			$html = "<h3 id='audioListHeader'><a href='". $recListNavView->getOutboxLink() ."'>Audio outbox</a></h3>
					<div id='recordingList'>";
			if (count($recordings->get()) > 0) {
				
				foreach ($recordings->get() as $r) {
					$toMemberId = $r->getToMemberId();
					$username = $r->getUsername();
					$filename = $r->getFilename();
					$time = $r->getTimestamp();
					$recordingId = $r->getRecordingId();
					$new = $r->getNew();

					$newRecoring = '';
					$newRecordingText = '';
					if ($new == 1) {
						$newRecoring = ' newRecordingSent';
						$newRecordingText = "<p class='unheardMessage'>(Unheard)</p>";
					}
				
					$html .= "<div class='recordingDiv$newRecoring'>
								<p class='from'>To: <a href='#' class='username'>$username</a></p>
								<span class='remove'>
								<form method='post'>
									<input type='hidden' name='".self::$m_deleteId."' value='$recordingId' />
									<input type='submit' name='".self::$m_deleteRec."' value='X' class='".self::$m_deleteRecOutbox."' />
								</form>
								</span>$newRecordingText
								<p class='recordingTime'>$time</p>
								<div><audio src='/recs/$filename' controls></audio></div>
							</div>";
				}

			} else {
				$html .= '<p>You have no audio messages in your outbox.</p>';
			}
			$html .= "</div>";

			return $html;
		}

		/**
		 * User cliked delete button on recording
		 * @return bool true if clicked else false
		 */
		public function userClickedDelete() {
			if (isset($_POST[self::$m_deleteRec])) {
				return true;
			} else { 
				return false;
			}
		}
		
		/**
		 * Get id of recording to delete from hidden form field
		 * @return int recording id or null
		 */
		public function getIdOfRecordingToDelete() {
			$deleteId = $_POST[self::$m_deleteId];
			if ($deleteId) {
				return $deleteId;
			} else {
				return null;
			}
		}

		/**
		 * Error and OK messages for the user
		 * @param  string $message set in the controller via const view
		 * @return string HTML
		 */
		public function outputMessage($message) {
			$messageHTML = '';

			if ($message == self::MSG_DELETE_OK) {
				$messageHTML = '<h3>Recording was deleted.</h3>';
			
			} else if ($message == self::MSG_DELETE_NOT_OK) {
				$messageHTML = '<h3>Could not delete recording.</h3>';
			}

			if ($messageHTML != '') {
				return "<div class=\"errorMessages\">$messageHTML</div>";
			}
		}

	}