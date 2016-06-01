# Image Shop

This project is an implementation of a shop for photos. Mostly for photographers which will show the photos from a shooting or another order.
The software allows the customers to choose the photos they want. To order the photos, they have to choose a format and the count for each photo. At last the software need some personal Data from the customer.
The customer and the holder of the shop will receive an email with the details (also avaible through the admin view).

The Backend is implemented with PHP and the [Laravel Framework](https://laravel.com/) and uses a [MySQL Database](https://www.mysql.de/). The single page frontend is implemeted using [Angular.js](https://www.angularjs.org/) and CSS3.

## Requirements

* PHP >= 7.0
* OpenSSL PHP Extension
* PDO PHP Extension
* Mbstring PHP Extension
* Tokenizer PHP Extension
* MySQL >= 5.5

## Installation

* Download the Laravel Dependencies with `composer install`
* Edit the information for your admin user in */database/seeds/USersTableSeeder.php*
* Create the database in mysql
* Create an .env file from the .env.example and fill it with needed information
* Setup the Database with `php artisan migrate --seed`
* Generate the application key with `php artisan key:generate`
* Check that php has write permission on */bootstrap/cacke*, */storage*, and */public/albums*
* Install Javascript Dependencies with bower in */public*
* Customize your Messages in */public/lang/de_CH.json*

## License

The Image Shop is open-sourced software licensed under the [MIT license](http://opensource.org/licenses/MIT).
