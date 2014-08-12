<?php
	namespace permag\view;

	class RecordView {

		/**
		 * Recorder box
		 * @return string HTML
		 */
		public function doRecorderBox() {
			$html = "
					<div id=\"recorderContainer\">

						<div id=\"recorderTime\">
						  Time: <span id=\"time\">00:00</span>
						</div>
						<div id=\"levelbase\">
						  <div id=\"levelbar\"></div>
						</div>
						<div id=\"recorderLevel\">
						  Level: <span id=\"level\"></span>
						</div>  
						<div id=\"recorderStatus\">
						  Status: <span id=\"status\"></span>
						</div>

						<button id=\"record\">Record</button>
						<button id=\"stop\">Stop/Play</button>
						
					</div>";

			return $html;
		}

	}
