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


## Examples
This mediawiki skin is used in the following encyclopedia sites,
* https://en.banglapedia.org
* https://bn.banglapedia.org

## Screenshots

### Banglapedia (English)/ বাংলাপিডিয়া (ইংরেজি)

![Screenshot English Banglapedia](https://user-images.githubusercontent.com/396987/105332985-6a1cea00-5bff-11eb-9a6e-1cb771fca9f7.png)

### Banglapedia (Bangla)/ বাংলাপিডিয়া (বাংলা)

![Screenshot Bangla Banglapedia](https://user-images.githubusercontent.com/396987/105333071-7ef97d80-5bff-11eb-9896-cc7639691343.png)

