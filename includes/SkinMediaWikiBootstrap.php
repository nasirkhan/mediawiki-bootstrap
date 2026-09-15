<?php

namespace MediaWiki\Skins\MediaWikiBootstrap;

use MediaWiki\Skin\SkinMustache;

class SkinMediaWikiBootstrap extends SkinMustache {

	/**
	 * @return array Template data merged with our own additions.
	 */
	public function getTemplateData(): array {
		$parentData = parent::getTemplateData();

		return array_merge( $parentData, [
			'html-search-box' => $this->getSearchBoxHtml(),
			'msg-mainpage' => $this->msg( 'mainpage' )->text(),
		] );
	}

	/**
	 * Build a minimal, JS-free search form. It posts a normal GET request
	 * to index.php, so it keeps working even if scripts fail to load.
	 *
	 * @return string
	 */
	private function getSearchBoxHtml(): string {
		$config = $this->getConfig();
		$action = htmlspecialchars( $config->get( 'Script' ) );

		return <<<HTML
<form class="d-flex" role="search" action="{$action}" id="search-form" method="get">
	<input type="hidden" name="title" value="Special:Search" />
	<input type="search" id="searchInput" class="form-control" placeholder="Search" name="search" />
</form>
HTML;
	}
}
