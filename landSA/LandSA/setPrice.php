<!-- set price form -->
<?php
$REUN=null;
  #Check the connections with the server and DB
  include "components/connection.php";

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

    //Check that their are no repeted requests
        $query2="SELECT * FROM landsonsale WHERE REUN='$REUN'";
        $result2 = mysqli_query($con, $query2);
        $count2 = mysqli_num_rows($result2);

        if($count2 == 0){
          $insertUser = "INSERT INTO landsonsale(REUN, price) value($REUN, $price)";
          $result = mysqli_query($con,$insertUser); #send query to the databaes to use insert method

          if($result){  
            echo "<script>alert('تم إرسال الطلب بنجاح')</script>";
            echo "<script>setTimeout(\"location.href = 'controlLandsPage.php';\",1500);</script>";
          }else {
            die("Error: ".mysqli_errno($con));
          }

        }else{
          echo "<script>alert('تم عرض الأرض للبيع مسبقًا')</script>";
          echo "<script>setTimeout(\"location.href = 'controlLandsPage.php';\",1500);</script>";
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