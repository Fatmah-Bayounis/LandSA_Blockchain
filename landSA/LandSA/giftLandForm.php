<!-- Gift Land form -->
<?php
$REUN=null;
	#Check the connections with the server and DB
	include "components/connection.php";
	include "components/methods.php";

	#Check if the user is still logedin
	session_start();
	if(isset($_SESSION['loggedUser']) && $_SESSION['loggedUser']==true){
		// To get the REUN from 'Control Lands page'
		if ($_SERVER["REQUEST_METHOD"]=="GET"){
			$REUN = $_GET['REUN'];
		}
		

		// receive all the informations from the interface form specifically "giftLandForm.php"
		if ($_SERVER["REQUEST_METHOD"]=="POST"){
			$REUN = $_POST["REUN"];
			$requestID=rand (1000, 9999);
			$NOwnerID = $_POST["NOwnerID"];
			$NOwnerFirstName = $_POST["NOwnerFirstName"];
			$NOwnerMiddleName = $_POST["NOwnerMiddleName"];
			$NOwnerLastName = $_POST["NOwnerLastName"];
			$NOwnerPhone = $_POST["NOwnerPhone"];
			

			//Check that the new owner is a user in the website
			$query1="SELECT ID FROM users WHERE ID='$NOwnerID' AND phoneNum='$NOwnerPhone'"; // AND $NOwnerName=`Name` AND $NOwnerPhone=`phoneNum`;
			$result = mysqli_query($con, $query1);
			$count = mysqli_num_rows($result);
			if ($count == 1){

				//Check that their are no repeted requests
				$query2="SELECT * FROM giftrecord WHERE REUN='$REUN'";
				$result2 = mysqli_query($con, $query2);
				$count2 = mysqli_num_rows($result2);

				if($count2 == 0){
					$row = mysqli_fetch_array($result, MYSQLI_ASSOC);
					$newOwnerFirstName = $row["firstName"];
					$newOwnerMiddleName = $row["middleName"];
					$newOwnerLastName = $row["lastName"];
					$newOwnerNationality = $row["nationality"];
					$newOwnerShare = "100%";
					$newOwnerAddress = $row["address"];
					$newOwnerIDType = $row["IDType"];
					$newOwnerIDNumber = $row["ID"];
					$newOwnerIDdate = $row["IDdate"];
					$todayDate = date("Y-m-d");


					$NOwner_Data="{\"newOwnerFirstName\":\" $newOwnerFirstName \",\"newOwnerMiddleName\":\" $newOwnerMiddleName  \",\"newOwnerLastName\":\" $newOwnerLastName \",\"newOwnerNationality\":\"$newOwnerNationality\" ,\"newOwnerShare\":\" $newOwnerShare \",\"newOwnerAddress\":\" $newOwnerAddress \",\"newOwnerIDType\":\" $newOwnerIDType \",\"newOwnerIDNumber\":\" $NOwnerID \",\"newOwnerIDdate\":\"$newOwnerIDdate\",\"todayDate\":\"$todayDate\",\"transactionType\":\"Gift\"}";
					$Land_REUN=$REUN;
					$BChainResponse=UpdateLandOwner($Land_REUN,$NOwner_Data);
					// print($BChainResponse);

					if($BChainResponse == '1'){
						$insertGift = "INSERT INTO giftrecord (requestID,NOwnerID,NOwnerFirstName,NOwnerMiddleName,NOwnerLastName,NOwnerPhone,REUN,UserID) 
						value(?,?,?,?,?,?,?,?)";
						$stmt = mysqli_prepare($con,$insertGift);
						mysqli_stmt_bind_param($stmt,"ssssssss",$requestID,$NOwnerID,$NOwnerFirstName,$NOwnerMiddleName,$NOwnerLastName,$NOwnerPhone,$REUN,$_SESSION['loggedUser']);
						$resultGift=mysqli_stmt_execute($stmt);	
						
						$DeleteSQL = "DELETE FROM `landrecord` WHERE REUN='$REUN'";
						$DeleteQuery = mysqli_query($con, $DeleteSQL);
						$DeleteSQL = "DELETE FROM `landsonsale` WHERE REUN='$REUN'";
						$DeleteQuery = mysqli_query($con, $DeleteSQL);
						$DeleteSQL = "DELETE FROM `map` WHERE REUN='$REUN'";
						$DeleteQuery = mysqli_query($con, $DeleteSQL);
						$DeleteSQL = "DELETE FROM `offers` WHERE REUN='$REUN'";
						$DeleteQuery = mysqli_query($con, $DeleteSQL);
						
						$sqlGift = "UPDATE UsersLands SET UserID = '$NOwnerID' WHERE REUN = '$REUN'";
            			$queryGift = mysqli_query($con, $sqlGift);
				
						if($queryGift){	
							echo "<script>alert('???? ?????????? ?????????? ??????????')</script>";
							echo "<script>setTimeout(\"location.href = 'controlLandspage.php';\");</script>";
						}else {
							die("Error: ".mysqli_stmt_error($stmt));
						}
					}
				}else{echo "<script>alert('???? ?????????????? ?????? ?????????? ?????? ?????? ?????????? ????????????')</script>";}
				
			}else{ 	echo "<script>alert('?????????????? ?????? ???????? ?????????????? ???? ?????????????????? ?????? ??????????')</script>";
					echo "<script>setTimeout(\"location.href = 'controlLandspage.php';\");</script>";}

		}
		#else if the user is NOT logedin
	}else{
		echo "<script>alert('???????????? ?????????? ???????????? ??????????')</script>";
		echo "<script>setTimeout(\"location.href = '../log/login.php';\",1500);</script>";
	}
?>


<!-- -------------------------------------------------#HTML Code#-------------------------------------------------- -->

<!DOCTYPE html>
<html lang="ar" style='direction: rtl'>
	<head>
		<title> Gift land form</title>
		<link rel="stylesheet" href="style.css">
		<script src="components/ComponentHandler.js" ></script>
		
		<style>
			.card {
				background-color: red;
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
			
			<div style="text-align:center; " >

				<div class="container" style= "text-align:center; width: 650px;">
					<h1 style="padding-left:1%; margin: 5%; color: black" >?????????????? ?????????? ??????</h1>
					<h2><?PHP echo $REUN; ?></h2>

					<!-- Gift Land Form -->
					<form class="gift" method="POST" action="giftLandForm.php">
					<?php echo"<input type='hidden' id='REUN' name='REUN' value='$REUN' />";?>

					<h3>???????? ?????? ???????????? ?????????????? ?????????? ???????????? ???????? (?????? ???? ???????? ???????????? ???? ????????????):*</h3><br>
					<div class="form">
						<input type="text" minlength="10" maxlength="10" id="NOwnerID" name="NOwnerID" required><br><br>
					</div>
					
					<h3 for="NOwnerName">?????????? ?????????????? ?????????? ???????????? ????????:</h3><br>
					<div class="form multiple">
						<input type="text" name="NOwnerFirstName" id="NOwnerFirstName" placeholder="?????????? ??????????" required>
						<input type="text" name="NOwnerMiddleName" id="NOwnerMiddleName" placeholder="?????? ????????" required>
						<input type="text" name="NOwnerLastName" id="NOwnerLastName" placeholder="?????? ??????????????" required>
					</div><br><br>

					<h3>?????? ???????? ?????????? ???????????? ????????*:</h3><br>
					<div class="form">
						<input type="text" minlength="10" maxlength="10" id="NOwnerPhone" name="NOwnerPhone" placeholder="????????: 0555555555" required><br><br>
					</div>

					<button><input type="submit" value="??????????" ></button>

					</form>
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
