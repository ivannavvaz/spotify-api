Steps to follow for configuration:
1. Clone the repository
```git clone ...```
2. Configure the .env file
```cp .env .env.local```
3. Install dependencies
```docker compose up -d```
```Docker compose exec web bash```
```composer install```
4. Loading the database
```mysql -u root -pdbrootpass -h add-dbms < db/spotify.sql```
