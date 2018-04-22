<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Tubes 3 STIMA</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" media="screen" href="webstyle.css" />
</head>
<body>

    <?php
    $keywordErr=$algorithmErr=$topicErr="";
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
            if (empty($_POST['topic'])) {
                $topicErr = "Topic is required";
            }
            if (empty($_POST['keyword'])) {
                $keywordErr = "Keyword is required";
            } 
            if (empty($_POST['algorithm'])) {
                $algorithmErr = "Algorithm is required";
            }
        }
    ?>
    <?php
        define("ROOT_URI","http://localhost:8080/");
    ?>
    <div>
        <p style='text-align:right'><a href = '<?php echo ROOT_URI; ?>about.php' target = '_blank'>About Us</a></p>
    </div>
    <div class= "query">
        <div id= "query-box">
            <h1>SPAM Detector for Twitter</h1>
            <form name="form" action="" method="post">
                <input type="text" name="topic" placeholder = "Your chosen topic">
                <span class="error"> <?php echo $topicErr;?></span>
                <br><br> 
                <input type="text" name="keyword" placeholder = "Your spam word">
                <span class="error"> <?php echo $keywordErr;?></span>
                <br><br>
                <h2>Algorithm:</h2>
                <input type="radio" name="algorithm" <?php if (isset($algorithm) && $algorithm=="KMP") echo "checked";?> value="KMP" id = "KMP"><label class="choice" for="KMP">KMP</label>
                <br>
                <input type="radio" name="algorithm" <?php if (isset($algorithm) && $algorithm=="Boyer-Moore") echo "checked";?> value="Boyer-Moore" id = "Boyer-Moore"><label class="choice" for="Boyer-Moore">Boyer-Moore</label>
                <br>
                <input type="radio" name="algorithm" <?php if (isset($algorithm) && $algorithm=="Regex") echo "checked";?> value="Regex" id= "regex"><label class="choice" for="regex">Regex</label>
                <br>
                <span class="error"> <?php echo $algorithmErr;?></span>
                <br><br>
                <input type="submit">
            </form>
        </div>
    </div>

    <br><br>
    <br><br>

    <?php
        function encodeURIComponent($str) {
            $revert = array('%21'=>'!', '%2A'=>'*', '%27'=>"'", '%28'=>'(', '%29'=>')');
            return strtr(rawurlencode($str), $revert);
        }
        if (empty($_POST['topic']) or empty($_POST['keyword']) or empty($_POST['algorithm'])) {
            
        } else {
            $key = $_POST['keyword'];
            $chosentopic = $_POST['topic'];
            echo "<div><h2>Topic: $chosentopic <br></h2></div>";
            echo "<div><h2>Keyword: $key <br></h2></div>";
            if ($_POST['algorithm']=="Boyer-Moore" or $_POST['algorithm']=="KMP" or $_POST['algorithm']=="Regex") {
                if ($_POST['algorithm']=="Boyer-Moore") {
                    echo "<div><h2>Algorithm: Boyer-Moore<br></h2></div>";
                    
                    $output = (shell_exec("python boyermoore.py $chosentopic $key"));
    
                    $file = "data.txt";
                    $array = json_decode(file_get_contents($file),true);  
                } else if ($_POST['algorithm']=="KMP") {
                    echo "<div><h2>Algorithm: KMP<br></h2></div>";
                    
                    $output = (shell_exec("python KMP.py $chosentopic $key"));
    
                    $file = "data.txt";
                    $array = json_decode(file_get_contents($file),true);
                } else if ($_POST['algorithm']=="Regex") {
                    echo "<div><h2>Algorithm: Regex<br></h2></div>";
                    
                    $output = (shell_exec("python regex.py $chosentopic $key"));
    
                    $file = "data.txt";
                    $array = json_decode(file_get_contents($file),true);
                }
                echo "<br>";
                echo "<br>";
                echo "<br>";
                echo "<hr />";
                echo "<hr />";
                echo "<br>";
                echo "<br>";
                $num = 0;
                foreach ($array as $key => $value) {
                    $num = $num + 1;
                    // echo $num;
                    
                    $i=0;
                    
                    foreach ($value as $arraykey => $arrayvalue) {
                        switch ($i) {
                            case 0:
                            $time = $arrayvalue;
                            break;
                            case 1:
                            $id = $arrayvalue;
                            break;
                            case 2:
                            $image = $arrayvalue;
                            break;
                            case 3:
                            $username = $arrayvalue;
                            break;
                            case 4:
                            $name = $arrayvalue;
                            break;
                            case 5:
                            $text = $arrayvalue;
                            break;
                            case 6:
                            $spam = $arrayvalue;
                            break;
                        }
                        $i++;
                        // echo "<pre>$arrayvalue</pre>";
                    }
                    echo "<div class='overflow-hidden'>";
                    // user data
                    echo "<div><h2>{$num}. {$name}</h2></div>";
                    echo "<div id=tweet_box>";
                    echo "<div class='left'>";
                    echo "<span class = 'img-thumbnail'><img src='{$image}' height=100px width=100px/></span>";
                    echo "<div><span class = 'username'><a href='https://twitter.com/{$username}' target='_blank' >@{$username}</a></span></div>";
                    echo "</span>";
                    // show tweet content
                    echo "<div class='tweet-text'>";
                    
                    // show name and screen name
                    
                    echo "<p>{$name}</p>";
                    // echo "<span class='color-gray'>@{$username}</span>";
                    
                    echo "</div>";
                    echo "</div>";
                    
                    // show tweet text
                    echo "<div class='right'>";
                    echo "<span class='tweet'>{$text}</span>";
                    echo "</div>";
                    echo "<div id=timebox>";
                    echo "<div class='time'>{$time}</div>";
                    echo "</div>";
                    echo "<div class='logo'>";
                    $logo ='https://www.am-pm.co.uk/wp-content/uploads/2011/02/twitter-icon.gif';
                    echo "<img src='{$logo}' width=70px />";
                    echo "</div>";
                    if ($spam != " ") {
                        echo "<div class='bottom'>";
                        echo "{$spam}";
                        echo "</div>";
                        echo "<br>";
                        echo "<br>";
                    }
                    echo "</div>";
                    echo "<div >";
                        // echo "<hr />";
                    echo "</div>";
                }
            }
        }
        
    ?>
        
</body>
</html>