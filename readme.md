# PHP Twitch Interactive Game Base

  

This repo is a basic example implementation to make a twitch browser interactive game in PHP/Ajax

Its main functionnality for now is to get your viewers "display_name" which typed "!play" in your chat

## HOW TO USE IT :

- Create a Twitch Developer account if you don't have one yet: https://dev.twitch.tv/
(you have to activate double authentication on your twitch account before)
- Create an application and get the client_id, the secret (click on "new secret" in your application management page) and at least one oAuth redirection uri
- replace client_id, client_secret, channel and redirect_uri with yours

  
  

## Index.php

The file handles your oAuth connection to Twitch

  

## Dashboard.php

The file contains the main logic : a simple list into a div with the functionnality in ajax to adding the viewers display_name which typed "!play" in the channel chat.

  

###  TODO :
- rewrite it in a better way
- refactoring