<?php
include_once('header.php');

function get_ads(){
    global $conn;
    $query = "SELECT * FROM ads ORDER BY datetime DESC;";
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
?>

<script>
$(document).ready(async function() {
    await loadLastComments();
});

async function loadLastComments() {
    await $.get("/api/comments/lastFive", renderLastComments);
}

function renderLastComments(comments) {
	comments.forEach(function(comment) {
		label.appendChild(text);
		div.appendChild(label);

		var button = document.createElement("button");
		button.className = "btn btn-primary";
		button.innerText = "Povezava";
		button.style.float = "right";

		button.onclick = function() {
			window.location.href = "ad.php?id=" + comment.ad_id;
		}
		div.appendChild(button);	
		$("#izpis").append(div);
	})
}

</script>
<div >
	<h4>Zadnjih pet komentarjev</h2>
		<div id="izpis"></div>
</div>
<hr/>
<?php
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
        <a href="ad.php?id=<?php echo $ad->id;?>"><button class="btn btn-primary">Preberi več</button></a>
    </div>
    <hr/>
    <?php
}

include_once('footer.php');
?>
