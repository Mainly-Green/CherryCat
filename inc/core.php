<?php
//	Core Functions I guesss

session_start();
$db = new PDO('mysql:unix_socket=/var/run/mysqld/mysqld.sock;dbname=xxx', 'xxx', 'xxx', array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'UTF8'"));

function register ($email, $pass) {
        global $db;
        $do = $db->prepare("SELECT email FROM accounts WHERE email = (:email)");
        $do->bindParam(':email', $email);
        $do->execute();
        $result = $do->fetch();
        if($result['email'] === $email){
                header('Location: ../register/index.html#fail');
        }else{
        $do = $db->prepare("INSERT INTO accounts (email, pass) VALUES (:email, :pass)");
        $do->bindParam(':email', $email);
        $hash = password_hash($pass, PASSWORD_DEFAULT);
        $do->bindParam(':pass', $hash);
        $do->execute();
        $_SESSION['id'] = $result['id'];
        $_SESSION['email'] = $result['email'];
        header('Location: api.php?do=cp');
        }
}

function login ($email, $pass) {
        global $db;
        $do = $db->prepare("SELECT pass, id, email, level FROM accounts WHERE email = (:email)");
        $do->bindParam(':email', $email);
        $do->execute();
        $result = $do->fetch(PDO::FETCH_ASSOC);

        if (password_verify($pass, $result['pass'])) {
                $_SESSION['id'] = $result['id'];
                $_SESSION['email'] = $result['email'];
                $_SESSION['level'] = $result['level'];
                header('Location: api.php?do=cp');
        }else{
                header('Location: ../login/index.html#fail');
  
	}

// Function totally not done at all
function upload ($torrent, $name, $desc, $tags){
        global $db;
        $do = $db->prepare("INSERT INTO torrents (torrent, name, desc, tags) VALUES (:torrent, :name, :desc, :tags)");
        $do->bindParam('torrent', $torrent);
        $do->bindParam('name', $name);
        $do->bindParam('desc', $desc);
        $do->bindParam('tags', $tags);
        $do->execute();
}
?>