About
-----
Apache's mod_autoindex styling capabilities are heavily limited. This .php script tries to solve this.
You can see it in action [here](https://desto.hercules.uberspace.de/autoindex.php/).



Installation
------------
Throw the `.autoindex` folder, `.autoindex.php` and `.htaccess` into the root of your webserver and you should be good to go. If you already have a `.htaccess` file, add its content to your existing one.



Options
-------
At the top of `.autoindex.php` are a few settings:
- `$PARSE_URL (boolean) [=true]` Wether or not .url files will redirect to its content rather than the file itself. A .url file ist just a plain text file with a .url extension containing only a link.
- `$TITLE (string) [=""]` The prefix to use for the title.