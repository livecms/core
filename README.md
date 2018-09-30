# Project LiveCMS - Core

## Note :
Only works with Laravel version 5.5 or above.

### For Laravel 5.3, please checkout branch V1
### For Laravel 5.2, please checkout branch V0

# How To Install :

1. Create Laravel Project (5.5.\*)
    ````
         composer create-project laravel/laravel liveCMS "5.5.*" --prefer-dist
    ````

2. Edit composer.json
    Change  :
    ````    
        "config": {
            "preferred-install": "dist"
        }
    ````

    with :
    
    ````
        "minimum-stability": "dev",
        "prefer-stable": true
    ````


3. After finish, add livecms core in your project
    ````
         cd liveCMS 
         composer require livecms/core "dev-develop"
    ````

4. Update your .env
    update based on what your site url:
    ````
        CMS_URL=http://yourdomain.com/cms
    ````

5. Run
    visit : http://yourdomain.com/cms

