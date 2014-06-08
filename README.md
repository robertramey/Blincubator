Blincubator
===========

Boost Library Incubator website

<ul>
  <li>Built with Wordpress</li>
  <li>Plus nine Wordpress "plug-ins"</li>
  <li>Plus approximately 1000 lines of php code in 7  files</li>
  <li>Pages built with DocBook and converted to html via xslt</li>
  <li>Maintains compatibility with current Boost practices</li>
  <li>Complements Boost Modularization with GitHub to move Boost to the 21st century social media/interactive model for creative and collaborative development</li>
</ul>

Notes on Setting up version on local Mac OS machine
===================================================
<ul>
  <li>I've found the best source for information on this to be at http://coolestguidesontheplanet.com/get-apache-mysql-php-phpmyadmin-working-osx-10-9-mavericks/ 
It focuses on using/configuring the tools already installed on the Mac to do this.  That is PHP and Apache server are already installed.  What remains to be downloaded and configured are:
  <ul>
    <li>MySQL</li>
    <li>phpMyAdmin - for administering the My SQL installation </li>
  </ul>
  </li>
  <li>I had to change the default PHP configuration to permit input of larger files.  This was needed to permit loading of a back up database to the local installation.  In my case this entailed editing /private/etc/php.ini .  For this to take effect, I had to reboot my system!
  </li>
</ul>