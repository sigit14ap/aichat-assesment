## How to run
- Clone the repository
```
git clone https://github.com/sigit14ap/aichat-assesment.git
```
- Setup the env with your local settings

- Run migration with seeder
```
php artisan migrate --seed
```

- Run application
```
php artisan serve
```

- Import Collection Postman
```
AiChat.postman_collection.json
```

- You can change time expired of campaign in
```
App\Http\Controllers\CampaignController

private $campaign_end_at = '2022-04-01 10:00:00';
```