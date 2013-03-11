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

Requires PHP 5.2 or later.

Licensed under the PHP License. Use at your own risk!

Tim Strehle http://www.strehle.de/tim/
