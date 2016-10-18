# grav-symfony-example
This project is an example of an instance of Grav Running with Symfony

# Setup 

Run ```composer install```

# Run

```cd web && php -S localhost:8000```

Open http://localhost:8000/home and see a Grav page
Open http://localhost:8000/fabpot and see a Symfony page

# How does it work ?

This is what we did :

* Install Symfony
* Install Grav 
* Create a composer.json file in root folder tha mixes Symfony and Grav composer file
* Create symlink for Symfony vendor 
* Create symlink for Symfony vendor
* Create a web folder an insert an ```index.php``` file based on Grav file.
* Create some symlinks in web folder in order to make Grav work.
* Desactivate the error plugin in order to allow symfony to handle pages not found by Grav
