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
    header('location:' . VIRTUAL_PATH . 'surveys/index.php');
}
//sql statement to select individual item
$sql = "select Title,Description from srv_surveys where surveyID = " . $myID;
//---end config area --------------------------------------------------

$foundRecord = FALSE; # Will change to true, if record found!
   
//Put class code here
$mySurvey = new Survey($myID);

dumpDie($mySurvey);

if($foundRecord)
{#only load data if record found
	$config->titleTag = $Title . " survey"; #overwrite PageTitle with Muffin info!
}
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

if($foundRecord)
{#records exist - show survey!
    echo '
    <h3 align="center"> ' . $Title . '</h3>
    <p><b>Description:</b> ' . $Description . '</p>
    
    ';
}else{//no such survey!
    echo '<h3 align="center">No such survey</h3>';     
}
  echo '<div align="center"><a href="' . VIRTUAL_PATH . 'surveys/index.php">Back</a></div>';
get_footer(); #defaults to theme footer or footer_inc.php

class Survey{
    public $Title = '';
	public $Description = '';
	public $SurveyID =0;	
    public $isValid = false;
    public $Questions = array();
    public function __construct($id){
        //forcibly cast the data to an int
        $id = (int)$id;
        
		$sql = "select Title, Description from srv_surveys where SurveyID = " . $id;
        # connection comes first in mysqli (improved) function
        $result = mysqli_query(IDB::conn(),$sql) or die(trigger_error(mysqli_error(IDB::conn()), E_USER_ERROR));
    
        if(mysqli_num_rows($result) > 0)
        {#records exist - process
            $this->isValid = TRUE;//survey exists	
            while ($row = mysqli_fetch_assoc($result))
            {
                $this->Title = dbOut($row['Title']);
                $this->Description = dbOut($row['Description']);
            }
        }
        @mysqli_free_result($result); # We're done with the data!
        //Add Question objuects here
        $sql = "select QuestionID, Question, Description  from srv_questions where SurveyID = " . $id;
        # connection comes first in mysqli (improved) function
        $result = mysqli_query(IDB::conn(),$sql) or die(trigger_error(mysqli_error(IDB::conn()), E_USER_ERROR));
    
        if(mysqli_num_rows($result) > 0)
        {#records exist - process
            $this->isValid = TRUE;//survey exists	
            while ($row = mysqli_fetch_assoc($result))
            {
              $this->Questions[] = new Question(
                  dbOut($row['QuestionID']),
                  dbOut($row['Question']),
                  dbOut($row['Description'])
                  );
            }
        }
        @mysqli_free_result($result); # We're done with the data!
	}//end Survey contructor
}//end Survey Class

class Question
{
    public $QuestionID = 0;
    public $Text = '';
    public $Description = '';
    public function __construct($QuestionID,$Text, $Description)
    {
        $this->QuestionID = $QuestionID;
        $this->Text = $Text;
        $this->Description = $Description;
    }//end Question constructor
    
}//end Question Clas