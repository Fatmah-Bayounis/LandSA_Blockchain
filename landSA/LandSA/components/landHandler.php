<?php
    #Check the connections with the server and DB
	include "connection.php";
    include "methods.php";

	
	#Check if the user is still logedin
	session_start();
	if(isset($_SESSION['loggedUser'] )&& $_SESSION['loggedUser']==true){

        //user info
        $firstName = $_POST["firstName"];
        $middleName = $_POST["middleName"];
        $lastName = $_POST["lastName"];
        $share = $_POST["share"];
        $shareS=(string)$share;
        $IDNumber= $_SESSION['loggedUser'];
        $IDType = $_POST["IDType"];

        //land info
        $pieceNumber = $_POST["pieceNumber"];
        $blockNumber = $_POST["blockNumber"];
        $planNumber = $_POST["planNumber"];

        $pieceNumberS = (string)$pieceNumber;
        $blockNumberS = (string)$blockNumber;
        $planNumberS = (string)$planNumber;


        $neighborhoodName = $_POST["neighborhoodName"];
        $city = $_POST["city"];
        $REUN = $_POST["REUN"];
        $unitType = $_POST["unitType"];
        $deedNumber = $_POST["deedNumber"];
        $deedDate = $_POST["deedDate"];
        $deedDateS =(string)$deedDate ;


        $courtIssued = $_POST["courtIssued"];
        $spaceInNumbers = $_POST["spaceInNumbers"];
        $spaceInWriting = $_POST["spaceInWriting"];
        $bordersNorth = $_POST["bordersNorth"];
        $bordersSouth = $_POST["bordersSouth"];
        $bordersEast = $_POST["bordersEast"];
        $bordersWest = $_POST["bordersWest"];
        $lengthNorth = $_POST["lengthNorth"];
        $lengthSouth = $_POST["lengthSouth"];
        $lengthEast = $_POST["lengthEast"];
        $lengthWest = $_POST["lengthWest"];

        $spaceInNumbersS = (string)$spaceInNumbers;
        $lengthNorthS = (string)$lengthNorth;
        $lengthSouthS = (string)$lengthSouth;
        $lengthEastS = (string)$lengthEast;
        $lengthWestS = (string)$lengthWest;

        //location info
        $LongitudeA = $_POST["LongitudeA"];
        $LongitudeB = $_POST["LongitudeB"];
        $LongitudeC = $_POST["LongitudeC"];
        $LongitudeD = $_POST["LongitudeD"];
        $LatitudeA = $_POST["LatitudeA"];
        $LatitudeB = $_POST["LatitudeB"];
        $LatitudeC = $_POST["LatitudeC"];
        $LatitudeD = $_POST["LatitudeD"];
        $angleA = $_POST["angleA"];
        $angleB = $_POST["angleB"];
        $angleC = $_POST["angleC"];
        $angleD = $_POST["angleD"];
        // $L1 = $_POST['lats'];
        // $L2 = $_POST['longs'];
        $Lats = $_POST['lats'];
        $Longs = $_POST['longs'];
        $LatsS = (string)$Lats;
        $LongsS = (string)$Longs;

        $image =$ElectronicTitleDeed= $_FILES['ElectronicTitleDeed']['tmp_name']; 
        $imgContent = addslashes(file_get_contents($image)); 

        //Get user info. to insert it to the blockchain
        $queryUser="SELECT * FROM users WHERE ID='$IDNumber'";
        $resultUser = mysqli_query($con, $queryUser);
        $rowUser = mysqli_fetch_array($resultUser, MYSQLI_ASSOC);
        $nationality = $rowUser["nationality"];
        $address = $rowUser["address"];
        $IDdate = $rowUser["IDdate"];
        $IDdateS =(string)$IDdate ;


        $Check="SELECT * FROM landrecord WHERE REUN = '$REUN'";
        $Check = mysqli_query($con,$Check);
        $count = mysqli_num_rows($Check);

        if($count == 0){
            // // // Register Land
            $part1='\"firstName\":\"'.$firstName .'\",\"middleName\":\"' .$middleName .'\",\"lastName\":\"' .$lastName .'\",';
            $part2='\"nationality\":\"' .$nationality .'\",\"share\":\"' .$shareS .'\",\"address\":\"' .$address .'\",\"IDType\":\"' .$IDType .'\",\"IDNumber\":\"' .$IDNumber .'\",';
            $part3='\"IDdate\":\"' .$IDdateS .'\",\"pieceNumber\":\"' .$pieceNumberS .'\",\"blockNumber\":\"' .$blockNumberS .'\",\"planNumber\":\"' .$planNumberS .'\",\"neighborhoodName\":\"' .$neighborhoodName .'\",';
            $part4='\"city\":\"' .$city .'\",\"reun\":\"' .$REUN .'\",\"unitType\":\"' .$unitType .'\",\"DeedNumber\":\"' .$deedNumber .'\",\"deedDate\":\"' .$deedDate .'\",';
            $part5='\"courtIssued\":\"' .$courtIssued .'\",\"spaceInNumbers\":\"' .$spaceInNumbersS .'\",\"spaceInWriting\":\"' .$spaceInWriting .'\",';
            $part6='\"bordersNorth\":\"' .$bordersNorth .'\",\"bordersSouth\":\"' .$bordersSouth .'\",\"bordersEast\":\"' .$bordersEast .'\",\"bordersWest\":\"' .$bordersWest .'\",';
            $part7='\"lengthNorth\":\"' .$lengthNorthS .'\",\"lengthSouth\":\"' .$lengthSouthS .'\",\"lengthEast\":\"' .$lengthEastS .'\",\"lengthWest\":\"' .$lengthWestS .'\",';
            $part8='\"LongitudeA\":\"' .$LongitudeA .'\",\"LongitudeB\":\"' .$LongitudeB.'\",\"LongitudeC\":\"' .$LongitudeC .'\",\"LongitudeD\":\"' .$LongitudeD .'\",';
            $part9='\"LatitudeA\":\"' .$LatitudeA .'\",\"LatitudeB\":\"' .$LatitudeB .'\",\"LatitudeC\":\"' .$LatitudeC .'\",\"LatitudeD\":\"' .$LatitudeD .'\",';
            $part10='\"angleA\":\"' .$angleA .'\",\"angleB\":\"' .$angleB .'\",\"angleC\":\"' .$angleC .'\",\"angleD\":\"' .$angleD .'\",\"Lats\":\"' .$LatsS .'\",\"Longs\":\"' .$LongsS .'\",\"transactionType\":\"Add Land\"}" }';
            $Land_Data = '{"landData":"{'.$part1 .$part2 .$part3 .$part4 .$part5 .$part6 .$part7 .$part8 .$part9 .$part10;
            $BChainResponse=registerLand($Land_Data);
            print ($BChainResponse);

            // // Relational DB insert
            // $insertLand = "INSERT INTO landrecord(firstName, middleName, lastName, share, IDNumber, pieceNumber, blockNumber, planNumber, neighborhoodName, city, REUN, unitType, deedNumber, deedDate, courtIssued) 
            // value('$firstName', '$middleName', '$lastName', '$share', '$IDNumber', '$pieceNumber', '$blockNumber', '$planNumber', '$neighborhoodName', '$city', '$REUN', '$unitType', '$deedNumber', '$deedDate', '$courtIssued')";
            // $query = mysqli_query($con, $insertLand);

            if($BChainResponse == '1'){
                // // Relational DB insert
                // $insertLandInfo = "INSERT INTO landinfo(spaceInNumbers, spaceInWriting, bordersNorth, bordersSouth, bordersEast, bordersWest, lengthNorth, lengthSouth, lengthEast, lengthWest, LongitudeA, LongitudeB, LongitudeC, LongitudeD, LatitudeA, LatitudeB, LatitudeC, LatitudeD, angleA, angleB, angleC, angleD, ElectronicTitleDeed, REUN) 
                // VALUE('$spaceInNumbers', '$spaceInWriting', '$bordersNorth', '$bordersSouth', '$bordersEast', '$bordersWest', '$lengthNorth', '$lengthSouth', '$lengthEast', '$lengthWest', '$LongitudeA', '$LongitudeB', '$LongitudeC', '$LongitudeD', '$LatitudeA', '$LatitudeB', '$LatitudeC', '$LatitudeD', '$angleA', '$angleB', '$angleC', '$angleD', '$ElectronicTitleDeed', '$REUN')";
                // $query = mysqli_query($con, $insertLandInfo);
                // // insert the title image to images Table
                // $insertLocation = "INSERT INTO `map` (`REUN`, `latitude`, `longitude`) VALUES ('$REUN', '$L1', '$L2');";
                // $query = mysqli_query($con, $insertLocation);
                
                $insertLandInfo = "INSERT INTO UsersLands(UserID, REUN) 
                VALUE('$IDNumber', '$REUN')";
                $query = mysqli_query($con, $insertLandInfo);

                echo "<script>alert('تم إرسال الطلب بنجاح')</script>";
				echo "<script>setTimeout(\"location.href = '../homePage.php';\");</script>";
            }else{
                echo "there was an error submiting the form";
                // die("Error: ".mysqli_erron($con));
            }
        }else{
            echo "<script>alert('توجد ارض مسجلة مسبقًا برقم الوحدة العقارية المدخل')</script>";
            echo "<script>setTimeout(\"location.href = '../registerLand.php';\",1500);</script>";
        }
    }else{
        echo "<script>alert('الرجاء تسجيل الدخول اولاً')</script>";
        echo "<script>setTimeout(\"location.href = '../log/login.php';\",1500);</script>";
    }
?>
