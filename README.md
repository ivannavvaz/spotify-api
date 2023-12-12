Pasos a seguir para la configuración
1. Clonar el repositorio 
```git clone ...```
2. Configurar el archivo .env
```cp .env .env.local```
3. Instalar las dependencias
```docker compose up -d```
```Docker compose exec web bash```
```composer install```
4. Cargar la base de datos
```mysql -u root -pdbrootpass -h add-dbms < db/spotify.sql```