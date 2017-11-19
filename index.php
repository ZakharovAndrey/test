<?php
//error_reporting(0);

include('Net/SSH2.php');
include('Crypt/RSA.php');

$key = new Crypt_RSA();
$key->loadKey(file_get_contents('/usr/share/httpd/.ssh/id_rsa'));

## connect to backup server
$ssh_barman = new Net_SSH2('192.168.1.73');
if (!$ssh_barman->login('barman', $key)) {
    exit('Login Failed');
}

echo "check backup connectivity :<br>";
echo "<b>".$ssh_barman->exec("/usr/bin/barman check all| sed 's/$/\<br\>/g'")."</b>";

echo "<br>";
echo "<br>";

echo "list backups for main-db-server<br>";
echo "<b>".$ssh_barman->exec("/usr/bin/barman list-backup main-db-server | sed 's/$/\<br\>/g'")."</b>";

echo "<br>";

## connect to master server
$ssh_master = new Net_SSH2('192.168.1.71');
if (!$ssh_master->login('postgres', $key)) {
    exit('Login Failed');
}

echo "Show master IP :<br>";
echo "<b>".$ssh_master->exec("/sbin/ifconfig enp0s3 | grep 'inet' | cut -d: -f2 | awk '{print $2}'")."</b>";


echo "<br>";
echo "<br>";

echo "Show master date:<br>";
echo "<b>".$ssh_master->exec("/usr/bin/date")."</b>";


echo "<br>";
echo "<br>";

## connect to slave server
$ssh_slave = new Net_SSH2('192.168.1.72');
if (!$ssh_slave->login('postgres', $key)) {
    exit('Login Failed');
}

echo "Show slave IP :<br>";
echo "<b>".$ssh_slave->exec("/sbin/ifconfig enp0s3 | grep 'inet' | cut -d: -f2 | awk '{print $2}'")."</b>";


echo "<br>";
echo "<br>";

echo "Show slave date:<br>";
echo "<b>".$ssh_slave->exec("/usr/bin/date")."</b>";

?>
