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
class MediaWikiBootstrapTemplate extends BaseTemplate {

    /**
     * Outputs the entire contents of the page
     */
    public function execute() {
        global $wgGroupPermissions;
        global $wgVectorUseIconWatch;
        global $wgSearchPlacement;
        
        // Suppress warnings to prevent notices about missing indexes in $this->data
        wfSuppressWarnings();
        
        if (!$wgSearchPlacement) {
            $wgSearchPlacement['top-nav'] = true;
            $wgSearchPlacement['nav'] = true;
            $wgSearchPlacement['footer'] = false;
        }
        
        // Build additional attributes for navigation urls
        $nav = $this->data['content_navigation'];

        if ($wgVectorUseIconWatch) {
            $mode = $this->getSkin()->getTitle()->userIsWatching() ? 'unwatch' : 'watch';
            if (isset($nav['actions'][$mode])) {
                $nav['views'][$mode] = $nav['actions'][$mode];
                $nav['views'][$mode]['class'] = rtrim('icon ' . $nav['views'][$mode]['class'], ' ');
                $nav['views'][$mode]['primary'] = true;
                unset($nav['actions'][$mode]);
            }
        }

        $xmlID = '';
        foreach ($nav as $section => $links) {
            foreach ($links as $key => $link) {
                if ($section == 'views' && !( isset($link['primary']) && $link['primary'] )) {
                    $link['class'] = rtrim('collapsible ' . $link['class'], ' ');
                }

                $xmlID = isset($link['id']) ? $link['id'] : 'ca-' . $xmlID;
                $nav[$section][$key]['attributes'] = ' id="' . Sanitizer::escapeId($xmlID) . '"';
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
        $this->html('headelement');
        ?>
        <div id="globalWrapper" class="container">
            <!-- start navbar -->
            <div class="navbar navbar-default" role="navigation">
                <div class="container-fluid">
                    <div class="navbar-header">
                        <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                            <span class="sr-only">Toggle navigation</span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                        </button>
                        <a class="navbar-brand visible-xs" href="#"><?php $this->html('sitename'); ?></a>
                    </div>
                    <div class="navbar-collapse collapse">
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
        </div><!-- /#globalWrapper -->

        // [...]

        <?php $this->printTrail(); ?>
        </body>
        </html><?php
        wfRestoreWarnings();
    }
    
    /**
     * Render one or more navigations elements by name, automatically reveresed
     * when UI is in RTL mode
     *
     * @param $elements array
     */
    private function renderNavigation($elements) {
        global $wgVectorUseSimpleSearch;
//        global $wgStrappingSkinLoginLocation;
//        global $wgStrappingSkinDisplaySidebarNavigation;
//        global $wgStrappingSkinSidebarItemsInNavbar;
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
            switch ($element) :

                case 'PAGE-OPTIONS':
                    $theMsg = 'namespaces';
                    $theData = array_merge($this->data['namespace_urls'], $this->data['view_urls']);
                    ?>
                    <ul class="nav navbar-nav" role="navigation">
                        <li id="p-<?php echo $theMsg; ?>" class="dropdown <?php if (count($theData) == 0) echo ' emptyPortlet'; ?>">
                            <?php foreach ($theData as $link) :
                                if (array_key_exists('context', $link) && $link['context'] == 'subject') :?>
                                    <a data-toggle="dropdown" class="dropdown-toggle navbar-brand" role="menu">
                                        <?php echo htmlspecialchars($link['text']); ?> <b class="caret"></b>
                                    </a>
                                <?php endif; ?>
                            <?php endforeach; ?>
                            <ul class="dropdown-menu" aria-labelledby="<?php echo $this->msg($theMsg); ?>" role="menu"  <?php $this->html('userlangattributes') ?>>
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
                                    <?php foreach ($theData as $link):

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
                        if (!in_array($name, $wgStrappingSkinSidebarItemsInNavbar)) {
                            continue;
                        }
                        $msgObj = wfMessage($name);
                        $name = htmlspecialchars($msgObj->exists() ? $msgObj->text() : $name );
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
                    $theData = array_reverse($this->getToolbox());
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
                    <form class="navbar-form navbar-right" action="<?php $this->text('wgScript') ?>" id="searchform">
                        <input id="searchInput" class="form-control search-query" type="search" accesskey="f" title="<?php $this->text('searchtitle'); ?>" placeholder="<?php $this->msg('search'); ?>" name="search" value="<?php echo htmlspecialchars($this->data['search']); ?>">
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
                        <?php if ($wgStrappingSkinLoginLocation == 'navbar'): ?>
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
                    
                    
                    
            endswitch;
        }
    }

}
