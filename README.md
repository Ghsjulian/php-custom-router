### Core PHP Router

#### Web Developer - Ghs Julian

---

#### Descriptions :---

If you are looking for core php router and you have decide to build a rest
api using core php and MySQL. Then you can use this repository in your project
directory. It will work same as the laravel and express, just it has some different aproch
but don't wory you will understand more about this custom router.

---

#### Note :----

Actually it is a custom core php router. desgined by using core php only.
suppose someone challanged to that hey buddy can build a custom router system in
core php which will work same as the laravel and express frameworks. Then you can
reply him yes sure i can do it . and you need to just come here in my repository and
clone this in to your terminal through git.

---

#### How To Clone :---

**First of all you need to clone it in your project directory see the example bellow copy and paste the command on your terminal.**

```bash
git clone https://github.com/Ghsjulian/core-php-router.git
cd core-php-router
ls
```

---

**Now Let's see how to use this router. Open the index.php file . Don't delete this file never because your all routes will be contain in this file.**

**For creating database connection with your mysql server browse this directory.**

```bash
core-php-router
    |--- /controllers/
    |--- /assets/
             |--- /router/
             |--- /databases/databases.php

```

**Open the Databases.php file and in the top section setup your connection information and save the file**

```php
  private $secret_key = "YOUR_KEY_ANYTHING";
  private $host = "YOUR HOST";
  private $user = "YOUR USER NAME";
  private $password = "YOUR PASSWORD";
  private $db_name = "YOUR DATABASE NAME";
```

**Now open the index.php file and setup your controllers folder see the example bellow**

```php
# index.php
require_once __DIR__ . "/assets/router/Router.php";
require_once __DIR__ . "/assets/databases/Databases.php";
$router->set_controller("/controllers/");
$router->get("/", "Home@index");
$router->get("/user/name?name=", "User@index");

```
