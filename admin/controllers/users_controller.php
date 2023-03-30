<?php
/*
    Controller za oglase. Vključuje naslednje standardne akcije:
        index: izpiše vse oglase
        show: izpiše posamezen oglas
        create: izpiše obrazec za vstavljanje oglasa
        store: vstavi obrazec v bazo
        edit: izpiše vmesnik za urejanje oglasa
        update: posodobi oglas v bazi
        delete: izbriše oglas iz baze
*/

class users_controller
{
    public function index()
    {
        //s pomočjo statične metode modela, dobimo seznam vseh oglasov
        //$ads bo na voljo v pogledu za vse oglase index.php
        $users = User::all();

        //pogled bo oblikoval seznam vseh oglasov v html kodo
        require_once('views/users/index.php');
    }

    public function show()
    {
        //preverimo, če je uporabnik podal informacijo, o oglasu, ki ga želi pogledati
        if (!isset($_GET['id'])) {
            return call('pages', 'error'); //če ne, kličemo akcijo napaka na kontrolerju stran
            //retun smo nastavil za to, da se izvajanje kode v tej akciji ne nadaljuje
        }
        //drugače najdemo oglas in ga prikažemo
        $user = User::find($_GET['id']);
        require_once('views/users/show.php');
    }

    public function create()
    {
        // Izpišemo pogled z obrazcem za vstavljanje oglasa
        require_once('views/users/create.php');
    }

    public function store()
    {
        $error = "";
        if($_POST["username"] == "" || $_POST["password"] == "" || $_POST["email"] == "" || $_POST["firstname"] == "" || $_POST["lastname"] == "") {
            $error = "Prosim zapolnite vsa obvezna polja.";
            require_once('views/users/create.php');
            return;
        }
    
        // Check if the passwords match
        else if($_POST["password"] != $_POST["repeat_password"]){
            $error = "Gesli se ne ujemata.";
            require_once('views/users/create.php');
            return;
        }
    
        // Check if the username is already taken
        else if(User::username_exists($_POST["username"])){
            $error = "Uporabniško ime je že zasedeno.";
            require_once('views/users/create.php');
            return;
        }

        // Obdelamo podatke iz obrazca (views/ads/create.php), akcija pričakuje da so podatki v $_POST
        // Tukaj bi morali podatke še validirati, preden jih dodamo v bazo

        // Pokličemo metodo za ustvarjanje novega oglasa
        else if($user = User::insert($_POST["username"], $_POST["password"], $_POST["email"], $_POST["firstname"], $_POST["lastname"], $_POST["address"], $_POST["post"], $_POST["phone"])){
            require_once('views/users/createSuccess.php');
        }
        echo $error;
        //ko je oglas dodan, imamo v $ad podatke o tem novem oglasu
        //uporabniku lahko pokažemo pogled, ki ga bo obvestil o uspešnosti oddaje oglasa
    }

    public function edit()
    {
        // Ob klicu akcije se v URL poda GET parameter z ID-jem oglasa, ki ga urejamo
        // Od modela pridobimo podatke o oglasu, da lahko predizpolnimo vnosna polja v obrazcu
        if (!isset($_GET['id'])) {
            return call('pages', 'error');
        }
        $id = $_GET['id'];
        $user = User::find($_GET['id']);
        require_once('views/users/edit.php');
    }

    public function update()
    {
        $error = "";
        $id = $_POST["id"];

        if($_POST["username"] == "" || $_POST["password"] == "" || $_POST["email"] == "" || $_POST["firstname"] == "" || $_POST["lastname"] == "") {
            $error = "Prosim zapolnite vsa obvezna polja.";
            require_once('views/users/edit.php');
            return;
        }
    
        // Check if the passwords match
        else if($_POST["password"] != $_POST["repeat_password"]){
            $error = "Gesli se ne ujemata.";
            require_once('views/users/edit.php');
            return;
        }
        
        // Check if the username is already taken
        else if(User::username_exists_edit($_POST["username"], $id)){
            $error = "Uporabniško ime je že zasedeno.";
            require_once('views/users/edit.php');
            return;
        }

        // Obdelamo podatke iz obrazca (views/ads/edit.php), ki pridejo v $_POST.
        // Pričakujemo, da je v $_POST podan tudi ID oglasa, ki ga posodabljamo.
        else if (!isset($_POST['id'])) {
            return call('pages', 'error');
        }

        else{
            // Naložimo oglas
            $user = User::find($_POST['id']);
            // Pokličemo metodo, ki posodobi obstoječi oglas v bazi
            $user = $user->update($_POST["username"], $_POST["password"], $_POST["email"], $_POST["firstname"], $_POST["lastname"], $_POST["address"], $_POST["post"], $_POST["phone"]);
            // Izpišemo pogled s sporočilom o uspehu
            require_once('views/users/editSuccess.php');
        }
    }

    public function delete()
    {
        // Obdelamo zahtevo za brisanje oglasa. Akcija pričakuje, da je v URL-ju podan ID oglasa.
        if (!isset($_GET['id'])) {
            return call('pages', 'error');
        }
        // Poiščemo oglas
        $user = User::find($_GET['id']);
        // Kličemo metodo za izbris oglasa iz baze.
        $user->delete();
        // Izpišemo sporočilo o uspehu
    }
}
