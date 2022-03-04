<html>

<ul>
  <li><a href=index.php>Movie Database</a></li>
</ul>

<head>
  <link rel="stylesheet" href="style.css">
  <title>Search</title>

</head>

<body>
<h1>Search for an actor/movie</h1>

<!-- get user input -->
<form action="search.php" method=GET>
  <!-- actor -->
  <label for="actor"> Actor Name:</label><br>
  <input type="text" name="actor">
  <input type="submit" value="Search">
</form>

<form action="search.php" method=GET> 
  <!-- movie  -->
  <label for="movie"> Movie Title:</label><br>
  <input type="text" name="movie">
  <input type="submit" value="Search">
</form>

<h1>Results: </h1>

<?php

$actor_str =  $_GET['actor'];
$movie_str =  $_GET['movie'];


// var_dump($actor_str);
// var_dump($movie_str);
// parse the input string into a list of keywords, separated by sp


// SEARCH

// function searchActor($kwlist, $db){
//   // concat statement according to the amount of kw
//   $statement = "SELECT first, last, id FROM Actor WHERE ";
//   $firstkw = true;
//   foreach($kwlist as $kw){
//     if($firstkw){
//       $statement .= " (first LIKE ? OR last LIKE ?)";
//       $firstkw = false;
//     }
//     else{
//       $statement .= " AND (first LIKE ? OR last LIKE ?)";
//     }
//   }
//   echo "<br>$statement<br>"; // check query

//   echo "<br>start preparing<br>";
//   $getname = $db->prepare($statement);

//   echo "<br>start binding<br>";
//   foreach($kwlist as $kw){
//     echo "keyword: $kw";
//     $getname -> bind_param("ss", $kw, $kw);
//   }
//   $getname -> execute();
  
//   echo "<br>results:<br>";
//   $getname -> bind_result($returned_first, $returned_last, $returned_aid);
//   while($getname->fetch()){
//     echo "<a href=actor.php?id=$returned_aid>$returned_first $returned_last</a><br>";
//   }

//   $getname->close();
// }

function searchActor($kwlist, $db){
  $getname = "SELECT first, last, id FROM Actor WHERE ";
  $firstkw = true;
  foreach($kwlist as $kw){
    if($firstkw){
      $getname .= " (first LIKE '%$kw%' OR last LIKE '%$kw%')";
      $firstkw = false;
    }
    else{
      $getname .= " AND (first LIKE '%$kw%' OR last LIKE '%$kw%')";
    }
  }
  $getname .= ";";

  echo "<br>QUERY: $getname<br>"; // check query

  $rs = $db->query($getname);
  if(!$rs){ 
    $errmsg = $db->error; 
    print "Query failed: $errmsg <br>"; 
    exit(1); 
  }

  // output handling
  if($rs->num_rows > 0){
    while($row = $rs->fetch_assoc()){
      echo "<a href=actor.php?id=".$row["id"].">".$row["first"]." ".$row["last"]."</a><br>";
    }
  }
  else{
    echo "0 result";
  }

  $rs->free();
}


function searchMovie($kwlist, $db){
  $getname = "SELECT title, id FROM Movie WHERE ";
  $firstkw = true;
  foreach($kwlist as $kw){
    if($firstkw){
      $getname .= " (title LIKE '%$kw%')";
      $firstkw = false;
    }
    else{
      $getname .= " AND (title LIKE '%$kw%')";
    }
  }
  $getname .= ";";

  //echo "<br>QUERY: $getname<br>"; // check query

  $rs = $db->query($getname);
  if(!$rs){ 
    $errmsg = $db->error; 
    print "Query failed: $errmsg <br>"; 
    exit(1); 
  }

  // output handling
  if($rs->num_rows > 0){
    while($row = $rs->fetch_assoc()){
      echo "<a href=movie.php?id=".$row["id"].">".$row["title"]."</a><br>";
    }
  }
  else{
    echo "0 result";
  }

  $rs->free();
}


// CONNECT DB
$servername = "localhost";
$username = "cs143";
$password = ""; 
$dbname = "class_db";
$db = new mysqli($servername, $username, $password, $dbname);
if ($db->dbect_error) {
  die("dbection failed: " . $db->dbect_error);
}


// SEARCH DRIVER FN
$deliminator = ' ';
if($actor_str != NULL){
  //echo "<br>FOR DEBUGGING PURPOSE - the actor keywords you get are $actor_str!<br>";
  $actor_kw = explode($deliminator, strtolower($actor_str)); // parse + lowercase
  // var_dump($actor_kw);
  searchActor($actor_kw, $db);
}
elseif($movie_str != NULL){
  //echo "<br>FOR DEBUGGING PURPOSE - the movie keywords you get are $movie_str!<br>";
  $movie_kw = explode($deliminator, strtolower($movie_str)); // parse + lowercase
  searchMovie($movie_kw, $db);
}

$db->close();

?>

</body>
</html>
