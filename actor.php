<html>
<link rel="stylesheet" href="style.css">
<ul>
  <li><a href=index.php>Movie Database</a></li>
  <li><a href=search.php>Search</a></li>
</ul>


<head>
  <title>Actor</title>

</head>

<body>
<h1>Actor information</h1>

<?php

// input: id
// output: first name + last name + movies they were in
$actor_id =  $_GET['id'];

// CREATE AND CHECK CONNECTION
$servername = "localhost";
$username = "cs143";
$password = ""; 
$dbname = "class_db";

$db = new mysqli($servername, $username, $password, $dbname);
if ($db->connect_error) {
  die("connection failed: " . $db->connect_error);
}


if($actor_id == NULL){
  echo "<h2>ERROR - NULL actor id.</h2>";
  echo "<a href=index.php>return home</a><br>";
  $db->close();
  exit(1);
}


// DISPLAY
// name
echo "<h2>Name:</h2>";
$getname = $db->prepare(" 
  SELECT first, last, sex, dob, dod
  FROM Actor
  WHERE id = ?");

$getname -> bind_param('i', $actor_id);
$getname -> execute();

$getname -> bind_result($returned_first, $returned_last, $returned_sex, $returned_dob, $returned_dod);

while($getname->fetch()){
  echo $returned_first. " " . $returned_last . "<br>";
  echo "<br>SEX:   ";
  echo "$returned_sex<br>";
  echo "<br>date of birth:   ";
  echo "$returned_dob<br>";
  echo "<br>date of death:   ";
  if($returned_dod)
  echo "$returned_dod<br>";
  else
  echo "N/A<br>";
}
$getname->close();


// movies
echo "<h2>Movies:</h2>";
$getmov = $db->prepare("
  SELECT title, id
  FROM Movie
  WHERE id IN (
    SELECT mid
    FROM MovieActor
    WHERE aid = ?);");

$getmov -> bind_param('i', $actor_id);
$getmov -> execute();

$getmov -> bind_result($returned_title, $returned_mid);
while($getmov->fetch()){
  // echo $returned_title."<br>";
  echo "<a href=movie.php?id=$returned_mid>$returned_title</a><br>";
}
$getmov->close();

$db->close();

?>
<a href=index.php>return home</a><br>


</body>
</html>
