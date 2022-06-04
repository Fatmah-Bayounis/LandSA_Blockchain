<!-- set price form -->
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

    // receive all the informations from the interface form specifically "setPrice.php"
    if ($_SERVER["REQUEST_METHOD"]=="POST"){
        $REUN = $_POST["REUN"];
        $price = $_POST["price"];

        $ID=$_SESSION['loggedUser'];
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
    #else if the user is NOT logedin
  }else{
    echo "<script>alert('الرجاء تسجيل الدخول اولاً')</script>";
    echo "<script>setTimeout(\"location.href = '../log/login.php.php';\",1500);</script>";
  }
?>


<!-- -------------------------------------------------#HTML Code#-------------------------------------------------- -->

<!DOCTYPE html>
<html lang="ar" style='direction: rtl'>
  <head>
    <title> set price form</title>
    <link rel="stylesheet" href="style.css">
    <script src="components/ComponentHandler.js" ></script>

    <style>
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
      h2{
        margin: 30px 0 20px 0;
      }
      a{
        margin: 0 30px 0 20px;
      }
      #price{
        margin-bottom: 20px;
        width: 80%;
      }

    </style>
  </head>
  <body>
    <!--Page header-->
    <div id="Head" w3-include-html="components/nav.php"></div>
    <main>
        <aside></aside>
        <div class="content">
            <div style="text-align:center;margin: 5%;">
            

            
                <!-- Gift Land Form -->
                <form method="POST" action="setPrice.php">
                    
                    <?php echo"<input type='hidden' id='REUN' name='REUN' value='$REUN' />";?>

                    <h2 style="padding-left:1%;" >أدخل سعر لبيع الارض رقم:  </h2>
                    <h2><?PHP echo $REUN; ?></h2>

                    <div class="form"><input type="text" id="price" name="price" required></div>

                    <a href="controlLandspage.php">الغاء</a>
                    <button><input type="submit" value="ارسل"></button>
                    
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