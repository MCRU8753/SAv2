<?php
/*
    Model za oglas. Vsebuje lastnosti, ki definirajo strukturo oglasa in sovpadajo s stolpci v bazi.
    Nekatere metode so statične, ker niso vezane na posamezen oglas: poišči vse oglase, vstavi nov oglas, ... 
    Druge so statične, ker so vezane na posamezen oglas: posodobi oglas, izbriši oglas, ... 

    V modelu moramo definirati tudi relacije oz. povezane entitete/modele. V primeru oglasa je to $user, ki 
    povezuje oglas z uporabnikom, ki je oglas objavil. Relacija nam poskrbi za nalaganje podatkov o uporabniku, 
    da nimamo samo user_id, ampak tudi username, ...
*/

class User
{
    public $id;
    public $username;
    public $password;
    public $email;
    public $firstname;
    public $lastname;
    public $address;
    public $post;
    public $phone;
    public $admin;

    public function __construct($id, $username, $password, $email, $firstname, $lastname, $address, $post, $phone, $admin)
    {
        $this->id = $id;
        $this->username = $username;
        $this->password = $password;
        $this->email = $email;
        $this->firstname = $firstname;
        $this->lastname = $lastname;
        $this->address = $address;
        $this->post = $post;
        $this->phone = $phone;
        $this->admin = $admin;
    }

    // Metoda, ki iz baze vrne vse oglase
    public static function all()
    {
        $db = Db::getInstance(); // pridobimo instanco baze
        $query = "SELECT * FROM users;"; // pripravimo query
        $res = $db->query($query); // poženemo query
        $users = array();
        while ($user = $res->fetch_object()) {
            // Za vsak rezultat iz baze ustvarimo objekt (kličemo konstuktor) in ga dodamo v array $ads
            array_push($users, new User($user->id, $user->username, $user->password, $user->email, $user->firstname, $user->lastname, $user->address, $user->post, $user->phone, $user->admin));
        }
        return $users;
    }

    // Metoda, ki vrne en oglas z specifičnim id-jem iz baze
    public static function find($id)
    {
        $db = Db::getInstance();
        $query = "SELECT * FROM users WHERE id = '$id';";
        $res = $db->query($query);
        if ($user = $res->fetch_object()) {
            return new User($user->id, $user->username, $user->password, $user->email, $user->firstname, $user->lastname, $user->address, $user->post, $user->phone, $user->admin);
        }
        return null;
    }

    public static function username_exists($username){

        $db = Db::getInstance();
        $username = mysqli_real_escape_string($db, $username);
        $query = "SELECT * FROM users WHERE username='$username'";
        $res = $db->query($query);
        return mysqli_num_rows($res) > 0;
    }

    public static function username_exists_edit($username, $id){

        $db = Db::getInstance();
        $username = mysqli_real_escape_string($db, $username);
        $query = "SELECT * FROM users WHERE username='$username' AND id NOT IN ($id)";
        $res = $db->query($query);
        return mysqli_num_rows($res) > 0;
    }

    // Metoda, ki doda novega uporabnika v bazo
    public static function insert($username, $password, $email, $firstname, $lastname, $address, $post, $phone)
    {
        $db = Db::getInstance();
        $username = mysqli_real_escape_string($db, $username);
	    $passwordsha = sha1($password);
        $email = mysqli_real_escape_string($db, $email);
        $firstname = mysqli_real_escape_string($db, $firstname);
        $lastname = mysqli_real_escape_string($db, $lastname);
        $address = !empty($address) ? mysqli_real_escape_string($db, $address) : '';
        $post = !empty($post) ? mysqli_real_escape_string($db, $post) : '';
        $phone = !empty($phone) ? mysqli_real_escape_string($db, $phone) : '';

        $query = "INSERT INTO users (username, password, email, firstname, lastname, address, post, phone) VALUES ('$username', '$passwordsha', '$email', '$firstname', '$lastname', '$address', '$post', '$phone');";
        if ($db->query($query)) {
            $id = mysqli_insert_id($db); // preberemo id, ki ga je dobil vstavljen oglas
            return User::find($id); // preberemo nov oglas iz baze in ga vrnemo controllerju
        } else {
            return null; // v primeru napake vrnemo null
        }
    }

    // Metoda, ki posodobi obstoječega uporabnika v bazi
    public function update($username, $password, $email, $firstname, $lastname, $address, $post, $phone)
    {
        $db = Db::getInstance();
        $id = mysqli_real_escape_string($db, $this->id);
        $username = mysqli_real_escape_string($db, $username);
	    $passwordsha = sha1($password);
        $email = mysqli_real_escape_string($db, $email);
        $firstname = mysqli_real_escape_string($db, $firstname);
        $lastname = mysqli_real_escape_string($db, $lastname);
        $address = !empty($address) ? mysqli_real_escape_string($db, $address) : '';
        $post = !empty($post) ? mysqli_real_escape_string($db, $post) : '';
        $phone = !empty($phone) ? mysqli_real_escape_string($db, $phone) : '';

        $query = "UPDATE users SET username = '$username', password = '$passwordsha', email = '$email', firstname = '$firstname', lastname = '$lastname', address = '$address', post = '$post', phone = '$phone' WHERE id = $id";
        
        if ($db->query($query)) {
            return User::find($id); //iz baze pridobimo posodobljen oglas in ga vrnemo controllerju
        } else {
            return null;
        }
    }

    // Metoda, ki izbriše uporabnika in vse oglase iz baze
    public function delete()
    {
        $db = Db::getInstance();
        $id = mysqli_real_escape_string($db, $this->id);
          
        if (isset($_POST['submit'])) {
            $confirm = $_POST['submit'];
            if ($confirm == 'Da') {
                $query = "DELETE FROM ad_categories WHERE ad_id IN (SELECT id FROM ads WHERE user_id = $id)";
                $db->query($query);

                $query = "DELETE FROM ad_images WHERE ad_id IN (SELECT id FROM ads WHERE user_id = $id)";
                $db->query($query);

                $query = "DELETE FROM ads_users WHERE ad_id IN (SELECT id FROM ads WHERE user_id = $id)";
                $db->query($query);

                $query = "DELETE FROM ads WHERE user_id = $id";
                $db->query($query);

                $query = "DELETE FROM ads_users WHERE user_id = $id";
                $db->query($query);

                $query = "DELETE FROM users WHERE id = '$id'";
                $db->query($query);
                require_once('views/users/deleteSuccess.php');
                return true;
            }
            else {
                return false;
            }
        }
        require_once('views/users/modal.php');
    }
}
