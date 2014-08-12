<?php
	namespace permag\common;

	/**
	 * Page view, default HTML document for all pages.
	 */
	class PageView {

		/**
		 * HTML document
		 * @param  string $title page title
		 * @param  string $body all merged HTML for body
		 * @return string HTML      finalized HTML output page
		 */
		public function getHTMLPage($title, $body) {
			$html = "
					<!DOCTYPE html>
					<html lang=\"en\">
						<head>
							<meta charset=\"utf-8\" />
							<link rel=\"stylesheet\" type=\"text/css\" href=\"/css/basic.css\">							
							<link rel=\"stylesheet\" href=\"http://code.jquery.com/ui/1.9.1/themes/base/jquery-ui.css\" />
							<script src=\"http://code.jquery.com/jquery-1.8.2.js\"></script>
							<script src=\"http://code.jquery.com/ui/1.9.1/jquery-ui.js\"></script>

							<title>$title</title>
						</head>
						<body>
							$body
							<script src=\"/js/jRecorder.js\"></script>
							<script src=\"/js/init.js\"></script>
							<script src=\"/js/recInit.js\"></script>
							<script src=\"/js/recEvent.js\"></script>
						</body>
					</html>";
			    
			return $html;
		}
	}