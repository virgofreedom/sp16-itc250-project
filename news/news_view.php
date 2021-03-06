<?php
/**
 * survey_view.php works with index.php to create a list/view app
 *
 * The difference between demo_list.php and demo_list_pager.php is the reference to the 
 * Pager class which processes a mysqli SQL statement and spans records across multiple  
 * pages. 
 * 
 * @package SP16-SurveySez
 * @author Piano Hagens <pianohagens@gmail.com>
 * @version 1.0 2016/05/12
 * @link http://www.pianohagens.com/
 * @license http://www.apache.org/licenses/LICENSE-2.0
 * @see index.php
 * @see Pager.php 
 * @todo none
 */
# '../' works for a sub-folder.  use './' for the root  
require '../inc_0700/config_inc.php'; #provides configuration, pathing, error handling, db credentials
 
# check variable of item passed in - if invalid data, forcibly redirect back to index.php page
if(isset($_GET['id']) && (int)$_GET['id'] > 0){#proper data must be on querystring
	 $myID = (int)$_GET['id']; #Convert to integer, will equate to zero if fails
}else{
	//myRedirect(VIRTUAL_PATH . "demo/demo_list.php");
    header('location:' . VIRTUAL_PATH . 'news/index.php');
}

//---end config area --------------------------------------------------


   
//Put class code here
$myNews = new News($myID);



/*
$config->metaDescription = 'Web Database ITC281 class website.'; #Fills <meta> tags.
$config->metaKeywords = 'SCCC,Seattle Central,ITC281,database,mysql,php';
$config->metaRobots = 'no index, no follow';
$config->loadhead = ''; #load page specific JS
$config->banner = ''; #goes inside header
$config->copyright = ''; #goes inside footer
$config->sidebar1 = ''; #goes inside left side of page
$config->sidebar2 = ''; #goes inside right side of page
$config->nav1["page.php"] = "New Page!"; #add a new page to end of nav1 (viewable this page only)!!
$config->nav1 = array("page.php"=>"New Page!") + $config->nav1; #add a new page to beginning of nav1 (viewable this page only)!!
*/
# END CONFIG AREA ---------------------------------------------------------- 
get_header(); #defaults to theme header or header_inc.php
if($myNews->isValid)    {
    #feed stream exists - show feed!
    if (isset($_GET['cat']))    {
        $feed = $_GET['cat']."%20".$myNews->Title;
    }
$request = "https://news.google.de/news/feeds?pz=1&cf=all&ned=English&hl=US&output=rss&q=$feed";
session_open();//start session
$set_cache = FALSE;
if(isset($_SESSION['feeds'])){
    echo 'have cache';
    if(date('Y-m-d') == $_SESSION['feeds']['date'] && $_GET['cat'] == $_SESSION['feeds']['category']){
        $set_cache = FALSE;
        $response = $_SESSION['content'];
    }elseif (date('Y-m-d')!= $_SESSION['feeds']['date'] && $_GET['cat'] == $_SESSION['feeds']['category']) {
        # code...
    }
    dumpDie($_SESSION['feeds']);
    //$response = session_read('feeds');
}else{
    echo 'set cache';
    $response = file_get_contents($request);
    $data = array(
        'category'=> $_GET['cat'],
        'date'=>date('Y-m-d'),
        'content'=>$response
    );
    $_SESSION['feeds'] = $data;
}
session_close();//end session
    
    

    
    $xml = simplexml_load_string($response);
    
    print '<h1>' . $xml->channel->title . '</h1>';
    
foreach($xml->channel->item as $News)   {
    echo '
    <h3 align="left"> ' . $News->title .'</h3>
    <div class="col-sm-12">
        <a href="' . $News->link . '">' . $News->title . '</a>
        <p>' . $News->description . '</p>
        <p>'.$News->copyright.'</p>
   </div>';
    
   } 
    
    
}   else    {
    //no such category or feed is not valid!
    echo '<h3 align="center">No such Category</h3>';     
}
  echo '<div align="center"><a href="' . VIRTUAL_PATH . 'news/index.php">Back</a></div>';

get_footer(); #defaults to theme footer or footer_inc.php



class News  {
    public $Title = '';
	public $Description = '';
	public $NewsID =0;
    
    function __construct($id)   {
        //forcibly cast the data to an int
        $id = (int)$id;
        
		$sql = "select * from srv_News where NewsID = " . $id;
        
        # connection comes first in mysqli (improved) function
        $result = mysqli_query(IDB::conn(),$sql) or die(trigger_error(mysqli_error(IDB::conn()), E_USER_ERROR));
    
        if(mysqli_num_rows($result) > 0)    {
            #records exist - process
            $this->isValid = TRUE;	
            while ($row = mysqli_fetch_assoc($result))  {
                $this->Title = dbOut($row['Title']);
            }
        }
        
    # We're done with the data!
    @mysqli_free_result($result); 
        
	}//end News contructor
    
}//end News Class



