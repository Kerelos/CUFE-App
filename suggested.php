<?php include('func.php'); CheckLogin(true); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Suggested TimeTable</title>
<link type="text/css" rel="stylesheet" href="files/main_stylesheets.css" />
<script src="js_func.js" language="javascript"></script>
</head>
<body>
<table id="MainTable" align="center">
    <tr>
    	<td id="Header">Suggested TimeTable</td>
    </tr>
    <tr>
    	<td height="90%">
        	<div id="Main_Body">
        		<?php 
					$SQL="SELECT Program_ID, Student_GPA FROM Student WHERE Student_ID=". getID();					
					$row=DB_Manager::Query($SQL)->fetch_assoc();
					if($row['Program_ID']==0 && $row['Student_GPA']>=2)
					{
						//Freshman
							if(isset($_POST['Slot_Group']) && !empty($_POST['Slot_Group']) && in_array($_POST['Slot_Group'], array('1','2','3','4','5','6','7','8','9')))
								printFreshman($_POST['Slot_Group']);
							else
							{
								
								$IncludedCourses="";
								$AvailableCourses=getAvailableCourses();
								
								foreach($AvailableCourses as $Course)
									$IncludedCourses=$IncludedCourses.$Course.",";
								$IncludedCourses=substr($IncludedCourses, 0, -1);
								$SQL="
										SELECT T.Slot_Group FROM Time_Slot T, Offered_In OI
										WHERE T.Slot_ID=OI.Slot_ID AND T.Slot_Status=1
										AND   OI.Course_ID IN (".$IncludedCourses.")
										GROUP BY T.Slot_Group
										HAVING Count(DISTINCT OI.Course_ID)=7;

									";
								$query=DB_Manager::Query($SQL);
								
								echo('<table id="Suggested_Controls_Table"><form id="Suggested_Form" action="suggested.php" method="post">');
									echo('<tr>');
										echo('<td><label id="login_label">Select a Group</label></td>');
									echo('</tr>');
									
									echo('<tr>');
										echo('<td><select name="Slot_Group" id="login_input">');
								
								while($row=$query->fetch_assoc())
								{
									echo('<option value="'.$row["Slot_Group"].'">'); 
										echo("Group ".$row['Slot_Group']);
									echo('</option>');
								}
									echo('</select></td>');
								echo('</tr>');
								
								echo('<tr>');
								echo('<td><input type="submit" name="submit" value="Generate" id="login_input"></td></tr></table>');
							}
					}
					else
					{
						$AvailableCourses=getAvailableCourses();
						
						$SelectedCourse;
						if(!isset($_SESSION['SuggestedSelectedCourses']))
							$_SESSION['SuggestedSelectedCourses']=array();
						if(!empty($_SESSION['SuggestedSelectedCourses']) && isset($_POST['submit']) && $_POST['submit']=="Generate")
							optimizedSchedule($_SESSION['SuggestedSelectedCourses'], array(0,1, 2, 3, 4, 5), 100);
						else
						{	
							if(isset($_POST['SelectedCourse']))
								$SelectedCourse=$_POST['SelectedCourse'];
							if(!empty($SelectedCourse) && in_array($SelectedCourse, $AvailableCourses) && !in_array($SelectedCourse, $_SESSION['SuggestedSelectedCourses']) && $_POST['submit']=="Add")
								array_push($_SESSION['SuggestedSelectedCourses'], $SelectedCourse);
								
							if(isset($_POST['RemoveCourse']) && !empty($_POST['RemoveCourse']) && in_array($_POST['RemoveCourse'], $_SESSION['SuggestedSelectedCourses']) && $_POST['submit']=="Delete")
							{
								foreach (array_keys($_SESSION['SuggestedSelectedCourses'], $_POST['RemoveCourse']) as $key)
									unset($_SESSION['SuggestedSelectedCourses'][$key]);
							}
							if(isset($_POST['submit']) && (($_POST['submit']=="Add") || $_POST['submit']=="Delete"))
								goBack();
							
							echo('<table id="Suggested_Controls_Table"><form id="Suggested_Form" action="suggested.php" method="post">');
							echo('<tr>');
								echo('<td colspan="2"><label id="login_label">Select a Course</label></td>');
							echo('</tr>');
							
							echo('<tr>');
								echo('<td colspan="2"><select name="SelectedCourse" id="login_input">');
									foreach($AvailableCourses as $Course)
									{
										if(!in_array($Course, $_SESSION['SuggestedSelectedCourses']))
										{
											echo('<option value="'.$Course.'">'); 
											
												$query=DB_Manager::Query("SELECT Course_Name FROM Course WHERE Course_ID=".$Course);
												$row=$query->fetch_assoc();
												echo($row['Course_Name']);
											echo('</option>');
										}
									}
								echo('</select></td>');
							echo('</tr>');
							
							echo('<tr>');
							echo('<td><input type="submit" name="submit" value="Add" id="login_input"></td>');
							
							if(isset($_SESSION['SuggestedSelectedCourses'])&&!empty($_SESSION['SuggestedSelectedCourses']))
							{
								echo('<td><input type="submit" name="submit" value="Delete" id="login_input"></td>');
								echo('</tr>');
								echo('<tr>');
									echo('<td colspan="2"><input type="submit" name="submit" value="Generate" id="login_input"></td>');
								echo('</tr>');
								echo('</table>');
								printSelectedSuggested();	//Print Currently Selected Courses
							}
							else
								echo('</tr></table>');
								
							echo('</form></table><br>');
						}
					}
				?>
        	</div>
        </td>
    </tr>
    </table>
</body>
</html>
