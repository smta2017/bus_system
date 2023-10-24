
## How To Run:

After clone go to repo directory:

And run follow the steps

- install laravel package using
    ```
      composer update
   ```

- run the containers using
  
  ```
      cp .env.example .env 
    ```
 
- migration
    ```
     php artisan migrate
    ```

- data seeds
    ```
     php artisan db:seed
    ```


- run server
    ```
     php artisan serve
    ```








### Admin user test 

login as admin from <a href="localhost:8000/admin">here</a>

```
    email:  admin@admin.com
    password:  123456
    
```