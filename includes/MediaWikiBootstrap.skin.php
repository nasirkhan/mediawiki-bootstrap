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
			'msg-menu' => $this->msg( 'mediawikibootstrap-menu' )->text(),
			'msg-actions' => $this->msg( 'actions' )->text(),
			'enable-sidebar-menu' => $this->getConfig()->get( 'MediaWikiBootstrapEnableSidebarMenu' ),
			'array-main-menu' => $this->getMainMenuData(),
		] );
	}

	/**
	 * Build the top navbar's main menu from the editable
	 * MediaWiki:Mediawikibootstrap-mainmenu wiki page. Admins edit that page
	 * like any other wiki page; one link per line, in the same
	 * "* Label|Target" syntax used by MediaWiki:Sidebar (Target may be a
	 * page name or a full URL). Lines that introduce a heading
	 * ("* Heading" with no "|") are ignored here since the navbar is a flat
	 * list, not a grouped one.
	 *
	 * @return array List of [ 'text' => ..., 'href' => ... ]
	 */
	private function getMainMenuData(): array {
		$bar = [];
		$this->addToSidebar( $bar, 'mediawikibootstrap-mainmenu' );

		$items = [];
		foreach ( $bar as $heading => $links ) {
			foreach ( $links as $link ) {
				$items[] = [
					'text' => $link['text'],
					'href' => $link['href'],
				];
			}
		}

		return $items;
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
