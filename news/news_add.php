<?php
require '../inc_0700/config_inc.php'; #provides configuration, pathing, error handling, db credentials
include_once INCLUDE_PATH . 'admin_only_inc.php'; #session protected page - level is defined in $access var
get_header();
//news_add.php
if(!isset($_POST['submit']))
{//show form to add new feed
# shows details from a single customer, and preloads their first name in a form.
		

	echo '<h3 align="center">' . smartTitle() . '</h3>
	<h4 align="center">Add News Feed</h4>
	<form action="' . THIS_PAGE . '" method="post" >
	<table align="center">
	   <tr><td align="right">Categoy</td>
		   	<td>
		   		<input type="text" name="Category" />
		   	
		   	</td>
	   </tr>
	   <tr><td align="right">Feed Title</td>
		   	<td>
		   		<input type="text" name="FeedTtitle" />
		   		
		   	</td>
	   </tr>
	   
	   <tr>
	   		<td align="center" colspan="2">
	   			<input type="submit" value="Add Feed" name="submit">
	   		</td>
	   </tr>
	</table>    
	</form>
	
	';
	  
}else{//save the data into databse

    $cat = strtolower($_POST['Category']);
    $feed = strtolower($_POST['FeedTtitle']);
    $iConn = IDB::conn();//must have DB as variable to pass to mysqli_real_escape() via iformReq()

    //check if the category exist or not 
    $sql = "select LCASE(Category),CategoryID from srv_Categories Where LCASE(Category)='$cat'";
    $result = mysqli_query(IDB::conn(),$sql) or die(trigger_error(mysqli_error(IDB::conn()), E_USER_ERROR));
    $count = mysqli_num_rows($result);
    $CatID="";
    if ($count > 0)
    {//Category exist already
        //get the CategoryID
        while ($row = mysqli_fetch_array($result)) {
            $CatID = $row['CategoryID'];
        }
        //check the feed title
        $sql_feed = "select LCASE(Title) from srv_News Where CategoryID=$CatID AND LCASE(Title)='$feed'";
        $result_feed = mysqli_query(IDB::conn(),$sql_feed) or die(trigger_error(mysqli_error(IDB::conn()), E_USER_ERROR));
        $count_feed = mysqli_num_rows($result_feed); 
        if ($count_feed > 0){
            $error  = "This category and the feed already existing. Please add another one";
        }else{//Add to srv_News only
            $sql_News = "INSERT INTO srv_News (CategoryID, Title) VALUES ('$CatID','$feed')";    
            @mysqli_query($iConn,$sql_News) or die(trigger_error(mysqli_error($iConn), E_USER_ERROR));
            $error = "The new feed has added successfully";
        }
    }else{
    //Add to srv_Categories 
    $sql_Cat = "INSERT INTO srv_Categories (Category) VALUES ('$cat')";
    
    @mysqli_query($iConn,$sql_Cat) or die(trigger_error(mysqli_error($iConn), E_USER_ERROR));
    //get the ID from the new Category
    $sql = "select LCASE(Category),CategoryID from srv_Categories Where LCASE(Category)='$cat'";
    $result = mysqli_query(IDB::conn(),$sql) or die(trigger_error(mysqli_error(IDB::conn()), E_USER_ERROR));
    while ($row = mysqli_fetch_array($result)) {
            $CatID = $row['CategoryID'];
        }
        //Add to srv_News
    echo $sql_News = "INSERT INTO srv_News (CategoryID, Title) VALUES ('$CatID','$feed')";
    
    @mysqli_query($iConn,$sql_News) or die(trigger_error(mysqli_error($iConn), E_USER_ERROR));
    $error = "The new feed has added successfully";
    }
    //build string for SQL insert with replacement vars, %s for string, %d for digits 
    echo $error;
    
}
echo '<div align="center"><a href="' . THIS_PAGE . '">Exit Add Form</a></div>';
get_footer();