<!DOCTYPE html>
<html lang="en">
    <head>
        <title>Example</title>
    </head>
    <body>
        <h1>Home Page</h1>
        <?php include "welcome.php"; ?>
        <div>Visit a <a href="protected">protected page</a></div>
        <?php if (empty($user)) {
          echo '<div><a href="auth/login">Log in</a></div>';
        } else {
          echo '<div><a href="auth/logout">Log out</a></div>';
        } ?>
    </body>
</html>
