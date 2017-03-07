
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <title>PROKSAM</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="description" content="">
        <meta name="author" content="">

        <!-- Le styles -->
        <link href="<?php echo SITE_ROOT; ?>bootstrap/css/bootstrap.css" rel="stylesheet"/>
        <link href="<?php echo SITE_ROOT; ?>styles/style.css" rel="stylesheet"/>

        <!-- HTML5 shim, for IE6-8 support of HTML5 elements -->
        <!--[if lt IE 9]>
          <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
        <![endif]-->
        <script src="<?php echo SITE_ROOT; ?>scripts/jquery.js"></script>
    </head>

    <body style="background-color: #EEEEEE;">
            <div class="container" style="width: 300px;" style="">
                ini login
                <div class="panel panel-default box-shadow">
                    <div class="panel-body">
                        <div class="spacer"></div>
                        <form  method="POST" class="form-signin" role="form" action="login">                            
                            <input type="text" name="username" class="form-control" placeholder="Nama Pengguna" required autofocus style="margin-bottom: 10px;">                    
                            <input type="password" name="password" class="form-control" placeholder="Password" required style="margin-bottom: 10px;">
                            <button class="btn btn-lg btn-primary btn-block" type="submit">Daftar Masuk</button>
                        </form>
                    </div>
                </div>
            </div>
    </body>
</html>


