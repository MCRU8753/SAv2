<?php
include_once('header.php');

function publish($title, $desc, $images, $categories){
    global $conn;
    $title = mysqli_real_escape_string($conn, $title);
    $desc = mysqli_real_escape_string($conn, $desc);
    $user_id = $_SESSION["USER_ID"];

    $query = "INSERT INTO ads (title, description, user_id) VALUES ('$title', '$desc', '$user_id')";
    if($conn->query($query)){
        $ad_id = mysqli_insert_id($conn);

        $ad_images = array();
        foreach($images['tmp_name'] as $key => $tmp_name){
            $img_data = file_get_contents($tmp_name);
            $img_data = mysqli_real_escape_string($conn, $img_data);
            $query = "INSERT INTO ad_images (ad_id, image) VALUES ('$ad_id', '$img_data')";
            $conn->query($query);
            $ad_images[] = mysqli_insert_id($conn);
        }
        $ad_images = implode(",", $ad_images);

        foreach($categories as $category){
            $category_id = mysqli_real_escape_string($conn, $category);
            $query = "INSERT INTO ad_categories (ad_id, category_id) VALUES ('$ad_id', '$category_id')";
            $conn->query($query);
        }
        return true;
    }
    else{
        $error = "Objava ni uspela.";
        return false;
    }
};

$error = "";
if(isset($_POST["submit"])){
    if(publish($_POST["title"], $_POST["description"], $_FILES["images"], $_POST["categories"])){
        header("Location: index.php");
        die();
    }
    else{
        $error = "Prišlo je do napake pri objavi oglasa.";
    }
}

?>
	<h2>Objavi oglas</h2>
	<form action="publish.php" method="POST" enctype="multipart/form-data">
        <label>Naslov</label><input class="form-control" type="text" name="title" /><br>
        <label>Vsebina</label><textarea class="form-control" name="description" rows="10" cols="50"></textarea><br>
        <label>Slike</label><input class="form-control" type="file" name="images[]" id="formFileMultiple" multiple /><br>
        <label>Kategorija</label>
        <select name="categories[]" multiple>
        <?php
            $query = "SELECT * FROM categories";
            $result = mysqli_query($conn, $query);

            while($row = mysqli_fetch_assoc($result)) {
                echo "<option value='" . $row['id'] . "'>" . $row['name'] . "</option>";
            }
        ?>
        </select><br>
        <input class="btn btn-primary" type="submit" name="submit" value="Objavi" /><br>
        <label><?php echo $error; ?></label>
    </form>

<?php
include_once('footer.php');
?>
