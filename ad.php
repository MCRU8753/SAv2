<?php 
include_once('header.php');

#pridobitev oglasa in podatkov uporabnika
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

#štetje ogledov
function get_ad_views($ad_id) {
    global $conn;
    $user_id = "";
    if(isset($_SESSION["USER_ID"])){
        $user_id = $_SESSION["USER_ID"];
    }
    
    if(!empty($user_id)){
        $sql = "SELECT * FROM `ads_users` WHERE `ad_id` = $ad_id AND `user_id` = $user_id";
        $result = mysqli_query($conn, $sql);

        if (mysqli_num_rows($result) == 0) {
            $sql = "INSERT INTO `ads_users` (`ad_id`, `user_id`) VALUES ($ad_id, $user_id)";
            mysqli_query($conn, $sql);
        }
    }
    
    $sql = "SELECT COUNT(*) AS count FROM `ads_users` WHERE `ad_id` = $ad_id";
    $result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_assoc($result);
    $count = $row['count'];

    return $count;
}

function get_comments($id) {
	global $conn;
	$query = "SELECT * FROM comments WHERE ad_id='$id';";
	$res = $conn->query($query);
	$comments = array();
	while($comment = $res->fetch_object()){
		array_push($comments, $comment);
	}
	return $comments;
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
$comments = get_comments($id);
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

		<p>Objavil: <?php echo $ad->firstname . " " . $ad->lastname;?></p>
		<p>Email: <?php echo $ad->email;?></p>
		<?php if(!empty($ad->address)): ?>
			<p>Naslov: <?php echo $ad->address;?></p>
		<?php endif; ?>
		<?php if(!empty($ad->post)): ?>
			<p>Pošta: <?php echo $ad->post;?></p>
		<?php endif; ?>
		<?php if(!empty($ad->phone)): ?>
			<p>Telefon: <?php echo $ad->phone;?></p>
		<?php endif; ?>
        <p>Datum objave: <?php echo $ad->datetime;?></p>
        <?php $num_views = get_ad_views($ad->id) ?>
        <p>Število ogledov: <?php echo $num_views;?></p>
		<a href="index.php"><button class="btn btn-secondary">Nazaj</button></a>
	</div>
	<hr/>

<!--funkcije za komentarje-->

<script>
var endpoint = 'http://ip-api.com/json/?fields=status,message,countryCode';
var xhr = new XMLHttpRequest();
xhr.onreadystatechange = function() {
	if (this.readyState == 4 && this.status == 200) {
		var response = JSON.parse(this.responseText);
		if(response.status !== 'success') {
			console.log('query failed: ' + response.message);
			return
		}
		// Redirect
		if(response.countryCode == "SI") {
			console.log("Success");
			$("#country").val(response.countryCode);
		}
	}
};
xhr.open('GET', endpoint, true);
xhr.send();

$(document).ready(function() {
	$("#post_comment").submit(function(e) {
		e.preventDefault();

		var $form = $(this);
		var $inputs = $form.find("input, select, button, textarea");
		var serializedData = $form.serialize();

		// Fire off the request to /form.php
		request = $.ajax({
			url: "/api/comments",
			type: "post",
			data: serializedData
		});

		request.done(async function (response, textStatus, jqXHR){
			console.log(response);
			$("#izpis").empty();
			await loadComments();
		});

		request.fail(function (jqXHR, textStatus, errorThrown){
			console.error(
				"The following error occurred: "+
				textStatus, errorThrown
			);
		});

		request.always(function () {
			$inputs.prop("disabled", false);
		});
	});
});

</script>
<h4>Komentarji:</h4>
	<?php 
		if(isset($_SESSION["USER_ID"])) { ?>
				<form id="post_comment">
					<input type="hidden" name="ad_id" value="<?php echo $id; ?>">
					<input type="hidden" name="user_id" value="<?php echo $_SESSION["USER_ID"]; ?>">
					<input type="hidden" id="country" name="country" value="">
					<input type="text" class="form-control" name="text">
					<input type="submit" class="btn btn-primary" value="Objavi komentar">
				</form>
	<?php
		} 	?>
<script>

$(document).ready(async function() {
    await loadComments();
});

async function loadComments() {
    await $.get("/api/comments", renderComments);
}

function renderComments(comments) {
	comments.forEach(function(comment) {

		if(comment.ad_id != <?php echo $id;?>) {
			return false;
		}
		console.log(1);
		var div = document.createElement("div");

		var label = document.createElement("label");

		var text = document.createTextNode(comment.country + " - " + comment.user.username + ": " + comment.text);
		label.appendChild(text);
		div.appendChild(label);

		// Če uporabnik ni prijavljen
		<?php if(isset($_SESSION["USER_ID"])) {?>
			// Če ni isti uporabnik ne izrišemo gumba
			if(comment.user_id != <?php echo $_SESSION["USER_ID"] ?>) {
				$("#izpis").append(div);
				return false;
			}			

			var button = document.createElement("button");
			button.className = "btn btn-primary";
			button.innerText = "Izbris komentarja";

			button.onclick = function() {
				request = $.ajax( {
					url: '/api/comments/' + comment.id,
					method: 'DELETE',
					type: 'json'
				});

				request.done(async function (response, textStatus, jqXHR){
					console.log(response);
					$("#izpis").empty();
					await loadComments();
				});

				request.fail(function (jqXHR, textStatus, errorThrown){
					console.error(
						"The following error occurred: "+
						textStatus, errorThrown
					);
				});
			}
			div.appendChild(button);	
			$("#izpis").append(div);
		<?php } ?>
		
		$("#izpis").append(div);
	})
}
</script>
	<div id="izpis"></div>


<?php
include_once('footer.php');
?>
