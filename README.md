## Pawns app developer task

### Start the project

Docker Desktop is required to run the app out of the box.

* To start the application type: `make start`
* To stop the application type: `make stop`
* To run Pest/Style fixer: `make style`
* To run tests: `make test`

API Base Url: http://localhost:8880/

### API Documentation

API Documentation is generated with Scrambler and can be found 
at: http://localhost:8880/docs/api

### Database seeding

To populate the database with questions run: `php artisan db:seed --class=ProfilingQuestionSeeder`

### Platform global statistics

Global stats calculation is scheduled every day at 23:59

To run scheduler: `php artisan schedule:work`