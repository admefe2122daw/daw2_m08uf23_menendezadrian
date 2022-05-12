<?php
require 'vendor/autoload.php';

use Laminas\Ldap\Ldap;

session_start();
if (isset($_SESSION['admin'])) {
    ini_set('display_errors', 0);
?>
    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css">
        <title>Esborrar Usuari</title>
        <link rel="stylesheet" href="style.css">
        <style>
            .title {
                text-align: center;
            }
            .hidden{
            	display:none;
            }
        </style>
    </head>

    <body>
        <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav mr-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="http://zend-admefe/ldap/menu.php"><i class="bi bi-house-door-fill"></i> Inici </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="http://zend-admefe/ldap/tancarsessio.php"><i class="bi bi-power"></i> Tancar Sessió </a>
                    </li>
            </div>
        </nav>
        <div class="container">
            <?php
            if ($_POST['method'] == "DELETE") {
                if ($_POST['usr'] && $_POST['ou']) {

                    $uid = $_POST['usr'];
                    $unorg = $_POST['ou'];
                    $dn = 'uid=' . $uid . ',ou=' . $unorg . ',dc=fjeclot,dc=net';

                    $opcions = [
                        'host' => 'zend-admefe',
                        'username' => 'cn=admin,dc=fjeclot,dc=net',
                        'password' => 'fjeclot',
                        'bindRequiresDn' => true,
                        'accountDomainName' => 'fjeclot.net',
                        'baseDn' => 'dc=fjeclot,dc=net',
                    ];

                    $ldap = new Ldap($opcions);
                    $ldap->bind();
                    $isEsborrat = false;
                    try {
                        if ($ldap->delete($dn)) echo "<b>Entrada esborrada</b><br>";
                    } catch (Exception $e) {
                        echo "<b>Error, Aquesta entrada no existeix</b><br>";
                    }
                    echo '<a href="http://zend-admefe/ldap/esborrarUsuari.php">Esborrar un altre Usuari</a> <br />';
                    echo '<a href="http://zend-admefe/ldap/menu.php">Tornar a Inici</a>';
                }
            } else {
            ?>
                <div class="formldap">
                    <div class="form">
                        <form class="login-form" action="http://zend-admefe/ldap/esborrarUsuari.php" method="POST" autocomplete="off">
                            <h5>Esborrar Usuari</h5>
                            <input type="text" name="method" value="DELETE" class="hidden">
                            <input type="text" name="ou" placeholder="Unitat Organitzativa" required />
                            <input type="text" name="usr" placeholder="Usuari" required />
                            <input type="submit" class="button" value="Esborrar Usuari" />
                            <input type="reset" class="button" value="Neteja Formulari" />
                        </form>
                    </div>
                </div>
            <?php
            }
            ?>
        </div>
    </body>

    </html>
<?php
} else {
    header("Location: http://zend-admefe/ldap/");
}
?>