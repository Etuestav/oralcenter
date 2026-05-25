<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>DentalSac | Registrate</title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <!-- Bootstrap 3.3.7 -->

  <link rel="stylesheet" href="<?php echo base_url('vendor');?>/template/bootstrap/css/bootstrap.min.css">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="<?php echo base_url('vendor');?>/template/bootstrap/font-awesome/css/font-awesome.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="<?php echo base_url('vendor');?>/template/Ionicons/css/ionicons.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="<?php echo base_url('vendor');?>/template/adminlte4/css/adminlte.min.css">
  <link rel="stylesheet" href="<?php echo base_url('vendor');?>/adminlte4-legacy.css">
  <!-- iCheck -->
  <link rel="stylesheet" href="<?php echo base_url('vendor');?>/plugins/iCheck/square/blue.css">



  <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
  <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
  <!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
  <![endif]-->

  <!-- Google Font -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">

</head>
<body data-path="<?= base_url() ?>" class="register-page adminlte4-legacy">
 
<div class="register-box" style="margin: 2px auto;">
  <div class="register-logo" style="margin-bottom: 10px;">
      <img style="width:40%;margin-top: unset;" src="<?= base_url('vendor') ?>/img/dentalsac.png">
    <!-- <a href="../../index2.html"><b>Admin</b>LTE</a> -->
  </div>

  <div class="register-box-body" >
     <form id="FormRegistrarUsuario" action="<?= base_url('regpersona/guardarusuario')?>" method="POST" autocomplete="off" >
    <h4 class="login-box-msg">Registra tus Datos</h4>


      
    
      <div class="form-group">
        <input name="apellidos" type="text" class="form-control" placeholder="Apellidos" required="ingrese su nombres">
        <span class="glyphicon glyphicon-hand-left form-control-feedback"></span>
      </div>

      <div class="form-group has-feedback">
        <input name="nombres" type="text" class="form-control" placeholder="Nombres" required="ingresar sus nombres">
        <span class="glyphicon glyphicon-hand-left form-control-feedback"></span>
      </div>

        <div class="form-group has-feedback">
        <select class="form-control input-sm" name="tipo_documento" required="" >
                                  <option value="">--Tipo Documento--</option>
                                  <option value="1">DNI</option>
                                  <option value="2">RUC</option>
                                  <option value="3">PASAPORTE</option>
                                  <option value="4">NIC</option>
                                  <option value="5">CEDULA</option>

                                </select>
      </div>

         <div class="form-group has-feedback">
        <input name="documento" type="text" class="form-control" placeholder="Documento" required="">
        <span class="glyphicon glyphicon-edit form-control-feedback"></span>
      </div>


       <div class="form-group has-feedback" visible="false">
        <select  name="tipo_usuario" class="form-control input-sm" style="display:none" required="">
                    
                    <?php foreach ($roles as $r): ?>
                      <option value="<?= $r->codi_rol ?>"><?= $r->nomb_rol ?> </option>
                    <?php endforeach ?>
                    </select>
      </div>

      <div class="form-group has-feedback">
        <input name="email" type="email" class="form-control" placeholder="Email" required="">
        <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
      </div>

        <div class="form-group has-feedback">
        <input name="usuario" type="text" class="form-control" placeholder="Usuario" required="">
        <span class="glyphicon glyphicon-user form-control-feedback"></span>
      </div>
      <div class="form-group has-feedback">
        <input name="password" type="password" class="form-control" placeholder="Password"  minlength="6" required="">
        <span class="glyphicon glyphicon-lock form-control-feedback"></span>
      </div>

         <div class="form-group has-feedback">
        <input name="passconf" type="password" class="form-control" placeholder="Repita password"  minlength="6" required="">
        <span class="glyphicon glyphicon-log-in form-control-feedback"></span>
      </div>

      <div class="row">
  
        <!-- /.col -->
        <div class="col-xs-12">
          <button type="submit" class="btn btn-primary btn-block btn-lg">Registrar</button>
        </div>
        <div class="row" style="">

                    <div class="col-xs-12" style="text-align:center;">
                        <h4><a href="<?php echo base_url('');?>auth" class="text-center">Iniciar Sesión</a></h4>
                    </div>

                </div>
        <!-- /.col -->
      </div>
   

  <!--   <div class="social-auth-links text-center">

      <a href="#" class="btn btn-block btn-social btn-facebook btn-flat"><i class="fa fa-facebook"></i> Siguenos en Facebook</a>
    
    </div> -->
  
 </form>
  </div>
  <!-- /.form-box -->
</div>
<!-- /.register-box -->

<!-- jQuery 3 -->





<script src="<?php echo base_url('vendor');?>/template/jquery/jquery.min.js"></script>
<script src="<?= base_url('vendor');?>/plugins/jquery-validate/jquery.validate.js"></script>

<script src="<?= base_url('vendor');?>/plugins/jquery-validate/additional-methods.js"></script>
<script src="<?= base_url('vendor');?>/plugins/jquery-validate/localization/messages_es.js"></script>
<script src="<?= base_url('vendor');?>/plugins/jquery-form/jquery.form.js"></script>
  <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
  <script src="<?php echo base_url('vendor');?>/plugins/iCheck/icheck.min.js"></script>
<script src="<?php echo base_url('vendor');?>/template/bootstrap/jsregistro/jquery.backstretch.js"></script>
  <script src="<?php echo base_url('vendor');?>/template/bootstrap/jsregistro/jquery.backstretch.min.js"></script>
   <script src="<?php echo base_url('vendor');?>/template/bootstrap/jsregistro/scripts.js"></script>


<script language="javascript">
    var base_url = '<?php echo base_url(''); ?>';
    var path = '<?= base_url(''); ?>';
</script>
<script>
  $(function () {
    $('input').iCheck({
      checkboxClass: 'icheckbox_square-blue',
      radioClass: 'iradio_square-blue',
      increaseArea: '20%' /* optional */
    });
  });
</script>

</body>
</html>
