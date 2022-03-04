<html>
<link rel="stylesheet" href="style.css">
<ul>
  <li><a href=index.php>Movie Database</a></li>
  <li><a href=search.php>Search</a></li>
</ul>

<head><title>Movie</title></head>

<body>
<h1>Movie information</h1>


<?php

// input: id
// output: first name + last name + movies they were in

$movie_id =  $_GET['id'];
// echo "<br>FOR DEBUGGING PURPOSE - the movie id you get is $movie_id!<br>";

// CREATE AND CHECK CONNECTION
$servername = "localhost";
$username = "cs143";
$password = ""; 
$dbname = "class_db";

$db = new mysqli($servername, $username, $password, $dbname);
if ($db->connect_error) {
  die("connection failed: " . $db->connect_error);
}

if($movie_id == NULL){
  echo "<h2>ERROR - NULL movie id.</h2>";
  echo "<a href=index.php>return home</a><br>";
  $db->close();
  exit(1);
}

// DISPLAY

// movie title
$gettitle = $db->prepare(" 
  SELECT title, year, rating, company
	FROM Movie
	WHERE id = ?");
$gettitle -> bind_param('i', $movie_id);
$gettitle -> execute();
$gettitle -> bind_result($returned_title, $returned_year, $returned_rating, $returned_company);
$gettitle -> fetch();
echo "<h2>$returned_title, $returned_year</h2>";
echo "<br>produced by $returned_company<br>";
echo "MPAA rating: $returned_rating<br>";
echo "<br>Actors staring in '$returned_title'<br>";
$gettitle -> close();


// actors
$getname = $db->prepare(" 
  SELECT first, last, id
	FROM Actor
	WHERE id IN (
		SELECT aid
		FROM MovieActor
		WHERE mid = ?);");
$getname -> bind_param('i', $movie_id);
$getname -> execute();
$getname -> bind_result($returned_first, $returned_last, $returned_aid);
while($getname -> fetch()){
	echo "<a href=actor.php?id=$returned_aid>$returned_first $returned_last</a><br>";
  // echo $returned_first. " " . $returned_last . "<br>";
}
$getname->close();


// average rating
echo "<h2>Average Score</h2>";
$getscore = $db->prepare(" 
	SELECT AVG(rating)
	FROM Review
	WHERE mid = ?");
$getscore -> bind_param('i', $movie_id);
$getscore -> execute();
$getscore -> bind_result($returned_score);
$getscore -> fetch();
echo "$returned_score<br>";
$getscore -> close();


// comments
echo "<h2>Comments</h2>";
$getcmt = $db->prepare(" 
	SELECT name, time, comment
	FROM Review
	WHERE mid = ?");
$getcmt -> bind_param('i', $movie_id);
$getcmt -> execute();
$getcmt -> bind_result($returned_name, $returned_time, $returned_comment);
while($getcmt -> fetch()){
  echo $returned_comment."<br>";
  echo "-- commented by $returned_name at $returned_time.<br>";
}


$getcmt -> close();

// CLOSE DB CONNECTION
$db->close();

echo "<a href=review.php?id=$movie_id>comment on $returned_title</a><br>";

?>


<a href=index.php>return home</a><br>
</body>
</html>
