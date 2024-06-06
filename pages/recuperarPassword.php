<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="author" content="Ignacio Basso">
    <title>Recupera tu contraseña</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.3.1/css/all.css" integrity="sha384-mzrmE5qonljUremFsqc01SB46JvROS7bZs3IO2EmfFsd15uHvIt+Y8vEf7N7fWAU" crossorigin="anonymous">
    <link rel="stylesheet" href="../css/style.css">
    <script src="https://www.google.com/recaptcha/api.js" async defer></script> 
</head>
<body class="roboto-mono bg-eurogames-blanco">
    <header>
        <?php
            require_once 'header.php';
        ?>
    </header>
    <div class="container register">
        <div class="borde-plantilla">
            <div class="row">
                <div class="col-md-3 register-left ">
                    <h1>¿Olvidaste tu contraseña?</h1><br>
                    <h3 style="color:white">CERO DRAMAS. En unos simples pasos la puedes recuperar</h3>
                    <span class="btn-sm justify-content-center login_btn btn-naranja-outline-success text-white font-weight-bold"><i class="fas fa-key"></i></span>                                          
                </div>                    
                <div class="col-md-9 register-right">                    
                    <div class="tab-content btn-naranja-outline-success" id="myTabContent">
                        <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
                            <div class="mt-3 card  card-login">
                                <div class="card-header justify-content-center">
                                    <h3 class="text-center"> Ingresa tu cuenta de correo electrónico y recibirás un enlace de restablecimiento de tu contraseña </h3>
                                </div>
                                <div class="mt-3 card-body">
                                    <form action="actionCaptcha.php" method="POST">
                                        <div class="input-group form-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text"><i class="fa fa-at"></i></span>
                                            </div>
                                            <input type="text" class="form-control" placeholder="Email">						
                                        </div>
                                        <div class="g-recaptcha d-flex justify-content-center"data-sitekey="6LdASospAAAAAPE1pV4MDk-GJURScVLBY3cfhBeY"> 
                                        </div> 
                                        <br> 
                                        <div class="card-footer">
                                            <div class="d-flex justify-content-center ">                                    
                                                <input type="submit" value="RECUPERAR CONTRASEÑA" class="btn justify-content-center login_btn btn-naranja-outline-success text-white font-weight-bold">
                                            </div>
                                        </div>  
                                    </form>
                                </div>               
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php require 'footer.php'?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script src="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/vue@2"></script>
</body>
</html>