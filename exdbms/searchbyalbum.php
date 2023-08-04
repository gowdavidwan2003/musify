<?php 
  session_start(); 

  if (!isset($_SESSION['username'])) {
  	$_SESSION['msg'] = "You must log in first";
  	header('location: login.php');
  }
  if (isset($_GET['logout'])) {
  	session_destroy();
  	unset($_SESSION['username']);
  	header("location: login.php");
  }
?>
<?php
// Establish a connection to the MySQL database

$username = "";
$email    = "";
$errors = array(); 

// connect to the database
$db = mysqli_connect('localhost', 'root', '', 'dbms_project');
if ($db->connect_error) {
    die("Connection failed: " . $db->connect_error);
}
$searchQuery = $_GET['search'];
//$sql = "SELECT * FROM track WHERE track_name LIKE '%$searchQuery%'";
$sql = "SELECT *
        FROM track t
        JOIN album ab ON t.albid = ab.album_id
        JOIN artist a ON ab.artid = a.artist_id
        WHERE ab.album_name LIKE '%$searchQuery%'";
$result = $db->query($sql);
$songs = array();
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $song = array(
            'songName' => $row['track_name'],
            'filePath' => $row['track_path'],
            'coverPath' => $row['cover_path']
        );
        $songs[] = $song;
    }
}
else {
    echo "No results found.";
}


$output = 'songs = [' . PHP_EOL;
if (is_array($songs)) {
    foreach ($songs as $song) {
        $output .= "{";
        $output .= "songName: " . '"' . $song['songName'] . '", ';
        $output .= "filePath: " . '"' . $song['filePath'] . '", ';
        $output .= "coverPath: " . '"' . $song['coverPath'] . '"';
        $output .= "}," . PHP_EOL;
    }
}
$output .= '];';
$json_songs = '[';
foreach ($songs as $song) {
    $json_songs .= '{';
    $json_songs .= 'songName: "' . $song['songName'] . '", ';
    $json_songs .= 'filePath: "' . $song['filePath'] . '", ';
    $json_songs .= 'coverPath: "' . $song['coverPath'] . '"';
    $json_songs .= '},';
}
// Remove the trailing comma and close the array
$json_songs = rtrim($json_songs, ',');
$json_songs .= ']';

?>
<!DOCTYPE html>
<html lang="en">
    <head>
    <style>
@import url('https://fonts.googleapis.com/css2?family=Ubuntu&display=swap');
@import url('https://fonts.googleapis.com/css2?family=Varela+Round&display=swap');
body{
    background-color: antiquewhite;
}

*{
    margin: 0;
    padding: 0;
}

nav{
    font-family: 'Ubuntu', sans-serif;
}

nav ul{
    display: flex;
    align-items: center;
    list-style-type: none;
    height: 65px;
    background-color: black;
    color: white;
}

nav ul li{
    padding: 0 12px;
}
.brand img{
    width: 44px;
    padding: 0 8px;
}

.brand {
    display: flex;
    align-items: center;
    font-weight: bolder;
    font-size: 1.3rem;
}

.container{
    min-height: 72vh;
    background-color: black;
    color: white;
   font-family: 'Varela Round', sans-serif;
   display: flex;
   margin: 23px auto;
   width: 70%;
   border-radius: 12px;
   padding: 34px;
   background-image: url('bg.jpg');
}

.bottom{
    position: sticky;
    bottom: 0;
    height: 130px;
    background-color: black;
    color: white;
    display: flex;
    justify-content: center;
    align-items: center;
    flex-direction: column; 
}

.icons{
    margin-top: 14px; 
}
.icons i{
    cursor: pointer;
}

#myProgressBar{
    width: 80vw; 
    cursor: pointer;
}

.songItemContainer{
    margin-top: 74px;
}

.songItem{
    height: 50px;
    display: flex;
    background-color: white;
    
    color: black;
    margin: 12px 0;
    justify-content: space-between;
    align-items: center;
    border-radius: 34px;
}

.songItem img{
    width: 43px;
    margin: 0 23px;
    border-radius: 34px;
}

.timestamp{
    margin: 0 23px;
}

.timestamp i{
    cursor: pointer;
}

.songInfo{
    position: absolute;
    left: 10vw;
    font-family: 'Varela Round', sans-serif;
}

.songInfo img{
    opacity: 0;
    transition: opacity 0.4s ease-in;
}

@media only screen and (max-width: 1100px) {
    body {
      background-color: red;
    }
  }
  .search-tab {
      display: flex;
      align-items: center;
      padding: 10px;
      background-color: #f0f0f0;
    }
    
    .search-input {
      flex-grow: 1;
      padding: 5px;
      font-size: 16px;
    }
    
    .search-button {
      padding: 5px 10px;
      font-size: 16px;
    }
    </style>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Spotify - Your favourite music is here</title>
        </head>
<body>
<nav>
        <ul>
        <li class="brand"><img src="OIG.jpg" alt="Spotify">Musify</li>
           
        <div class="brand">
    <form action="searchbysongs2.php" method="GET">
        <input type="text" name="search" class="search-input" placeholder="Search Song">
        <button type="submit" class="search-button">Search</button>
    </form>
</div>

  <div class="brand">
  <form action="searchbyartist.php" method="GET">
        <input type="text" name="search" class="search-input" placeholder="Search by Artist">
        <button type="submit" class="search-button">Search</button>
    </form>
  </div>
  <div class="brand">
  <form action="searchbyalbum.php" method="GET">
        <input type="text" name="search" class="search-input" placeholder="Search by Album">
        <button type="submit" class="search-button">Search</button>
    </form>
  </div>
  <?php  if (isset($_SESSION['username'])) : ?>
    	<p>Welcome <strong><?php echo $_SESSION['username']; ?></strong></p>
    	<p> <a href="index.php?logout='1'" style="color: red;">logout</a> </p>
    <?php endif ?>
        </ul>
    </nav>

    <div class="container">
        <div class="songList">
            <h1>Trending Songs </h1>
            <div class="songItemContainer">
                <div class="songItem">
                    <img alt="1">
                    <span class="songName">Let me Love You</span>
                    <span class="songlistplay"><span class="timestamp">05:34 <i id="0" class="far songItemPlay fa-play-circle"></i> </span></span>
                </div>    
                <div class="songItem">
                    <img alt="1">
                    <span class="songName">Let me Love You</span>
                    <span class="songlistplay"><span class="timestamp">05:34 <i id="0" class="far songItemPlay fa-play-circle"></i> </span></span>
                </div>    
            </div>
        </div>
        <div class="songBanner"></div>
    </div>

    <div class="bottom">
        <input type="range" name="range" id="myProgressBar" min="0" value="0" max="100">
        <div class="icons">
            <!-- fontawesome icons -->
            <i class="fas fa-3x fa-step-backward" id="previous"></i>
            <i class="far fa-3x fa-play-circle" id="masterPlay"></i>
            <i class="fas fa-3x fa-step-forward" id="next"></i> 
        </div>
        <div class="songInfo">
            <img src="playing.gif" width="42px" alt="" id="gif"> <span id="masterSongName">Warriyo - Mortals [NCS Release]</span>
        </div>
    </div>
    <script>
  console.log("Welcome to Spotify");

  // Initialize the Variables
  var songs = <?php echo $json_songs; ?>;
  let songIndex = 0;

  let masterPlay = document.getElementById('masterPlay');
  let myProgressBar = document.getElementById('myProgressBar');
  let gif = document.getElementById('gif');
  let masterSongName = document.getElementById('masterSongName');
  let songItems = Array.from(document.getElementsByClassName('songItem'));

  let audioElement = new Audio();

  // Function to load a song and update the UI
  const loadSong = (index) => {
    audioElement.src = songs[index].filePath;
    masterSongName.innerText = songs[index].songName;
    gif.style.opacity = 0; // Hide the gif when changing songs
  };

  // Function to play the song
  const playSong = () => {
    audioElement.play();
    masterPlay.classList.remove('fa-play-circle');
    masterPlay.classList.add('fa-pause-circle');
    gif.style.opacity = 1; // Show the gif when playing a song
  };

  // Function to pause the song
  const pauseSong = () => {
    audioElement.pause();
    masterPlay.classList.remove('fa-pause-circle');
    masterPlay.classList.add('fa-play-circle');
    gif.style.opacity = 0; // Hide the gif when pausing a song
  };

  // Function to handle song play/pause button clicks
  const handlePlayClick = () => {
    if (audioElement.paused || audioElement.currentTime <= 0) {
      playSong();
    } else {
      pauseSong();
    }
  };

  // Function to handle song progress update
  const updateProgressBar = () => {
    const progress = parseInt((audioElement.currentTime / audioElement.duration) * 100);
    myProgressBar.value = progress;
  };

  // Function to play the next song
  const playNextSong = () => {
    songIndex = (songIndex + 1) % songs.length;
    loadSong(songIndex);
    playSong();
    makeAllPlays();
    document.getElementById(songIndex).classList.add('fa-pause-circle');
  };

  // Function to make all play buttons show "play" icon
  const makeAllPlays = () => {
    Array.from(document.getElementsByClassName('songItemPlay')).forEach((element) => {
      element.classList.remove('fa-pause-circle');
      element.classList.add('fa-play-circle');
    });
  };

  // Add event listeners
  masterPlay.addEventListener('click', handlePlayClick);

  audioElement.addEventListener('timeupdate', updateProgressBar);

  myProgressBar.addEventListener('change', () => {
    audioElement.currentTime = (myProgressBar.value * audioElement.duration) / 100;
  });

  songItems.forEach((element, i) => {
    const songItemPlay = element.getElementsByClassName('songItemPlay')[0];
    songItemPlay.addEventListener('click', () => {
      songIndex = i;
      loadSong(songIndex);
      playSong();
      makeAllPlays();
      songItemPlay.classList.add('fa-pause-circle');
    });

    const img = element.getElementsByTagName("img")[0];
    img.src = songs[i].coverPath;

    const songName = element.getElementsByClassName("songName")[0];
    songName.innerText = songs[i].songName;
  });

  document.getElementById('next').addEventListener('click', playNextSong);

  document.getElementById('previous').addEventListener('click', () => {
    songIndex = (songIndex - 1 + songs.length) % songs.length;
    loadSong(songIndex);
    playSong();
    makeAllPlays();
    document.getElementById(songIndex).classList.add('fa-pause-circle');
  });
</script>
    <script src="https://kit.fontawesome.com/26504e4a1f.js" crossorigin="anonymous"></script>
</body>
</html>