<?PHP
session_start();
include_once("includes/dbClass.php");
$cmdClass = new dbClass();
$cmdClass ->getConnection();
$con = $cmdClass->getConnection();
$sql="SELECT BK_ID,BK_STOCK,BK_TITLE ,authermaster.AUTH_NAME ,PUB_NAME, BK_EDITION,BK_PAGES ,BK_BINDING,BK_PRICE ,BK_SHOPPRICE,BK_ISBNONE ,BK_ISBNTWO,BK_SHIPDAY,BK_DESC,BK_TABLECNT,BK_AUTHDETAILS,BK_IMAGE,BK_AUTH,PUB_ID,BK_CURRENCY   FROM bookmaster,publishermaster,authermaster WHERE bookmaster.BK_PUBLISHER = publishermaster.PUB_ID AND bookmaster.BK_AUTH  = authermaster.AUTH_ID AND BK_ID = ".$_REQUEST['prdid']; //select record from tha table
$res = $cmdClass->ExecuteQuery($sql);
$objBook = $cmdClass->getObject($res);

if($objBook->BK_CURRENCY == "Rs")
{
	$currency  = "US$";
	$price     = $cmdClass->getRate($objBook->BK_PRICE,"RS");
	$shopprice = $cmdClass->getRate($objBook->BK_SHOPPRICE,"RS");
}
elseif($objBook->BK_CURRENCY == "Euro")
{
	$currency  = "US$";
	$price     = $cmdClass->getRate($objBook->BK_PRICE,"EURO");
	$shopprice = $cmdClass->getRate($objBook->BK_SHOPPRICE,"EURO");
}
else
{
	$currency = $objBook->BK_CURRENCY;
	$price    = $objBook->BK_PRICE;
	$shopprice = $objBook->BK_SHOPPRICE;
}
?>
<html>
<head>
<title>Online bookshop</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="includes/onlinebook.css" rel="stylesheet" type="text/css">
</head>
<body leftmargin="0" topmargin="0">
<?PHP include("header.php"); ?>
<p class="menu">&nbsp;</p>
<tr> 
    <td align="left" valign="top">
<table width="983" border="0" cellpadding="0" cellspacing="0">
      <tr> 
        <td width="128" align="left" valign="top"> 
          <?PHP include_once("leftmenu_inner.php");?>
        </td>
        <td colspan="2" align="left" valign="top" class="menu"><table width="570" border="0" cellpadding="0" cellspacing="0">
            <tr> 
              <td height="22" colspan="2" class="homewhite"> 
                <?PHP include_once("search.php");?>
              </td>
            </tr>
            <tr> 
              <td width="27" height="22">&nbsp;</td>
              <td width="543" class="txtblack">
                <?PHP
			if(!isset($_GET['path'])){
			  echo '<a href="index.php">Home</a>&nbsp;&gt;&gt;&nbsp;Book Details';
			  }
			  ?>
              </td>
            </tr>
            <tr> 
              <td colspan="2" align="left" valign="top"><table width="570" border="0" cellpadding="0" cellspacing="0">
                  <tr> 
                    <td width="570"><table width="570" border="0" cellpadding="0" cellspacing="0">
                        <tr> 
                          <td height="53">&nbsp;</td>
                          <td valign="bottom" class="bigtitle"><table width="209" height="52" border="0" cellspacing="2">
                              <tr> 
                                <td valign="bottom" background="images/bandbook.jpg"><table width="209" border="0" cellpadding="0" cellspacing="0">
                                    <tr> 
                                      <td width="64">&nbsp;</td>
                                      <td width="145" class="wtlogin">Book Details</td>
                                    </tr>
                                  </table></td>
                              </tr>
                            </table></td>
                        </tr>
                        <tr> 
                          <td height="29">&nbsp;</td>
                          <td class="bigtitle"><?PHP echo $objBook->BK_TITLE; ?></td>
                        </tr>
                        <tr> 
                          <td width="4">&nbsp;</td>
                          <td width="566"><table width="555" border="0" cellpadding="0" cellspacing="0">
                              <tr> 
                                <td width="147" align="center" valign="middle"><img src="images/<?PHP echo $objBook->BK_IMAGE;?>" width="100" height="130"></td>
                                <td width="408" align="left" valign="top"><table width="415" border="0">
                                    <?PHP 
								   if($objBook->AUTH_NAME  !="0"){ 
								   ?>
                                    <tr> 
                                      <td width="134" height="25" class="ashlabel">Author</td>
                                      <td class="menu">:</td>
                                      <td class="txtblack"><a href="bookauther.php?auth=<?=$objBook->BK_AUTH?>"><?PHP echo $objBook->AUTH_NAME ; ?></a></td>
                                    </tr>
                                    <?PHP
									}
									if($objBook->BK_ISBNTWO !="0"){ 
									?>
                                    <tr> 
                                      <td height="24" class="ashlabel">ISBN 10 
                                        | ISBN 13</td>
                                      <td width="6" class="menu">:</td>
                                      <td width="254" class="txtblack"> <?PHP echo $objBook->BK_ISBNONE; ?> 
                                        : <?PHP echo $objBook->BK_ISBNTWO; ?></td>
                                    </tr>
                                    <?PHP
									 }if($objBook->BK_ISBNONE !="0"){
									 ?>
                                    <?PHP
									}if($objBook->PUB_NAME !="0"){
									?>
                                    <tr> 
                                      <td height="26" class="ashlabel">Publisher</td>
                                      <td class="menu">:</td>
                                      <td class="txtblack"><a href="bookpublisher.php?pub=<?=$objBook->PUB_ID?>"><?PHP echo $objBook->PUB_NAME; ?></a></td>
                                    </tr>
                                    <?PHP 
									}if($objBook->BK_PRICE !="0"){
									?>
                                    <tr> 
                                      <td height="24" class="ashlabel">List Price</td>
                                      <td class="menu">:</td>
                                      
                   <td class="txtblack">$<?PHP echo $price; ?></td>
                                    </tr>
                                    <?PHP 
									}if($objBook->BK_SHOPPRICE < $objBook->BK_PRICE && $objBook->BK_SHOPPRICE !="" && $objBook->BK_SHOPPRICE !=0 ){
									?>
                                    <tr> 
                                      <td height="23" class="ashlabel">Our Price</td>
                                      <td class="menu">:</td>
                                      
                   <td class="txtblack">$<?PHP echo $shopprice; ?></td>
                                    </tr>
                                    <?PHP 
									}if($objBook->BK_EDITION !="0"){
									?>
                                    <tr> 
                                      <td class="ashlabel">Edition</td>
                                      <td class="menu">:</td>
                                      <td class="txtblack"><?PHP echo $objBook->BK_EDITION; ?></td>
                                    </tr>
                                    <?PHP
									 } if($objBook->BK_PAGES !="0"){
									 ?>
                                    <tr> 
                                      <td height="25" class="ashlabel">Pages | 
                                        Binding</td>
                                      <td class="menu">:</td>
                                      <td class="txtblack"><?PHP echo $objBook->BK_PAGES; ?> 
                                        <span class="menu"> : </span> <?PHP echo $objBook->BK_BINDING; ?></td>
                                    </tr>
                                    <?PHP 
									}if($objBook->BK_BINDING !="0"){
									?>
                                    <?PHP 
									}
									
									if($objBook->BK_SHIPDAY !="0"){
									?>
                                    <tr> 
                                      <td height="25" class="ashlabel">Shipping 
                                        Time</td>
                                      <td class="menu">:</td>
                                      <td class="txtblack">Normaly <?PHP echo $objBook->BK_SHIPDAY; ?> 
                                        working days</td>
                                    </tr>
                                    <?PHP 
									}
									?>
                                    <tr align="center" valign="middle"> 
                                      <td colspan="3" align="left" valign="top" class="normaltxt"> 
                                        <?PHP
									 if($objBook->BK_STOCK == 0)
									 {
									  ?>
                                        <a href="add_to_cart.php?prdid=<?PHP echo $objBook->BK_ID ; ?>"><img src="images/addtocart.jpg" width="78" height="32" border="0"></a> 
                                        <?PHP
									  }
									  else
									  {
									 	echo "<a href='mailto:sales@onlinebookshop.in?subject=Purchase enquiry-".$objBook->BK_TITLE."'>This item is currently out of stock.If you would like to be notified whenever this item is back in stock,please click this link and send it across to us</a>";
									  }
									  ?>
                                      </td>
                                    </tr>
                                    <tr align="center" valign="middle"> 
                                      <td height="20" colspan="3" align="left" valign="top" class="normaltxt"><table width="303" border="0" cellpadding="0" cellspacing="0" class="redborder">
                                          <tr class="error"> 
                                            <td width="31" height="30" align="center" valign="middle"><img src="images/email.gif" width="16" height="9"></td>
                                            <td width="114"><a href="tellafriend.php" class="error">Share 
                                              with a friend</a></td>
                                            <td width="26" align="center" valign="middle"><img src="images/print.gif" width="11" height="12"></td>
                                            <td width="130"><a href="#" class="error" onClick="window.open('bookdetails_print.php?prdid=<?php echo $_REQUEST['prdid']; ?>','SearchTip','width=600,height=600,resizable=yes,scrollbars=yes')">Printer Friendly Version</a></td>
                                          </tr>
                                        </table></td>
                                    </tr>
                                  </table></td>
                              </tr>
                            </table></td>
                        </tr>
                        <?PHP 
						if(trim($objBook->BK_DESC) !="0" ){
						?>
                        <tr> 
                          <td height="30">&nbsp;</td>
                          <td class="blackbold">Description</td>
                        </tr>
                        <tr> 
                          <td rowspan="2">&nbsp;</td>
                          <td class="normaltxt"><?PHP echo stripslashes($objBook->BK_DESC); ?></td>
                        </tr>
                        <tr>
                          <td height="2" class="normaltxt"></td>
                        </tr>
                        <tr> 
                          <td></td>
                          <td height="1" bgcolor="#990000"></td>
                        </tr>
                        <?PHP 
						}
						if(trim($objBook->BK_TABLECNT) != "0" ){
						?>
                        <tr> 
                          <td height="28">&nbsp;</td>
                          <td class="blackbold">Table Of Contents</td>
                        </tr>
                        <tr> 
                          <td height="21">&nbsp;</td>
                          <td class="normaltxt"><?PHP echo stripslashes($objBook->BK_TABLECNT); ?></td>
                        </tr>
						<tr>
                          <td height="2" class="normaltxt"></td>
                        </tr>
                        <tr> 
                          <td></td>
                          <td height="1"  bgcolor="#990000" ></td>
                        </tr>
                        <?PHP
						 }
						if(trim($objBook->BK_AUTHDETAILS) != "0"){
						?>
                        <tr> 
                          <td height="31">&nbsp;</td>
                          <td class="blackbold">Author Details</td>
                        </tr>
                        <tr> 
                          <td >&nbsp;</td>
                          <td height="21" class="normaltxt"><?PHP echo stripslashes($objBook->BK_AUTHDETAILS); ?></td>
                        </tr>
						<tr>
                          <td height="2" class="normaltxt"></td>
                        </tr>
                        <tr> 
                          <td></td>
                          <td height="1"  bgcolor="#990000" ></td>
                        </tr>
						 <tr> 
                          <td></td>
                          <td height="17" ></td>
                        </tr>
                        <?PHP
						}
						 $sql="SELECT BK_ID,BK_AUTH,BK_TITLE,BK_CURRENCY,BK_SHOPPRICE,BK_IMAGE  FROM bookmaster  WHERE  BK_ID IN ( SELECT re_book FROM relatedbook WHERE rel_masterbook =".$_REQUEST['prdid'].") LIMIT 6"; //select record from tha table
   						 $res = $cmdClass->ExecuteQuery($sql);
						 $tot = $cmdClass->getNumberRows($res);
						 if( $tot>0){
						 $cnt = 0;
					
						?>
                        <tr> 
                          <td height="30">&nbsp;</td>
                          <td class="relbooktitle">Related Books</td>
                        </tr>
                        <tr> 
                          <td colspan="2"><table width="570" border="0" cellpadding="0" cellspacing="4" class="redborder">
                              <tr> 
                                <td width="1">&nbsp;</td>
                                <td width="555" height="41" align="center" valign="middle"> 
                                  <table width="286" height="195" border="0" cellpadding="0" cellspacing="0">
                                    <tr> 
                                      <?PHP
								 while($obj = $cmdClass->getObject($res)){
									
								 ?>
                                      <td width="239"> <table width="227" border="0" align="center" cellpadding="0" cellspacing="2">
                                          <tr align="center" valign="top"> 
                                            <td height="130" colspan="2"><a href="bookdetails.php?prdid=<?PHP echo $obj->BK_ID; ?>"><img src="images/<?PHP echo $obj->BK_IMAGE; ?>" alt="View Details" width="100" height="130" border="0"></a></td>
                                          </tr>
                                          <tr align="center" class="itemtitle"> 
                                            <td colspan="2"><a href="bookdetails.php?prdid=<?PHP echo $obj->BK_ID; ?>" class="itemtitle"><?PHP echo $obj->BK_TITLE; ?></a></td>
                                          </tr>
                                          <tr align="center" class="itemtitle"> 
                                            <td colspan="2">By &nbsp;<?PHP echo $obj->BK_AUTH ; ?></td>
                                          </tr>
                                          <tr class="prred"> 
                                            <td width="138" class="error"><strong>&nbsp;&nbsp;Price 
                                              : <?PHP echo $obj->BK_CURRENCY ." ".$obj->BK_SHOPPRICE; ?></strong></td>
                                            <td width="83" align="left"><a href="add_to_cart.php?prdid=<?PHP echo $obj->BK_ID ; ?>"><img src="images/cart.jpg" alt="Add to Cart" width="25" height="20" border="0"></a></td>
                                          </tr>
                                          <tr class="prred"> 
                                            <td>&nbsp;</td>
                                            <td align="left">&nbsp;</td>
                                          </tr>
                                        </table>
                                        <?PHP
									$cnt++;
										if($cnt ==2 ){
										 $cnt =0 ;
										 echo " </tr><tr>";
										}
									 }
									  ?>
                                    </tr>
                                  </table></td>
                              </tr>
                              <?PHP } ?>
                            </table></td>
                        </tr>
                        <tr> 
                          <td colspan="2"> 
                            <?PHP include_once("footermenu.php");?>
                          </td>
                        </tr>
                      </table></td>
                  </tr>
                </table></td>
            </tr>
          </table></td>
      </tr>
      <tr align="center" valign="middle"> 
        <td colspan="3" background="images/index_05.jpg">&nbsp; </td>
      </tr>
    </table>
</body>
</html>
