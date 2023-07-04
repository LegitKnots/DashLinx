# DashLinx
 Customizable network dashboard that is optimazed to be used for a browser startpage.  It features customizeable shortcut links and a custom search bar.

 This dashboard project was created by AJP Networks as an open source project that may be freely distributed without license.

Few things to get started on this, first off this is raw source code only right now (I am putting together an install script that can install dependancies for the code).
You will need to have a webservice running with a MySQL database on any type of server.

You will need to head into the setup.php file and define a few things, first off some basic things like the page name and tad name, along with database credentials.
Then you will need to specify the public html folder, e.g. for Apache, it defaults to /var/www/html, so you would put in '/var/www/html' without the trailing backslash.

After that you should be good to go, well another thing I should note is that you need to make sure your PHP settings are set up properly, usually this will be you php.ini file, you will need to increase the max post size and max file upload size to something appropriate, something like 5-10M should be enough.  This is to eliminate errors that you may run into when uploading larger images for either icons or the background.

Last but not least, This software /code is open source and free but donations are appreciated to help keep thios project going.  I accept donations through PayPal at the following link

https://paypal.me/kn0t5

