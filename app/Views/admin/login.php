<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Dental</title>

    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto:400,100,300,500">

    <link rel="stylesheet" href="<?= base_url('vendor/template/bootstrap/css/bootstrap.min.css') ?>">
    <link rel="stylesheet" href="<?= base_url('vendor/template/bootstrap/font-awesome/css/font-awesome.min.css') ?>">
    <link rel="stylesheet" href="<?= base_url('vendor/template/bootstrap/css/form-elements.css') ?>">
    <link rel="stylesheet" href="<?= base_url('vendor/template/bootstrap/css/style.css') ?>">

    <link rel="shortcut icon" href="<?= base_url('vendor/template/dist/img/ico/favicon.png') ?>">
</head>

<body>

<div class="top-content">

    <div class="inner-bg">
        <div class="container">

            <div class="row">
                <div class="col-sm-6 col-sm-offset-3 form-box">

                    <div class="form-top">
                        <div class="form-top-left">
                            <h3>SISTEMA DENTAL</h3>
                            <p>Ingrese su nombre de usuario y contraseña</p>
                        </div>
                    </div>

                    <div class="login-box-msg">

                        <?php if (session()->getFlashdata('error')): ?>
                            <div class="alert alert-danger">
                                <p><?= session()->getFlashdata('error') ?></p>
                            </div>
                        <?php endif; ?>

                    </div>

                    <div class="form-bottom">

                        <form action="<?= base_url('auth') ?>" method="post" class="login-form">

                            <?= csrf_field() ?>

                            <div class="form-group">
                                <input
                                    type="text"
                                    name="username"
                                    placeholder="Usuario..."
                                    class="form-username form-control"
                                    required
                                >
                            </div>

                            <div class="form-group">
                                <input
                                    type="password"
                                    name="paswoord"
                                    placeholder="Contraseña..."
                                    class="form-password form-control"
                                    required
                                >
                            </div>

                            <button type="submit" class="btn btn-primary btn-block">
                                Ingresar
                            </button>

                            <br>

                            <a style="color:#2D62FF;" href="<?= base_url('regpersona') ?>">
                                ¿No tiene cuenta? Crear Cuenta
                            </a>

                        </form>

                    </div>

                </div>
            </div>

        </div>
    </div>

</div>

<script src="<?= base_url('vendor/template/bootstrap/js/jquery-1.11.1.min.js') ?>"></script>
<script src="<?= base_url('vendor/template/bootstrap/js/bootstrap.min.js') ?>"></script>
<script src="<?= base_url('vendor/template/bootstrap/js/jquery.backstretch.min.js') ?>"></script>
<script src="<?= base_url('vendor/template/bootstrap/js/scripts.js') ?>"></script>

</body>
</html>