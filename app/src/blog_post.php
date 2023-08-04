<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tim's blog</title>
    <link
  rel="stylesheet"
  href="stylesheet.css"
>

<script async src="https://stats.kicker.dev/script.js" data-website-id="183095cc-1d82-4fb4-a957-87736566ea78"></script>
    
</head>
<body>
<div class=firstdiv>
<hr />
<div style="text-align: center;">
		<a href="index.php" style="text-decoration: underline;">Home</a> |
		<a href="about.php">About</a> |
		<a href="faq.php">Faq</a> |
		<a href="https://tim.kicker.dev">Modern blog</a> |
        <a href="https://tim.kicker.dev/rss" style="color: #ffb800">RSS</a>
	</div>
<?php
    require('blogloader.php');
    require_once __DIR__.'/vendor/autoload.php';
    
    $get_valid = false;

    try{
        $file_name = $_GET['entry'];
        $get_valid = true;
    }
    catch (Exception $e){
    }

    if ($get_valid){
        $entry_post = new Post();
        $entry_post->file_path = './blog/source/_posts/' . $file_name;

        echo $entry_post->get_html();
    }


?>
</div>
</body>
</html>
