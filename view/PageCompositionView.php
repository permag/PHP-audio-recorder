<?php
	namespace permag\view;

	/**
	 * View to create page layout
	 */
	class PageCompositionView {

		private $m_leftSection = '';
		private $m_mainSection = '';
		private $m_rightSection = '';

		/**
		 * Add HTML to member variable for section
		 * @param string $html HTML from view
		 */
		public function addToLeftSection($html) {
			$this->m_leftSection .= $html;
		}

		/**
		 * Add HTML to member variable for section
		 * @param string $html HTML from view
		 */
		public function addToMainSection($html) {
			$this->m_mainSection .= $html;
		}

		/**
		 * Add HTML to member variable for section
		 * @param string $html HTML from view
		 */
		public function addToRightSection($html) {
			$this->m_rightSection .= $html;
		}


		/**
		 * Merge views into page sections from HTML stored in private members above
		 * @return string HTML
		 */
		public function mergeSectionsToPage() {
			$html = "
					<div id=\"container\">
						<div id=\"leftSection\">$this->m_leftSection</div>
						<div id=\"mainSection\">$this->m_mainSection</div>
						<div id=\"rightSectionWrapper\"><div id=\"rightSection\">$this->m_rightSection</div></div>
					</div>";

			return $html;
		}
	}