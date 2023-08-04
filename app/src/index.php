<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tim's blog</title>
    <link
  rel="stylesheet"
  href="stylesheet.css">
    
  <script async src="https://stats.kicker.dev/script.js" data-website-id="183095cc-1d82-4fb4-a957-87736566ea78"></script>

</head>


<?php
    require('blogloader.php');
    require_once __DIR__.'/vendor/autoload.php';
    //include('spyc.php');
    //use Symfony\Component\Yaml\Yaml;
	$info = get_site_info();
    $posts = get_blog_posts();

	
?>


<body>
<div class=firstdiv>
    <div class="card">
		<div class="row">
			<div class="photo-container">
				<img class="headshot" src="https://avatars.githubusercontent.com/u/33966128?v=4" />
			</div>
			<div class="text-container">
			<div>
				<h2 style="margin-bottom:20px;"><?php echo $info->title ?></h2>

				<p><?php echo $info->theme_info; ?></p>

				<p><?php echo $info->theme_desc; ?> Also, this is the "minimal/retro/web1.0" version of my blog btw. But there should be no difference at all (except some minor quoting errors)</p>

				
				<a href="https://tim.kicker.dev">Modern theme</a><br> <br>
				<a href="mailto:<?php echo $info->theme_email; ?>">Mail</a><br>
				<a href="<?php echo $info->github; ?>">Github</a><br>
				<a href="<?php echo $info->linkedin; ?>">LinkedIn</a><br>
				<a href="<?php echo $info->twitter; ?>">Twitter</a><br>
			  </p>
			</div>
		  </div>
		</div>
	  </div>

	<hr />
	<div style="text-align: center;">
		<a href="index.php" style="text-decoration: underline;">Home</a>
		<a href="about.php">About</a> |
		<a href="faq.php">Faq</a> |
		<a href="https://tim.kicker.dev">Modern blog</a> |
        <a href="https://tim.kicker.dev/rss" style="color: #ffb800">RSS</a>
	</div>
	<hr />


	<h3 style="margin-bottom:0.25em;">Posts</h3>
	<ul>
		
		<?php
			foreach ($posts as $post){
				$post_line = '<li>' . $post->date . ': ' .' <a href="blog_post.php?entry='. $post->file_name . '">';
				$post_line = $post_line . $post->title;
				$post_line = $post_line  . '</a>'. '</li>';
				echo $post_line;
			}
		?>
		
	</ul>

	<hr style="margin-top:5px;"><br><br>
</div>
</body>
</html>
