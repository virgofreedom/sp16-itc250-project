<?php
/**
 * index.php works with survey_view.php to create a list/view app
 * * The difference between demo_list.php and demo_list_pager.php is the reference to the 
 * Pager class which processes a mysqli SQL statement and spans records across multiple pages.  
 *  * @package SP16-SurveySez
 * @author Piano Hagens <pianohagens@gmail.com>
 * @version 1.0 2016/05/12
 * @link http://www.pianohagens.com/
 * @license http://www.apache.org/licenses/LICENSE-2.0
 * @see survey_view.php
 * @see Pager.php 
 * @todo none
 */  
require '../inc_0700/config_inc.php'; #provides configuration, pathing, error handling, db credentials 
 
# SQL statement
$sql = "select * from srv_Categories";

#Fills <title> tag. If left empty will default to $PageTitle in config_inc.php  
$config->titleTag = 'News Today';

#Fills <meta> tags.  Currently we're adding to the existing meta tags in config_inc.php
$config->metaDescription = 'News feeds from around the world brought to you here in one place. ' . $config->metaDescription;

$config->metaKeywords = 'World, Politics, International, Local, Business, Finance'. $config->metaKeywords;

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
?>

<h3 align="center">News Feeds</h3>

<?php

#reference images for pager
$prev = '<img src="' . VIRTUAL_PATH . 'images/arrow_prev.gif" border="0" />';
$next = '<img src="' . VIRTUAL_PATH . 'images/arrow_next.gif" border="0" />';

# Create instance of new 'Pager' class
$myPager = new Pager(10,'',$prev,$next,'');

$sql = $myPager->loadSQL($sql);  #load SQL, add offset

# connection comes first in mysqli (improved) function
$result = mysqli_query(IDB::conn(),$sql) or die(trigger_error(mysqli_error(IDB::conn()), E_USER_ERROR));

#records exist - process
#If the number of categories in the DB table is only 1 then show singular version
#else show the plural version of "category"
if(mysqli_num_rows($result) > 0)    {   
	   if($myPager->showTotal()==1)    {
            $itemz = "catergory";
        }   else    {
            $itemz = "catergories";
        }    
    
echo '<div align="center">We have ' . $myPager->showTotal() . ' ' . $itemz . '!</div>';
	
# process each row and display the name of each in a list (news_list.php)
while($row = mysqli_fetch_assoc($result))   {
        echo '<div align="center">
                <a href="' . VIRTUAL_PATH . 'news/news_list.php?id=' . (int)$row['CategoryID'] . '&cat='.$row['Category'].'">' . dbOut($row['Category']) . '</a> 
                </div>';
	}
    
# show paging nav, only if enough records	 
echo $myPager->showNAV('<div align="center">','</div>','index.php'); 
    
}   else    {
        # if there are no records
        echo "<div align='center'>They are currently no active catergories!</div>";	
    } #end row processing 

@mysqli_free_result($result);

get_footer(); #defaults to theme footer or footer_inc.php

?>
