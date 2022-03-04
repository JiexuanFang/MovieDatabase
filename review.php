<html>
<link rel="stylesheet" href="style.css">
<ul>
  <li><a href=index.php>Movie Database</a></li>
  <li><a href=search.php>Search</a></li>
</ul>

<head><title>Review</title></head>

<body>
<h1>Add a review to a movie</h1>


<?php
// if it is a POST request, insert review to database
if($_SERVER["REQUEST_METHOD"]=="POST"){

  // accept params
  $mid = $_POST['id'];
  $name = $_POST['name'];
  $rating = $_POST['rating'];
  $comment = $_POST['comment'];
  // echo "COMMENT: MID=$mid, $NAME=$name, RATING=$rating, CMT=$comment<br>";

  // connect db
  $servername = "localhost";
  $username = "cs143";
  $password = ""; 
  $dbname = "class_db";
  $db = new mysqli($servername, $username, $password, $dbname);
  if ($db->connect_error) {
    die("connection failed: " . $db->connect_error);
  }
  // echo "<br>CONNECTION ESTABLISHED.<br>";

  // insert tuple
  $addcmt = $db->prepare(
  "INSERT INTO Review
  VALUES (?, NOW(),?, ?, ?)
  ");
  $addcmt -> bind_param('siis', $name,  $mid, $rating, $comment);
  $addcmt -> execute();
  $addcmt -> close();
  $db->close();
  // echo "<br>CONNECTION CLOSED.<br>";

  echo "Your comment is submitted!<br>";
}

// if it is a GET request, display the form
else{
  $movie_id =  $_GET['id'];
  if($movie_id != NULL){
    // echo "DEBUGGING - movie_id: $movie_id";

    // the form POST:
      // name of commenter
      // rating: int from 1 to 5
      // comment
      // (hidden) movie id
    echo "
      <form action='review.php?' method=POST> 

        <label for='name'> Name:</label><br>
        <input type='text' name='name'><br>

        <label for='rating'> Rating (between 1 and 5):</label><br>
        <input type='number' name='rating' min='1' max='5'><br>

        <label for='comment'> Comment:</label><br>
        <input type='text' name='comment'><br>

        <input type='hidden' name='id' value=$movie_id><br>

        <input type='submit' value='Submit comment!'>
      </form>";
    }
}
?>

<a href=index.php>return home</a><br>
</body>
</html>
