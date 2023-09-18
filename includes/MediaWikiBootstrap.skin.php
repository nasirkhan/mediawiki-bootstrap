<?php

class SkinMediaWikiBootstrap extends SkinMustache
{
    public function getTemplateData()
    {
        $skin = $this;
        $out = $skin->getOutput();
        $title = $out->getTitle();
        $parentData = parent::getTemplateData();

        $skinData = array_merge($parentData, [
            // 'html-credit-line' => '<a href="https://nasirkhn.com" target="_blank" style="font-variant:small-caps;">Nasir Khan Saikat</a>',
            'html-search-box' => $this->html_search_box(),
        ]);

        return $skinData;
    }

    function html_search_box()
    {
        $config = $this->getConfig();
        $html_search_box = '<form class="d-flex" role="search" action="' .
            $config->get('Script') .
            '" id="search-form">' .
            '<input type="text" id="searchInput" class="form-control" placeholder="Search" name="search" onchange="$(\'#search-form\').submit()" />
		</form>';
        return $html_search_box;
    }
}
