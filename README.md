# Image Shop

The Image Shop allows a photographer to sell his photos.

## Requirements

* PHP >= 7.0
* OpenSSL PHP Extension
* PDO PHP Extension
* Mbstring PHP Extension
* Tokenizer PHP Extension
* MySQL >= 5.5

## Installation

* Download the Laravel Dependencies with composer.
* Edit the information for your admin user in /database/seeds/USersTableSeeder.php 
* Create the database in mysql
* Create an .env file from the .env.example and fill it with needed information
* Setup the Database with php artisan migrate --seed
* Generate the application key with php artisan key:generate
* Check that php has write permission on /bootstrap/cacke, /storage, and /public/albums
* Install Javascript Dependencies with bower in /public
* Customize your Messages in /public/lang/de_CH.json

## License

The Image Shop is open-sourced software licensed under the [MIT license](http://opensource.org/licenses/MIT).
