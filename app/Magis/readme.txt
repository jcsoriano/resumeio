Installation notes


Pre-Setup:

1. Environment requirements:
 - PHP 7 (http://ryanstelmat.com/upgrading-wamp-server-to-php-7/)
 - MySQL 5.7 (for native JSON support!) (http://forum.wampserver.com/read.php?2,111797,124054)
 - Redis for Windows (https://github.com/MSOpenTech/redis/releases)
 - NodeJS 6.5 (https://nodejs.org/en/)

2. Recommended IDE Packages (Sublime Text 3):
 - Package Installer
 - PHP Companion (easy "use" imports)
 - PHP Getters and Setters (easy getters and setters generation)
 - AdvancedNewFile (easy creation of new file)
 - SublimeCodeIntel (autocomplete + easy function definitions)
 - DocBlockr (easy creation of function definition [to work better with sublimecodeintel])
 - Laravel Blade Highlighter (syntax highlighting for Blade!)


How to Install:

1. Put changes to config files:
 - Copy "Magis" folder inside "app" folder
 - Copy 'directories/public/magis' to 'public/magis'
 - Copy 'directories/views/magis' to 'resources/views/magis'
 - Copy 'directories/controllers/magis' to 'app/http/controllers/magis'
 - Copy 'directories/assets/magis' to 'resources/assets/magis'
 - Copy 'directories/components/magis' to 'resources/assets/components/magis'
 - Copy changes in migration/create_users_table.php
 - Copy Code in Magis/app/Providers/EventServiceProvider@listen
 - Copy Code in Magis/app/Providers/AppServiceProvider@boot (also copy bootMagisSocialite function)
 - Copy Code in config/app@providers and config/app@aliases
 - Set timezone to "Asia/Manila" in config/app.php
 - Copy fonts to fonts folder

2. Run 4 console commands:
 - "composer require predis/predis"
 - "composer require laravel/socialite"
 - "npm install"
 - "php artisan magis:install"
 - "gulp"
 - "php artisan migrate"

3. Enjoy ;)

To view available commands and what they do, type: "php artisan help [magis:something]" (replace 'something' with the command)


Quick-start tutorial:

1. Install Major components:
 - "php artisan magis:install:cms"
 	*You now have blogs and pages installed :O
 - "php artisan magis:install:shop products"
 	*You now have a full online store installed with shopping cart, checkout, etc.

2. Create CRUD (add/edit/delete) for content called "Books": "php artisan magis:content Book"
 - Go to /books/manage and enjoy adding, editing, or deleting :O

Note: Everytime you create something (like adding routes), you'll have to assign permissions to the roles. Go to /roles/manage


Notable changes from CodeIgniter:

1. Role and Permission management is now FOR DEVELOPERS ONLY. DO NOT TRUST CLIENTS WITH THIS. Let them contact us for role changes.

