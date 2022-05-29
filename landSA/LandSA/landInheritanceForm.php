<?php
//connect and check connection
include "components/connection.php";
include "components/methods.php";
$IDNumber=null;
#Check if the user is still logedin
session_start();
if(isset($_SESSION['loggedUser']) && $_SESSION['loggedUser']==true){
        if ($_SERVER["REQUEST_METHOD"]=="POST"){
			//to display errors
			error_reporting(E_ALL);
			ini_set('display_errors', 1);

			//get the values 
			$courtOrder = $_POST["courtOrder"];
			$OwnerID = $_POST["OwnerID"];
			$REUN = $_POST["REUN"];
			$requestID = rand (1000, 9999);
			$NOwnerID= $_SESSION['loggedUser'];

			//connect and check connection
			include "components/connection.php";
			$query1="SELECT ID FROM users WHERE  ID ='$OwnerID' "; 
			$result = mysqli_query($con, $query1);
			$row = mysqli_fetch_array($result);
			$ID = $row["ID"];
			if ($ID == $_SESSION['loggedUser']){
			  echo "<script>alert('رقم هوية المالك غير صحيح')</script>";

            }else{      
				// $stmt=$con->prepare("INSERT INTO inheritancerecord (courtOrder,OwnerID,REUN,requestID,UserID) VALUES (?,?,?,?,?)");
                // $stmt -> bind_param("sssss",$courtOrder,$OwnerID,$REUN,$requestID,$_SESSION['loggedUser']);
                // $stmt->execute();

				//Check that the OLD owner is a user in the website
				$queryUser="SELECT * FROM UsersLands WHERE UserID='$OwnerID'AND REUN='$REUN'";
				$resultUser = mysqli_query($con, $queryUser);
				$count = mysqli_num_rows($resultUser);
				if ($count > 0){
					//Get the NEW owner info
					$queryNOwner="SELECT * FROM users WHERE ID='$NOwnerID'";
					$resultNOwner = mysqli_query($con, $queryNOwner);
					$row = mysqli_fetch_array($resultNOwner, MYSQLI_ASSOC);
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

					$NOwner_Data="{\"newOwnerFirstName\":\" $newOwnerFirstName \",\"newOwnerMiddleName\":\" $newOwnerMiddleName  \",\"newOwnerLastName\":\" $newOwnerLastName \",\"newOwnerNationality\":\"$newOwnerNationality\" ,\"newOwnerShare\":\" $newOwnerShare \",\"newOwnerAddress\":\" $newOwnerAddress \",\"newOwnerIDType\":\" $newOwnerIDType \",\"newOwnerIDNumber\":\" $NOwnerID \",\"newOwnerIDdate\":\"$newOwnerIDdate\",\"todayDate\":\"$todayDate\",\"transactionType\":\"Inheritance\"}";

					$Land_REUN=$REUN;
					$BChainResponse=UpdateLandOwner($Land_REUN,$NOwner_Data);
					// print($BChainResponse);

					if($BChainResponse == '1'){ 

						$sqlInhirit = "UPDATE UsersLands SET UserID = '$NOwnerID' WHERE REUN = '$REUN'";
						$resultNOwner = mysqli_query($con, $sqlInhirit);
					
						$stmt=$con->prepare("INSERT INTO inheritancerecord (ownerID,courtOrder,REUN,requestID,UserID) VALUES (?,?,?,?,?)");
						$stmt -> bind_param("sssss",$OwnerID,$courtOrder,$REUN,$requestID,$NOwnerID);
						$resultGift=mysqli_stmt_execute($stmt);	

						echo "<script>alert('تم إرسال الطلب بنجاح')</script>";

					}else{echo "<script>alert('لم يتم استقبال الطلب بنجاح رجاءً حاول مره اخرى')</script>";}
					
				}else{ 	echo "<script>alert('المسخدم غير مسجل بالموقع او المعلومات غير صحيحه')</script>";
						echo "<script>setTimeout(\"location.href = 'controlLandspage.php';\",1500);</script>";}
                }

        }
}else{
	echo "<script>alert('الرجاء تسجيل الدخول اولاً')</script>";
	echo "<script>setTimeout(\"location.href = '../log/login.php';\",1500);</script>";
}
        
?>

<!-- land Inheritance form HTML -->
<!DOCTYPE html>
<html lang="ar" style='direction: rtl'>
	<head>
		<title> land Inheritance form</title>
		<link rel="stylesheet" href="style.css">
		<script src="components/ComponentHandler.js" ></script>
		<style>
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
			<div class="container" style="text-align:center; width: 650px;" class="content">
				<h1 style="padding-left:1%;margin: 5%; color: black">استبيان وراثة ارض</h1>
				<form action="landInheritanceForm.php" method="post" >
			

				<label for="courtOrder">ادخل صورة من أمر المحكمة (امر انتقال الملكية)</label><br><br>
				<button><input type="file" id="courtOrder" name="courtOrder" src="img_submit.gif" alt="Submit" width="48" height="48" laceholder="Photo" required="" capture></button><br><br>

				<label for="OwnerID">ادخل رقم هوية صاحب الأرض (الشخص المتوفي)</label><br>
				<div class="form"><input type="text" id="OwnerID" minlength="10" maxlength="10" name="OwnerID" required=""></div><br><br>
				
				<label for="REUN">أدخل رقم الوحدة العقارية (REUN)</label><br>
				<div class="form"><input type="text" id="REUN" name="REUN" required=""></div><br><br>

				<button><input name="submit" type="submit" value="إرسال" ></button>

				</form>
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