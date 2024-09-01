# MySihat
Connected Care – Anytime, Anywhere


## Install

To clone this project
```bash
    1. Open the Terminal.
    2. Make sure the current directory is '/www'.
    3. Type in 'git clone <url> <project-name>'.
    4. Type in 'cd <project-name>' to enter the project's directory.
```

To setup this project
```bash
    1. Make sure the current directory is '/<project-name>'.
    2. Type in 'composer install' to install the packages and dependencies.
    3. First, make sure you have pnpm installed. If you don't, you can install it globally using npm ' npm install -g pnpm'
    4. Once pnpm is installed, navigate to your project directory 'cd /path/to/your/project'
    5. Type in 'pnpm install' to install the packages and dependencies.
    6. Click on Database in Laragon/phpMyAdmin.
    7. Create a new database with the name of your project. Eg: <project_name>
    8. Type in 'code .' to open the current directory in VS Code.
    9. In the explorer tab on the left, copy the '.env.example' file and paste it in the same directory.
```

To publish this project
```bash
    1. Open the copied file and rename it to '.env'.
    2. In the '.env' file, edit the 'DB_DATABASE' value to the new Database name that you   have created.
    3. If your MySQL is using a password, edit the 'DB_PASSWORD' value with your password.
    4. In the Terminal, type in 'php artisan key:generate' to generate the project's 'APP_KEY'.
    5. Finally, type in 'php artisan migrate:fresh --seed' to migrate the database tables for the project.
```

## Run
```bash
  1. Type in 'cd <project-name>' to enter the project's directory.
  2. php artisan serve
```
    
## Download
 - [Laragon](https://laragon.org/download/)
 - [Visual Studio Code](https://code.visualstudio.com/download)


## Appendix
Developed and Deployed using Visual Studio Code, Laravel Herd, DBngin, TablePlus and Laravel 11.


## Build By
- [@Amir](https://github.com/amir1611)



## Documentation
 - [Laravel](https://laravel.com/docs/10.x)
 - [Visual Studio Code](https://code.visualstudio.com/docs)

## License
The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
