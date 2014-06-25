MediaWikiBootstrap Skin
===================

A customizable responsive MediaWiki skin based on Bootstrap3. Check the [MediaWiki-Bootstrap Github repo](https://github.com/nasirkhan/mediawiki-bootstrap) to get the updated documentation.  


## Getting Started

1. Go to the *"skins"* subdirectory of your MediaWiki installation:

   ```
   cd skins
   ```

2. Clone the repository form the git repo:

   ```
   git clone https://github.com/nasirkhan/mediawiki-bootstrap strapping
   ```

3. Update the `LocalSettings.php` to install and enable the skin: 

   ```php
    require_once( "$IP/skins/MediaWikiBootstrap/MediaWikiBootstrap.php" );
    $wgDefaultSkin = "mediawikibootstrap";
    ```
   
   (Please remove or comment out other mentions of
   `$wgDefaultSkin`.)

4. Edit the wiki page `MediaWiki:Sidebar` of your mediawiki installation to change the navigation links .

5. Customize the other settings and modify the style based on your need. 


## Example 
This mediawiki skin is used in the following encyclopedia sites,
* http://en.banglapedia.org
* http://bn.banglapedia.org

