<?php
require_once "config.php";

$email = $password = "";
$email_err = $password_err = "";

if($_SERVER["REQUEST_METHOD"] == "POST"){
    $link = openConnection();

    //Verificar se o email está vazio
    if(empty(trim($_POST["email"]))){
        $email_err = "Por favor introduza o seu email.";
    }
    else{
        $email = trim($_POST["email"]);
    }

    //Verificar se a password está vazia
    if(empty(trim($_POST["password"]))){
        $password_err = "Por favor introduza a sua palavra passe.";
    }
    else{
        $password = trim($_POST["password"]);
    }

    //Validar credenciais
    if(empty($email_err) && empty($password_err)){
        $sql = "SELECT id, admin, email, password, name, active, avatar, darkTheme FROM users WHERE email = ?";

        if($stmt = mysqli_prepare($link, $sql)){
            mysqli_stmt_bind_param($stmt, "s", $param_email);

            $param_email = $email;

            //Tentar executar o prepared statement
            if(mysqli_stmt_execute($stmt)){
                mysqli_stmt_store_result($stmt);

                //Verificar se o email existe, se sim verificar a password
                if(mysqli_stmt_num_rows($stmt) == 1){
                    mysqli_stmt_bind_result($stmt, $id, $admin, $email, $hashed_password, $name, $active, $avatar, $darkTheme);

                    if(mysqli_stmt_fetch($stmt)){
                        if(password_verify($password, $hashed_password)){
                            /* Password está correta, começar
                            nova sessão e salvar o email na sessão */

                            //Verificar se o utilizador está ativo
                            if($active == 1){
                              session_start();
                              $_SESSION["id"] = $id;
                              $_SESSION["admin"] = $admin;
                              $_SESSION["email"] = $email;
                              $_SESSION["name"] = $name;
                              $_SESSINO["active"] = $active;
                              $_SESSION["avatar"] = $avatar;
                              $_SESSION["dark_theme"] = $darkTheme;

                              if($admin == true){
                                  header("location: admin/adminHomePage.php");
                              }
                              else{
                                  header("location: user/userListaProvasInscrito.php");
                              }
                            }
                            else{
                              $email_err = "A conta não se encontra activa. Entre em contacto com o administrador para  resolver este problema";
                            }
                        }
                        else{
                            //Mostrar erro se a password não for válida
                            $password_err = "Dados inválidos.";
                        }
                    }
                }
                else{
                    //Mostrar erro se o email não for válido
                    $password_err = "Dados inválidos.";
                }
            }
            else{
                echo("Whoops! Algo de errado aconteceu. Por favor tente mais tarde");
            }
        }

        //Fechar statement
        mysqli_stmt_close($stmt);
    }

    //Fechar conexão
    mysqli_close($link);
}
?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Login</title>

        <!-- Bootstrap -->
        <link href="../bootstrap337/css/bootstrap.min.css" rel="stylesheet">

        <link href="../styles/signin.css" rel="stylesheet">
    </head>
    <body>
        <div class="container">
            <div class="row">
                <h1>Bem vindo à plataforma de provas!</h1>
            </div>

            <div class="row">
                <img src="../avatars/avatar.png" class="img-thumbnail img-responsive center-block avatar" alt="Avatar" id="avatar-user">
            </div>

            <div class="row">
                <!-- Chamar PHP quando a form for submetida -->
                <form name="signin" class="form-horizontal" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post">
                    <h2 class="for-signin-heading text-center">Introduza os seus dados</h2>

                    <div class="form-group">
                      <label class="col-md-4 control-label" for="email">E-mail</label>
                      <div class="col-md-4 inputGroupContainer">
                        <div class="input-group">
                          <label class="input-group-addon" for="email"><i class="glyphicon glyphicon-envelope"></i></label>
                          <input name="email" id="email" placeholder="E-mail" value="<?php echo $email; ?>" class="form-control" type="email">
                        </div>
                      </div>
                      <?php echo ($email_err != "" ? '<p class="col-md-4 form-control-static">' . $email_err . '</p>' : ''); ?>
                    </div>

                    <div class="form-group">
                      <label class="col-md-4 control-label" for="password">Palavra passe</label>
                      <div class="col-md-4 inputGroupContainer">
                        <div class="input-group">
                          <label class="input-group-addon" for="password"><i class="glyphicon glyphicon-lock"></i></label>
                          <input name="password" id="password" placeholder="Palavra Passe" class="form-control" type="password">
                        </div>
                      </div>
                      <?php echo ($password_err != "" ? '<p class="col-md-4 form-control-static">' . $password_err . '</p>' : ''); ?>
                    </div>

                    <div class="form-group">
                        <button class="btn btn-lg btn-primary center-block" type="submit">Entrar</button>
                    </div>
                </form>
            </div>
        </div>
    </body>
</html>
