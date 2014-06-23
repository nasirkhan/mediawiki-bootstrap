<?php
/**
 * MediaWikiBootstrap is a simple Mediawiki Skin build on Bootstrap 3.
 *
 * @file
 * @ingroup Skins
 * @author Nasir Khan Saikat http://nasirkhn.com
 */
if (!defined('MEDIAWIKI')) {
    die(-1);
}

/**
 * SkinTemplate class for MediaWikiBootstrap skin
 * @ingroup Skins
 */
class SkinMediaWikiBootstrap extends SkinTemplate {

    var $skinname = 'mediawikibootstrap', 
            $stylename = 'mediawikibootstrap',
            $template = 'MediaWikiBootstrapTemplate', 
            $useHeadElement = true;

    /**
     * Initializes output page and sets up skin-specific parameters
     * @param $out OutputPage object to initialize
     */
    public function initPage(OutputPage $out) {
        global $wgLocalStylePath;

        parent::initPage($out);

        // Append CSS which includes IE only behavior fixes for hover support -
        // this is better than including this in a CSS fille since it doesn't
        // wait for the CSS file to load before fetching the HTC file.
        $min = $this->getRequest()->getFuzzyBool('debug') ? '' : '.min';
        $out->addHeadItem('csshover', '<!--[if lt IE 7]><style type="text/css">body{behavior:url("' .
                htmlspecialchars($wgLocalStylePath) .
                "/{$this->stylename}/csshover{$min}.htc\")}</style><![endif]-->"
        );

        $out->addHeadItem('responsive', '<meta name="viewport" content="width=device-width, initial-scale=1.0">');
        $out->addModuleScripts('skins.mediawikibootstrap');
    }

    /**
     * Load skin and user CSS files in the correct order
     * fixes bug 22916
     * @param $out OutputPage object
     */
    function setupSkinUserCss(OutputPage $out) {
        global $wgResourceModules;

        parent::setupSkinUserCss($out);

        // FIXME: This is the "proper" way to include CSS
        // however, MediaWiki's ResourceLoader messes up media queries
        // See: https://bugzilla.wikimedia.org/show_bug.cgi?id=38586
        // &: http://stackoverflow.com/questions/11593312/do-media-queries-work-in-mediawiki
        //
        //$out->addModuleStyles( 'skins.strapping' );
        // Instead, we're going to manually add each, 
        // so we can use media queries
        foreach ($wgResourceModules['skins.mediawikibootstrap']['styles'] as $cssfile => $cssvals) {
            if (isset($cssvals)) {
                $out->addStyle($cssfile, $cssvals['media']);
            } else {
                $out->addStyle($cssfile);
            }
        }
    }

}

/**
 * Template class of the MediaWikiBootstrap Skin
 * @ingroup Skins
 */
class MediaWikiBootstrapTemplate extends BaseTemplate{
    /**
     * Outputs the entire contents of the page
     */
    public function execute() {
        // Suppress warnings to prevent notices about missing indexes in $this->data
        wfSuppressWarnings();
        
        $this->html( 'headelement' ); ?>
        
        // [...]
        
        <?php $this->printTrail(); ?>
    </body>
    </html><?php
    wfRestoreWarnings();
}
}



