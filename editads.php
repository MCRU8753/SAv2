<?php 
include_once('header.php');

#pridovitev oglasa
function get_ad($id){
	global $conn;
	$id = mysqli_real_escape_string($conn, $id);
	$query = "SELECT ads.*, users.firstname, users.lastname, users.email, users.address, users.post, users.phone FROM ads LEFT JOIN users ON users.id = ads.user_id WHERE ads.id = $id;";
	$res = $conn->query($query);
	if($obj = $res->fetch_object()){
		return $obj;
	}
	return null;
}

function get_ad_images($id) {
    global $conn;
    $id = mysqli_real_escape_string($conn, $id);
    $query = "SELECT * FROM ad_images WHERE ad_id = $id ORDER BY id ASC;";
    $res = $conn->query($query);
    $images = array();
    while ($obj = $res->fetch_object()) {
        $images[] = $obj->image;
    }
    return $images;
}

if(!isset($_GET["id"])){
	echo "Manjkajoči parametri.";
	die();
}
$id = $_GET["id"];
$ad = get_ad($id);
if($ad == null){
	echo "Oglas ne obstaja.";
	die();
}

function update($title, $desc, $images, $categories, $id){
    global $conn;
    $title = mysqli_real_escape_string($conn, $title);
    $desc = mysqli_real_escape_string($conn, $desc);
    $user_id = $_SESSION["USER_ID"];
    
    $existing_images = get_ad_images($id);
    
    if(isset($images)){
        #neuporabno
        if (isset($existing_images)) {
            $query = "DELETE FROM ad_images WHERE ad_id = $id";
            $conn->query($query);
        }
        
        $ad_images = array();
        foreach($images['tmp_name'] as $key => $tmp_name){
            $img_data = file_get_contents($tmp_name);
            $img_data = mysqli_real_escape_string($conn, $img_data);

            $query = "INSERT INTO ad_images (ad_id, image) VALUES ('$id', '$img_data')";
            $conn->query($query);
            $ad_images[] = mysqli_insert_id($conn);
        }
        $ad_images = implode(",", $ad_images);
    } else {
        #neuporabno
        $ad_images = implode(",", $existing_images);
    }
    
    if(isset($categories)){
        $query = "DELETE FROM ad_categories WHERE ad_id = $id";
        $conn->query($query);
        
        foreach($categories as $category){
            $category_id = mysqli_real_escape_string($conn, $category);

            $query = "INSERT INTO ad_categories (ad_id, category_id) VALUES ('$id', '$category_id')";
            $conn->query($query);
        }
    }
    
    $query = "UPDATE ads SET title='$title', description='$desc' WHERE ads.id = $id";

    if($conn->query($query)){
        return true;
    }
    else{
        $error = "Posodobitev ni uspela.";
        return false;
    }
}

function delete($id){
    global $conn;
    $user_id = $_SESSION["USER_ID"];

    $query = "DELETE FROM ad_images WHERE ad_id = $id";
    $conn->query($query);

    $query = "DELETE FROM ad_categories WHERE ad_id = $id";
    $conn->query($query);

    $query = "DELETE FROM ads_users WHERE ad_id = $id";
    $conn->query($query);

    $query = "DELETE FROM ads WHERE ads.id = $id";
    $conn->query($query);
}


$error = "";

if(isset($_POST["submit"])) {
    if (!empty($_POST['title']) && !empty($_POST["description"])) {
        if (!empty($_FILES['images']['name'][0]) && !empty($_POST["categories"])) {
            if(update($_POST["title"], $_POST["description"], $_FILES["images"], $_POST["categories"], $id)){
                header("Location: myads.php");
            }
            else{
                $error = "Prišlo je do napake pri posodobitvi oglasa.";
            }
        }
        else{
            $error = "Izberite vsaj eno sliko in kategorijo.";
        }
    }
    else{
        $error = "Ime in vsebina ne smeta biti prazni.";
    }
}

if(isset($_POST["delete"])) {
    delete($id);
    header("Location: myads.php");
}

if(isset($_POST["back"])) {
    header("Location: myads.php");
}

?>
	<div class="ad">
		<h4><?php echo $ad->title;?></h4>
		<p><?php echo $ad->description;?></p>

        <?php
            $images = get_ad_images($ad->id);
            foreach ($images as $image) {
                $img_data = base64_encode($image);
                echo "<img src='data:image/jpg;base64,$img_data' height='400' />";
            }
        ?>

<form method="POST" enctype="multipart/form-data">
	<label for="title">Naslov</label><input class="form-control" type="text" name="title" value="<?php echo $ad->title; ?>" /><br/>
	<label for="description">Vsebina</label><textarea class="form-control" name="description" rows="10" cols="30"><?php echo $ad->description; ?></textarea><br/>
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
	<input class="btn btn-primary" type="submit" name="submit" value="Shrani spremembe" />
</form>
<form method="POST">
    <input class="btn btn-danger" type="submit" name="delete" value="Brisanje oglasa" />
</form>
<form method="POST">
    <input class="btn btn-secondary" type="submit" name="back" value="Nazaj" />
</form>
<label><?php echo $error; ?></label>

	</div>
	<?php

include_once('footer.php');
?>
