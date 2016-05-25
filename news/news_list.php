<?php
/**
 * survey_list.php works with survey_view.php to create a list/view app
 *
 * The difference between demo_list.php and demo_list_pager.php is the reference to the 
 * Pager class which processes a mysqli SQL statement and spans records across multiple  
 * pages. 
 *
 * 
 * @package SP16-SurveySez
 * @author Piano Hagens <pianohagens@gmail.com>
 * @version 1.0 2016/05/12
 * @link http://www.pianohagens.com/
 * @license http://www.apache.org/licenses/LICENSE-2.0
 * @see survey_view.php
 * @see Pager.php 
 * @todo none
 */

  
require '../inc_0700/config_inc.php'; #provides configuration, pathing, error handling, db credentials 
 
if(isset($_GET['id'])){
    $id = $_GET['id'];
    $condition = "Where CategoryID='$id'";
}else{
    $condition = "";
}
# SQL statement
$sql = "select * from srv_News $condition";
$count = "";
setlocale(LC_MONETARY,"en_US");
#Fills <title> tag. If left empty will default to $PageTitle in config_inc.php  
$config->titleTag = 'The Best games in Seattle';

#Fills <meta> tags.  Currently we're adding to the existing meta tags in config_inc.php
$config->metaDescription = 'All the Best game you can find in seatlle. ' . $config->metaDescription;
$config->metaKeywords = 'Games, Best, Seattle, Shooter, Sports'. $config->metaKeywords;

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

# connection comes first in mysqli (improved) function
$result = mysqli_query(IDB::conn(),$sql) or die(trigger_error(mysqli_error(IDB::conn()), E_USER_ERROR));
$count = mysqli_num_rows($result);
?>
<h3 align="left"><?=$count?> Shooter Game Results </h3>

<?php
if (isset($_GET['cat'])){
        $cat = $_GET['cat'];
    }
#reference images for pager
$prev = '<img src="' . VIRTUAL_PATH . 'images/arrow_prev.gif" border="0" />';
$next = '<img src="' . VIRTUAL_PATH . 'images/arrow_next.gif" border="0" />';

# Create instance of new 'pager' class
$myPager = new Pager(10,'',$prev,$next,'');
$sql = $myPager->loadSQL($sql);  #load SQL, add offset



if(mysqli_num_rows($result) > 0)
{#records exist - process
	if($myPager->showTotal()==1){$itemz = "item";}else{$itemz = "items";}  //deal with plural
    
	while($row = mysqli_fetch_assoc($result))
	{# process each row
         echo '<div class="col-sm-6 col-md-4 col-lg-3">
                    <div class="col-sm-12">
                        <a href="' . VIRTUAL_PATH . 'news/news_view.php?id='.(int)$row['NewsID'].'&cat='.$cat .'">' . dbOut($row['Title']) . '</a></br>
                    </div>
                ';
         echo '</div>';
	}
	echo $myPager->showNAV(); # show paging nav, only if enough records	 
}else{#no records
    echo "<div align='center'>They are currently no items!</div>";	
}
@mysqli_free_result($result);

get_footer(); #defaults to theme footer or footer_inc.php
?>
