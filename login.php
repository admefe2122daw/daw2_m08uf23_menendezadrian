<?php
require 'vendor/autoload.php';

use Laminas\Ldap\Ldap;

if ($_POST['usuari'] && $_POST['password']) {
    $opcions = [
        'host' => 'zend-admefe',
        'username' => "cn=admin,dc=fjeclot,dc=net",
        'password' => 'fjeclot',
        'bindRequiresDn' => true,
        'accountDomainName' => 'fjeclot.net',
        'baseDn' => 'dc=fjeclot,dc=net',
    ];
    $ldap = new Ldap($opcions);
    $dn = 'cn=' . $_POST['usuari'] . ',dc=fjeclot,dc=net';
    $ctsnya = $_POST['password'];
    try {
        $ldap->bind($dn, $ctsnya);
        session_start();
        $SESSIONDATA = array("usuari" => $_POST['usuari'], "password" => $_POST['password']);
        $_SESSION['admin'] = $SESSIONDATA;
        header("Location: http://zend-admefe/ldap/menu.php");
    } catch (Exception $e) {
        echo "<b>Error, Usuari i/o contrasenya incorrecta</b><br>";
        echo '<a href="http://zend-admefe/ldap/">Tornar a l\'inici</a>';
    }
}
