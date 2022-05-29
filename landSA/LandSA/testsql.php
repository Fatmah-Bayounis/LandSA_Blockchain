<?php

include "components/connection.php";
$REUN="7165267";
$insertLand = "INSERT INTO `landrecord` (`REUN`, `landState`, `firstName`, `middleName`, `lastName`, `share`, `IDNumber`, `pieceNumber`, `blockNumber`, `planNumber`, `neighborhoodName`, `city`, `unitType`, `deedNumber`, `deedDate`, `courtIssued`, `requestID`) VALUES ('$REUN', b'0', NULL, NULL, NULL, NULL, '876543456', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '');";
$query_insertL = mysqli_query($con, $insertLand);
print($query_insertL);


?>