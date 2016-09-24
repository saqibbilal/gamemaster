<!doctype html>
<html class="fuelux" lang="en">
    <head> 
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Game Masters | play on!</title>
        
        <link rel="stylesheet" type="text/css" href="<?php echo base_url().'assets/bootstrap/css/bootstrap.css' ?>">
        <link rel="stylesheet" type="text/css" href="<?php echo base_url().'assets/font-awesome/css/font-awesome.css' ?>">
        <link rel="stylesheet" type="text/css" href="<?php echo base_url().'assets/css/login.css' ?>">

        <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
        <![endif]-->

        <script src="<?php echo base_url().'assets/js/jquery-3.1.0.js' ?>"></script>
        <script src="<?php echo base_url().'assets/js/login.js' ?>"></script>
        <script src="<?php echo base_url().'assets/bootstrap/js/bootstrap.js' ?>"></script>


        <!--  <link rel="shortcut icon" href="favicon.ico" type="image/png">-->
        <!--  <link rel="shortcut icon" type="image/png" href="favicon.ico" />-->
        <script type="text/javascript">
            var SITE_URL = '<?php echo site_url(); ?>';
            var BASE_URL = '<?php echo base_url(); ?>';
        </script>
    </head>
    <body>
        
                        {my_yield}
                  
<?php
//foreach ($this->js as $js) {
//    echo link_js($js) . "\n";
//}
?>                   
    </body>
</html>