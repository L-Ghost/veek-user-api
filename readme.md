# Veek User API

PHP Lumen API to handle users' information in JSON format.

### Setup

 - composer install
 - create **MySQL** databases for production and testing of application
 - generate **.env** file from **.env.example**, configuring databases access
 - migrate databases
 > php artisan migrate
 > php artisan migrate --database=mysql_testing
 
### Usage

 #### inserting/adding users
  
  > POST /users  
   *name* and *email* should be provided in the x-www-form-encoded
  
  #### getting information about users
  
  > GET /users  
   will return all users in JSON format  
      
  > GET /users/{id}  
   will return information about the user with the specified id in JSON format
   
  #### updating users
  
  > PATCH /users/{id}  
  > PUT /users/{id}  
   will updated the user with the specified id
   *name* and *email* should be provided in the x-www-form-encoded
   
  #### deleting users
  
  > DELETE /users/{id}  
   will delete the user with the specified id
   
  #### note  
  
  404 code will be returned for all requests regarding non existent ids

### Testing

 - php vendor/bin/phpunit tests