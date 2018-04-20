<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Tubes 3 STIMA</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <style>
    .error {color: #FF0000;}
    </style>
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

    <h1>SPAM Detector for Twitter</h1>
    
    <form name="form" action="" method="post">
        Topic: <input type="text" name="topic">
        <span class="error">* <?php echo $topicErr;?></span>
        <br><br> 
        Keyword: <input type="text" name="keyword">
        <span class="error">* <?php echo $keywordErr;?></span>
        <br><br>
        Algorithm:
        <input type="radio" name="algorithm" <?php if (isset($algorithm) && $algorithm=="KMP") echo "checked";?> value="KMP">KMP
        <input type="radio" name="algorithm" <?php if (isset($algorithm) && $algorithm=="Boyer-Moore") echo "checked";?> value="Boyer-Moore">Boyer-Moore
        <input type="radio" name="algorithm" <?php if (isset($algorithm) && $algorithm=="Regex") echo "checked";?> value="Regex">Regex  
        <span class="error">* <?php echo $algorithmErr;?></span>
        <br><br>
        <input type="submit">
    </form>

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
                echo "<pre>$output</pre>";
            } else if ($_POST['algorithm']=="KMP") {
                echo "Algorithm: KMP<br>";
                
                $output = (shell_exec("python KMP.py $chosentopic $key"));
                echo "<pre>$output</pre>";
            } else if ($_POST['algorithm']=="Regex") {
                echo "Algorithm: Regex<br>";
                
                $output = (shell_exec("python regex.py $chosentopic $key"));
                echo "<pre>$output</pre>";
            }
        }
       
    ?>
</body>
</html>