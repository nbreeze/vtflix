# VTFlix

## Purpose
This website was originally created as an assignment for a database class.

## Requirements
- PHP Version 7.0+
	- Any version less won't work because this uses the mysqli module (mysql module was removed in version 7.0).
- Ampps, XAMPPS, or some other local webserver environment.

## Configuration
- Database info must be edited in the php/dbinfo.php file in order for this to work.
- The database user that this website will utilize must also have SELECT, INSERT, UPDATE, and DELETE privileges in the database. 
	- Or alternatively, just use a database user that has all privileges :)

## Notes
- This web application was developed with Ampps, PHP 7.1, and MySQL.
- You can register an account (while as an unregistered user) by going to the top right of any page, and selecting the username which will show a dropdown of options. This is the User Menu. Select the Login/Register button to login or register.
- You can view friend requests in your Account Settings, which is accessible through the Settings option in the User menu.
	- Overview of the Account Settings hasn't been implemented, but accepting/declining friend requests from other users is functional.
- Search for movies, collections, tv episodes, actors, or directors in the index page of the website. Suggestions will automatically show upon entering some characters. Clicking on a suggestion will take you to the page containing info about that particular piece of media or a biography of an actor or director.
	- Rating is also functional; only works if you are logged in.
