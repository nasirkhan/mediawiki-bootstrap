MediaWikiBootstrap Skin
===================

A customizable responsive MediaWiki skin based on Bootstrap3. Check the [MediaWiki-Bootstrap Github repo](https://github.com/nasirkhan/mediawiki-bootstrap) to get the updated documentation. You may visit the following link to get more details about this Skin from the Mediawiki portal [Skin:MediaWikiBootstrap](https://www.mediawiki.org/wiki/Skin:MediaWikiBootstrap)


## Getting Started

1. Go to the `skins` subdirectory of your MediaWiki installation:

   ```
   cd skins
   ```

2. Clone the repository form the git repo:

   ```
   git clone https://github.com/nasirkhan/mediawiki-bootstrap MediaWikiBootstrap
   ```

3. To install the skin add the following line to your `LocalSettings.php`:

   ```php
   wfLoadSkin( 'MediaWikiBootstrap' );
    ```
    
    Now update the details skin to MediaWikiBoottrap by changing the value to `$wgDefaultSkin`
    
    ```php
    $wgDefaultSkin = "mediawikibootstrap";
    ```

4. Edit the wiki page `MediaWiki:Sidebar` of your mediawiki installation to change the navigation links .

5. Customize the other settings and modify the style based on your need.


## Customizing the Header

### Navigation Menu

The top navbar links are driven by the wiki page `MediaWiki:Mediawikibootstrap-mainmenu`. Edit it like any other wiki page using the same `* Label|Target` syntax as `MediaWiki:Sidebar`.

Navigate to `MediaWiki:Mediawikibootstrap-mainmenu` and add one link per line:

```
* Main Page|Main Page
* About|About
* Contact|Special:Contact
* External Site|https://example.com
```

- Lines without a `|` separator (section headings) are ignored — the navbar is a flat list.
- The target can be a wiki page name or a full URL.
- Changes take effect immediately after saving.

### Main Page Display Title

You can override the title shown in the header on the main page by editing `MediaWiki:Mediawikibootstrap-mainpage-title`.

Navigate to `MediaWiki:Mediawikibootstrap-mainpage-title` and enter the title text:

```
Welcome to My Wiki
```

If the page does not exist or is empty, the default site name is used.


## Custom Footer Text

You can add custom text (e.g. a credit line) to the footer without touching any code. The skin reads content from the `MediaWiki:Bootstrap-custom-footer` system message page and renders it below the standard footer.

1. Navigate to `MediaWiki:Bootstrap-custom-footer` in your wiki (e.g. `https://yourwiki.com/wiki/MediaWiki:Bootstrap-custom-footer`).
2. Edit the page and add your content using standard wikitext:

   ```
   Developed by [https://example.com Nasir Khan Saikat | Blue Cube]
   ```

3. Save the page — the text will appear in the footer immediately.

If the page does not exist or is empty, no custom footer is shown.


## Footer Copyright Notice

To display a custom copyright text (e.g. "All rights reserved.") without a license icon, set the following in `LocalSettings.php`:

```php
$wgRightsText = "All rights reserved.";
$wgRightsUrl  = "";
$wgRightsIcon = "";
// Prevent a broken <img> tag with alt text appearing in the footer.
// MediaWiki includes the icon block whenever $wgRightsIcon is set (even to ""),
// so the block must be cleared explicitly.
$wgFooterIcons['copyright'] = [];
```

Without `$wgFooterIcons['copyright'] = []`, MediaWiki generates `<img src="" alt="All rights reserved.">` — a broken image whose alt text appears in the footer. Clearing the block removes the image entirely while leaving the copyright text (from `$wgRightsText`) unaffected.


## Examples
This mediawiki skin is used in the following encyclopedia sites,
* https://en.banglapedia.org
* https://bn.banglapedia.org

## Screenshots

### Banglapedia (English)/ বাংলাপিডিয়া (ইংরেজি)

![Screenshot English Banglapedia](https://user-images.githubusercontent.com/396987/105332985-6a1cea00-5bff-11eb-9a6e-1cb771fca9f7.png)

### Banglapedia (Bangla)/ বাংলাপিডিয়া (বাংলা)

![Screenshot Bangla Banglapedia](https://user-images.githubusercontent.com/396987/105333071-7ef97d80-5bff-11eb-9896-cc7639691343.png)

