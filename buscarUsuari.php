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
        <title>Cerca d'Usuaris</title>
        <link rel="stylesheet" href="style.css">
        <style>
            .title {
                text-align: center;
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
            if ($_GET['usr'] && $_GET['ou']) {
                $domini = 'dc=fjeclot,dc=net';
                $opcions = [
                    'host' => 'zend-admefe',
                    'username' => "cn=admin,$domini",
                    'password' => 'fjeclot',
                    'bindRequiresDn' => true,
                    'accountDomainName' => 'fjeclot.net',
                    'baseDn' => 'dc=fjeclot,dc=net',
                ];
                $ldap = new Ldap($opcions);
                $ldap->bind();
                $entrada = 'uid=' . $_GET['usr'] . ',ou=' . $_GET['ou'] . ',dc=fjeclot,dc=net';
                $usuari = $ldap->getEntry($entrada);

                echo '<table class="table">
            <thead>
                <tr>
                    <th scope="col" colspan="2" class="title">' . $usuari["dn"] . '</th>
                </tr>
                <tr>
                    <th scope="col">Atribut</th>
                    <th scope="col">Valor</th>
                </tr>
            </thead>
            <tbody>';
                foreach ($usuari as $atribut => $dada) {
                    if ($atribut != "dn") {
                        //echo $atribut . ": " . $dada[0] . '<br>';
                        echo '<tr>
                    <th scope="row">' . $atribut . '</th>
                    <td>' . $dada[0] . '</td>
                </tr>';
                    }
                }
                echo "</tbody>
        </table>";
                echo '<a href="http://zend-admefe/ldap/buscarUsuari.php">Buscar un altre Usuari</a> <br />';
                echo '<a href="http://zend-admefe/ldap/menu.php">Tornar a Inici</a>';
            } else {
            ?>
                <div class="formldap">
                    <div class="form">
                        <form class="login-form" action="http://zend-admefe/ldap/buscarUsuari.php" method="GET" autocomplete="off">
                            <h5>Cercar Usuari</h5>
                            <input type="text" name="ou" placeholder="Unitat Organitzativa" required />
                            <input type="text" name="usr" placeholder="Usuari" required />
                            <input type="submit" class="button" value="Cercar Usuari" />
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