<!DOCTYPE HTML>
<html>
    <head>
        <title>repetitori</title>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <link href="../css/index.css" rel="stylesheet" type="text/css">
        <link href="../css/login.css" rel="stylesheet" type="text/css">
    </head>
    <body>
    <div id="Main">
        <?=
            $_REQUEST['auth']  == 'login' ?
                '<div class="AuthWrapper">
                    <h1>
                        Sign In
                    </h1>
                    <form name="login" action="main.php" method="POST" >
                        <input name="login" type="email" placeholder="email" required/>
                        <input name="password" type="password" placeholder="password" required/>
                        <fieldset name="type" aria-required="true">
                            <label>
                                <input name="type" type="radio" value="student" checked />
                                <span>student</span>
                            </label>
                            <label>
                                <input name="type" type="radio" value="teacher" />
                                <span>teacher</span>
                            </label>
                        </fieldset>
                        <button name="action" value="login" type="submit">submit</button>
                    </form>
                    <a href="login.php?auth=register">Still doesn’t have an account?</a>
                </div>'
                :
                '<div class="AuthWrapper">
                    <h1>
                        Sign Up
                    </h1>
                    <form name="register" action="main.php" method="POST" >
                        <input name="email" type="email" placeholder="email" required/>
                        <input name="password" type="password" placeholder="password" required/>
                        <input name="password2" type="password" placeholder="repeat password" required/>
                        <fieldset name="type" aria-required="true">
                            <label>
                                <input name="type" type="radio" value="student" checked />
                                <span>student</span>
                            </label>
                            <label>
                                <input name="type" type="radio" value="teacher" />
                                <span>teacher</span>
                            </label>
                        </fieldset>
                        <button name="action" type="submit" value="register">submit</button>
                    </form>
                    <a href="login.php?auth=login">Already have an account?</a>
                </div>'
            ?>
    </body>
</html>

