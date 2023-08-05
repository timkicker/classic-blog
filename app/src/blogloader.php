
<?php

class SiteInfo{
  // stored in _config.yml
  public $title;
  public $description;
  public $author;
  public $subtitle;
  public $keywords;

  // stored in theme config
  public $theme_title;
  public $theme_owner;
  public $theme_email;
  public $theme_info;
  public $theme_desc;

  public $github;
  public $linkedin;
  public $twitter;
}

class Post {
  public $title;
  public $date;
  public $date_unix;
  public $file_path;
  public $file_name;
  public $thumbnail;
  public $valid;

  function get_date(){
    $string = explode(' ', $this->date);
    return $string[0];
  }

  // Methods
  function get_html() {
    $myfile = fopen($this->file_path, "r") or die("Unable to open file!");
    $markdown = fread($myfile,filesize($this->file_path));
    fclose($myfile);

    $html_file = markdown_to_html($markdown);

    // correct path
    $this->file_name = str_replace(".md","",$this->file_name);
    $html_file->html = str_replace($this->file_name."/","blog/source/_posts/".$this->file_name."/",$html_file->html);

   
    return $html_file->html;
  }
}

class HtmlFile{
  public $html;
  public $title;
  public $date;
  public $thumbnail;
}

function markdown_to_html($markdown){
  $html_file = new HtmlFile;

   // get title
   require_once "spyc.php";

   $possible_yaml = " ";
   $max = 10;
   $current = 0;
   foreach(preg_split("/((\r?\n)|(\r\n?))/", $markdown) as $line){

     if ($current > $max){
       break;
     }

     $possible_yaml = $possible_yaml . $line . "\n";
     $current++;
   }

   try{
     $data = Spyc::YAMLLoad($possible_yaml);
     $html_file->title = $data['title'];
   } catch ( Exception $e){
     //echo "error loading file " . $file . "because of " . $e;
   }

   // remove metadata
   $yaml_over = FALSE;
   $yaml_headers_seen = 0;
   $pure_markdown = "";
   foreach(preg_split("/((\r?\n)|(\r\n?))/", $markdown) as $line){
     
     if ($yaml_over){
       $pure_markdown = $pure_markdown . "\n" . $line;
     } elseif( str_contains($line, "---")){
       $yaml_headers_seen++;
     }

     if ($yaml_headers_seen >= 2){
       $yaml_over = TRUE;
     }
   } 
   $markdown = "## " . $html_file->title . "\n" . $pure_markdown;
 
     
   require_once "Parsedown.php";
   $Parsedown = new Parsedown();
   $html_file->html = $Parsedown->text($markdown);
   return $html_file;
}


function get_faq_html(){
  $file_path = './blog/source/faq/index.md';
  $myfile = fopen($file_path, "r") or die("Unable to open file!");
  $markdown = fread($myfile,filesize($file_path));
  fclose($myfile);
  $html_file = markdown_to_html($markdown);
  return $html_file->html;
}

function get_about_html(){
  $file_path = './blog/source/About/index.md';
  $myfile = fopen($file_path, "r") or die("Unable to open file!");
  $markdown = fread($myfile,filesize($file_path));
  fclose($myfile);


  


  // replace faq link
  $markdown = str_replace("https://tim.kicker.dev/faq/","faq.php",$markdown);

  // replace block quote
  $markdown = str_replace("{% codeblock lang:cs %}","```cs\n",$markdown);
  $markdown = str_replace("{% endcodeblock %}","```",$markdown);

  $html_file = markdown_to_html($markdown);
  return $html_file->html;
}

function get_site_info(){

  $site_info = new SiteInfo;
  // general config
  $config_general_path = './blog/_config.yml';
  require_once "spyc.php";
  try{
    $config_general_data = spyc_load_file($config_general_path);
  } catch ( Exception $e){
    echo "error loading general data";
  }

    

  try {

    

    $site_info->title = $config_general_data['title'];
    $site_info->subtitle = $config_general_data['subtitle'];
    $site_info->author = $config_general_data['author'];
  } catch (Exception $e) {    
  }

  // theme specific
  $config_path = './blog/themes/minima/_config.yml';
  require_once "spyc.php";
  try{
    $config_data = spyc_load_file($config_path);
  } catch ( Exception $e){echo "error loading file";}

  
  try{
    $site_info->theme_title = $config_data['title'];
    $site_info->theme_owner = $config_data['owner'];
    $site_info->theme_email = $config_data['email'];
    $site_info->theme_info = $config_data['info'];
    $site_info->theme_desc = $config_data['desc'];

    $site_info->github = $config_data['github'];
    $site_info->linkedin = $config_data['linkedin'];
    $site_info->twitter = $config_data['twitter'];
  }catch(Exception $e){}
  
  return $site_info;
  

}

function get_blog_posts(){

    $scan_path = './blog/source/_posts';
    $posts = array();
    $files = scandir($scan_path);
    
    foreach($files as $file) {
        $file_name = $file;  

        $file = $scan_path . '/'.$file;
        

        $ext = pathinfo($file, PATHINFO_EXTENSION);
        if ($ext == 'md'){
            require_once "spyc.php";

            $line = file($file);
            $possible_yaml = " ";

            for ($x = 0; $x <= 10; $x++ ){
              $possible_yaml = $possible_yaml . $line[$x];
            }

            try{
              $data = Spyc::YAMLLoad($possible_yaml);

            } catch ( Exception $e){
              //echo "error loading file " . $file . "because of " . $e;
            }
            
            $blog_post = new Post;
            $blog_post->file_name = $file_name;
            
            try {
              $blog_post->title = $data['title'];
              $blog_post->date = $data['date'];
              $blog_post->date_unix = strtotime($blog_post->date);
              //$blog_post->thumbnail = $data['thumbnail'];
              $blog_post->valid = TRUE;
              $blog_post->file_path = $file;
            } catch (Exception $e) {
                
            }
            

            array_push($posts,$blog_post);

        }
    }

    usort($posts, "sort_posts");
    return $posts;
}

function sort_posts($a, $b)
{
    if ($a->date_unix == $b->date_unix) {
        return 0;
    }
    return ($a->date_unix < $b->date_unix) ? 1 : -1;
}


?>
