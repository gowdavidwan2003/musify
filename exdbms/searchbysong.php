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

// Get the search query from the request
$searchQuery = $_GET['search'];

// Prepare and execute the SQL query
$sql = "SELECT * FROM track WHERE track_name LIKE '%$searchQuery%'";
$result = $db->query($sql);
//$sql1 = "select * from album where album_id in(SELECT track_id FROM track WHERE track_name LIKE '%$searchQuery%')";
//$result1 = $db->query($sql1);
//$sql2 = "select * from artist where artist_id in(select artid from album where album_id in(SELECT track_id FROM track WHERE track_name LIKE '%$searchQuery%'))";
//$result2 = $db->query($sql2);
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

// Display the search results
//if ($result->num_rows > 0) {
   // while ($row = $result->fetch_assoc()) {
    //    echo "Track Name: " . $row["track_name"] . "<br>";
        //echo "Artist: " . $row["artist"] . "<br><br>";
  //  }
//    while ($row = $result1->fetch_assoc()) {
    //    echo "Album Name: " . $row["album_name"] . "<br>";
  //      //echo "Artist: " . $row["artist"] . "<br><br>";
//    }
//    while ($row = $result2->fetch_assoc()) {
    //    echo "Artist Name: " . $row["artist_name"] . "<br>";
        //echo "Artist: " . $row["artist"] . "<br><br>";
  //  }
//} 
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
echo $songs


// Close the database connection
$db->close();
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Spotify - Your favourite music is here</title>
        <link rel="stylesheet" href="style.css">
        </head>
<body>
    <nav>
        <ul>
            <li class="brand"><img src="logo.png" alt="Spotify"> Spotify</li>
            <li>Home</li>
            <li>About</li>
        </ul>
    </nav>

    <div class="container">
        <div class="songList">
            <h1>Best of NCS - No Copyright Sounds</h1>
            <div class="songItemContainer">
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
    <script> // Embed the PHP-generated JSON data into a JavaScript variable
        var songs = <?php echo $songsJson; ?>;
        
        // Now you can access the songs data in your JavaScript code
        console.log(songs);
        
        // Example: Loop through the songs and display them on the page
        window.onload = function() {
            // ...
        };
        console.log("Welcome to Spotify");

// Initialize the Variables
let songIndex = 0;
let audioElement = new Audio('songs/1.mp3');
let masterPlay = document.getElementById('masterPlay');
let myProgressBar = document.getElementById('myProgressBar');
let gif = document.getElementById('gif');
let masterSongName = document.getElementById('masterSongName');
let songItems = Array.from(document.getElementsByClassName('songItem'));
// Embed the PHP-generated JSON data into a JavaScript variable
var songs = <?php echo $songsJson; ?>;
        
        // Now you can access the songs data in your JavaScript code
        console.log(songs);
        
        // Example: Loop through the songs and display them on the page
        window.onload = function() {
            // ...
        };
//let songs = [
  //  {songName: "Warriyo - Mortals [NCS Release]", filePath: "songs/1.mp3", coverPath: "covers/1.jpg"},
    //{songName: "Cielo - Huma-Huma", filePath: "songs/2.mp3", coverPath: "covers/2.jpg"},
//    {songName: "DEAF KEV - Invincible [NCS Release]-320k", filePath: "songs/3.mp3", coverPath: "covers/3.jpg"},
  //  {songName: "Different Heaven & EH!DE - My Heart [NCS Release]", filePath: "songs/4.mp3", coverPath: "covers/4.jpg"},
    //{songName: "Janji-Heroes-Tonight-feat-Johnning-NCS-Release", filePath: "songs/5.mp3", coverPath: "covers/5.jpg"},
//    {songName: "Rabba - Salam-e-Ishq", filePath: "songs/2.mp3", coverPath: "covers/6.jpg"},
  //  {songName: "Sakhiyaan - Salam-e-Ishq", filePath: "songs/2.mp3", coverPath: "covers/7.jpg"},
    //{songName: "Bhula Dena - Salam-e-Ishq", filePath: "songs/2.mp3", coverPath: "covers/8.jpg"},
//    {songName: "Tumhari Kasam - Salam-e-Ishq", filePath: "songs/2.mp3", coverPath: "covers/9.jpg"},
  //  {songName: "Na Jaana - Salam-e-Ishq", filePath: "songs/4.mp3", coverPath: "covers/10.jpg"},
//]

songItems.forEach((element, i)=>{ 
    element.getElementsByTagName("img")[0].src = songs[i].coverPath; 
    element.getElementsByClassName("songName")[0].innerText = songs[i].songName; 
})
 

// Handle play/pause click
masterPlay.addEventListener('click', ()=>{
    if(audioElement.paused || audioElement.currentTime<=0){
        audioElement.play();
        masterPlay.classList.remove('fa-play-circle');
        masterPlay.classList.add('fa-pause-circle');
        gif.style.opacity = 1;
    }
    else{
        audioElement.pause();
        masterPlay.classList.remove('fa-pause-circle');
        masterPlay.classList.add('fa-play-circle');
        gif.style.opacity = 0;
    }
})
// Listen to Events
audioElement.addEventListener('timeupdate', ()=>{ 
    // Update Seekbar
    progress = parseInt((audioElement.currentTime/audioElement.duration)* 100); 
    myProgressBar.value = progress;
})

myProgressBar.addEventListener('change', ()=>{
    audioElement.currentTime = myProgressBar.value * audioElement.duration/100;
})

const makeAllPlays = ()=>{
    Array.from(document.getElementsByClassName('songItemPlay')).forEach((element)=>{
        element.classList.remove('fa-pause-circle');
        element.classList.add('fa-play-circle');
    })
}

Array.from(document.getElementsByClassName('songItemPlay')).forEach((element)=>{
    element.addEventListener('click', (e)=>{ 
        makeAllPlays();
        songIndex = parseInt(e.target.id);
        e.target.classList.remove('fa-play-circle');
        e.target.classList.add('fa-pause-circle');
        audioElement.src = `songs/${songIndex+1}.mp3`;
        masterSongName.innerText = songs[songIndex].songName;
        audioElement.currentTime = 0;
        audioElement.play();
        gif.style.opacity = 1;
        masterPlay.classList.remove('fa-play-circle');
        masterPlay.classList.add('fa-pause-circle');
    })
})

document.getElementById('next').addEventListener('click', ()=>{
    if(songIndex>=9){
        songIndex = 0
    }
    else{
        songIndex += 1;
    }
    audioElement.src = `songs/${songIndex+1}.mp3`;
    masterSongName.innerText = songs[songIndex].songName;
    audioElement.currentTime = 0;
    audioElement.play();
    masterPlay.classList.remove('fa-play-circle');
    masterPlay.classList.add('fa-pause-circle');

})

document.getElementById('previous').addEventListener('click', ()=>{
    if(songIndex<=0){
        songIndex = 0
    }
    else{
        songIndex -= 1;
    }
    audioElement.src = `songs/${songIndex+1}.mp3`;
    masterSongName.innerText = songs[songIndex].songName;
    audioElement.currentTime = 0;
    audioElement.play();
    masterPlay.classList.remove('fa-play-circle');
    masterPlay.classList.add('fa-pause-circle');
})</script>
    <script src="https://kit.fontawesome.com/26504e4a1f.js" crossorigin="anonymous"></script>
</body>
</html>
