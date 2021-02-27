## Installation

#### 1. To install run the following commands in a working directory: 
```
git clone https://github.com/aminulbinnoor/frontend.git
```
#### 2. Now run and make sure you have the composer installed on your machine:
```
composer install 
```

#### 3. In the next step, open the .env.example file. Change the data of this file to .env and the details of the site, database.

#### 4. Run the command from the main project folder to create database tables.
```
php artisan migrate --seed
```

#### 5. Run the command from the main project folder for image link setup.
```
php artisan storage:link
```

#### 6. that's all ! Now start the laravel server using the command:
```
php artisan serve
```

#### 6. Login to admin panel by using this account:
```
user: 100003
password: 12345678
```
