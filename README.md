MediaBox
========

MediaBox - Your Plex box landing page. Deeply integrated with MyPlex, Plex, PlexWatch, CouchPotato, and SickBeard, it is the one landing page to make multiple users enjoy your Plex box more.

Note: This is still heavily in development. If you *really* want to play along with it, simple open a terminal and type the following:

-----------

cd /path/to/MediaBox/app

Console/cake schema create

-----------

After running those two commands, you will have a fresh database located in your App directory! The default username:password is admin:admin. I recommend you change this ASAP.  To authenticate via MyPlex, simply go to Settings -> User Access List. Click on Administrator, then click on the password area (It should say Password Hidden). Simply remove all that text (make it empty) and press save. Then click on your username, and change that to your MyPlex username. Now when you login, you would simply put in your MyPlex username and password.

To audit the MyPlex authentication, please view the file /app/vendor/PlexAuth.php
