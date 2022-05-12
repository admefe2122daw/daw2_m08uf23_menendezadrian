<?php
require 'vendor/autoload.php';

use Laminas\Ldap\Attribute;
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
        <title>Afegir Usuari</title>
        <link rel="stylesheet" href="style.css">
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
            if (
                $_POST['uid'] && $_POST['ou'] && $_POST['uidNumber'] && $_POST['gidNumber'] && $_POST['homeDirectory'] && $_POST['shell']
                && $_POST['cn'] && $_POST['sn'] && $_POST['givenName'] && $_POST['postalAddress'] && $_POST['mobil'] && $_POST['telefon']
                && $_POST['title'] && $_POST['description']
            ) {
                $uid = '' . $_POST['uid'];
                $ou = '' . $_POST['ou'];
                $uidNumber = $_POST['uidNumber'];
                $gidNumber = $_POST['gidNumber'];
                $homeDirectory = '' . $_POST['homeDirectory'];
                $shell = '' . $_POST['shell'];
                $cn = '' . $_POST['cn'];
                $sn = '' . $_POST['sn'];
                $givenName = '' . $_POST['givenName'];
                $postalAddress = '' . $_POST['postalAddress'];
                $mobil = '' . $_POST['mobil'];
                $telefon = '' . $_POST['telefon'];
                $titol = '' . $_POST['title'];
                $descripcio = '' . $_POST['description'];
                $objcl = array('inetOrgPerson', 'organizationalPerson', 'person', 'posixAccount', 'shadowAccount', 'top');

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
                $nova_entrada = [];
                Attribute::setAttribute($nova_entrada, 'objectClass', $objcl);
                Attribute::setAttribute($nova_entrada, 'uid', $uid);
                Attribute::setAttribute($nova_entrada, 'uidNumber', $uidNumber);
                Attribute::setAttribute($nova_entrada, 'gidNumber', $gidNumber);
                Attribute::setAttribute($nova_entrada, 'homeDirectory', $homeDirectory);
                Attribute::setAttribute($nova_entrada, 'loginShell', $shell);
                Attribute::setAttribute($nova_entrada, 'cn', $cn);
                Attribute::setAttribute($nova_entrada, 'sn', $sn);
                Attribute::setAttribute($nova_entrada, 'givenName', $givenName);
                Attribute::setAttribute($nova_entrada, 'mobile', $mobil);
                Attribute::setAttribute($nova_entrada, 'postalAddress', $postalAddress);
                Attribute::setAttribute($nova_entrada, 'telephoneNumber', $telefon);
                Attribute::setAttribute($nova_entrada, 'title', $titol);
                Attribute::setAttribute($nova_entrada, 'description', $descripcio);
                $dn = 'uid=' . $uid . ',ou=' . $ou . ',dc=fjeclot,dc=net';
                if ($ldap->add($dn, $nova_entrada)) {
                    echo "Usuari creat <br />";
                    echo '<a href="http://zend-admefe/ldap/afegirUsuari.php">Afegir un altre Usuari</a> <br />';
                    echo '<a href="http://zend-admefe/ldap/menu.php">Tornar a Inici</a>';
                }
            } else {
            ?>
                <div class="formldap">
                    <div class="form">
                        <form class="login-form" action="http://zend-admefe/ldap/afegirUsuari.php" method="POST" autocomplete="off">
                            <h5>Cercar Usuari</h5>
                            <input type="text" name="uid" placeholder="UID" required />
                            <input type="text" name="ou" placeholder="Unitat Organitzativa" required />
                            <input type="number" name="uidNumber" placeholder="UID Number" required />
                            <input type="number" name="gidNumber" placeholder="GID Number" required />
                            <input type="text" name="homeDirectory" placeholder="Directori Personal" required />
                            <input type="text" name="shell" placeholder="Shell" required />
                            <input type="text" name="cn" placeholder="CN" required />
                            <input type="text" name="sn" placeholder="SN" required />
                            <input type="text" name="givenName" placeholder="Given Name" required />
                            <input type="text" name="postalAddress" placeholder="Areça Postal" required />
                            <input type="text" name="mobil" placeholder="Nº de Telèfon Mòbil" required />
                            <input type="text" name="telefon" placeholder="Nº de Telèfon" required />
                            <input type="text" name="title" placeholder="Títol" required />
                            <input type="text" name="description" placeholder="Descripció" required />
                            <input type="submit" class="button" value="Afegir Usuari" />
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