<?php
    #Check the connections with the server and DB
    session_start();
	if(isset($_SESSION['loggedUser']) && $_SESSION['loggedUser']==true){
		include "./components/connection.php";
		include "components/methods.php";
		if ($_SERVER["REQUEST_METHOD"]=="GET"){
			$REUN = $_GET['REUN'];		
		}
		
		if (!empty($_POST['setOffer'])){
			$REUN = $_GET['REUN'];
			$landPrice = $_POST['landPrice'];
			$OfferID = rand (1000, 9999);
			$requestID = rand (1000, 9999);
			
			//Check that the  owner can not giva an offer for his land 
			$query1="SELECT ID FROM users WHERE ID IN (SELECT IDNumber from landrecord WHERE REUN= $REUN)"; 
			$result = mysqli_query($con, $query1);
			$row = mysqli_fetch_array($result);
			$ID = $row["ID"];
			if ($ID == $_SESSION['loggedUser']){
			  echo "<script>alert(' لايمكنك تقديم عرض شراء للارض التي تملكها')</script>";
			}else{ 
			  $OwnerInfo = "SELECT IDNumber FROM landrecord WHERE REUN = $REUN";
			  $OwnerRes = mysqli_query($con, $OwnerInfo);
			  $OwnerRow = mysqli_fetch_array($OwnerRes);
			  $OwnerID = $OwnerRow["IDNumber"];
	  
			  $stmt=$con->prepare("INSERT INTO offers (OfferID,landPrice,OwnerID, BuyerID,REUN,requestID) VALUES (?,?,?,?,?,?)");
			  $stmt -> bind_param("ssssss",$OfferID,$landPrice,$OwnerID,$_SESSION['loggedUser'],$REUN,$requestID);
			  $stmt->execute();
			  echo "<script>alert('تم إرسال الطلب بنجاح')</script>";
			  echo "<script>setTimeout(\"location.href = 'landBrowsePage.php';\",1500);</script>";
			}
		  }else{

		  	// $viewLand = "SELECT * FROM landrecord NATURAL JOIN landinfo WHERE REUN = $REUN AND landrecord.REUN=landinfo.REUN;";
			// $result = mysqli_query($con,$viewLand);
			$Landinfo=array();
			$Landinfo=getLandInfo($REUN);
			$IDNumber = $Landinfo[7];

			//user info
			$firstName = $Landinfo[0];
			$middleName = $Landinfo[1];
			$lastName = $Landinfo[2];
			$nationality = $Landinfo[3];
			$share = $Landinfo[4];
			$address = $Landinfo[5];
			$IDType = $Landinfo[6];
			$IDdate = $Landinfo[8];
			

			//land info
			$pieceNumber = $Landinfo[9];
			$blockNumber = $Landinfo[10];
			$planNumber = $Landinfo[11];
			$neighborhoodName = $Landinfo[12];
			$city = $Landinfo[13];
			
			$unitType = $Landinfo[15];
			$deedNumber = $Landinfo[16];
			$deedDate = $Landinfo[17];
			$courtIssued = $Landinfo[18];
			$spaceInNumbers = $Landinfo[19];
			$spaceInWriting = $Landinfo[20];
			$bordersNorth = $Landinfo[21];
			$bordersSouth = $Landinfo[22];
			$bordersEast = $Landinfo[23];
			$bordersWest = $Landinfo[24];
			$lengthNorth = $Landinfo[25];
			$lengthSouth = $Landinfo[26];
			$lengthEast = $Landinfo[27];
			$lengthWest = $Landinfo[28];
			
			//location info
			$LongitudeA = $Landinfo[29];
			$LongitudeB = $Landinfo[30];
			$LongitudeC = $Landinfo[31];
			$LongitudeD = $Landinfo[32];
			$LatitudeA = $Landinfo[33];
			$LatitudeB = $Landinfo[34];
			$LatitudeC = $Landinfo[35];
			$LatitudeD = $Landinfo[36];
			$angleA = $Landinfo[37];
			$angleB = $Landinfo[38];
			$angleC = $Landinfo[39];
			$angleD = $Landinfo[40];
			$Lats   = $Landinfo[41];
			$Longs  = $Landinfo[42];

			$lat = (double)$Lats;
			$long = (double)$Longs;
		}

	}else{
		echo "<script>alert('الرجاء تسجيل الدخول اولاً')</script>";
		echo "<script>setTimeout(\"location.href = '../log/login.php';\",1500);</script>";
	}
?>

<!-- -------------------------------------------------#HTML Code#-------------------------------------------------- -->

<!DOCTYPE html>
	<html lang="ar" style='direction: rtl'>
		<head>
			<title>Home Page</title>
			<link rel="stylesheet" href="style.css">
			<script src="components/ComponentHandler.js" ></script>
		</head>
		<style>
			/* Set the size of the div element that contains the map */
			#map {
				height: 300px;
				/* The height is 400 pixels */
				width: 100%;
				/* The width is the width of the web page */
				background-color: rgba(103,178,147, 0.8);
			}
			.container{
				width: 1000px;
				display: block;
				padding: 50px;
				margin: 20px;
				background: #ffffff;
				border-radius: 6px;
			}

			h1{
				color: black;
				text-align: center;
			}

			td{
				text-align: center; 
				padding :8px; 
			} 
			
			th { 
				text-align: center; 
				padding :8px; 
				background-color: #3781a1;
				color: white;
			}

			table{
				line-height:40px;  
				border-collapse: collapse;
				background-color: #ffff;
				box-shadow: 1px 1px 8px 0 grey;
				height: auto;
				margin-bottom: 20px;
				padding: 20px 0 20px 50px;
				width: 100%; 
			}
			tr:nth-child(odd) {
				background-color: #b3d3e2;
			}
			tr {border-bottom: 1px solid #dddddd;}

			p,h2,h3{
				margin-bottom: 20px; 
			}
			.location{
				width: 100%;
    			height: auto;
			}
			.col{
				float: left;
				padding: 10px;
				height: 300px;
			}
			.left{
				width: 40%;
			}
			.right{
				width: 60%;
			}
			.row{
				display: flex;
				flex-direction: column;
				margin: 20px 0;
			}
			.row:after{
				content: "";
				display: table;
				clear: both;
			}
			#id01{display: none;}

			/* The Close Button (x) */
			.close {
				right: 50px;
				top: 75px;
				font-size: 40px;
				font-weight: bold;
				color: #d1d1d1;
			}

			.close:hover, .close:focus {
				color: #f44336;
				cursor: pointer;
			}

			.overlay-style {
				position: fixed;
				display: none;
				width: 100%;
				height: 100%;
				top: 0;
				left: 0;
				right: 0;
				bottom: 0;
				overflow: auto;
				padding-top: 50px;
				background-color: rgba(0, 0, 0, 0.5);
			}

			.block {
				padding: 16px;
				width: 700px;
				margin: auto;
				background-color: #fff;
			}

			.Namefeild{
				text-align: center;
				width: 80%;
				margin:0 auto;
				border: 1.5px rgba(0, 0, 0, 0.5);
			}
			table.Namefeild{
			border-collapse: collapse;
			width: 80%;
			}
			.Namefeild input[type=text] {
				width: 98.5%;
			}
			.card {
				background-color: #fff;
				border-radius: 18px;
				box-shadow: 1px 1px 8px 0 grey;
				height: auto;
				margin-bottom: 20px;
				padding: 20px 0 20px 0px;
				width: 100%;
			}
		</style>

		<script>      // Initialize and add the map
			// In the following example, markers appear when the user clicks on the map.
			var marker;
			var longitude;
			var latitude;
			var map;

			function initMap() {
				<?php
					echo "var lats ='$lat';";
					echo "var longs ='$long';";
				?>

				var uluru = { lat: <?php echo($lat)?>, lng: <?php echo($long)?> };

				map = new google.maps.Map(document.getElementById("map"), {
				zoom: 17,
				center: uluru,
				});

				new google.maps.Marker({
				position: uluru,
				map
				});
				
			}
		</script>

		<body>
		<!--header call-->
		<div id="Head" w3-include-html="components/nav.php"></div>
        

		<!-- Page content -->
		<main>
			<aside></aside><!--just to make it look better with flex display-->

            <div class="container">
				<div class="row">
					<h1>رقم الوحدة العقارية: <?php echo "<h1>$REUN</h1>";?></h1>
					<h3>معلومات المالك</h3>
					<table class="fixed">
						<tbody>
							<col width="10px" />
							<col width="100px" />
							<col width="40px" />
							<col width="40px" />
							<col width="40px" />
							<col width="40px" />
							<col width="40px" />
							<col width="40px" />
							<tr class='title'>
								<th>م</th>
								<th>اسم المالك</th>
								<th>الجنسية</th>
								<th>الحصة/النسبة</th>
								<th>العنوان</th>
								<th>نوع الهوية</th>
								<th>رقم الهوية</th>
								<th>تاريخ الهوية</th>
							</tr>
							<tr>
								<td></td>
								<td> <?php echo $firstName . ' ' . $middleName . ' ' . $lastName; ?> </td>
								<td> <?php print($nationality);?> </td>
								<td> <?php print($share);?> </td>
								<td> <?php print($address);?> </td>
								<td> <?php print($IDType);?> </td>
								<td> <?php print($IDNumber);?> </td>
								<td> <?php print($IDdate);?> </td>
							</tr>
						</tbody>
					</table>
				</div>

				<div class="row">
					<h3>معلومات الوحدة العقارية</h3>
					<table>
						<tbody>
							<col width="30px" />
							<col width="80px" />
							<col width="40px" />
							<col width="100px" />
							<col width="100px" />
							<col width="30px" />
							<tr>
								<th>رقم القطعة</th>
								<td><?php print($pieceNumber);?></td>
								<th>الاتجاهات</th>
								<th colspan="2">الحدود</th>
								<th>الاطوال</th>
							</tr>
							<tr>
								<th>رقم البلك</th>
								<td><?php print($blockNumber);?></td>
								<th rowspan="2">شمالا</th>
								<td rowspan="2"><?php print($bordersNorth);?></td>
								<td rowspan="2"><?php print($bordersNorth);?></td>
								<td rowspan="2"><?php print($lengthNorth);?></td>
							</tr>
							<tr>
								<th>رقم المخطط</th>
								<td><?php print($planNumber);?></td>
							</tr>
							<tr>
								<th>اسم الحي</th>
								<td><?php print($neighborhoodName);?></td>
								<th rowspan="2">جنوبا</th>
								<td rowspan="2"><?php print($bordersSouth);?></td>
								<td rowspan="2"><?php print($bordersSouth);?></td>
								<td rowspan="2"><?php print($lengthSouth);?></td>
							</tr>
							<tr>
								<th>المدينة</th>
								<td><?php print($city);?></td>
							</tr>
							<tr>
								<th>نوع الوحدة</th>
								<td><?php print($unitType);?></td>
								<th rowspan="2">شرقا</th>
								<td rowspan="2"><?php print($bordersEast);?></td>
								<td rowspan="2"><?php print($bordersEast);?></td>
								<td rowspan="2"><?php print($lengthEast);?></td>
							</tr>
							<tr>
								<th>رقم الصك</th>
								<td><?php print($deedNumber);?></td>

							</tr>
							<tr>
								<th>تاريخ الصك</th>
								<td><?php print($deedDate);?></td>
								<th rowspan="2">غربا</th>
								<td rowspan="2"><?php print($bordersWest);?></td>
								<td rowspan="2"><?php print($bordersWest);?></td>
								<td rowspan="2"><?php print($lengthWest);?></td>
							</tr>
							<tr>
								<th>مصدر الصك</th>
								<td><?php print($courtIssued);?></td>

							</tr>
							<tr>
								<th>المساحة</th>
								<td><?php print($spaceInNumbersLength);?></td>
								<th>كتابة</th>
								<td colspan="3"><?php print($spaceInWritingLength);?></td>
							</tr>
						</tbody>
					</table>
				</div>

				<div class="row" style="flex-direction: row;">
					<div class="col left">
						<h3>خريطة الموقع</h3>
						<!--The div element for the map -->
    					<div id="map"></div>
					</div>
					<div class="col right">
						<h3>احداثيات واركان الوحدة العقارية</h3>
						<table>
							<tbody>
								<tr>
									<th>النقطة</th>
									<th>خط الطول</th>
									<th>خط العرض</th>
									<th>زوايا الانكسار</th>
								</tr>
								<tr>
									<th>أ</th>
									<td><?php print($LatitudeA);?></td>
									<td><?php print($LongitudeA);?></td>
									<td><?php print($angleA);?></td>
								</tr>
								<tr>
									<th>ب</th>
									<td><?php print($LatitudeB);?></td>
									<td><?php print($LongitudeB);?></td>
									<td><?php print($angleB);?></td>
								</tr>
								<tr>
									<th>ج</th>
									<td><?php print($LatitudeC);?></td>
									<td><?php print($LongitudeC);?></td>
									<td><?php print($angleC);?></td>
								</tr>
								<tr>
									<th>د</th>
									<td><?php print($LatitudeD);?></td>
									<td><?php print($LongitudeD);?></td>
									<td><?php print($angleD);?></td>
								</tr>
							</tbody>
						</table>
					</div>
				</div>
				<br><br><br>
				<div class="row" style="flex-direction: row;">
					<button onclick="document.getElementById('id01').style.display='block'">تقديم عرض</button>
					<button onclick="document.getElementById('id02').style.display='block'">التفاوض مع المالك</button>
				</div>

				<div id="id01" class="overlay-style">
					<div class="block">
						<span onclick="document.getElementById('id01').style.display='none'" class="close" title="Close Modal">&times;</span>
						<div style="text-align:center;margin: 5%;">
						<h1 style="padding-left:1%;" >تقديم عرض شراء للمالك </h1>
						<!--  Offers Form -->
						<form method="POST">
							<table class="Namefeild">
								<tr>
								<td style="border: none;"><label for="landPrice"> السعر:</label></td>
								</tr>
								<tr>
								<td style="border: none;"><input type="text" id="landPrice" name="landPrice" required></td>
								</tr>
							</table><br><br>
							<button><input type="submit" name="setOffer" value="إرسال"></button>
						</form>
						<br>
						</div>
					</div>
				</div>
				<div id="id02" class="overlay-style">
					<div class="block">
						<span onclick="document.getElementById('id02').style.display='none'" class="close" title="Close Modal">&times;</span>
						<div style="text-align:center;margin: 5%;">
							<h1 style="padding-left:1%;" >   رقم المالك  </h1>
							<?php 
								$query="SELECT phoneNum FROM users,landrecord WHERE landrecord.REUN='$REUN' AND users.ID=landrecord.IDNumber"; 
								$result = $con->query($query);
								$row = mysqli_fetch_array($result);
								echo $row['phoneNum'];
							?>
							<br>
						</div>
					</div>
				</div>

			<aside></aside><!--just to make it look better with flex display-->
		</main>
		
		<!--footer call-->
		<div w3-include-html="components/footer.php"></div>

		<script>
			// Get the modal
			var modal1 = document.getElementById('id01');
			var modal2 = document.getElementById('id02');

			// When the user clicks anywhere outside of the modal, close it
			window.onclick = function(event) {
				if (event.target == modal1) {
					modal1.style.display = "none";
				}
			}
			// When the user clicks anywhere outside of the modal, close it
			window.onclick = function(event) {
				if (event.target == modal2) {
					modal2.style.display = "none";
				}
			}
		</script>
		<script async
            src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCl39nJCT9GvsrbmIlEexdz9LPr7v_9s3E&callback=initMap">
        </script>
		<script>
		includeHTML();
		</script>
	</body>
</html>
