<?php include('func.php'); CheckLogin(true); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Latest News</title>
<link type="text/css" rel="stylesheet" href="files/main_stylesheets.css" />
<script src="js_func.js" language="javascript"></script>
</head>
<body>
<table id="MainTable" align="center">
    <tr>
    	<td id="Header">Latest News</td>
    </tr>
    <tr>
    	<td height="90%">
        	<div id="Main_Body">
            <?php

                $xml=("http://eng.cu.edu.eg/ar/category/credit-news/feed/");
                $xmlDoc = new DOMDocument();
                $xmlDoc->load($xml);

                //get and output "<item>" elements
                $x=$xmlDoc->getElementsByTagName('item');
                echo("<table id='Timetable_Table'>");
                for ($i=0; $i<=7; $i++) {
                  $item_title=$x->item($i)->getElementsByTagName('title')
                  ->item(0)->childNodes->item(0)->nodeValue;
                  $item_link=$x->item($i)->getElementsByTagName('link')
                  ->item(0)->childNodes->item(0)->nodeValue;
                  $item_desc=$x->item($i)->getElementsByTagName('description')
                  ->item(0)->childNodes->item(0)->nodeValue;
                  $item_date=$x->item($i)->getElementsByTagName('pubDate')
                  ->item(0)->childNodes->item(0)->nodeValue;

                  $item_date=substr($item_date, 0, -5);
                  echo("<tr>");
                  echo ("<th><a style='text-decoration:blink;' href='" . $item_link
                  . "'>" . $item_title . "</a></th>");
                  echo('</tr>');
                  echo('<tr>');
                  echo("<td style='text-align:center;'>".$item_date."</td>");
                  echo ("</tr>");
                  echo("<tr>");
                  echo ("<td style='text-align:right;'>".$item_desc."</td>");
                  echo("</tr>");
                }
                echo('</table>');

            ?>
        	</div>
        </td>
    </tr>
    </table>
</body>
</html>
