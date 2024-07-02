<?php

use MediaWiki\Hook;
use Wikimedia\AtEase\AtEase;

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
class SkinMediaWikiBootstrap extends SkinTemplate
{

    public $skinname        = 'mediawikibootstrap';
    public $stylename       = 'mediawikibootstrap';
    public $template        = 'MediaWikiBootstrapTemplate';
    public $useHeadElement  = true;

    /**
     * Initializes output page and sets up skin-specific parameters
     * @param $out OutputPage object to initialize
     */
    public function initPage(OutputPage $out)
    {
        parent::initPage($out);

        $out->addHeadItem('viewport', '<meta name="viewport" content="width=device-width, initial-scale=1">');

        $styles = [];
        $out->addModuleStyles($styles);
    }
}

/**
 * Template class of the MediaWikiBootstrap Skin
 * @ingroup Skins
 */
class MediaWikiBootstrapTemplate extends BaseTemplate
{

    /**
     * Outputs the entire contents of the page
     */
    public function execute()
    {
        global $wgGroupPermissions;
        global $wgSearchPlacement;
        global $wgMediaWikiBootstrapSkinLoginLocation;
        global $wgMediaWikiBootstrapSkinAnonNavbar;
        global $wgMediaWikiBootstrapSkinUseStandardLayout;


        // Suppress warnings to prevent notices about missing indexes in $this->data
        AtEase::suppressWarnings();

        // search box locations
        if (!$wgSearchPlacement) {
            $wgSearchPlacement['top-nav'] = true;
            $wgSearchPlacement['nav'] = true;
        }

        // Build additional attributes for navigation urls
        $nav = $this->data['content_navigation'];

        $xmlID = '';
        foreach ($nav as $section => $links) {
            foreach ($links as $key => $link) {
                if ($section == 'views' && !(isset($link['primary']) && $link['primary'])) {
                    $link['class'] = rtrim('collapsible ' . $link['class'], ' ');
                }

                $xmlID = isset($link['id']) ? $link['id'] : 'ca-' . $xmlID;
                $nav[$section][$key]['attributes'] = ' id="' . Sanitizer::escapeIdForAttribute($xmlID) . '"';
                if ($link['class']) {
                    $nav[$section][$key]['attributes'] .=
                        ' class="' . htmlspecialchars($link['class']) . '"';
                    unset($nav[$section][$key]['class']);
                }
                if (isset($link['tooltiponly']) && $link['tooltiponly']) {
                    $nav[$section][$key]['key'] = Linker::tooltip($xmlID);
                } else {
                    $nav[$section][$key]['key'] = Xml::expandAttributes(Linker::tooltipAndAccesskeyAttribs($xmlID));
                }
            }
        }
        $this->data['namespace_urls'] = $nav['namespaces'];
        $this->data['view_urls'] = $nav['views'];
        $this->data['action_urls'] = $nav['actions'];
        $this->data['variant_urls'] = $nav['variants'];

        // Output HTML Page
?>
        <div id="wrapper" class="container">
            <!-- start navbar -->

            <?php if ($wgGroupPermissions['*']['edit'] || $wgMediaWikiBootstrapSkinAnonNavbar || $this->data['loggedin']) : ?>

                <div class="navbar navbar-default" role="navigation">
                    <div class="container-fluid">
                        <div class="navbar-header">
                            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#main-nav-top">
                                <span class="sr-only">Toggle navigation</span>
                                <span class="icon-bar"></span>
                                <span class="icon-bar"></span>
                                <span class="icon-bar"></span>
                            </button>
                            <a class="navbar-brand visible-xs" href="#"><?php $this->html('sitename'); ?></a>
                        </div>
                        <div class="navbar-collapse collapse" id="main-nav-top">
                            <?php
                            # Page options & menu
                            $this->renderNavigation(array('PAGE-OPTIONS'));
                            /*
                        # This content in other languages
                        if ($this->data['language_urls']) {
                            $this->renderNavigation(array('LANGUAGES'));
                        }
                        */
                            # Edit button
                            $this->renderNavigation(array('EDIT'));

                            # Actions menu
                            $this->renderNavigation(array('ACTIONS'));

                            # Sidebar items to display in navbar
                            $this->renderNavigation(array('SIDEBARNAV'));

                            # Toolbox
                            if (!isset($portals['TOOLBOX'])) {
                                $this->renderNavigation(array('TOOLBOX'));
                            }

                            # Personal menu (at the right)
                            $this->renderNavigation(array('PERSONAL'));

                            # Search box (at the right)
                            if ($wgSearchPlacement['top-nav']) {
                                $this->renderNavigation(array('TOP-NAV-SEARCH'));
                            }

                            ?>
                        </div><!--/.nav-collapse -->
                    </div><!--/.container-fluid -->
                </div> <!-- /navbar -->
            <?php endif; ?>

            <div id="mw-page-base" class="noprint"></div>
            <div id="mw-head-base" class="noprint"></div>

            <?php
            if ($this->data['loggedin']) {
                $userStateClass = "user-loggedin";
            } else {
                $userStateClass = "user-loggedout";
            }
            ?>

            <?php
            if ($wgGroupPermissions['*']['edit'] || $this->data['loggedin']) {
                $userStateClass .= " editable";
            } else {
                $userStateClass .= " not-editable";
            }
            ?>

            <section id="header">
                <div id="page-header" class="row">
                    <!--site logo-->
                    <div id="logo" class="col-xm-12 col-sm-12 col-md-12 col-lg-12">
                        <div class="<?php echo $this->data['loggedin'] ? 'signed-in' : 'signed-out'; ?>">
                            <?php $this->renderLogo(); ?>
                        </div>
                    </div>

                    <div class="clearfix"></div>


                    <div id="main-nav-area" class="col-xm-12 col-sm-12 col-md-12 col-lg-12">
                        <!--navigation menu-->
                        <nav id="main-navbar" class="navbar navbar-default main-navbar" role="navigation">
                            <div class="container-fluid">
                                <!-- Brand and toggle get grouped for better mobile display -->
                                <div class="navbar-header">
                                    <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#main-nav">
                                        <span class="sr-only">Toggle navigation</span>
                                        <span class="icon-bar"></span>
                                        <span class="icon-bar"></span>
                                        <span class="icon-bar"></span>
                                    </button>
                                    <a class="navbar-brand visible-xs" href="#"><?php $this->html('sitename'); ?></a>
                                </div>

                                <div class="collapse navbar-collapse" id="main-nav">

                                    <ul class="nav navbar-nav navbar-right">
                                        <?php
                                        foreach ($this->getSidebar() as $boxName => $box) { ?>
                                            <!-- <div id="<?php echo Sanitizer::escapeIdForAttribute($box['id']) ?>"<?php echo Linker::tooltip($box['id']) ?>> -->
                                            <!-- <h5><?php echo htmlspecialchars($box['header']); ?></h5> -->
                                            <!-- If you do not want the words "Navigation" or "Tools" to appear, you can safely remove the line above. -->

                                            <?php
                                            if (htmlspecialchars($box['header']) == "Tools" || htmlspecialchars($box['header']) == "সরঞ্জাম") {
                                                continue;
                                            } elseif (htmlspecialchars($box['header']) == "In other languages" || htmlspecialchars($box['header']) == "অন্যান্য ভাষাসমূহ") {
                                                continue;
                                            } elseif (is_array($box['content'])) { ?>
                                                <?php
                                                foreach ($box['content'] as $key => $item) {
                                                    echo $this->makeListItem($key, $item);
                                                }
                                                ?>
                                            <?php
                                            } else {
                                                echo $box['content'];
                                            }
                                            ?>
                                            <!-- </div> -->
                                        <?php } ?>
                                        <?php
                                        // $this->renderNavigation(array('SIDEBAR'));

                                        if ($wgSearchPlacement['nav']) {
                                            $this->renderNavigation(array('SEARCHNAV'));
                                        }
                                        /*
                                        # This content in other languages
                                        if ($this->data['language_urls']) {
                                                $this->renderNavigation(array('LANGUAGES'));
                                        }
                                        */
                                        ?>
                                    </ul>
                                </div><!-- /.navbar-collapse -->
                            </div><!-- /.container-fluid -->
                        </nav>

                    </div>
                </div>
            </section>
            <!-- /page-header -->

            <!-- content -->
            <section id="content" class="mw-body <?php echo $userStateClass; ?>">
                <div id="top"></div>
                <div class="row">
                    <div id="mw-js-message" class="col-xm-12 col-sm-12 col-md-12 col-lg-12" style="display:none;" <?php $this->html('userlangattributes') ?>></div>
                </div>

                <?php if ($this->data['sitenotice']) : ?>
                    <!-- sitenotice -->

                    <div class="row">
                        <div class="col-xm-12 col-sm-offset-1 col-sm-10 col-md-offset-1 col-md-10 col-lg-offset-1 col-lg-10">

                            <div id="siteNotice" class="alert alert-info">
                                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                                <?php $this->html('sitenotice') ?>
                            </div>

                        </div>
                    </div>

                    <!-- /sitenotice -->
                <?php endif; ?>

                <div class="clearfix"></div>
                <div id="bodyContent" class="row">
                    <?php if ($this->data['newtalk']) : ?>
                        <!-- newtalk -->

                        <div class="usermessage col-xm-12 col-sm-offset-1 col-sm-10 col-md-offset-1 col-md-10 col-lg-offset-1 col-lg-10">

                            <div class="alert alert-success">
                                <i class="fa fa-comments"></i>
                                <?php $this->html('newtalk') ?>
                            </div>

                        </div>


                        <!-- /newtalk -->
                    <?php endif; ?>


                    <?php if ($this->data['showjumplinks']) : ?>
                        <!-- jumpto -->
                        <!-- <div id="jump-to-nav" class="mw-jump">
                            <?php $this->msg('jumpto') ?>
                            <a href="#mw-head"><?php $this->msg('jumptonavigation') ?></a>,
                            <a href="#p-search"><?php $this->msg('jumptosearch') ?></a>
                        </div> -->
                        <!-- /jumpto -->
                    <?php endif; ?>

                    <!-- innerbodycontent -->
                    <div id="innerbodycontent">
                        <div class="col-xm-12 col-sm-offset-1 col-sm-8 col-md-offset-1 col-md-8 col-lg-offset-1 col-lg-8">
                            <h1 id="firstHeading" class="firstHeading page-header">
                                <span dir="auto"><?php $this->html('title') ?></span>
                            </h1>
                        </div>
                        <div id="other_language_link" class="col-xm-12 col-sm-2 col-md-2 col-lg-2 pull-right">
                            <?php
                            if ($this->data['language_urls']) {
                                $this->renderNavigation(array('LANGUAGES'));
                            }
                            ?>
                        </div>
                        <div class="col-xm-12 col-sm-offset-1 col-sm-10 col-md-offset-1 col-md-10 col-lg-offset-1 col-lg-10">
                            <!-- subtitle -->
                            <div id="contentSub" <?php $this->html('userlangattributes') ?>><?php $this->html('subtitle') ?></div>
                            <!-- /subtitle -->
                            <?php if ($this->data['undelete']) : ?>
                                <!-- undelete -->
                                <div id="contentSub2"><?php $this->html('undelete') ?></div>
                                <!-- /undelete -->
                            <?php endif; ?>
                            <?php $this->html('bodycontent'); ?>
                        </div>
                    </div>
                    <!-- /innerbodycontent -->

                    <?php if ($this->data['printfooter']) : ?>
                        <!-- printfooter -->
                        <div class="printfooter">
                            <?php $this->html('printfooter'); ?>
                        </div>
                        <!-- /printfooter -->
                    <?php endif; ?>
                    <?php if ($this->data['catlinks']) : ?>
                        <!-- catlinks -->
                        <div class="row">
                            <div class="col-xm-12 col-sm-offset-1 col-sm-10 col-md-offset-1 col-md-10 col-lg-offset-1 col-lg-10">
                                <?php $this->html('catlinks'); ?>
                            </div>
                        </div>
                        <!-- /catlinks -->
                    <?php endif; ?>
                    <?php if ($this->data['dataAfterContent']) : ?>
                        <!-- dataAfterContent -->
                        <div class="row">
                            <div class="col-xm-12 col-sm-offset-1 col-sm-10 col-md-offset-1 col-md-10 col-lg-offset-1 col-lg-10">
                                <?php $this->html('dataAfterContent'); ?>
                            </div>
                        </div>
                        <!-- /dataAfterContent -->
                    <?php endif; ?>
                    <div class="visualClear"></div>
                    <!-- debughtml -->
                    <?php $this->html('debughtml'); ?>
                    <!-- /debughtml -->
                </div>
            </section>
            <!-- /content -->

        </div>
        <!-- /#wrapper -->


        <?php
        /* Support a custom footer, or use MediaWiki's default, if footer.php does not exist. */
        $footerfile = dirname(__FILE__) . '/footer.php';

        if (file_exists($footerfile)) : ?>
            <div id="footer" class="text-center footer container" style="padding-top:20px;" <?php $this->html('userlangattributes') ?>>
                <div class="row">
                    <div class="col-xm-12 col-sm-12 col-md-12 col-lg-12">
                        <?php include($footerfile); ?>
                        <ul class="list-inline">
                            <li id="pt-login">
                                <?php if (!$this->data['loggedin']) : ?>
                                    <a href="#"><?php echo $personalTemp[$loginType]['links'][0]['text']; ?></a>
                                <?php endif; ?>
                            </li>
                        </ul>
                    </div>
                </div>
            </div><?php
                else : ?>
            <div id="footer" class="footer container" style="padding-top:20px;" <?php $this->html('userlangattributes') ?>>
                <div class="row">
                    <?php

                    $footerLinks = $this->getFooterLinks();

                    if (is_array($footerLinks)) {

                        foreach ($footerLinks as $category => $links) : ?>

                            <ul id="footer-<?php echo $category ?>" class="list-inline text-center">
                                <?php foreach ($links as $link) : ?>
                                    <li id="footer-<?php echo $category ?>-<?php echo $link ?>"><?php $this->html($link) ?></li>
                                <?php endforeach; ?>
                                <?php
                                if ($category === 'places') {

                                    # Show sign in link, if not signed in
                                    if ($wgMediaWikiBootstrapSkinLoginLocation == 'footer' && !$this->data['loggedin']) {
                                        $personalTemp = $this->getPersonalTools();

                                        if (isset($personalTemp['login'])) {
                                            $loginType = 'login';
                                        } else {
                                            $loginType = 'anonlogin';
                                        }
                                ?>
                                        <li id="pt-login">
                                            <a href="<?php echo $personalTemp[$loginType]['links'][0]['href'] ?>"><?php echo $personalTemp[$loginType]['links'][0]['text']; ?></a>
                                        </li><?php
                                            }
                                        } ?>
                            </ul>
                        <?php
                        endforeach;
                    }

                    $footericons = $this->get('footericons');
                    foreach ($footericons as $footerIconsKey => &$footerIconsBlock) {
                        foreach ($footerIconsBlock as $footerIconKey => $footerIcon) {
                            if (!isset($footerIcon['src'])) {
                                unset($footerIconsBlock[$footerIconKey]);
                            }
                        }
                    }
                    if (count($footericons) > 0) :
                        ?>
                        <ul id="footer-icons" class="noprint list-inline text-center">
                            <?php foreach ($footericons as $blockName => $footerIcons) : ?>
                                <li id="footer-<?php echo htmlspecialchars($blockName); ?>ico">
                                    <?php foreach ($footerIcons as $icon) : ?>
                                        <?php echo $this->getSkin()->makeFooterIcon($icon); ?>

                                    <?php endforeach; ?>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    <?php endif; ?>
                </div>
            </div>
            <!-- /footer -->
            <?php endif; ?><?php
                            AtEase::restoreWarnings();
                        }


                        /**
                         * Render logo
                         */
                        private function renderLogo()
                        {
                            $mainPageLink = $this->data['nav_urls']['mainpage']['href'];
                            $toolTip = Xml::expandAttributes(Linker::tooltipAndAccesskeyAttribs('p-logo'));
                            ?>
            <div id="p-logo" class="text-center">
                <a href="<?php echo htmlspecialchars($this->data['nav_urls']['mainpage']['href']) ?>" <?php echo Xml::expandAttributes(Linker::tooltipAndAccesskeyAttribs('p-logo')) ?>><img class="logo_image" src="<?php $this->text('logopath'); ?>" alt="<?php $this->html('sitename'); ?>" /></a>
            </div>
            <?php
                        }


                        /**
                         * Render one or more navigations elements by name, automatically reveresed
                         * when UI is in RTL mode
                         *
                         * @param $elements array
                         */
                        private function renderNavigation($elements)
                        {
                            global $wgVectorUseSimpleSearch;
                            global $wgMediaWikiBootstrapSkinDisplaySidebarNavigation;
                            global $wgMediaWikiBootstrapSkinSidebarItemsInNavbar;
                            // If only one element was given, wrap it in an array, allowing more
                            // flexible arguments
                            if (!is_array($elements)) {
                                $elements = array($elements);
                                // If there's a series of elements, reverse them when in RTL mode
                            } elseif ($this->data['rtl']) {
                                $elements = array_reverse($elements);
                            }
                            // Render elements
                            foreach ($elements as $name => $element) {
                                echo "\n<!-- {$name} -->\n";
                                switch ($element):

                                    case 'PAGE-OPTIONS':
                                        $theMsg = 'namespaces';
                                        $theData = array_merge($this->data['namespace_urls'], $this->data['view_urls']);
            ?>
                        <ul class="nav navbar-nav" role="navigation">
                            <li id="p-<?php echo $theMsg; ?>" class="dropdown <?php if (count($theData) == 0) echo ' emptyPortlet'; ?>">
                                <?php foreach ($theData as $link) :
                                            if (array_key_exists('context', $link) && $link['context'] == 'subject') : ?>
                                        <a data-toggle="dropdown" class="dropdown-toggle navbar-brand" role="menu">
                                            <?php echo htmlspecialchars($link['text']); ?> <b class="caret"></b>
                                        </a>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                                <ul class="dropdown-menu" aria-labelledby="<?php echo $this->msg($theMsg); ?>" role="menu" <?php $this->html('userlangattributes') ?>>
                                    <?php foreach ($theData as $link) :
                                            # Skip a few redundant links
                                            if (preg_match('/^ca-(view|edit)$/', $link['id'])) {
                                                continue;
                                            } ?>
                                        <li<?php echo $link['attributes'] ?>>
                                            <a href="<?php echo htmlspecialchars($link['href']) ?>" <?php echo $link['key'] ?> tabindex="-1"><?php echo htmlspecialchars($link['text']) ?></a>
                            </li>
                        <?php endforeach; ?>
                        </ul>
                        </li>
                        </ul>
                        <?php
                                        break;

                                    case 'EDIT':
                                        if (!array_key_exists('edit', $this->data['content_actions'])) {
                                            break;
                                        }
                                        $navTemp = $this->data['content_actions']['edit'];

                                        if ($navTemp) {
                        ?>
                            <ul class="nav navbar-nav">
                                <li>
                                    <a id="b-edit" href="<?php echo $navTemp['href']; ?>" class="btn btn-default">
                                        <i class="fa fa-edit"></i> <strong><?php echo $navTemp['text']; ?></strong>
                                    </a>
                                </li>
                            </ul>
                        <?php
                                        }
                                        break;

                                    case 'ACTIONS':

                                        $theMsg = 'actions';
                                        $theData = array_reverse($this->data['action_urls']);

                                        if (count($theData) > 0) : ?>
                            <ul class="nav navbar-nav" role="navigation">
                                <li class="dropdown" id="p-<?php echo $theMsg; ?>" class="vectorMenu<?php if (count($theData) == 0) echo ' emptyPortlet'; ?>">
                                    <a data-toggle="dropdown" class="dropdown-toggle" role="button"><?php echo $this->msg('actions'); ?> <b class="caret"></b></a>
                                    <ul aria-labelledby="<?php echo $this->msg($theMsg); ?>" role="menu" class="dropdown-menu" <?php $this->html('userlangattributes') ?>>
                                        <?php foreach ($theData as $link) :

                                                if (preg_match('/MovePage/', $link['href'])) {
                                                    echo '<li class="divider"></li>';
                                                }
                                        ?>

                                            <li<?php echo $link['attributes'] ?>>
                                                <a href="<?php echo htmlspecialchars($link['href']) ?>" <?php echo $link['key'] ?> tabindex="-1"><?php echo htmlspecialchars($link['text']) ?></a>
                                </li>
                            <?php endforeach; ?>
                            </ul>
                            </li>
                            </ul><?php
                                        endif;

                                        break;

                                    case 'SIDEBARNAV':
                                        foreach ($this->data['sidebar'] as $name => $content) :
                                            if (!$content) {
                                                continue;
                                            }
                                            if (!in_array($name, $wgMediaWikiBootstrapSkinSidebarItemsInNavbar)) {
                                                continue;
                                            }
                                            $msgObj = wfMessage($name);
                                            $name = htmlspecialchars($msgObj->exists() ? $msgObj->text() : $name);
                                    ?>
                            <ul class="nav navbar-nav" role="navigation">
                                <li class="dropdown" id="p-<?php echo $name; ?>" class="vectorMenu">
                                    <a data-toggle="dropdown" class="dropdown-toggle" role="menu">
                                        <?php echo htmlspecialchars($name); ?> <b class="caret"></b>
                                    </a>
                                    <ul aria-labelledby="<?php echo htmlspecialchars($name); ?>" role="menu" class="dropdown-menu" <?php $this->html('userlangattributes') ?>><?php
                                                                                                                                                                                # This is a rather hacky way to name the nav.
                                                                                                                                                                                # (There are probably bugs here...)
                                                                                                                                                                                foreach ($content as $key => $val) :
                                                                                                                                                                                    $navClasses = '';

                                                                                                                                                                                    if (array_key_exists('view', $this->data['content_navigation']['views']) && $this->data['content_navigation']['views']['view']['href'] == $val['href']) {
                                                                                                                                                                                        $navClasses = 'active';
                                                                                                                                                                                    }
                                                                                                                                                                                ?>

                                            <li class="<?php echo $navClasses ?>"><?php echo $this->makeLink($key, $val); ?></li><?php

                                                                                                                                                                                endforeach; ?>
                                    </ul>
                                <li>
                            </ul><?php
                                        endforeach;
                                        break;

                                    case 'TOOLBOX':

                                        $theMsg = 'toolbox';
                                        $theData = array_reverse($this->data['sidebar']['TOOLBOX']);
                                    ?>

                        <ul class="nav navbar-nav" role="navigation">

                            <li id="p-<?php echo $theMsg; ?>" class="dropdown<?php if (count($theData) == 0) echo ' emptyPortlet'; ?>">

                                <a data-toggle="dropdown" class="dropdown-toggle" role="button">
                                    <?php $this->msg($theMsg) ?> <b class="caret"></b>
                                </a>

                                <ul class="dropdown-menu" aria-labelledby="<?php echo $this->msg($theMsg); ?>" role="menu" <?php $this->html('userlangattributes') ?>>

                                    <?php
                                        foreach ($theData as $key => $item) :

                                            if (preg_match('/specialpages|whatlinkshere/', $key)) {
                                                echo '<li class="divider"></li>';
                                            }

                                            echo $this->makeListItem($key, $item);

                                        endforeach;
                                    ?>
                                </ul>

                            </li>

                        </ul>
                    <?php
                                        break;

                                    case 'TOP-NAV-SEARCH':
                    ?>
                        <form class="navbar-form navbar-right" action="<?php $this->text('wgScript') ?>" id="nav-searchform">
                            <input id="nav-searchInput" class="form-control search-query" type="search" accesskey="f" title="<?php $this->text('searchtitle'); ?>" placeholder="<?php $this->msg('search'); ?>" name="search" value="<?php echo htmlspecialchars($this->data['search']); ?>">
                            <?php echo $this->makeSearchButton('fulltext', array('id' => 'mw-searchButton', 'class' => 'searchButton btn hidden')); ?>
                        </form>

                    <?php
                                        break;

                                    case 'PERSONAL':
                                        $theMsg = 'personaltools';
                                        $theData = $this->getPersonalTools();
                                        $theTitle = $this->data['username'];
                                        $showPersonal = false;
                                        foreach ($theData as $key => $item) :
                                            if (!preg_match('/(notifications|login|createaccount)/', $key)) {
                                                $showPersonal = true;
                                            }
                                        endforeach;
                    ?>
                        <ul class="nav navbar-nav pull-right" role="navigation">

                            <li id="p-notifications" class="dropdown<?php if (count($theData) == 0) echo ' emptyPortlet'; ?>">
                                <?php
                                        if (array_key_exists('notifications', $theData)) {
                                            echo $this->makeListItem('notifications', $theData['notifications']);
                                        }
                                ?>
                            </li>
                            <?php if ($this->getSkin()->getConfig('MediaWikiBootstrapSkinLoginLocation') == 'navbar') : ?>
                                <li class="dropdown" id="p-createaccount" class="vectorMenu<?php if (count($theData) == 0) echo ' emptyPortlet'; ?>">
                                    <?php
                                            if (array_key_exists('createaccount', $theData)) {
                                                echo $this->makeListItem('createaccount', $theData['createaccount']);
                                            }
                                    ?>
                                </li>
                                <li class="dropdown" id="p-login" class="vectorMenu<?php if (count($theData) == 0) echo ' emptyPortlet'; ?>">
                                    <?php
                                            if (array_key_exists('login', $theData)) {
                                                echo $this->makeListItem('login', $theData['login']);
                                            }
                                    ?>
                                </li>
                            <?php endif; ?>
                            <?php
                                        if ($showPersonal) :
                            ?>
                                <li id="p-<?php echo $theMsg; ?>" class="dropdown<?php if (!$showPersonal) echo ' emptyPortlet'; ?>">
                                    <a data-toggle="dropdown" class="dropdown-toggle" role="button">
                                        <i class="fa fa-user"></i>
                                        <?php echo $theTitle; ?> <b class="caret"></b></a>
                                    <ul aria-labelledby="<?php echo $this->msg($theMsg); ?>" role="menu" class="dropdown-menu" <?php $this->html('userlangattributes') ?>>
                                        <?php
                                            foreach ($theData as $key => $item) :

                                                if (preg_match('/preferences|logout/', $key)) {
                                                    echo '<li class="divider"></li>';
                                                } else if (preg_match('/(notifications|login|createaccount)/', $key)) {
                                                    continue;
                                                }

                                                echo $this->makeListItem($key, $item);

                                            endforeach;
                                        ?>
                                    </ul>
                                </li>
                            <?php endif; ?>
                        </ul>
                        <?php
                                        break;

                                    case 'SIDEBAR':
                                        foreach ($this->data['sidebar'] as $name => $content) {
                                            if (!isset($content)) {
                                                continue;
                                            }
                                            if (in_array($name, $wgMediaWikiBootstrapSkinSidebarItemsInNavbar)) {
                                                continue;
                                            }
                                            $msgObj = wfMessage($name);
                                            $name = htmlspecialchars($msgObj->exists() ? $msgObj->text() : $name);
                                            if ($wgMediaWikiBootstrapSkinDisplaySidebarNavigation) {
                        ?>
                                <li class="dropdown">
                                    <a data-toggle="dropdown" class="dropdown-toggle" role="button">
                                        <?php echo htmlspecialchars($name); ?><b class="caret"></b>
                                    </a>
                                    <ul aria-labelledby="<?php echo htmlspecialchars($name); ?>" role="menu" class="dropdown-menu"><?php
                                                                                                                                }
                                                                                                                                # This is a rather hacky way to name the nav.
                                                                                                                                # (There are probably bugs here...)
                                                                                                                                foreach ($content as $key => $val) :
                                                                                                                                    $navClasses = '';

                                                                                                                                    if (array_key_exists('view', $this->data['content_navigation']['views']) && $this->data['content_navigation']['views']['view']['href'] == $val['href']) {
                                                                                                                                        $navClasses = 'active';
                                                                                                                                    }
                                                                                                                                    ?>

                                        <li class="<?php echo $navClasses ?> main-nav-li"><?php echo $this->makeLink($key, $val); ?></li><?php
                                                                                                                                        endforeach;

                                                                                                                                        if ($wgMediaWikiBootstrapSkinDisplaySidebarNavigation) { ?>
                                    </ul>
                                </li><?php
                                                                                                                                        }
                                                                                                                                    }
                                                                                                                                    break;

                                                                                                                                case 'SEARCHNAV':
                                        ?>
                        <li>
                            <form class="navbar-form navbar-right" action="<?php $this->text('wgScript') ?>" id="searchform">
                                <input id="searchInput" class="form-control" type="search" accesskey="f" title="<?php $this->text('searchtitle'); ?>" placeholder="<?php $this->msg('search'); ?>" name="search" value="<?php echo htmlspecialchars($this->data['search']); ?>">
                                <?php echo $this->makeSearchButton('fulltext', array('id' => 'mw-searchButton', 'class' => 'searchButton btn hidden')); ?>
                            </form>
                        </li>

                    <?php
                                                                                                                                    break;

                                                                                                                                case 'LANGUAGES':
                                                                                                                                    $theMsg = 'otherlanguages';
                                                                                                                                    $theData = $this->data['language_urls'];
                                                                                                                                    $options = [];
                    ?>
                        <?php foreach ($theData as $key => $val) : ?>
                            <li class="<?php echo $navClasses ?>">
                                <?php echo $this->getSkin()->makeLink($key, $val, $options); ?>
                            </li>
                        <?php endforeach; ?>
    <?php
                                                                                                                                    break;

                                                                                                                            endswitch;
                                                                                                                        }
                                                                                                                    }
                                                                                                                }
