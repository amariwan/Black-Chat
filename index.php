<?php
session_start();
 
function loginForm(){
    echo'
    <div id="loginform">
	<h1>Welcome to Black Chat</h1>
    <form action="index.php" method="post">
        <p>Bitte geben Sie Ihren Namen ein:</p>
        <label for="name">Name:</label>
        <input type="text" name="name" id="name" />
        <input type="submit" name="enter" id="enter" value="Anmelden" />
    </form>
    </div>
    ';
}
 
if(isset($_POST['enter'])){
    if($_POST['name'] != ""){
        $_SESSION['name'] = stripslashes(htmlspecialchars($_POST['name']));
    }
    else{
        echo '<span class="error">Please enter your name first!</span>';
    }
}

if(isset($_GET['logout'])){
     
    $fp = fopen("log.html", 'a');
    fwrite($fp, "<div class='msgleft'><em>User ". $_SESSION['name'] ." left the chat.</em><br></div>");
    fclose($fp);
     
    session_destroy();
    header("Location: index.php"); // back to login
}
?>

<!DOCTYPE html>

<head>
    <title>BlackChat using PHP / jQuery</title>
    <link rel="stylesheet" href="style.css" />
    <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.3/jquery.min.js"></script>
</head>

<?php
if(!isset($_SESSION['name'])){
    loginForm();
}
else{
?>

<body>

    <div id="chatwindow">
        <div id="menu">
            <p class="welcome">Welcome to Black Chat, <b><?php echo $_SESSION['name']; ?></b></p>
            <p class="logout"><a id="exit" href="#">X</a></p>
            <div style="clear:both"></div>
        </div>

        <div id="chatbox">
            <?php
	if(file_exists("log.html") && filesize("log.html") > 0){
    $handle = fopen("log.html", "r");
    $contents = fread($handle, filesize("log.html"));
    fclose($handle);
     
    echo $contents;
	}
	?>
        </div>

        <form name="message" action="">
            <input name="usermsg" type="text" id="usermsg" size="63" />
            <input name="submitmsg" type="submit" id="submitmsg" value="senden" />
        </form>
    </div>
    <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.3/jquery.min.js"></script>
    <script type="text/javascript">
    // jQuery Document
    $(document).ready(function() {
        //If user wants to end session
        $("#exit").click(function() {
            var exit = confirm("Are you sure you want to quit Black Chat?");
            if (exit == true) {
                window.location = 'index.php?logout=true';
            }
        });

        //If user submits the form
        $("#submitmsg").click(function() {
            var clientmsg = $("#usermsg").val();
            $.post("post.php", {
                text: clientmsg
            });
            $("#usermsg").attr("value", "");
            return false;
        });



        //Load the file containing the chat log
        function loadLog() {
            var oldscrollHeight = $("#chatbox").attr("scrollHeight") - 20; // höhe vor request scrollen
            $.ajax({
                url: "chatlog.html",
                cache: false,
                success: function(html) {
                    $("#chatbox").html(html); //chat log ins chatfenster einfügen

                    //Auto-scroll			
                    var newscrollHeight = $("#chatbox").attr("scrollHeight") - 20;
                    if (newscrollHeight > oldscrollHeight) {
                        $("#chatbox").animate({
                            scrollTop: newscrollHeight
                        }, 'normal'); //Autoscroll ans page ende
                    }
                },
            });
        }
        setInterval(loadLog, 2000); // lädt alle 2000ms neu
    });
    </script>
    <?php
}

?>

</body>

</html>

</html>