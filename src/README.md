EXP_API

## Get Started

Open a terminal and from the project root run 
`cp .env.example .env`.
`docker-compose build --no-cache && docker-compose up -d`. 

Open up your browser of choice to [http://localhost:8080](http://localhost:8080) and you should see your Laravel-Lumen app running as intended. 

To get into the application bash. So you can run php artisan and other commands run
`docker exec -it paychange_app bash`

Run composer install `composer install`

For first time cloning of the project. Also generate app key

Run `php artisan key:generate`

Run `php artisan route:clear`

Run `php artisan cache:clear`

Run `php artisan config:clear`

Run `php artisan passport:install`

Run `php artisan migrate`

Run `php artisan db:seed`


Containers created and their ports are as follows:

- **mmwebserver** - `:8080`

- **mmdatabase** - `:3306`

- **mmapp** - `:9000`
