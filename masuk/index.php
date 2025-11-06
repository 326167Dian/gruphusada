<?php
// session_start();
// if (!empty($_SESSION['namauser']) and !empty($_SESSION['passuser'])) {
//     echo "<script type='text/javascript'>window.location='media_admin.php?module=home'</script>";
// }
?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>MYSIFA</title>
    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <!-- Bootstrap 3.3.5 -->
    <link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css">
    <!-- Ionicons -->
    <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="dist/css/AdminLTE.min.css">
    <!-- iCheck -->
    <link rel="stylesheet" href="plugins/iCheck/square/blue.css">


    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
          <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
          <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
      <![endif]-->

</head>

<body class="hold-transition login-page">

<div class="login-box">

    <div class="login-box-body">
        <div class="container-fluid text-center">
            <img align="center" src="images/mysifalogo.png">
            <h3 style="text-align:center"><b>GRUP APOTEK HUSADA</b></h3>
        </div>

        <OL>
            <li><a style="color:#FFFFFF;font-size:20px;font-weight:bold;" class='btn  btn-success btn-flat' href='../aulia/index.php'>Apotek Aulia Husada Taufiqurrohman </a></li>
            <li><a style="color:#FFFFFF;font-size:20px;font-weight:bold;" class='btn  btn-info btn-flat' href='../anugrah/index.php'>Apotek Anugrah Husada Taufiqurrohman</a></li>
            <li><a style="color:#FFFFFF;font-size:20px;font-weight:bold;" class='btn  btn-primary btn-flat' href='../asyfa/index.php'>Apotek Anugrah Husada Baru</a></li>
            <li><a style="color:#FFFFFF;font-size:20px;font-weight:bold;" class='btn  btn-info btn-flat' href='../temboro/index.php'>Apotek Anugrah Sukowinangun Baru</a></li>
            <li><a style="color:#FFFFFF;font-size:20px;font-weight:bold;" class='btn  btn-warning btn-flat' href='../sukowinangun/index.php'>Apotek Anugrah Sukowinangun</a></li>
            <li><a style="color:#FFFFFF;font-size:20px;font-weight:bold;" class='btn  btn-danger btn-flat' href='../assyfanew/index.php'>Apotek As Sifa Baru</a></li>
            <li><a style="color:#FFFFFF;font-size:20px;font-weight:bold;" class='btn  btn-danger btn-flat' href='../auliabaru/index.php'>Apotek Anugrah Temboro</a></li>

        </OL>



    </div><!-- /.login-box-body -->
    <center>BEKASI 05 Desember 2024</center>
</div><!-- /.login-box -->

<!-- jQuery 2.1.4 -->
<script src="../../plugins/jQuery/jQuery-2.1.4.min.js"></script>
<!-- Bootstrap 3.3.5 -->
<script src="../../bootstrap/js/bootstrap.min.js"></script>
<!-- iCheck -->
<script src="../../plugins/iCheck/icheck.min.js"></script>

<script>
    $(function() {
        $('input').iCheck({
            checkboxClass: 'icheckbox_square-blue',
            radioClass: 'iradio_square-blue',
            increaseArea: '20%' // optional
        });
    });
</script>

</body>


</html>