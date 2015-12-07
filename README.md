#Usage

Add Sauce Username and Access Key to build.yml line 10:
```
SAUCE_USERNAME=user_name
SAUCE_ACCESS_KEY=access_key
```

Install Behat, Mink, MinkExtension and their dependencies with composer:

      $curl http://getcomposer.org/installer | php
      $php composer.phar install

#To Run tests on Sauce Labs: 
```
$ make run_all_in_parallel
```


























