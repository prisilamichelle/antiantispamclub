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
            echo "Topic: $chosentopic <br>";
            echo "Keyword: $key <br>";
            if ($_POST['algorithm']=="Boyer-Moore") {
                echo "Algorithm: Boyer-Moore<br>";
                
                $output = (shell_exec("python boyermoore.py $chosentopic $key"));

                $file = "data.txt";
                $array = json_decode(file_get_contents($file),true);

                $num = 0;
                foreach ($array as $key => $value) {
                    $num = $num + 1;
                    echo $num;

                    foreach ($value as $arraykey => $arrayvalue) {
                        echo "<pre>$arrayvalue</pre>";
                    }
                }   
            } else if ($_POST['algorithm']=="KMP") {
                echo "Algorithm: KMP<br>";
                
                $output = (shell_exec("python KMP.py $chosentopic $key"));

                $file = "data.txt";
                $array = json_decode(file_get_contents($file),true);

                $num = 0;
                foreach ($array as $key => $value) {
                    $num = $num + 1;
                    echo $num;

                    foreach ($value as $arraykey => $arrayvalue) {
                        echo "<pre>$arrayvalue</pre>";
                    }
                }
            } else if ($_POST['algorithm']=="Regex") {
                echo "Algorithm: Regex<br>";
                
                $output = (shell_exec("python regex.py $chosentopic $key"));

                $file = "data.txt";
                $array = json_decode(file_get_contents($file),true);

                $num = 0;
                foreach ($array as $key => $value) {
                    $num = $num + 1;
                    echo $num;

                    foreach ($value as $arraykey => $arrayvalue) {
                        echo "<pre>$arrayvalue</pre>";
                    }
                }
            }
        }
       
    ?>
</body>
</html>