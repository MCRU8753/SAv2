<?php
include_once('header.php');

#pridobitev oglasov glede na uporabnika
function get_ads(){
    global $conn;
    $user_id = $_SESSION["USER_ID"];
    $query = "SELECT * FROM ads WHERE ads.user_id = $user_id ORDER BY datetime DESC;";
    $res = $conn->query($query);
    $ads = array();
    while($ad = $res->fetch_object()){
        array_push($ads, $ad);
    }
    return $ads;
}

function get_ad_categories($id){
    global $conn;
    $categoryNames = array();

  $sql = "SELECT categories.name FROM categories INNER JOIN ad_categories ON categories.id=ad_categories.category_id WHERE ad_categories.ad_id='$id'";
  $result = $conn->query($sql);

  if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
      echo "-" . $row['name'] . "<br>";
    }
  }
}

function get_ad_image($id){
    global $conn;
    $id = mysqli_real_escape_string($conn, $id);
    $query = "SELECT * FROM ad_images WHERE ad_id = $id ORDER BY id ASC LIMIT 1;";
    $res = $conn->query($query);
    if($obj = $res->fetch_object()){
        return $obj->image;
    }
    return null;
}

$ads = get_ads();

#izpis oglasov in link z GET parametrom
foreach($ads as $ad){
    ?>
    <div class="ad">
        <h4><?php echo $ad->title;?></h4>
        <p><?php echo $ad->description;?></p>

        <?php
        $ad_image = get_ad_image($ad->id);
        if($ad_image != null){
            $img_data = base64_encode($ad_image);
            ?>
            <img src="data:image/jpg;base64, <?php echo $img_data;?>" width="400"/><br>
            <?php
        }
        ?>
        <p>Kategorija:<br>
        <?php
        $categories = get_ad_categories($ad->id);
        ?>
        </p>
        <a href="editads.php?id=<?php echo $ad->id;?>"><button class="btn btn-primary">Uredi</button></a>
    </div>
    <hr/>
    <?php
}

include_once('footer.php');
?>
