# Migration-Script
<div id="main-content" class="wiki-content">

<strong>Context</strong> : The idea is to execute the php based utility which decrypts the column1 and column2 of table1 and table2 as well with crypt_key of size 12  and then encrypts the same column1 and column2 with crypt_key of size 24 in database. Now we can upgrade the app from PHP 5.5 to PHP 5.6. Followings are the steps which are needed to perform the desired task -
<ol>
	<li>Take the backup of application database. Please follow the below steps to take the backup -
<ol>
	<li>Go to Mysql installation bin directory (Mine is C:\Program Files\MySQL\MySQL Server 5.7\bin).</li>
	<li>Execute the below command and it'll prompt for the database_password.
<ol>
	<li>C:\Program Files\MySQL\MySQL Server 5.7\bin> mysqldump -u database_user --password -h localhost database_name > C:\Users\tangupta\Desktop\AppDatabase_Backup.sql (Please change this path where you want to take backup)
Enter password: ***********</li>
	<li>In case, you are not able to run the above command and getting the error like "mysqldump: Got error: 1045: Access denied for user 'ODBC'@'localhost' (using password: NO) when trying to connect", Please check whether you had granted all the privilages on this box database (local_esbox) in Mysql. if not , Please execute the below command on Mysql Command line client for the same -                       mysql> grant all privileges on db_name.* to 'username'@'localhost' identified by 'password';</li>
</ol>
</li>
</ol>
</li>
	<li>Execute the PHP script to decrypt and then encrypt the keys (column1 and column12 of table1 and table2 as well) in database. Please follow the below steps to execute the PHP script -
<ol>
	<li>Go to the path where script is saved (Mine is C:\xampp\htdocs)</li>
	<li>Execute the below command to do the same -
<ol>
	<li>C:\xampp\htdocs>php MigrationScript.php 127.0.0.1 local_user local_pass local_dbname 012345678901 012345678901234567890123 C:\Users\tangupta\Desktop\DbMigrationScriptLogs.txt
<ol>
	<li>MigrationScript.php (Name of the PHP Script)</li>
	<li>127.0.0.1 (ServerName)</li>
	<li>local_user (database_user)</li>
	<li>local_pass (database_password)</li>
	<li>local_dbname (database_name)</li>
	<li>012345678901 (Old 12 characters Encrypt Key as stored in PHP 5.5 chef recipe)</li>
	<li>012345678901234567890123 (New 24 characters Encrypt Key as stored in PHP 5.6 chef recipe)</li>
	<li>C:\Users\tangupta\Desktop\DbMigrationScriptLogs.txt (path where you want to save the script logs)
<strong>Note</strong>* - If you are not able to execute the script because of invalid character error, Please give the arguments in single quotes (Ex- '012345678901'). If there is some issue regarding database access with the user, please execute the query mentioned in step 1. (a. (ii.)).</li>
</ol>
</li>
	<li>Now script will give ask user whether he/she wants to execute the script or not (Yes or No). If yes, script will continue to execute else it will halt the execution.</li>
</ol>
</li>
</ol>
</li>
	<li>Upgrade the Box App from PHP 5.5 to PHP 5.6.</li>
	<li>Update the crypt_key as per PHP5.6 statndard (Only keys of sizes 16, 24 or 32 supported) in the chef-recipe-bag.</li>
	<li>Deploy the application.</li>
	<li>In case there is any failure, Please rollback the database by using below steps-
<ol>
	<li>Go to Mysql Command line client.</li>
	<li>Execute the below command to restore from DB backup created at step 1 -
<ol>
	<li>mysql> source  C:\Users\tangupta\Desktop\AppDatabase_Backup.sql (Path where you took the db backup, please change this accordingly.)</li>
</ol>
</li>
	<li>Downgrade the App from PHP 5.6 to PHP 5.5.</li>
	<li>Revert to the old 12 character encryt key in chef recipe and rebuild the app server.</li>
</ol>
</li>
	<li>Finish.</li>
</ol>
</div>
<div id="likes-and-labels-container"></div>
