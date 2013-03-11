twitter_archive_to_xml
======================

Convert the JSON archive you can download from Twitter to XML.

First, request your Twitter archive from the Twitter settings page.

Download it as soon as the e-mail with your download link arrives.

Unzip the downladed archive file.

Run digicol_twitter_archive_to_xml.php to view one of the JSON tweet data files as XML:

    php digicol_twitter_archive_to_xml.php tweets/data/js/tweets/2013_01.js

If you want to, convert all of the tweet data files to XML files (.xml in the same directory):

    php digicol_twitter_archive_to_xml.php --write tweets/data/js/tweets/*.js

The XML format is a simple, exact representation of the JSON field names (see example output below).
The field names are documented by Twitter here: https://dev.twitter.com/docs/platform-objects/tweets
If this PHP script adds its own data, the field name will start with an underscore.

Requires PHP 5.2 or later.

Licensed under the PHP License. Use at your own risk!

Tim Strehle http://www.strehle.de/tim/

Example output:

    <?xml version="1.0" encoding="UTF-8"?>
    <tweets>
      <tweet>
        <source><a href="http://itunes.apple.com/us/app/twitter/id409789998?mt=12" rel="nofollow">Twitter for Mac</a></source>
        <entities>
          <user_mentions>
            <name>Google Chrome</name>
            <screen_name>googlechrome</screen_name>
            <indices>14</indices>
            <indices>27</indices>
            <id_str>56505125</id_str>
            <id>56505125</id>
          </user_mentions>
          <media/>
          <hashtags>
            <text>XML</text>
            <indices>28</indices>
            <indices>32</indices>
          </hashtags>
          <urls>
            <indices>105</indices>
            <indices>128</indices>
            <url>https://t.co/ot5PPSDscw</url>
            <expanded_url>https://chrome.google.com/webstore/detail/xml-tree/gbammbheopgpmaagmckhpjbfgdfkpadb</expanded_url>
            <display_url>chrome.google.com/webstore/detai…</display_url>
          </urls>
        </entities>
        <geo/>
        <id_str>309217712125644800</id_str>
        <text>Installed the @googlechrome #XML Tree extension, a nice XML source viewer for text/xml, application/xml. https://t.co/ot5PPSDscw</text>
        <id>309217712125644800</id>
        <created_at>Wed Mar 06 08:23:42 +0000 2013</created_at>
        <_created_at_iso>2013-03-06T09:23:42+01:00</_created_at_iso>
        <user>
          <name>Tim Strehle</name>
          <screen_name>tistre</screen_name>
          <protected>0</protected>
          <id_str>39279413</id_str>
          <profile_image_url_https>https://si0.twimg.com/profile_images/208357962/TimStrehle_normal.jpg</profile_image_url_https>
          <id>39279413</id>
          <verified>0</verified>
        </user>
      </tweet>
    </tweets>
