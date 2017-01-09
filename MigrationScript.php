<?php

if($argc==8)
{
$servername = $argv[1]; //"localhost";
$username = $argv[2]; //"local_app";
$password = $argv[3]; //"local_app";
$dbname = $argv[4]; // "local_app";
$decrypt_key = $argv[5]; // "012345678901";
$encrypt_key = $argv[6]; // "012345678901234567890123";
$outputFilePath= $argv[7]; // 'C:\Users\tangupta\Desktop\dump.txt'

echo "\n This is a PHP based utility to do the re-encryption of App Database. Do you want to proceed ? Press Y/y to continue else press any key to Exit. \n";

fscanf(STDIN,"%c\n",$flag);
if($flag=='Y'||$flag=='y')
{
	$startTime=microtime(true);
	function encrypt($toEncrypt) {
        //$this->encrypt_key  = $encrypt_key; 
        $iv = mcrypt_create_iv(mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB), MCRYPT_RAND);
        $passcrypt = mcrypt_encrypt(MCRYPT_RIJNDAEL_256, $GLOBALS['encrypt_key'], trim($toEncrypt), MCRYPT_MODE_ECB, $iv);
        $encode = rtrim(base64_encode($passcrypt));

        return $encode;
    }

    function decrypt($toDecrypt) {
        //$this->decrypt_key  = $decrypt_key; // 
        $decoded = base64_decode($toDecrypt);
        $iv = mcrypt_create_iv(mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB), MCRYPT_RAND);
        $decrypted = trim(mcrypt_decrypt(MCRYPT_RIJNDAEL_256, $GLOBALS['decrypt_key'], $decoded, MCRYPT_MODE_ECB, $iv));

        return $decrypted;
    }
	
// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
     die("Connection failed: " . $conn->connect_error);
} 

$sql = "SELECT id, column1 , column2 FROM table1";
$result = $conn->query($sql);

$file=fopen($outputFilePath,'w');
fwrite($file,"App Id");
fwrite($file,"  |  ");
fwrite($file,"Original App column1");
fwrite($file,"  |  ");
fwrite($file,"Original App Refresh column1");
fwrite($file,"  |  ");
fwrite($file,"Encrypted App column1");
fwrite($file,"  |  ");
fwrite($file,"Encrypted App Refresh column1");
fwrite($file,"  |  ");
fwrite($file,"Status");
fwrite($file,"  |  ");
fwrite($file,"\n");
if ($result->num_rows > 0) {
     // output data of each row
     while($row = $result->fetch_assoc()) {
		 /*
		 echo "<br> Id: ". $row["id"].  "<br>";
         echo "<br> column1: ". encrypt(decrypt($row["column1"])) .  "<br>";
		 echo "<br> column2: ". encrypt(decrypt($row["column2"])). "<br>";
		 
         echo "<br> Decrypted column1: ". decrypt(encrypt(decrypt($row["column1"]))) .  "<br>";
		 echo "<br> Decrypted column2: ". decrypt(encrypt(decrypt($row["column2"]))). "<br>";
		 
		 echo "<br> Encrypted column1: ". encrypt(decrypt(encrypt(decrypt($row["column1"])))) .  "<br>";
		 echo "<br> Encrypted column2: ". encrypt(decrypt(encrypt(decrypt($row["column2"])))). "<br>";
		 */
		 fwrite($file,$row["id"]);
		 fwrite($file,"  |  ");
		 fwrite($file,$row["column1"]);
		 fwrite($file,"  |  ");
		 fwrite($file,$row["column2"]);
		 fwrite($file,"  |  ");
		 $Id=$row["id"];
		 $Updatedcolumn1=encrypt(decrypt($row["column1"]));
		 $UpdatedRefreshcolumn1= encrypt(decrypt($row["column2"]));
		 fwrite($file,$Updatedcolumn1);
		 fwrite($file,"  |  ");
		 fwrite($file,$UpdatedRefreshcolumn1);
		 fwrite($file,"  |  ");
		 //$sql1 = "UPDATE table table1" ." SET column1= encrypt(decrypt('$row["column1"]')) ".",column2= encrypt(decrypt('$row["column2"]'))" ." WHERE id= $row["id"]";
		 $sql1 = "update table1 set column1 = '$Updatedcolumn1', column2='$UpdatedRefreshcolumn1' where id=$Id ";
		 $result1 = $conn->query($sql1);
			if ($result1 == TRUE) {
				 fwrite($file,"Success");
				 fwrite($file,"  |  ");
				 echo "Record updated successfully with App Id :: " .$row["id"]. "\n";
			} else {
				fwrite($file,"Fail");
				fwrite($file,"  |  ");
				echo "Error updating App record: " . $conn->error . "\n";
			}
			fwrite($file,"\n");
	 }
     
} else {
     echo "0 results for table1  table ";
}

$sql2 = "SELECT id, column1 , refreshcolumn1 FROM table2";
$result2 = $conn->query($sql2);
fwrite($file,"\n");
fwrite($file,"\n");
fwrite($file,"App Id");
fwrite($file,"  |  ");
fwrite($file,"Original App column1");
fwrite($file,"  |  ");
fwrite($file,"Original App Refresh column1");
fwrite($file,"  |  ");
fwrite($file,"Encrypted App column1");
fwrite($file,"  |  ");
fwrite($file,"Encrypted App Refresh column1");
fwrite($file,"  |  ");
fwrite($file,"Status");
fwrite($file,"  |  ");
fwrite($file,"\n");
if ($result2->num_rows > 0) {
     // output data of each row
     while($row = $result2->fetch_assoc()) {
		 /*
		 echo "<br> Id: ". $row["id"].  "<br>";
         echo "<br> column1: ". encrypt(decrypt($row["column1"])) .  "<br>";
		 echo "<br> column2: ". encrypt(decrypt($row["refreshcolumn1"])). "<br>";
		 
         echo "<br> Decrypted column1: ". decrypt(encrypt(decrypt($row["column1"]))) .  "<br>";
		 echo "<br> Decrypted column2: ". decrypt(encrypt(decrypt($row["refreshcolumn1"]))). "<br>";
		 
		 echo "<br> Encrypted column1: ". encrypt(decrypt(encrypt(decrypt($row["column1"])))) .  "<br>";
		 echo "<br> Encrypted column2: ". encrypt(decrypt(encrypt(decrypt($row["refreshcolumn1"])))). "<br>";
		 */
		 fwrite($file,$row["id"]);
		 fwrite($file,"  |  ");
		 fwrite($file,$row["column1"]);
		 fwrite($file,"  |  ");
		 fwrite($file,$row["refreshcolumn1"]);
		 fwrite($file,"  |  ");
		 $AppId=$row["id"];
		 $AppUpdatedcolumn1=encrypt(decrypt($row["column1"]));
		 $AppUpdatedRefreshcolumn1= encrypt(decrypt($row["refreshcolumn1"]));
		 fwrite($file,$AppUpdatedcolumn1);
		 fwrite($file,"  |  ");
		 fwrite($file,$AppUpdatedRefreshcolumn1);
		 fwrite($file,"  |  ");
		 //$sql1 = "UPDATE table table2" ." SET column1= encrypt(decrypt('$row["column1"]')) ".",refreshcolumn1= encrypt(decrypt('$row["refreshcolumn1"]'))" ." WHERE id= $row["id"]";
		 $sql3 = "update table2 set column1 = '$AppUpdatedcolumn1', refreshcolumn1='$AppUpdatedRefreshcolumn1' where id=$AppId ";
		 $result3 = $conn->query($sql3);
			if ($result3 == TRUE) {
				 fwrite($file,"Success");
				 fwrite($file,"  |  ");
				 echo "Record updated successfully with table2 Id :: " .$row["id"]. "\n";
			} else {
				fwrite($file,"Fail");
				 fwrite($file,"  |  ");
				echo "Error updating table2 record: " . $conn->error . "\n";
			}
			fwrite($file,"\n");
	 }
     
} else {
     echo "0 results for table2 table";
}
echo "File has been created successfully.";
fclose($file);
$conn->close();
echo "\n Script execution time in seconds :: " .(microtime(true)-$startTime);
}
else
		echo "You have decided to not to run the script. Thanks! \n";
}
else
{
	 echo "Please give the arguments in the below format \n";
		echo "php filename servername username password dbname decryptKey(PHP 5.5 Key) encryptKey(PHP 5.6 Key) outPutFilePath((path where you want to save the script logs)";
}
 
?>  