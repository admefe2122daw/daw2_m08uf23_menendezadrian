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
        <title>Modificar Usuari</title>
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
            if ($_POST['method'] == "PUT") {
                if ($_POST['uid'] && $_POST['ou'] && $_POST['radioValue'] && $_POST['nouContingut']) {

                    $atribut = $_POST['radioValue'];
                    $nou_contingut = $_POST['nouContingut'];

                    $uid = $_POST['uid'];
                    $ou = $_POST['ou'];
                    $dn = 'uid=' . $uid . ',ou=' . $ou . ',dc=fjeclot,dc=net';

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
                    $entrada = $ldap->getEntry($dn);
                    if ($entrada) {
                        Attribute::setAttribute($entrada, $atribut, $nou_contingut);
                        $isModificat = true;
                        $ldap->update($dn, $entrada);
                        echo "Atribut modificat correctament<br />";
                    } else {
                        echo "<b>Aquesta entrada no existeix</b><br />";
                    }
                    echo '<a href="http://zend-admefe/ldap/modificarUsuari.php">Modificar un altre Usuari</a> <br />';
                    echo '<a href="http://zend-admefe/ldap/menu.php">Tornar a Inici</a>';
                }
            } else {
            ?>
                <div class="formldap">
                    <div class="form">
                        <form class="login-form" action="http://zend-admefe/ldap/modificarUsuari.php" method="POST" autocomplete="off">
                            <h5>Modificar Usuari</h5>
                            <input type="text" name="method" value="PUT" class="hidden">
                            <input type="text" name="ou" placeholder="Unitat Organitzativa" required />
                            <input type="text" name="uid" placeholder="Usuari" required />
                            <div class="d-flex flex-form p-2 bd-highlight"><input type="radio" name="radioValue" value="uidNumber" /><span class="formLabel">UID Number</span></div>
                            <div class="d-flex flex-form p-2 bd-highlight"><input type="radio" name="radioValue" value="gidNumber" /><span class="formLabel">GID Number</span></div>
                            <div class="d-flex flex-form p-2 bd-highlight"><input type="radio" name="radioValue" value="homeDirectory" /><span class="formLabel">Directori Personal</span></div>
                            <div class="d-flex flex-form p-2 bd-highlight"><input type="radio" name="radioValue" value="loginShell" /><span class="formLabel">Shell</span></div>
                            <div class="d-flex flex-form p-2 bd-highlight"><input type="radio" name="radioValue" value="cn" /><span class="formLabel">CN</span></div>
                            <div class="d-flex flex-form p-2 bd-highlight"><input type="radio" name="radioValue" value="sn" /><span class="formLabel">SN</span></div>
                            <div class="d-flex flex-form p-2 bd-highlight"><input type="radio" name="radioValue" value="givenName" /><span class="formLabel">Given Name</span></div>
                            <div class="d-flex flex-form p-2 bd-highlight"><input type="radio" name="radioValue" value="postalAddress" /><span class="formLabel">Adreça Postal</span></div>
                            <div class="d-flex flex-form p-2 bd-highlight"><input type="radio" name="radioValue" value="mobile" /><span class="formLabel">Mòbil</span></div>
                            <div class="d-flex flex-form p-2 bd-highlight"><input type="radio" name="radioValue" value="telephoneNumber" /><span class="formLabel">Telèfon Fixe</span></div>
                            <div class="d-flex flex-form p-2 bd-highlight"><input type="radio" name="radioValue" value="title" /><span class="formLabel">Títol</span></div>
                            <div class="d-flex flex-form p-2 bd-highlight"><input type="radio" name="radioValue" value="description" /><span class="formLabel">Descripció</span></div>
                            <input type="text" name="nouContingut" placeholder="Nou Contingut" required />
                            <input type="submit" class="button" value="Modificar Usuari" />
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