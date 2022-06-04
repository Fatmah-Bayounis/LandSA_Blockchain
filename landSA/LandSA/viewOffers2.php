<?php
  include "components/connection.php";
  include "components/methods.php";

    session_start();
  if(isset($_SESSION['loggedUser']) && $_SESSION['loggedUser']==true){
    $ID = $_SESSION['loggedUser'];
  }else{
    echo "<script>alert('الرجاء تسجيل الدخول اولاً')</script>";
    echo "<script>setTimeout(\"location.href = '../log/login.php';\",1500);</script>";
  }

  if(isset($_POST["accept"])){
    $REUN=$_POST['REUN'];
    $OfferID = $_POST['OfferID'];
    $NOwnerID=$_POST['NOwnerID'];
    $landPrice=$_POST['landPrice'];

    
    $query = "UPDATE offers set offerStatus= '1' where OfferID ='$OfferID'";    
    $query2 = "UPDATE offers set offerStatus= '2' WHERE REUN = '$REUN' AND OfferID != '$OfferID';";

       $res2 = mysqli_query($con,$query);
       $res = mysqli_query($con,$query2);

      //Get the information of current owner
    $queryCOwner="SELECT * FROM users WHERE ID='$ID'";
    $resultCOwner = mysqli_query($con, $queryCOwner);
    $rowCOwner = mysqli_fetch_array($resultCOwner, MYSQLI_ASSOC);
    $curOwnerFirstName = $rowCOwner["firstName"];
    $curOwnerMiddleName = $rowCOwner["middleName"];
    $curOwnerLastName = $rowCOwner["lastName"];
    $curOwnerNationality = $rowCOwner["nationality"];
    $curOwnerAddress = $rowCOwner["address"];
    $curOwnerIBAN = $rowCOwner["IBAN"];

    //Get the information of Current owner
    $queryNOwner="SELECT * FROM users WHERE ID='$NOwnerID'";
    $resultNOwner = mysqli_query($con, $queryNOwner);
    $rowNOwner = mysqli_fetch_array($resultNOwner, MYSQLI_ASSOC);
    $newOwnerFirstName = $rowNOwner["firstName"];
    $newOwnerMiddleName = $rowNOwner["middleName"];
    $newOwnerLastName = $rowNOwner["lastName"];
    $newOwnerNationality = $rowNOwner["nationality"];
    $newOwnerShare = "100%";
    $newOwnerAddress = $rowNOwner["address"];
    $newOwnerIDType = $rowNOwner["IDType"];
    $newOwnerIDNumber = $rowNOwner["ID"];
    $newOwnerIDdate = $rowNOwner["IDdate"];
    $newOwnerIBAN = $rowNOwner["IBAN"];
      
    $todayDate = date("Y-m-d");

    $queryLand="SELECT * FROM landrecord WHERE REUN='$REUN'";
    $resultLand = mysqli_query($con, $queryLand);
    $rowLand = mysqli_fetch_array($resultLand, MYSQLI_ASSOC);
    $neighborhoodName = $rowNOwner["neighborhoodName"];
    $city = $rowNOwner["city"];
    $deedNumber = $rowNOwner["deedNumber"];
      
      if ($con->query($query)==TRUE) {

        $NOwner_Data="{\"newOwnerFirstName\":\" $newOwnerFirstName \",\"newOwnerMiddleName\":\" $newOwnerMiddleName  \",\"newOwnerLastName\":\" $newOwnerLastName \",\"newOwnerNationality\":\" $newOwnerNationality \" ,\"newOwnerShare\":\" $newOwnerShare \",\"newOwnerAddress\":\" $newOwnerAddress \",\"newOwnerIDType\":\" $newOwnerIDType \",\"newOwnerIDNumber\":\" $NOwnerID \",\"newOwnerIDdate\":\"$newOwnerIDdate\",\"todayDate\":\"$todayDate\",\"transactionType\":\"Sell\"}";
        $Land_REUN=$REUN;
        $BChainResponse=UpdateLandOwner($Land_REUN,$NOwner_Data);
        print($BChainResponse);

        if($BChainResponse == '1'){
          $sqlBell ="INSERT INTO `Bill`(`OwnerID`, `BuyerID`, `REUN`, `SellerFName`, `SellerMName`, `SellerLName`, `BuyerFName`, `BuyerMName`, `BuyerLName`, `SellerIBAN`, `BuyerIBAN`, `offerID`, `landPrice`, `address`, `city`, `deedNumber`, `deedDate`) 
            VALUES ('$ID','$NOwnerID','$REUN','$curOwnerFirstName','$curOwnerMiddleName','$curOwnerLastName','$newOwnerFirstName','$newOwnerMiddleName','$newOwnerLastName','$curOwnerIBAN','$newOwnerIBAN','$OfferID','','$neighborhoodName','$city','$deedNumber','$todayDate')";
            $querySell = mysqli_query($con, $sqlBell);

            $DeleteSQL = "DELETE FROM `landrecord` WHERE REUN='$REUN'";
            $DeleteQuery = mysqli_query($con, $DeleteSQL);
            $DeleteSQL = "DELETE FROM `landsonsale` WHERE REUN='$REUN'";
            $DeleteQuery = mysqli_query($con, $DeleteSQL);
            $DeleteSQL = "DELETE FROM `map` WHERE REUN='$REUN'";
            $DeleteQuery = mysqli_query($con, $DeleteSQL);
            $DeleteSQL = "DELETE FROM `offers` WHERE REUN='$REUN'";
            $DeleteQuery = mysqli_query($con, $DeleteSQL);

          $sqlSell = "UPDATE UsersLands SET UserID = '$NOwnerID' WHERE REUN = '$REUN'";
          $querySell = mysqli_query($con, $sqlSell);

          if($querySell){	
            echo "<script>alert('تم إرسال الطلب بنجاح')</script>";
            echo "<script>setTimeout(\"location.href = 'controlLandspage.php';\");</script>";
          }else {
            die("Error: ".mysqli_stmt_error($stmt));
          }
        }
      } else {
         echo "Eroo". $query. "<br>" . $con->error;
       }        
    } 
    if(isset($_POST["reject"])){
      $OfferID = $_POST['OfferID'];
      $query = "UPDATE offers set offerStatus= '2' where OfferID ='$OfferID'";        
      $res2 = mysqli_query($con,$query);
      if ($con->query($query)==TRUE) {
      } else {
        echo "Eroo". $query. "<br>" . $con->error;
      }
    }
  ?>

<!DOCTYPE html>
<html lang="ar" style='direction: rtl'>
<head>
  <title>view offers page </title>
  <link rel="stylesheet" href="style.css">
  <!-- <script src="components/ComponentHandler.js" ></script> -->
  
  <style>
  
    .block{
      display: flex;
      flex-direction: column;
        align-items: stretch;
    }
    .MiniBlock {
      display: flex;
      justify-content: space-evenly;
    }
    

    @media only screen and (max-width: 800px ) {
      .land {
        flex-direction: column;
        align-items: center;
      }
      .block {
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

      td{
        text-align: center; 
        padding :8px; 
      } 
      
      th { 
        text-align: center; 
        padding :8px; 
        background-color: #3781a1;
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
    tr:nth-child(even) {
      background-color: #b3d3e2;
    }
    tr {border-bottom: 1px solid #dddddd;}

    .bt {
      background-color:#BD2504;
      color: #ffffff;
    }
    .bt:hover{background-color:#D3705B}

    input{
      padding: 9px 25px;
    }

    button{
      padding: 0px;
    }


      #id01{
        display: none;
        
      }
      /* The Close Button (x) */
    .close {
      right: 50px;
      top: 75px;
      font-size: 40px;
      font-weight: bold;
      color: #d1d1d1;
    }

    .close:hover,
    .close:focus {
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
      }
  </style>

</head>
<body>
    <!--Page header-->
    <!-- <div id="Head" w3-include-html="components/nav.php"></div> -->
    <?php include "components/nav.php";?>
    <main>
        <aside></aside>
        <div class="content">
          <h1> قائمة العروض</h1><br>
            <div class="landList">
                <table> 
                    <tr colspan="6" class= "heed"> </tr>
                            
                    <th> رقم الطلب </th> 
                    <th> رقم هوية المشتري </th> 
                    <th> رقم الوحدة العقارية </th> 
                    <th>  السعر </th> 
                    <th> </th>
                        
                        
                    <?php 
                        $query="SELECT * from offers WHERE offerStatus = 0 AND OwnerID = $ID"; 
                        $result = $con->query($query); 
                        $count = mysqli_num_rows($result);
			                  if ($count >0){
                            while($rows = $result->fetch_assoc()) 
                        {   
                    ?> 
                    <tr> 
                        <td><?php echo $rows['OfferID']; ?></td> 
                        <td><?php echo $rows['BuyerID']; ?></td> 
                        <td><?php echo $rows['REUN']; ?></td>         
                        <td><?php echo $rows['landPrice']; ?></td> 
                        <form method= "post" action="viewOffers.php">
                          <?php echo" <input type='hidden' id='NOwnerID' name='NOwnerID' value='$rows[BuyerID]';/> " ;?>
                            <?php echo" <input type='hidden' id='OfferID' name='OfferID' value='$rows[OfferID]';/> " ;?>
                            <?php echo" <input type='hidden' id='REUN' name='REUN' value='$rows[REUN]';/> " ;?>
                            <input type='hidden' id='landPrice' name='landPrice' value='<?php echo $rows['landPrice']; ?>';/>
                             
                            
                            <td>
                                <button><input name="accept" type="submit" value="قبول" ></button>
                                <button class="bt"><input style="color: #ffffff;" name="reject" type="submit" value="رفض" ></button>
                            </td>
                        </form> 
                    </tr> 
                    <?php 
                        } }else{
                          echo"<tr><td style='align-items: center; right: 50px;'>--لا يوجد بيانات لعرضها--</td> <tr>";
                        }
                    ?> 
                </table><br><br> 
            </div>
            <h1> سجل معاملات البيع </h1><br>
            <div class="landList">
                <table> 
                    <tr colspan="6" class= "heed"> </tr>
                            
                    <th> رقم الطلب </th> 
                    <th> رقم هوية المشتري </th> 
                    <th> رقم الوحدة العقارية </th> 
                    <th>  السعر </th> 
                    <th> </th>
                        
                        
                    <?php 
                        $query="SELECT * from offers WHERE offerStatus = 1 AND OwnerID = $ID"; 
                        $result = $con->query($query); 
                        $count = mysqli_num_rows($result);
			                  if ($count >0){
                            while($rows = $result->fetch_assoc()) 
                        {   
                    ?> 
                    <tr> 
                        <td><?php echo $rows['OfferID']; ?></td> 
                        <td><?php echo $rows['BuyerID']; ?></td> 
                        <td><?php echo $rows['REUN']; ?></td>         
                        <td><?php echo $rows['landPrice']; ?></td> 
                        <?php echo" <input type='hidden' id='OfferID' name='OfferID' value='$rows[OfferID]';/> " ;?>
                        <?php echo" <input type='hidden' id='REUN' name='REUN' value='$rows[REUN]';/> " ;?>
                        <td>
                          <button><input type="submit" value="فاتورة"  onclick="document.getElementById('id01').style.display='block'"></button>
                        </td>
                    </tr> 
                    <?php 
                        }}else{
                          echo"<tr><td style='align-items: center; right: 50px;'>--لا يوجد بيانات لعرضها--</td> <tr>";
                        }
                    ?> 
                </table><br><br> 
                
                <div id="id01" class="overlay-style">
                  <div class="block">
                    <span onclick="document.getElementById('id01').style.display='none'" class="close" title="Close Modal">&times;</span>
                    <h3 style="text-align: center; margin-bottom: 20px;">فاتورة البيع</h3>
                    <table>
                      <tr>
                        <th>رقم الفاتورة:  </th>
                        <td><?php print('1----<br>');
                        $billInfo = "SELECT * FROM `bill` WHERE 1";
                        $billRes = mysqli_query($con, $billInfo);
                        $count = mysqli_num_rows($billRes);
                      	if ($count >0){
print('2----<br>');
                          $billRow = mysqli_fetch_array($billRes);
                          echo $billRow['offerID'];
                          $REUN = $billRow['REUN'];
  
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
  
                          $space = $spaceInNumbers;
                          $price=$billRow['landPrice'];;
                        
                        ?></td>
                        <th>تاريخ الفاتورة:  </th>
                        <td><?php echo $deedDate; ?></td>
                      </tr>
                      <tr>
                        <th>اسم البائع</th>
                        <td><?php echo $billRow['SellerFName'], ' ', $billRow['SellerMName'], ' ', $billRow['SellerLName'];  ?></td>
                        <th>اسم المشتري</th>
                        <td><?php echo $billRow['BuyerFName'], ' ', $billRow['BuyerMName'], ' ', $billRow['BuyerLName']; ?></td>
                      </tr>
                      <tr>
                        <th>رقم هوية البائع: </th>
                        <td><?php echo $billRow['OwnerID']; ?></td>
                        <th>رقم هوية المشتري: </th>
                        <td><?php echo $billRow['BuyerID']; ?></td>
                      </tr>
                      <tr>
                        <th>رقم ايبان البائع: </th>
                        <td><?php echo $billRow['BuyerID']; ?></td>
                        <th>رقم ايبان المشتري: </th>
                        <td><?php echo $billRow['BuyerID']; ?></td>
                      </tr>
                      <tr>
                        <th colspan="2">الوصف</th>
                        <td colspan="2">
                          <?php echo 'رقم الوحده: ' . $billRow['REUN'] . ' العنوان: ' . $billRow['address'] . ' المدينة: ' . $billRow['city'] . ' رقم الصك: ' . $billRow['deedNumber'] . ' تاريخ الصك: ' . $deedDate . ' المساحة: ' .$spaceInNumbers; ?>
                          <?php?>
                        </td>
                      </tr>
                      <tr>
                        <th colspan="2">الاجمالي بدون الضرية:</th>
                        <td colspan="2"><?php echo (double)$price; ?></td>
                      </tr>
                      <tr>
                        <th colspan="2">ضريبة القيمة المضافة:</th>
                        <td colspan="2"><?php echo $tax=(double)$price*0.15; ?></td>
                      </tr>
                      <tr>
                        <th colspan="2">المجموع:</th>
                        <td colspan="2"><?php echo $tax+(double)$price;?></td>
                      </tr>
                    </table>
                    <br>
                  </div>
                  <?php }?>
                </div>
            </div>
        </div>
        <aside></aside>
    </main>

    <!-- footer -->
    <div w3-include-html="components/footer.php"></div>
    <?php 
    // include "components/footer.php";
    ?>

    <script>
      // Get the modal
      var modal = document.getElementById('id01');

      // When the user clicks anywhere outside of the modal, close it
      window.onclick = function(event) {
        if (event.target == modal) {
          modal.style.display = "none";
        }
      }
    </script>
    <script>
      includeHTML();
    </script>
    
</body>
</html>
