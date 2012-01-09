<?php
	class group_member_roles  extends UnitTestCase{


		public function test()
		{
			echo 

	"<script language='javascript'>
         function printpage()
          {
           window.print();
          }
        </script>

	<title>Group Member Roles</title><br><br>		
	<b>Created:</b> 2JUNE2011 <br>
	<b>Last Update:</b> 2JUNE2011<br>
	<b>Base URL:</b> http://pserguta.selican.dyndns.org	
	<b>Sub URL:</b> /groups/members/102 <br><br>		
			

	<b>Feature being tested:</b> <br><br>
	
	The group members page allows an administrator or manager to add members by name or email address.  They may then assign users to specific roles. Custom roles can be created from the group.s dashboard.<br> <br>
	
	<b>Conditional Expectations:</b> <br><br>
	If an administrator deletes a member, then he should be able to re add the member. <br>
	If only one administrator exists in a group, then he should not be able to be removed. <br>
	If there is only one administrator, then his role may not be downgraded. <br>
	If a user already exists in the group, then he should not be seen in Add User search<br> <br>

	<b>Manual Testing:</b><br><br><body>
	
	<div><input type='radio' name='pass' value='pass'><input type='radio' name='fail' value='fail'>1.	<a href ='http://pserguta.selican.dyndns.org/groups/members/102' target='_new'>Open Selenium Testing Group Members page <input type='checkbox' name='step1'  /></a></div><br>
	<div>2.	Verify only one administrator exists<input type='checkbox' name='step2'  /></div>  <br>
	<div>3.	Verify inability to remove sole administrator from group<input type='checkbox' name='step3'  /></div>  <br>
	<div>4.	Verify inability to downgrade sole administrators role<input type='checkbox' name='step4'  /></div>  <br>
	<div>5.	Click Add User<input type='checkbox' name='step5'  /></div>  <br>
	<div>6.	Click Search Users<input type='checkbox' name='step6'  /></div>  <br>
	<div>7.	Search for Selenium<input type='checkbox' name='step7'  /></div>  <br>
	<div>8.	Verify Selenium Tester does not appear<input type='checkbox' name='step8'  /></div>  <br>
	<div>9.	Go back to members page<input type='checkbox' name='step9'  /></div>  <br>
	<div>10. Remove Selenium Tester from group<input type='checkbox' name='step10'  /></div>  <br>
	<div>11. Click Add User<input type='checkbox' name='step11'  /></div>  <br>
	<div>12. Click Search Users<input type='checkbox' name='step12'  /></div>  <br>
	<div>13. Search for Selenium<input type='checkbox' name='step13'  /></div>  <br>
	<div>14. Verify Selenium User appears on list<input type='checkbox' name='step14'  /></div>  <br>
	<div>15. Add Selenium User to group<input type='checkbox' name='step15'  /></div>  <br>
	<div>16. Go back to members page<input type='checkbox' name='step16'  /></div>  <br>
	<div>17. Verify Selenium User appears on members page<input type='checkbox' name='step17'  /> </div> <br>
	<div>18. Change Selenium Tester role to Manager<input type='checkbox' name='step18'  /></div>  <br><br>
	</body>
	
	<form name='input' action='' method='get'>
	<input type='submit' value='Complete' />

	</form><br><br>
	
	<b>Manual Test Revisions:</b> <br>

	<table border='1'>
	<tr>
	<td><b>Date</b></td>
	<td><b>Description</b></td>
	<td><b>Reason</b></td>
	</tr>
	<tr>
	<td></td>
	<td></td>
	<td></td>
	</tr>
	</table><br>
		

	<b>Automated Tests:</b> <br><br>
	<b>Automated Test Revisions:</b> <br>
	<table border='1'>
	<tr>
	<td><b>Date</b></td>
	<td><b>Test</b></td>
	<td><b>Description</b></td>
	<td><b>Reason</b></td>
	</tr>
	<tr>
	<td></td>
	<td></td>
	<td></td>
	<td></td>
	</tr>
	</table><br>
		
	<br>
	<b>Reported Bugs:</b> <br>
	<table border='1'>
	<tr>
	<td><b>Date</b></td>
	<td><b>Description</b></td>
	<td><b>Fixed Date</b></td>
	<td><b>Regression Test Date</b></td>
	</tr>
	<tr>
	<td>2JUNE2011</td>
	<td>Link titled Invite Members should be deleted</td>
	<td>6JUNE2011</td>
	<td>6JUNE2011</td>
	</tr>
	</table><br><br><br> 	
	<input type='button' value='Print' onclick='printpage();' />
	<br /><br />
	
<input type='button' value='Test' onclick='printpage();' />
	
";
//$this->pass();
}}
?>
