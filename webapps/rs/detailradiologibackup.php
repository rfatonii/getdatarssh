<?php
    session_start();
    require_once('../conf/conf.php');
    header("Expires: Mon, 26 Jul 1997 05:00:00 GMT"); 
    header("Last-Modified: ".gmdate("D, d M Y H:i:s")." GMT"); 
    header("Cache-Control: no-store, no-cache, must-revalidate"); 
    header("Cache-Control: post-check=0, pre-check=0", false);
    header("Pragma: no-cache"); // HTTP/1.0
    $action      = isset($_GET['aksi'])?$_GET['aksi']:NULL;
    if($action=="Keluar"){
        session_start();
        $_SESSION["ses_admin_login"]=null;
        unset($_SESSION["ses_admin_login"]); 
        session_destroy();
        exit(header("Location:index.php"));
    }
?>

<!DOCTYPE html>
<html lang="en">
<//?php include 'include/head.php'; ?>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Login</title>
    <link href="css/login.css" rel="stylesheet" type="text/css" />
    <script type="text/javascript" src="conf/validator.js"></script>
    <script>
        function PopupCenter(pageURL, title,w,h) {
            var left = (screen.width/2)-(w/2);
            var top = (screen.height/2)-(h/2);
            var targetWin = window.open (pageURL, title, 'toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=no, resizable=no, copyhistory=no, width='+w+', height='+h+', top='+top+', left='+left);
            
        }
    </script>
        <!-- Favicon icon -->
        <link rel="icon" type="image/png" sizes="16x16" href="./images/favicon.png">
        <link rel="stylesheet" href="./vendor/owl-carousel/css/owl.carousel.min.css">
        <link rel="stylesheet" href="./vendor/owl-carousel/css/owl.theme.default.min.css">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
        <link href="./vendor/jqvmap/css/jqvmap.min.css" rel="stylesheet">
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@500&family=Varela+Round&display=swap" rel="stylesheet">
        <link href="./css/style.css" rel="stylesheet">
        <link href="./css/custom.css" rel="stylesheet">
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>

<body style="font-family: 'Poppins', sans-serif; color: black; background-image: url('../images/rsshlogin.jpg');background-size:cover;">
<?php 
           $sesilogin=isset($_SESSION['ses_admin_login'])?$_SESSION['ses_admin_login']:NULL;
           if ($sesilogin==USERHYBRIDWEB.PASHYBRIDWEB){
                echo "
    <?//php include 'include/navbar.php'; ?>
    <div class='container'>
        <h1  class='mt-3'>GET DATA RSSH</h1>
        <div class='row'>
            <div class='list-group'>
                <a href='detailradiologi.php' class='list-group-item list-group-item-action list-group-item-success'>DETAIL TINDAKAN RADIOLOGI</a>
                <a href='detaillab.php' class='list-group-item list-group-item-action list-group-item-success'>DETAIL TINDAKAN LABORATORIUM</a>
                <a href='catatankeperawatanranap.php' class='list-group-item list-group-item-action list-group-item-success'>CATATAN KEPERAWATAN RANAP</a>
                <a href='?aksi=Keluar'>                                                 
                <img src='images/1360484978_application-pgp-signature.png'/><br>
                Keluar                                               
             </a>
            </div>
        </div>
    </div>";
}else{
    $BtnLogin=isset($_POST['BtnLogin'])?$_POST['BtnLogin']:NULL;
     if (isset($BtnLogin)) {
         $usere      = validTeks4($_POST['usere'],30);
         $passworde  = validTeks4($_POST['passworde'],30);
         if(getOne("select count(admin.passworde) from admin where admin.usere=aes_encrypt('$usere','nur') and admin.passworde=aes_encrypt('$passworde','windi')")>0){
             $_SESSION["ses_admin_login"]= USERHYBRIDWEB.PASHYBRIDWEB;
             exit(header("Location:index.php"));
         }else if(getOne("select count(user.password) from user where user.id_user=aes_encrypt('$usere','nur') and user.password=aes_encrypt('$passworde','windi')")>0){
             $_SESSION["ses_admin_login"]= USERHYBRIDWEB.PASHYBRIDWEB;
             exit(header("Location:index.php"));
         }else{
            echo "        
            <div class=\"container\" style=\"margin-top:11em;\">
            <div class=\"row justify-content-center\">
                <div class=\"col-4 bg-white p-5 rounded-start rounded-4\">
                    <div class=\"card border-0 p-3\">
                        <form id=\"pengenmasuk-form\" role=\"form\" onsubmit=\"return validasi();\" method=\"post\" action=\"\" enctype=multipart/form-data>
                            <div class=\"mb-4\">
                                <label for=\"exampleInputEmail1\" class=\"form-label\">ID User</label>
                                <input type=\"password\" name=\"usere\" class=\"form-control rounded-pill\" id=\"TxtIsi1\" aria-describedby=\"emailHelp\" pattern=\"[a-zA-Z0-9, ./_]{1,30}\" title=\" a-zA-Z0-9, ./_ (Maksimal 30 karakter)\" onkeydown=\"setDefault(this, document.getElementById('MsgIsi1'));\">
                            </div>
                            <div class=\"mb-3\">
                                <label for=\"exampleInputPassword1\" class=\"form-label\">Password</label>
                                <input type=\"password\" name=\"passworde\" class=\"form-control rounded-pill\" id=\"TxtIsi2\" onkeydown=\"setDefault(this, document.getElementById('MsgIsi2'));\">
                                <div id=\"passHelp\" class=\"form-text\">Jika lupa ID/pass, Silahkan konfirmasi ke Unit IT.</div>
                            </div>
                            <input type=\"submit\"  name=\"BtnLogin\" class=\"btn btn-login border-success border-3 text-center rounded-pill\" value=\"Login\" style=\"width:70%;color:#17D777;\">
                        </form>
                    </div>
                </div>
                <div class=\"col-4 rounded-end rounded-4\" style=\"background-color:#17D777;\"><img src=\"../images/cyberlogin1.png\" class=\"img-fluid\" style=\"width:30em;\" alt=\"\"></div>
            </div>
            </div>";
        }
     }else{
        echo "        
        <div class=\"container\" style=\"margin-top:11em;\">
        <div class=\"row justify-content-center\">
            <div class=\"col-4 bg-white p-5 rounded-start rounded-4\">
                <div class=\"card border-0 p-3\">
                    <form id=\"pengenmasuk-form\" role=\"form\" onsubmit=\"return validasi();\" method=\"post\" action=\"\" enctype=multipart/form-data>
                        <div class=\"mb-4\">
                            <label for=\"exampleInputEmail1\" class=\"form-label\">ID User</label>
                            <input type=\"password\" name=\"usere\" class=\"form-control rounded-pill\" id=\"TxtIsi1\" aria-describedby=\"emailHelp\" pattern=\"[a-zA-Z0-9, ./_]{1,30}\" title=\" a-zA-Z0-9, ./_ (Maksimal 30 karakter)\" onkeydown=\"setDefault(this, document.getElementById('MsgIsi1'));\">
                        </div>
                        <div class=\"mb-3\">
                            <label for=\"exampleInputPassword1\" class=\"form-label\">Password</label>
                            <input type=\"password\" name=\"passworde\" class=\"form-control rounded-pill\" id=\"TxtIsi2\" onkeydown=\"setDefault(this, document.getElementById('MsgIsi2'));\">
                            <div id=\"passHelp\" class=\"form-text\">Jika lupa ID/pass, Silahkan konfirmasi ke Unit IT.</div>
                        </div>
                        <input type=\"submit\"  name=\"BtnLogin\" class=\"btn btn-login border-success border-3 text-center rounded-pill\" value=\"Login\" style=\"width:70%;color:#17D777;\">
                    </form>
                </div>
            </div>
            <div class=\"col-4 rounded-end rounded-4\" style=\"background-color:#17D777;\"><img src=\"../images/cyberlogin1.png\" class=\"img-fluid\" style=\"width:30em;\" alt=\"\"></div>
        </div>
        </div>";
    }    
}
?>
    

    <?php include 'include/script.php'; ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>