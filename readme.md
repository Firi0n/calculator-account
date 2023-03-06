# Instructions for use

## Change database settings

The database is set up in the `account` class constructor, which is located in the `Account.php` file in the `class` folder.

---

## Change project directory

Go to `template.php` file in `templates` folder and modify `path` variable to the path to the actual project path.

---

## IMail interface

The `IMail` interface allows you to use a library other than `PHPMailer` as long as you create a specific method called `send` which takes the parameters `contact`, `header` and `message` as strings and return a `bool` variable type. If you are using a library other than `PHPMailer` you can skip the steps below.

---

## Create credentials file

To use this code create the `credentials.php` file in the `class` folder with this code:

```PHP
<?php
$username = '';
$password = '';
?>
```

In the username variable enter your google email and in the password variable enter the password obtained with [this](https://support.google.com/accounts/answer/185833 "Sign in with App Passwords") method.
