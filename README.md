# todo
Todo Application written using procedural PHP

How it works?

The application uses cookies to track users, there is no registration system.
A visitator hits the main page (index.php) of the application.If he has the user_key cookie already stored, automatically the application will retrieve from database all his tasks( if he has ).If the user_key cookie is not stored, then the application will create a new cookie with the name user_key having the value as the maximum of the user_key column from users table, incremented by one.The cookie is created and a new record containing the user_key value is stored in database.Using the database with the cookie, we have a powerful system to track users and their tasks.The user can "mark as done" or "undone" his tasks.When all his tasks are "done", all tasks are automatically deleted and a new page is created.

The application uses MySQL as database and PDO with prepared statements.
Also the application uses the php_procedural_app_basic_structure project.

DEMO here: http://mindcrazy.tk/todo/

