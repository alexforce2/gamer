<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Авторизация в админ центре </title>
    <?=$arrCss;?>
</head>
<body>
<div id="login-form">
    <h1>Админ центр</h1>
    <fieldset>
        <form action="" method="post">
            <input type="text" required placeholder="Логин" name="login" >
            <input type="password" required placeholder="Пароль"  name="pass" >
            <input type="submit" value="ВОЙТИ" name="auth">
        </form>
    </fieldset>

</div>
</body>
</html>