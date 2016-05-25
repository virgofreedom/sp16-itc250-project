<?php
/**
 * news.php is a list/view app with a pager that shows news items drawn from an RSS feed.
 * 
 * @package news_aggregator
 * @author Ian Bryan, Rattana Neak, Travis Wichtendahl <traviswichtendahl@gmail.com>
 * @version 1.0 2016/05/12
 * @link http://www.twichtendahl.com/
 * @license http://www.apache.org/licenses/LICENSE-2.0
 * @see 
 * @see Pager.php 
 * @todo 
 */

# Start session
session_start();

/* Force include of configuration, with configuration, pathing, error handling,
   db credentials, predefined classes, etc. */
require '../inc_0700/config_inc.php'; 
 
# SQL statement
$sql = "SELECT CategoryID, Category, Description FROM srv_Categories;";

#Fills <title> tag. If left empty will default to $PageTitle in config_inc.php  
$config->titleTag = 'News from around the web';

#Fills <meta> tags.  Currently we're adding to the existing meta tags in config_inc.php
$config->metaDescription = 'Our news feeds draw relevant information from elsewhere on the web. ' . $config->metaDescription;
$config->metaKeywords = 'News Feeds,RSS,XML' . $config->metaKeywords;

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

<h3 align="center">News Categories</h3> 
<?php
#reference images for pager
$prev = '<img src="' . VIRTUAL_PATH . 'images/arrow_prev.gif" border="0" />';
$next = '<img src="' . VIRTUAL_PATH . 'images/arrow_next.gif" border="0" />';

# Create instance of new 'pager' class
$myPager = new Pager(2,'',$prev,$next,'');
$sql = $myPager->loadSQL($sql);  #load SQL, add offset

# connection comes first in mysqli (improved) function
$result = mysqli_query(IDB::conn(),$sql) or die(trigger_error(mysqli_error(IDB::conn()), E_USER_ERROR));

if(mysqli_num_rows($result) > 0)
{#records exist - process
	if($myPager->showTotal()==1){$itemz = "news category";}else{$itemz = "news categories";}  //deal with plural
    echo '<div align="center">We have ' . $myPager->showTotal() . ' ' . $itemz . '!</div>';
	while($row = mysqli_fetch_assoc($result))
	{# process each row
         echo '<div align="center"><a href="' . VIRTUAL_PATH . 'news/news_view.php?id=' . (int)$row['CategoryID'] . '">' . dbOut($row['Category']) . '</a>: ' . dbOut($row['Description']);
         echo '</div>';
	}
	echo $myPager->showNAV(); # show paging nav, only if enough records	 
}else{#no records
    echo "<div align=center>There are currently no news categories.</div>";	
}
@mysqli_free_result($result);

get_footer(); #defaults to theme footer or footer_inc.php
?>