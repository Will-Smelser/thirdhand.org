<?php
require_once 'includes/base.php';?>
<?php require_once('Connections/database_functions.php'); ?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Welcome to The Third Hand</title>
<link href="css_yb_standard.css" rel="stylesheet" type="text/css" />
<style type="text/css">
<!--
.style8 {font-style: italic; font-size: 18px;}
.style9 {font-size: 16px}
-->
</style>
</head>

<body class="yb_standard">
<table width="760" border="0" align="center" cellpadding="1" cellspacing="0">
  <tr>
    <td><p align="right"><a href="shop_log.php">Current Shop</a> | <a href="start_shop.php"> All Shops</a> | <a href="contact_add_edit_select.php">Edit Contact Info</a> | <a href="stats.php">Statistics</a> | <a href="login.php">Admin Login / Logout</a></p>
      <p><span class="yb_heading2">Welcome to The Third Hand Bicycle Cooperative</span></p>
      <p>Here are a few things to know about using the shop:</p>
      <ul>
        <li><span class="yb_heading3red">This is Your Community Bike Shop; it is free of charge and open to the public</span>, providing a space for people to work on bikes, and learn bike mechanics skills.</li>
      </ul>
      <ul>
        <li>The Third Hand is an all-volunteer non-profit organization  <span class="yb_heading3red">entirely supported by volunteer time, part donations, and money  donations</span>. </li>
      </ul>
      <ul>
        <li><span class="yb_heading3red">We expect that you volunteer time back  to the project</span> equal to the time in the shop spent on personal projects to leave the project a better place than you found it. </li>
      </ul>
      <ul>
        <li>If you are unable to contribute time to the project <span class="yb_heading3red">we suggest  a $5 donation for personal use of the shop</span> in addition to any donations made for  parts. </li>
      </ul>
      <ul>
        <li><span class="yb_heading3red">Donations go towards</span> shop tools and supplies as well as  helping to build a better Cooperative.</li>
      </ul>
      <ul>
        <li><span class="yb_heading3red">To get started,</span> just sign-in and  talk to one of the coordinators. <span class="yb_heading3red">Make sure to sign-out</span> when you are done. </li>
      </ul>
      <table height="40" border="1" align="center" cellpadding="1" cellspacing="0">
      <tr align="center">
        <td width="187"><span class="style8"><span class="style9"><a href="<?php echo PAGE_EDIT_CONTACT; ?>?contact_id=new_contact">First Time User</a></span> <br />
        </span><span class="yb_standardCENTERred">Fill out intial information </span></td>
        <td width="195"><span class="style8"><span class="style9"><a href="shop_log.php">Sign In</a> to Get Started</span><br /> 
          </span><span class="yb_standardCENTERred">Talk to a coordinator</span></td>
        <td width="203"><span class="style8"><span class="style9"><a href="shop_log.php">Sign Out</a> Before Leaving</span><br /> 
          </span><span class="yb_standardCENTERred">Workspace cleaned up?</span></td>
        <td width="155"><span class="style8"><span class="style9"><a href="survey.php"> Take Our Survey!</a></span><br />
        </span><span class="yb_standardCENTERred">How are  we doing?</span></td>
      </tr>
    </table>
    <p><br />
      <span class="yb_pagetitle">Learn More</span>:<br />
        <span class="yb_heading3red">THBC Info:   </span><a href="http://thirdhand.org/joom/index.php?option=com_frontpage&Itemid=1" target="_blank">Third Hand Home Page</a> | <a href="http://thirdhand.org/joom/index.php?option=com_content&task=view&id=13&Itemid=26" target="_blank">About THBC</a> | <a href="http://thirdhand.org/joom/index.php?option=com_gcalendar&Itemid=48" target="_blank">Shop Schedule </a> | <a href="http://thirdhand.org/joom/index.php?option=com_content&task=blogcategory&id=15&Itemid=43" target="_blank">Meeting Minutes</a> | <a href="http://thirdhand.org/joom/index.php?option=com_content&task=view&id=23&Itemid=33" target="_blank">Shop Services</a><span class="yb_heading3red"><br />
        Giving Back:</span> <a href="http://thirdhand.org/joom/index.php?option=com_rsform&Itemid=49" target="_blank">Volunteering at THBC</a> | <a href="http://thirdhand.org/joom/index.php?option=com_gcalendar&Itemid=48" target="_blank">Volunteer Shops</a> | <a href="http://thirdhand.org/joom/index.php?option=com_content&task=blogcategory&id=1&Itemid=39" target="_blank">News</a> | <a href="http://thirdhand.org/joom/index.php?option=com_content&task=view&id=18&Itemid=31" target="_blank">Donating Online</a><br />
    </p>
    </td>
  </tr>
</table>
</body>
</html>
