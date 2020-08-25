# Repository: archived

The website within this repository was active from 2014-2018, representing the first version of our website (MFA Tools), followed by the second version named Metabolism of Cities. Under this name the website continues, and a new repository supersedes this one. 

# How to run this site on your own server

## Requirements

* PHP 5.3.5 +
* Recent version of MySQL
* Recent version of Apache
* Linux is fully supported; Windows support may be lacking but is likely no problem

## Getting started

First, clone this repo as per Github instructions. Are you new to Git and/or Github? Fear not! Github has a friendly [help section](https://help.github.com/) where you can go to for more information. 

Once you have the repo on your machine, put this in the webroot of your Apache server, and you should be able to navigate there using http://localhost/mfa-tools, or something similar. You will see an error about not having a config file - you're ready for the next step!

It's time to set up the database. Create a new MySQL database, and load the database structure. You can find this structure in load.sql. Please note that this structure does not contain any data. If you want to load the publications and other data from the website, request a copy of the most current database from one of the current maintainers. 

Once your database is loaded, open the config.sample.php file and adjust this for your own situation. Save this as config.php (don't overwrite config.sample.php!) and navigate again to the local URL. If all went well, you will now see the MFA Tools website! 

## File uploads

In order for file uploads to work (OMAT has an option to upload files for each source), be sure to create the 'files' directory (or whatever you called it in your config file), and to give the Apache user permissions to upload files there. If you have a fork of MFA Tools operating in production, then make sure you place this folder outside of your webroot!

# How to contribute to this project

## What to work on?

In principle you can work on anything you like! However, we have a [Wish List](http://mfa-tools.net/page/wishlist) with a variety of features that are wanted. Any of those features will be greatly appreciated, but you can also work on other functionality if you so prefer. You may want to check in upfront with the developers before initiating something radically different or out of the current scope. 

## Coding Standards

We follow the [Pear Coding Standards](http://pear.php.net/manual/en/standards.php) with one main exception: we use 2 space indentation instead of 4 space indentation! Other than that, try to stick to the Pear Coding Standards. If you deviate too much, you may be requested to reformat your code before it is accepted. 

## Git workflow

Read through the Github article on the [Git workflow](https://help.github.com/articles/what-is-a-good-git-workflow) if you are not too familiar with how Git works. Normally, you do *not* need to make a branch unless you are working on a large change or big featureset. Smaller changes can be committed directly onto the Master branch. Just ensure that you work off the most recent version as the codebase can be rapidly changing. If your changes will take several days of coding to complete, then do create a branch to avoid merging conflicts - especially if it concerns code others may also be working on. 

## File and URL naming conventions

Look at current files and URLs to get an idea. If in doubt, check in beforehand. 

## Versioning of the website

We provide a "Version" number for the website. Every month we'll increment the version with 0.1 if changes were made. In order to have a good overview of the changes, please add the changes you made to the CHANGELOG file. These changes will be accumulated on a monthly basis and placed online when the new version is 'launched' (do note that in reality new versions are introduced on a rolling basis, at any time, but for easy public perception the site is 'versioned' and changes are grouped this way). 
