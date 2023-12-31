# DashLinx (version 1.2.1)
 Customizable network dashboard that is optimazed to be used for a browser startpage.  It features customizeable shortcut links and a custom search bar.

 ### Demo

 We have published a demo install that is publically accessable at https://demo.dashlinx.com


## Instalation

To install DashLinx, simply run the following command

```sh <(curl -sS https://raw.githubusercontent.com/AJPNetworks/DashLinx/main/install.sh)```

This will download and run the install script which installs all dependancies and sets everything up the way it should.

On some installs (like containers), you may need to install curl, apt-utils and/or sudo to get the installer to work properly.  You can make sure they are installed with the following:
`apt-get install apt-utils curl sudo`

Or if sudo is already installed:
`sudo apt-get install apt-utils curl`

## Options

`-t` Will let you customize the Title of the page in the title bar. (Default is DashLinx)

`-b` Will let you customize the Browser tab title in case you want that. (Default is DashLinx)

`-p` Will let you set the port that the server will listen on. (Default is 80) Note: 443 may not work as expected as there is currently no self-signed cert issued to the server

`-P` Will let you specify a set password for the database that is created (Default is a randomly generated string)


## Donate

 This dashboard project was created by AJP Networks as an open source project that may be freely distributed without license.
 I accept donations through PayPal at the following link

 https://paypal.me/kn0t5