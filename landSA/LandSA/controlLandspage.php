<?php
$REUN = null;
	include "components/connection.php";
	include "components/methods.php";
	$con = mysqli_connect("localhost","root","") or die("Error: Can't Connect to Server");
$db = mysqli_select_db($con,"landsa_DB") or die("Error: Can't Connect to DB");

	session_start();
	if(isset($_SESSION['loggedUser']) && $_SESSION['loggedUser']==true){
		$ID = $_SESSION['loggedUser'];
		
		  
		// receive all the informations from the interface form specifically "setPrice.php"
		if (!empty($_POST['setPrice'])){
			$REUN = $_POST["REUN"];
			$price = $_POST["price"];


			//Check that their are no repeted requests
			$query2="SELECT * FROM `landrecord` WHERE REUN='$REUN'";
			$result2 = mysqli_query($con, $query2);
			$count2 = mysqli_num_rows($result2);

	  
			if($count2 == 0){
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
				
			 	// Relational DB insert
				$insertLand = "INSERT INTO `landrecord` (`REUN`, `landState`, `firstName`, `middleName`, `lastName`, `share`, `IDNumber`, `pieceNumber`, `blockNumber`, `planNumber`, `neighborhoodName`, `city`, `unitType`, `deedNumber`, `deedDate`, `courtIssued`, `requestID`) 
				VALUES ('$REUN',b'0','$firstName', '$middleName', '$lastName', '$share', '$ID', '$pieceNumber', '$blockNumber', '$planNumber', '$neighborhoodName', '$city', '$unitType', '$deedNumber', '$deedDate', '$courtIssued','')";
				$query_insertL = mysqli_query($con, $insertLand);
				
				 if($query_insertL){
					// Relational DB insert
					$insertLandInfo = "INSERT INTO `landinfo` (`REUN`, `spaceInNumbers`, `spaceInWriting`, `bordersNorth`, `bordersSouth`, `bordersEast`, `bordersWest`, `lengthNorth`, `lengthSouth`, `lengthEast`, `lengthWest`, `LatitudeA`, `LatitudeB`, `LatitudeC`, `LatitudeD`, `LongitudeA`, `LongitudeB`, `LongitudeC`, `LongitudeD`, `angleA`, `angleB`, `angleC`, `angleD`,`ElectronicTitleDeed`) 
					VALUE('$REUN','$spaceInNumbers', '$spaceInWriting', '$bordersNorth', '$bordersSouth', '$bordersEast', '$bordersWest', '$lengthNorth', '$lengthSouth', '$lengthEast', '$lengthWest','$LatitudeA', '$LatitudeB', '$LatitudeC', '$LatitudeD', '$LongitudeA', '$LongitudeB', '$LongitudeC', '$LongitudeD',  '$angleA', '$angleB', '$angleC', '$angleD',NULL)";
					$query_insertLI = mysqli_query($con, $insertLandInfo);

					// insert the title image to images Table
					$insertLocation = "INSERT INTO `map` (`REUN`, `latitude`, `longitude`) VALUES ('$REUN', '$Lats', '$Longs');";
					$query_insertLoc = mysqli_query($con, $insertLocation);


					$insertUser = "INSERT INTO landsonsale(`REUN`, `price`) value($REUN, $price)";
					$result = mysqli_query($con,$insertUser); #send query to the databaes to use insert method

		
					if($result){  
					echo "<script>alert('تم إرسال الطلب بنجاح')</script>";
					echo "<script>setTimeout(\"location.href = 'controlLandspage.php';\");</script>";
					}else {
					die("Error: ".mysqli_errno($con));
					}
				  }
			}else{
				echo "<script>alert('تم عرض الأرض للبيع مسبقًا')</script>";
				echo "<script>setTimeout(\"location.href = 'controlLandspage.php';\");</script>";
			}
		}

		// receive all the informations from the interface form specifically "changePrice.php"
		if (!empty($_POST['updatePrice'])){
			$REUN = $_POST["REUN"];
			$price = $_POST["price"];
	    
			$query = "UPDATE landsonsale set price= '$price' where REUN='$REUN'";        
			$res2 = mysqli_query($con,$query);
			if ($con->query($query)==TRUE) {
				echo "<script>alert('تم تغيير السعر بنجاح')</script>";
				echo "<script>setTimeout(\"location.href = 'controlLandspage.php';\");</script>";
			} else {
				echo "Eroo". $query. "<br>" . $con->error;
			}
			
		  }
		  #else if the user is NOT logedin
	}else{
		echo "<script>alert('الرجاء تسجيل الدخول اولاً')</script>";
		echo "<script>setTimeout(\"location.href = 'controlLandspage.php';\");</script>";
	}

?>

<!-- Control Lands page-->
<!DOCTYPE html>
<html lang="ar" style='direction: rtl'>
<head>
	<title>Control Lands page </title>
	<link rel="stylesheet" href="style.css">
	<script src="components/ComponentHandler.js" ></script>
	
	<style>

		/* Style of each land */
		.land {
			background-color: #203864; /* Green background */
			/* color: white; White text */
			padding: 10px 24px; /* Some padding */
			cursor: pointer; /* Pointer/hand icon */
			margin: 8px 4px;
			display: flex;
			justify-content: space-between;

			background-color: #fff;
			border-radius: 18px;
			box-shadow: 1px 1px 8px 0 grey;
			height: auto;
			width: 100%;
			
		}
		.land p{
			display: flex;
			text-align: right;
			flex-wrap: wrap;
			
		}
		.land button {
			display: inline-flex;
			flex: left;
			text-align: left;
			margin-top: 10px;
			width: 100px;
		}
		.content form {
			background-color: #fff0;
			border-radius: 18px;
			padding: 0px;
		}
		#price{
			display: flex;
    		justify-content: space-between;
			align-items: baseline
		}
		.ch{
			background-color: #bb5a58;
			border: none;
			padding: 3px 5px;
			border-radius: 0%;
			color: white;
			text-align: center;
			text-decoration: none;
			display: inline-block;
			font-size: 18px;
			cursor: pointer;
			width: 55px;
    	}
		.ch:hover{
			background-color: #b70b09;
			transition: all 1s eas;
    	}
		b{padding-left: 20px;}
		.section{
			display: flex;
			flex-direction: column;
    		align-items: stretch;
		}
		
		/* style of buttons */
		.sellB {
			background-color: rgba(255, 80, 60, 0.568);
		}
		.giftB{
			background-color: #98cee8;
		}
		.moreB{
			background-color:#d1d1d1;
		}
		.MiniBlock {
			display: flex;
			justify-content: space-evenly;
		}
		.land img{
				width: 40%;
				border-radius: 5%;
			}

		@media only screen and (max-width: 800px ) {
			.land {
				flex-direction: column;
				align-items: center;
			}
			.section {
				display: flex;
				width: 100%;
				flex-direction: row;
				justify-content: space-between;
			}
			.MiniBlock{
				display: flex;
				flex-direction: row;
				align-items: center;
				justify-content: space-around;
			}
			.MiniBlock img{
				width: 50%;
				width:25%; 
				height:25%;
				 border-radius: 2%;
			}

		}

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
			border-radius: 15px;
			margin: auto;
			background-color: #fff;
			width: 580px;
		}
		.Namefeild{
        text-align: center;
        width: 80%;
        margin:0 auto;
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
</head>
<body>
	<!--Page header-->
	<div id="Head" w3-include-html="components/nav.php"></div>
	<main>
	<aside></aside>

	<div class="content">

		<h1>قائمة الاراضي</h1><br>
		<div class="landList">
			<?php
				// $sql_lands = "SELECT `landrecord`.REUN,deedDate, deedNumber,unitType,city,neighborhoodName,spaceInNumbers,landState FROM `landrecord`,`landinfo` WHERE IDNumber ='$ID' AND `landrecord`.REUN=`landinfo`.REUN";
				// $result = $con->query($sql_lands);
				$sql_lands = "SELECT * FROM UsersLands WHERE UserID ='$ID'";
				$result = $con->query($sql_lands);

				
				if ($result->num_rows > 0) {
					// output data of each row
					while($row = $result->fetch_assoc()) {
						$REUN = $row['REUN'];
						$Landinfo=array();
						$Landinfo=getLandInfo($REUN);

						$x = $REUN;
						$sql_price = "SELECT `landrecord`.REUN , price FROM `landrecord`,`landsonsale` WHERE `landsonsale`.REUN = '$REUN'";
						$result2 = $con->query($sql_price);
						$row2 = mysqli_fetch_array($result2);
						echo "<div class='land'>";

						// Informations section
						echo"<div class='section'>";
						echo "<table id='UserData'>";
						echo "<tr>
							 <th><h2>رقم الوحدة العقارية: </h2></th>
							 <th> <h2>$Landinfo[14]</h2></th>
							</tr>";
							echo "<tr>
							<td>&emsp;</td>
							<td>&emsp;</td>
						   </tr>";	
						echo "<tr>
							 <td>المدينة:</td>
							 <td> $Landinfo[13]</td>
							</tr>";
						 echo "<tr>
							 <td>اسم الحي:</td>
							 <td> $Landinfo[12]</td>
							</tr>";

						echo "<tr>
						 <td>نوع الوحدة:</td>
						 <td> $Landinfo[15]</td>
					  		</tr>";

						echo "<tr>
							<td>رقم الصك:</td>
							<td> $Landinfo[16]</td>
							</tr>";

						echo "<tr>
							<td>تاريخ الصك:</td>
							<td> $Landinfo[17]</td>
							</tr>";
						
						echo "<tr>
							<td>المساحة بالارقام:</td>
							<td> $Landinfo[19] متر</td>
						</tr>";

						// try to add the price if the land got for sale
						if(!empty($row2['price'])){
							echo "<tr>
								<td><b>السعر:</b></td>
								<td id=price><b>$row2[price]</b>
									<form method='GET' action='changePrice.php'>
										<input type='hidden' id='REUN' name='REUN' value='$REUN' />
										<button class='ch' name='price' type='submit'>تعديل</button>
									</form>
								</td>
							</tr>";
							
						}		

						echo"</table>";
						echo"</div> <br>";
							
							// Buttons section
							echo"<div class='section'>";

							$sql_lands_Sale = "SELECT REUN FROM landrecord WHERE IDNumber ='$ID' AND REUN = '$REUN' "; ////////////////////###Reviwe the condition///////////////
							$result_Sale = $con->query($sql_lands_Sale);
							if($result_Sale->num_rows < 1){
								echo"
								<form method='GET' action='setPrice.php'>
									<input type='hidden' id='REUN' name='REUN' value='$REUN' />
									<button type='submit'>بيع</button>
								</form>";

								echo"
								<form method='GET' action='giftLandForm.php'>
									<input type='hidden' id='REUN' name='REUN' value='$REUN' />
									<button class='giftB' type='submit'>اهداء</button>
								</form>";	
							}
							echo"
								<form method='GET' action='landViewSeller.php'>
									<input type='hidden' id='REUN' name='REUN' value='$REUN' />
									<button class='moreB' type='submit' >تفاصيل</button>
								</form>";							
							echo"</div>";

					echo "</div>";

					}
				} else {
					echo "<div class='land' style='padding:30px 85px;'>";
					echo "عفوًا لا توجد أراضي لديك";
					echo"</div>";
				}
			?>
			
			</div>
		</div>
		<aside></aside>
	</main>

        <!-- footer -->
		<div w3-include-html="components/footer.php"></div>
	<script>
		includeHTML();
	</script>
</body>
</html>