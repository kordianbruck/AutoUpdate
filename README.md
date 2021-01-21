# AutoUpdate (DEPRECATED aka. no longer maintained)

I haven't updated this code in a long time and chances are its broken. You problably should use some other sort of updating your production code (e.g. containers).

---

Automagically update web apps with a github hook.

Ever felt, that you were updating your apps so often, that deployment to production was
getting tedious? Look no further! With this awesome and simple php script you can
automatically deploy your web apps as soon as you merge any changes onto the master branch. (or any other branch)

Rapid deployment and testing changes as they are fresh is crucial to developers who want instant feedback.

*Do not test in production!* (Well you can, but that won't make your users happy)

# How to

1. Clone the AutoUpdate repository to a folder which is accessible via a web address:

```bash
mkdir /var/www/mywebsite
cd /var/www/mywebsite
chwown www-data:www-data /var/www/mywebsite
git clone https://github.com/kordianbruck/AutoUpdate.git
```
2. Clone your project to a directory which is **not** web accessible
```bash
cd /var/www/
git clone https://github.com/kordianbruck/TumCalProxy
```
3. Copy the configuration file
```bash
cp /var/www/mywebsite/AutoUpdate/Config.default.php /var/www/mywebsite/AutoUpdate/Config.php
```
4. Edit the */var/www/mywebsite/AutoUpdate/Config.php* and setup the variables to your liking. Example file follows:
```php
<?php

//Edit these values to fit your needs
define('PATH_REPOSITORY', '/var/www/TumCalProxy/'); //With trailing slash
define('PATH_PUBLIC', '/var/www/mywebsite/'); //With trailing slash
define('GIT_BRANCH', 'master'); //Use this branch as your development branch and only update this branch
define('GITHUB_SECRET', ''); //Set your secret here in order to verify that requests come from Github
```
5. Setup the webhook in your Github project of your choosing.
    * Go to Project
    * Settings on the bottom right
    * Select *Webhooks & services*
    * Click *Add Webhook*
    * Enter your domain  in  the Payload URL with the public path to the AutoUpdate folder: eg.: http(s)://mywebsite.example.com/AutoUpdate/AutoUpdate.php
    * Setup a secret if you want to improve security (*Don't forget to add it in the Config.php*)
    * Click *Add webhook*

6. ???
7. Profit!
