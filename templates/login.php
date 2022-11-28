<!DOCTYPE html>
<html lang="en">
    <head>
        <title>Login</title>
    </head>
    <body>
        <?php if (!empty($message)) {
          $class = $message_type ?: "";
          echo <<<EOT
        <div class="message $class">$message</div>
EOT;
        } ?>
        <form class="form login" method="post" action="/auth/login">
            <div class="field username">
                <label>Username</label>
                <input
                    name="username"
                    type="text"
                />
            </div>
            <div class="field password">
                <label>Password</label>
                <input
                    name="password"
                    type="password"
                />
            </div>
            <div class="buttons">
                <button class="submit">Login</button>
            </div>
        </form>
    </body>
</html>
