<?php
require_once ($_SERVER['DOCUMENT_ROOT'] . '/includes/php/session_profiles.php');
$page_title = "IONEEC - Search";

// Update visits
$query99 = "UPDATE prog_ioneec SET nvisits = nvisits + 1, currentvisits = currentvisits + 1 WHERE prog_id='40'";
$result99 = mysql_query($query99) or trigger_error("Query: $query99\n<br>MySQL Error: " . mysql_error());

// Selected events pagination: Number of records per page
$display = 25;
// Determine where in the database to start returning results.
if (isset($_GET['page'])) {
	$page = $_GET['page'];
	$start = ($page - 1) * $display;
} else {
	$page = 1;
	$start = 0;
}

$onedisp = FALSE;
$radiosample = "";
$sample_number = '';
$page_block = '';
$query_block = '';

if (isset($_GET['submitted'])) { // Handle the form

	// Check for a search type.
	if (!empty($_GET['stype']) && $_GET['stype'] > 0) {
		$stype = $_GET['stype'];
	} else {
		$stype = FALSE;
		$display_block .= "<p><font color=\"red\">Please enter the type of search!</font></p>";
	}

	// Check for a query.
	//if (eregi ('^[[:alnum:]_\.\' \-]{1,40}$', stripslashes(trim($_POST['searchval'])))) {
	if (!empty($_GET['searchval'])) {
		$sv0 = escape_data($_GET['searchval']);
		$svy = htmlentities($_GET['searchval'], ENT_QUOTES);

		// replace special characters by spaces
		$sv1 = str_replace('\"', '', $sv0);
		$sv1 = str_replace("\'", " ", $sv1);
		$sv1 = str_replace("(", " ", $sv1);
		$sv1 = str_replace(")", " ", $sv1);
		$sv1 = str_replace("&", "and", $sv1);
		$sv1 = str_replace("$", "S", $sv1);	
		$sv1 = str_replace("@", "at", $sv1);	
		$sv1 = str_replace("-", " ", $sv1);
		$sv1 = str_replace(":", " ", $sv1);
		$sv1 = str_replace(".", " ", $sv1);
		$sv1 = str_replace("¡", " ", $sv1);
		$sv1 = str_replace("!!!!!", " ", $sv1);
		$sv1 = str_replace("!!!!", " ", $sv1);
		$sv1 = str_replace("!!!", " ", $sv1);
		$sv1 = str_replace("!!", " ", $sv1);
		$sv1 = str_replace("!", " ", $sv1);
		$sv1 = strtolower($sv1);
		
		$querymat = explode(" ", $sv1);
		$corr_query = "";
		$stopinc = 0;
		$ntotalvalues = 0;
		$replace_query = "";
		foreach ($querymat as $key => $value) {
			$length = strlen($value);
			if ($length != 0) {
				$newvalue = " " . $value;
				$ntotalvalues++;
				$lengthx = $length + 1;
				$numthere0 = strpos($newvalue, 'zero');
				$numthere1 = strpos($newvalue, 'one');
				$numthere2 = strpos($newvalue, 'two');
				$numthere3 = strpos($newvalue, 'three');
				$numthere4 = strpos($newvalue, 'four');
				$numthere5 = strpos($newvalue, 'five');
				$numthere6 = strpos($newvalue, 'six');
				$numthere7 = strpos($newvalue, 'seven');
				$numthere8 = strpos($newvalue, 'eight');
				$numthere9 = strpos($newvalue, 'nine');
				$numlen = strlen($newvalue);
				$chg = FALSE;
				if ($numthere0 && $numlen == 5) {
					$newvalue = "Z";
					$length = 1;
					$chg = TRUE;
				}
				if ($numthere1 && $numlen == 4) {
					$newvalue = "1";
					$length = 1;
					$chg = TRUE;
				}
				if ($numthere2 && $numlen == 4) {
					$newvalue = "2";
					$length = 1;
					$chg = TRUE;
				}
				if ($numthere3 && $numlen == 6) {
					$newvalue = "3";
					$length = 1;
					$chg = TRUE;
				}
				if ($numthere4 && $numlen == 5) {
					$newvalue = "4";
					$length = 1;
					$chg = TRUE;
				}
				if ($numthere5 && $numlen == 5) {
					$newvalue = "5";
					$length = 1;
					$chg = TRUE;
				}
				if ($numthere6 && $numlen == 4) {
					$newvalue = "6";
					$length = 1;
					$chg = TRUE;
				}
				if ($numthere7 && $numlen == 6) {
					$newvalue = "7";
					$length = 1;
					$chg = TRUE;
				}
				if ($numthere8 && $numlen == 6) {
					$newvalue = "8";
					$length = 1;
					$chg = TRUE;
				}
				if ($numthere9 && $numlen == 5) {
					$newvalue = "9";
					$length = 1;
					$chg = TRUE;
				}
				// Stopwords lookup
				$query20 = "SELECT replace_by FROM stopwords WHERE stopword='$value'";
				$result20 = mysql_query($query20) or trigger_error("Query: $query20\n<br>MySQL Error: " . mysql_error());
				if (mysql_num_rows($result20) == 0) {

					if ($length == 1) {
						if ($chg) {
							if ($newvalue == "Z") {
								$corr_query .= "0" . 'ררר ';
							} else {
								$corr_query .= $newvalue . 'ררר ';
							}
						} else {
							$corr_query .= $value . 'ררר ';
						}
					} elseif ($length == 2) {
						$corr_query .= $value . 'רר ';
					} elseif ($length == 3) {
						$corr_query .= $value . 'ר ';
					} else {
						$corr_query .= $value . ' ';
					}
				
				} else {
					$stopinc++;
					$row20 = mysql_fetch_array($result20, MYSQL_NUM);
					$stoparray[$stopinc] = $value;
					//$replace_by[$stopinc] = $row20[0];
					$corr_query .= $row20[0] . " ";
					//$replace_query .= "description LIKE '%$replace_by[$stopinc]%'";
				}
			}
		}
		$sv = substr($corr_query, 0, -1);
	} elseif ($_GET['searchval'] == "0") {
		$svy = escape_data($_GET['searchval']);
		$sv = $svy . 'ררר';
		$stopinc = FALSE;
	} else {
		$sv = FALSE;
		$svy = escape_data($_GET['searchval']);
		$sv1 = $svy;
		$onedisp = TRUE;
		$display_block .= "<p><font color=\"red\">Please enter a valid query! (At least 1 character)</font></p>";

		if ($stype == 1) {
			$radioartist = " checked";
			$radiosample = "";
		} elseif ($stype == 2) {
			$radioartist = "";
			$radiosample = " checked";
		}

		$search_block = "<form method=\"get\" action=\"index.php\">
						<table class=\"radiob\" width=\"100%\"  border=\"0\" cellspacing=\"0\" cellpadding=\"0\">
						<tr>
						<td width=\"60\" height=\"25\"><img src=\"/bce/spacer.gif\" width=\"1\" height=\"1\" /></td>
						<td></td>
						</tr>
						<tr>
						<td width=\"60\"><img src=\"/bce/spacer.gif\" width=\"1\" height=\"1\" /></td>
						<td><input name=\"stype\" type=\"radio\" value=\"1\"" . $radioartist . "> <span class=\"mtext2\">Artist</span></td>
						</tr>
						<tr>
						<td width=\"60\"><img src=\"/bce/spacer.gif\" width=\"1\" height=\"1\" /></td>
						<td><input name=\"stype\" type=\"radio\" value=\"2\"" . $radiosample . "> <span class=\"mtext2\">Sample</span></td>
						</tr>
						</table>
						<table width=\"100%\"  border=\"0\" cellspacing=\"0\" cellpadding=\"0\">
						<tr>
						<td width=\"60\"><img src=\"/bce/spacer.gif\" width=\"1\" height=\"1\" /></td>
						<td width=\"200\" align=\"left\" valign=\"middle\"><INPUT style=\"FONT-FAMILY: Arial\" tabIndex=1 maxLength=40 size=40 name=\"searchval\" class=\"searchbar\" value=\"$svy\"></td> 
						<td width=\"5\"><img src=\"/bce/spacer.gif\" width=\"1\" height=\"1\" /></td>
						<td align=\"left\" valign=\"middle\"><input type=\"image\" src=\"/images/search.jpg\" name=\"submit\" class=\"imgsearch\"></td>
						<td width=\"50\"><img src=\"/bce/spacer.gif\" width=\"1\" height=\"1\" /><input type=\"hidden\" name=\"submitted\" value=\"TRUE\"></td>
						</tr>
						<tr>
						<td width=\"60\" height=\"25\"><img src=\"/bce/spacer.gif\" width=\"1\" height=\"1\" /></td>
						<td></td>
						</tr>
						</table>
						</form>";

	}

	if ($sv) { // If everything's OK.
		if ($stype == 1) {
			$radioartist = " checked";
			$radiosample = "";
		} elseif ($stype == 2) {
			$radioartist = "";
			$radiosample = " checked";
		}

		$search_block = "<form method=\"get\" action=\"index.php\">
						<table class=\"radiob\" width=\"100%\"  border=\"0\" cellspacing=\"0\" cellpadding=\"0\">
						<tr>
						<td width=\"60\" height=\"25\"><img src=\"/bce/spacer.gif\" width=\"1\" height=\"1\" /></td>
						<td></td>
						</tr>
						<tr>
						<td width=\"60\"><img src=\"/bce/spacer.gif\" width=\"1\" height=\"1\" /></td>
						<td><input name=\"stype\" type=\"radio\" value=\"1\"" . $radioartist . "> <span class=\"mtext2\">Artist</span></td>
						</tr>
						<tr>
						<td width=\"60\"><img src=\"/bce/spacer.gif\" width=\"1\" height=\"1\" /></td>
						<td><input name=\"stype\" type=\"radio\" value=\"2\"" . $radiosample . "> <span class=\"mtext2\">Sample</span></td>
						</tr>
						</table>
						<table width=\"100%\"  border=\"0\" cellspacing=\"0\" cellpadding=\"0\">
						<tr>
						<td width=\"60\"><img src=\"/bce/spacer.gif\" width=\"1\" height=\"1\" /></td>
						<td width=\"200\" align=\"left\" valign=\"middle\"><INPUT style=\"FONT-FAMILY: Arial\" tabIndex=1 maxLength=40 size=40 name=\"searchval\" class=\"searchbar\" value=\"$svy\"></td> 
						<td width=\"5\"><img src=\"/bce/spacer.gif\" width=\"1\" height=\"1\" /></td>
						<td align=\"left\" valign=\"middle\"><input type=\"image\" src=\"/images/search.jpg\" name=\"submit\" class=\"imgsearch\"></td>
						<td width=\"50\"><img src=\"/bce/spacer.gif\" width=\"1\" height=\"1\" /><input type=\"hidden\" name=\"submitted\" value=\"TRUE\"></td>
						</tr>
						<tr>
						<td width=\"60\" height=\"25\"><img src=\"/bce/spacer.gif\" width=\"1\" height=\"1\" /></td>
						<td></td>
						</tr>
						</table>
						</form>";

		// Query the database.
		//$query = "SELECT artist_id, name, picname, internal_url FROM artists WHERE (MATCH(description) AGAINST ('$sv' IN BOOLEAN MODE) OR description LIKE '%$sv%')";
		if (!$stopinc) {
			$svmat = explode(" ", $sv);
			$match = "MATCH(search) AGAINST ('";
			foreach ($svmat as $key => $value) {
				$match .= "+" . $value . " ";
			}
			$match .= "' IN BOOLEAN MODE)";
			
			if ($stype == 1) {
				$query = "SELECT member_id, username, picname FROM members WHERE deleted IS NULL AND $match ORDER BY reg_datetime DESC LIMIT $start, $display";
				$query91 = "SELECT COUNT(*) FROM members WHERE deleted IS NULL AND $match";
			} elseif ($stype == 2) {
				$query = "SELECT sample_id, title, sampletype_id, api_id, added_by, DATE_FORMAT(added_datetime, '%b %D %Y') AS dts FROM samples WHERE deleted IS NULL AND get_started IS NULL AND $match ORDER BY added_datetime DESC LIMIT $start, $display";
				$query91 = "SELECT COUNT(*) FROM samples WHERE deleted IS NULL AND get_started IS NULL AND $match";
			}
		
		} elseif ($stopinc == $ntotalvalues) {
			$svmat = explode(" ", $sv);
			$like = "";
			$nlike = 1;
			foreach ($svmat as $key => $value) {
				$query21 = "SELECT stopword FROM stopwords WHERE replace_by='$value'";
				$result21 = mysql_query($query21) or trigger_error("Query: $query21\n<br>MySQL Error: " . mysql_error());
				$row21 = mysql_fetch_array($result21, MYSQL_NUM);
				$value2 = $row21[0];
				if ($nlike <2) {
					$like .= "search LIKE '% " . $value2 . " %' ";
				} else {
					$like .= "AND search LIKE '% " . $value2 . " %' ";
				}
				$nlike++;
			}
			
			if ($stype == 1) {
				$query = "SELECT member_id, username, picname, internal_url FROM members WHERE deleted IS NULL AND $like ORDER BY reg_datetime DESC LIMIT $start, $display";
				$query91 = "SELECT COUNT(*) FROM members WHERE deleted IS NULL AND $like";
			} elseif ($stype == 2) {
				$query = "SELECT sample_id, title, sampletype_id, api_id, added_by, DATE_FORMAT(added_datetime, '%b %D %Y') AS dts FROM samples WHERE deleted IS NULL AND get_started IS NULL AND $like ORDER BY added_datetime DESC LIMIT $start, $display";
				$query91 = "SELECT COUNT(*) FROM samples WHERE deleted IS NULL AND get_started IS NULL AND $like";
			}
			
		} else {
			$svmat = explode(" ", $sv);
			$match = "(MATCH(search) AGAINST ('";
			$like = "";
			$nlike = 1;
			foreach ($svmat as $key => $value) {
				if (strpos($value, '_')){
					$query21 = "SELECT stopword FROM stopwords WHERE replace_by='$value'";
					$result21 = mysql_query($query21) or trigger_error("Query: $query21\n<br>MySQL Error: " . mysql_error());
					$row21 = mysql_fetch_array($result21, MYSQL_NUM);
					$value2 = $row21[0];
					if ($nlike <2) {
						$like .= "search LIKE '% " . $value2 . " %' ";
					} else {
						$like .= "AND search LIKE '% " . $value2 . " %' ";
					}
					$nlike++;
				} else {
					$match .= "+" . $value . " ";
				}
			}
			$match .= "' IN BOOLEAN MODE)";
			
			if ($stype == 1) {
				$query = "SELECT member_id, username, picname, internal_url FROM members WHERE deleted IS NULL AND $match AND $like) ORDER BY reg_datetime DESC LIMIT $start, $display";
				$query91 = "SELECT COUNT(*) FROM members WHERE deleted IS NULL AND $match AND $like)";
			} elseif ($stype == 2) {
				$query = "SELECT sample_id, title, sampletype_id, api_id, added_by, DATE_FORMAT(added_datetime, '%b %D %Y') AS dts FROM samples WHERE deleted IS NULL AND get_started IS NULL AND $match AND $like) ORDER BY added_datetime DESC LIMIT $start, $display";
				$query91 = "SELECT COUNT(*) FROM samples WHERE deleted IS NULL AND get_started IS NULL AND $match AND $like)";
			}
			
		}
		$result = mysql_query($query) or trigger_error("Query: $query\n<br>MySQL Error: " . mysql_error());
		// Calculate the total number of members
		$result91 = mysql_query($query91) or trigger_error("Query: $query91\n<br>MySQL Error: " . mysql_error());
		$row91 = mysql_fetch_array($result91, MYSQL_NUM);
		$num_selected = $row91[0];
		// Calculate the number of pages.
		if ($num_selected > $display) { // More than 1 page.
			$num_pages = ceil($num_selected/$display);
		} else {
			$num_pages = 1;
		}
		
		$query_block = "<h2 class=\"mtitle5\">Search Results for: $svy</h2>";
		if (@mysql_num_rows($result) > 0) { // A match was made.
			if ($stype == 1) {
				$query_block .= '<table width="100%"  border="0" cellspacing="0" cellpadding="0" class="lateent">';
			} elseif ($stype == 2) {
				$query_block .= '<table width="940" border="0" cellspacing="0" cellpadding="0" class="lateent">
    <tr height="25" bgcolor="#000000">
		<td width="230" height="18" align="center"><span class="adtitle">TITLE</span></td>
		<td width="80" align="center"><span class="adtitle">TYPE</span></td>
		<td width="80" align="center"><span class="adtitle">API</span></td>
		<td width="180" align="center"><span class="adtitle">ARTIST</span></td>
		<td width="170" align="center"><span class="adtitle">DATE</span></td>
    </tr>
</table>
<table width="940" border="0" cellspacing="0" cellpadding="0">
    <tr>
		<td width="100%" align="center" valign="top" background="../images/no-sample.jpg">
<table width="940" border="0" cellspacing="0" cellpadding="0" class="lateent">';
				$bg = '#F0F0F0'; // Set the background color.
			}
			

			while ($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
				if ($stype == 1) {
					$aid = $row['member_id'];
				} elseif ($stype == 2) {
					$bg = ($bg == '#FFFFFF' ? '#F0F0F0' : '#FFFFFF'); // Switch the background color 
					$query_block .= '
				<tr bgcolor="' . $bg . '">';
					$sid = $row['sample_id'];
					$title = $row['title'];
					$stid = $row['sampletype_id'];
					$apiid = $row['api_id'];
					$aid = $row['added_by'];
					$dts = $row['dts'];
				
					// Sample Type Images
					if ($stid == 1) {
						$stidlogo = 'media-video40.jpg';
					} elseif ($stid == 2) {
						$stidlogo = 'media-audio40.jpg';
					} elseif ($stid == 3) {
						$stidlogo = 'media-image40.jpg';
					} else {
						$stidlogo = 'media-text40.jpg';
					}
					
					// Media Type Images
					if ($apiid == 1) {
						$apilogo = 'rqyoutube.png';
					} elseif ($apiid == 2) {
						$apilogo = 'rqsoundcloud.png';
					} elseif ($apiid == 3) {
						$apilogo = 'rqvine-logo.png';
					} elseif ($apiid == 4) {
						$apilogo = 'rqpreview-2016_instagram_logo.png';
					} elseif ($apiid == 5) {
						$apilogo = 'rqFlickr.png';
					} else {
						$apilogo = 'rqSlideshare-icon.png';
					}
				
				}
				
				if ($stype == 1) {
					// Art & genre search
					$query9 = "SELECT ang.ang_id, name FROM ang, ang_related WHERE ang.ang_id=ang_related.ang_id AND ang_related.added_by='$aid' AND ang.art_id IS NULL";
					$result9 = mysql_query($query9) or trigger_error("Query: $query9\n<br>MySQL Error: " . mysql_error());

					if (mysql_num_rows($result9) != 0) {
						$art_block = "";
						$narts = 1;
						while ($row9 = mysql_fetch_array($result9, MYSQL_ASSOC)) {
							if ($narts == 1) { 	
								$art_block .= ucwords($row9['name']);
							} else {
								$art_block .= ", " . ucwords($row9['name']);
							}
							$narts++;
						}
					} else {
						$art_block = "";
					}
				
					$query10 = "SELECT ang.ang_id, name FROM ang, ang_related WHERE ang.ang_id=ang_related.ang_id AND ang.art_id IS NOT NULL AND ang_related.added_by='$aid' LIMIT 10";
					$result10 = mysql_query($query10) or trigger_error("Query: $query10\n<br>MySQL Error: " . mysql_error());

					if (mysql_num_rows($result10) != 0) {
						$gen_block = "";
						$ngenres = 1;
						while ($row10 = mysql_fetch_array($result10, MYSQL_ASSOC)) {
							if ($ngenres == 1) { 	
								$gen_block .= ucwords($row10['name']);
							} else {
								$gen_block .= ", " . ucwords($row10['name']);
							}
							$ngenres++;
						}
					
					} else {
						$gen_block = " ";
					}				
				//----------------------------------------------------------
				} elseif ($stype == 2) {
					$query24 = "SELECT username FROM members WHERE member_id='$aid'";
					$result24 = mysql_query($query24) or trigger_error("Query: $query24\n<br>MySQL Error: " . mysql_error());
					$row24 = mysql_fetch_array($result24, MYSQL_NUM);
					$unm = $row24[0];
				}
				
				$url = 'http://www.ioneec.com/view_member/index.php?member=' . $aid;
				if ($stype == 1) {
					$stt = "Artist";
					$location = '';
				} elseif ($stype == 2) {
					$stt = "Sample";
					$location = '';
				}
				if ($stype < 2) {
					$anm = $row['username'];
					$picname0 = $row['picname'];
				
					if ($picname0) {
						$picname = "/member_pic/r50" . $picname0;
					} else {
						$picname = '/images/rsno-userpicture.jpg';
					}
				
				}
				
				if ($stype == 1) {
					$query_block .= "      <tr>
        			<td width=\"60\" height=\"60\" align=\"center\" valign=\"middle\" background=\"/bce/coin_122_sh.gif\"><a href=\"$url\"><img src=\"$picname\" border=\"0\"></a></td>
       				<td height=\"60\" align=\"left\" valign=\"top\" bgcolor=\"#F0F0F0\"><a class=\"stitle\" href=\"$url\">$anm $location / $stt</a><br>
					<span class=\"scontent\">$gen_block</span></td>
     				</tr>
	 				<tr>
	  				<td height=\"5\"><img src=\"/bce/spacer.gif\" width=\"1\" height=\"1\" /></td>
					<td></td>
	  				</tr>";
				} elseif ($stype == 2) {
					$query_block .= "		<td width=\"230\" height=\"50\" align=\"center\" valign=\"middle\"><span class=\"adtext\"><a href=\"http://www.ioneec.com/view_sample/index.php?sampleid=$sid\">$title</a></span></td>
		<td width=\"80\" align=\"center\" valign=\"middle\"><a href=\"http://www.ioneec.com/view_parameter/index.php?param=1&id=$stid\"><img src=\"/images/$stidlogo\" width=\"40\" height=\"40\" /></a></td>
   		<td width=\"80\" align=\"center\" valign=\"middle\"><a href=\"http://www.ioneec.com/view_parameter/index.php?param=2&id=$apiid\"><img src=\"/images/$apilogo\" width=\"40\" height=\"40\" /></a></td>
   		<td width=\"180\" align=\"center\" valign=\"middle\"><span class=\"adtext\"><a href=\"http://www.ioneec.com/view_member/index.php?member=$aid\">$unm</a></span></td>
		<td width=\"170\" align=\"center\" valign=\"middle\"><span class=\"adtext\">$dts</span></td>
  	</tr>";
				}
			}
			if ($stype == 1) {
				$query_block .= '</table>';
			} elseif ($stype == 2) {
				$query_block .= '</table>
	  </td>
    </tr>
</table>';
			}
			
			
			// Add the pagination of the selected events
			$svyp = str_replace(' ', '+', $svy);
			if ($num_pages > 1) {
				
				$page_block = '<div id="paginbot"><ul id="pagination-digg">';
				// Determine what page the script is on
				$current_page = ($start/$display) + 1;
					
				// If it's not the first page, make a Previous button
				if($current_page != 1) {
					if ($current_page > 2) {
						$page_block .= '<li class="first"><a href="http://www.ioneec.com/search/index.php?stype=' . $stype . '&searchval=' . $svyp . '&submitted=TRUE"><< First</a></li> ';
						$page_block .= '<li class="previous"><a href="index.php?stype=' . $stype . '&searchval=' . $svyp . '&page=' . ($page - 1) . '&submitted=TRUE">< Previous</a></li> ';
					} else {
						$page_block .= '<li class="first"><a href="http://www.ioneec.com/search/index.php?stype=' . $stype . '&searchval=' . $svyp . '&submitted=TRUE"><< First</a></li> ';
						$page_block .= '<li class="previous"><a href="http://www.ioneec.com/search/index.php?stype=' . $stype . '&searchval=' . $svyp . '&submitted=TRUE">< Previous</a></li> ';
					}
				}
				
				// Make all the numbered pages.
				if ($current_page <= 5) {
					for ($i = 1; $i <= $current_page + 4; $i++) {
						if ($i <= $num_pages) {
							if ($i != $current_page) {
								if ($i ==1) {
									$page_block .= '<li><a href="http://www.ioneec.com/search/index.php?stype=' . $stype . '&searchval=' . $svyp . '&submitted=TRUE">' . $i . '</a></li> ';
								} else {
									$page_block .= '<li><a href="index.php?stype=' . $stype . '&searchval=' . $svyp . '&page=' . $i . '&submitted=TRUE">' . $i . '</a></li> ';
								}
							} else {
								$page_block .= '<li class="active">' . $i . '</li> ';
							}
						}
					}
				} elseif ($current_page > $num_pages - 5) {
					for ($i = $current_page - 4; $i <= $num_pages; $i++) {
						if ($i != $current_page) {
							if ($i == 1) {
								$page_block .= '<li><a href="http://www.ioneec.com/search/index.php?stype=' . $stype . '&searchval=' . $svyp . '&submitted=TRUE">' . $i . '</a></li> ';
							} else {
								$page_block .= '<li><a href="index.php?stype=' . $stype . '&searchval=' . $svyp . '&page=' . $i . '&submitted=TRUE">' . $i . '</a></li> ';
							}
						} else {
							$page_block .= '<li class="active">' . $i . '</li> ';
						}
					}
				} else {
					for ($i = $current_page - 4; $i <= $current_page + 4; $i++) {
						if ($i != $current_page) {
							if ($i ==1) {
								$page_block .= '<li><a href="http://www.ioneec.com/search/index.php?stype=' . $stype . '&searchval=' . $svyp . '&submitted=TRUE">' . $i . '</a></li> ';
							} else {
								$page_block .= '<li><a href="index.php?stype=' . $stype . '&searchval=' . $svyp . '&page=' . $i . '&submitted=TRUE">' . $i . '</a></li> ';
							}
						} else {
							$page_block .= '<li class="active">' . $i . '</li> ';
						}
					}
				}
					
				// If it's not the last page. make a Next button.
				if ($current_page != $num_pages) {
					$page_block .= '<li class="next"><a href="index.php?stype=' . $stype . '&searchval=' . $svyp . '&page=' . ($page + 1) . '&submitted=TRUE">Next ></a></li>';
					//$page_block .= '<li class="last"><a href="index.php?page=' . $num_pages . '">Last >></a></li> ';
				}
					
				$page_block .= '</ul></div>';
			} else {
				$page_block = '';
			}
			
			
			// Page Title details
			if ($page != 1) {
				$page_title = $page_title . " ($num_selected) - Page " . $page . ' of ' . $num_pages;
				$sample_number = " ($num_selected) - Page " . $page . ' of ' . $num_pages;
			} else {
				$page_title = $page_title . " ($num_selected)";
				$sample_number = " ($num_selected)";
			}
			
			
			
		} else {
			$ucsv = ucwords($sv);
			if ($stype == 1) {
				$query_block .= "<p class=mtext2>We couldn't find any Artist named \"$svy\"</p>";
			} elseif ($stype == 2) {
				$query_block .= "<p class=mtext2>We couldn't find any Sample named \"$svy\"</p>";
			}
			//$display_block .= "<p class=mtext2>We couldn't find any artist named \"$sv0\" Query: $query Stype: $stype</p>";
		}
	} else {
		if (!$onedisp) {
			$display_block .= "<p><font color=\"red\">Please enter a valid query!</font></p>";

			if ($stype == 1) {
				$radioartist = " checked";
				$radiosample = "";
			} elseif ($stype == 2) {
				$radioartist = "";
				$radiosample = " checked";
			}
			
			$search_block = "<form method=\"get\" action=\"index.php\">
							<table class=\"radiob\" width=\"100%\"  border=\"0\" cellspacing=\"0\" cellpadding=\"0\">
							<tr>
							<td width=\"60\" height=\"25\"><img src=\"/bce/spacer.gif\" width=\"1\" height=\"1\" /></td>
							<td></td>
							</tr>
							<tr>
							<td width=\"60\"><img src=\"/bce/spacer.gif\" width=\"1\" height=\"1\" /></td>
							<td><input name=\"stype\" type=\"radio\" value=\"1\"" . $radioartist . "> <span class=\"mtext2\">Artist</span></td>
							</tr>
							<tr>
							<td width=\"60\"><img src=\"/bce/spacer.gif\" width=\"1\" height=\"1\" /></td>
							<td><input name=\"stype\" type=\"radio\" value=\"2\"" . $radiosample . "> <span class=\"mtext2\">Sample</span></td>
							</tr>
							</table>
							<table width=\"100%\"  border=\"0\" cellspacing=\"0\" cellpadding=\"0\">
							<tr>
							<td width=\"60\"><img src=\"/bce/spacer.gif\" width=\"1\" height=\"1\" /></td>
							<td width=\"200\" align=\"left\" valign=\"middle\"><INPUT style=\"FONT-FAMILY: Arial\" tabIndex=1 maxLength=40 size=40 name=\"searchval\" class=\"searchbar\" value=\"$svy\"></td> 
							<td width=\"5\"><img src=\"/bce/spacer.gif\" width=\"1\" height=\"1\" /></td>
							<td align=\"left\" valign=\"middle\"><input type=\"image\" src=\"/images/search.jpg\" name=\"submit\" class=\"imgsearch\"></td>
							<td width=\"50\"><img src=\"/bce/spacer.gif\" width=\"1\" height=\"1\" /><input type=\"hidden\" name=\"submitted\" value=\"TRUE\"></td>
							</tr>
							<tr>
							<td width=\"60\" height=\"25\"><img src=\"/bce/spacer.gif\" width=\"1\" height=\"1\" /></td>
							<td></td>
							</tr>
							</table>
							</form>";
		}
	
	}

} else {
	$search_block = '<form method="get" action="index.php">
					<table class="radiob" width="100%"  border="0" cellspacing="0" cellpadding="0">
					<tr>
					<td width="60" height="25"><img src="/bce/spacer.gif" width="1" height="1" /></td>
					<td></td>
					</tr>
					<tr>
					<td width="60"><img src="/bce/spacer.gif" width="1" height="1" /></td>
					<td><input name="stype" type="radio" value="1" checked> <span class="mtext2">Artist</span></td>
					</tr>
					<tr>
					<td width="60"><img src="/bce/spacer.gif" width="1" height="1" /></td>
					<td><input name="stype" type="radio" value="2"> <span class="mtext2">Sample</span></td>
					</tr>
					</table>
					<table width="100%"  border="0" cellspacing="0" cellpadding="0">
					<tr>
					<td width="60"><img src="/bce/spacer.gif" width="1" height="1" /></td>
					<td width="200" align="left" valign="middle"><INPUT style="FONT-FAMILY: Arial" tabIndex=1 maxLength=40 size=40 name="searchval" class="searchbar"></td> 
					<td width="5"><img src="/bce/spacer.gif" width="1" height="1" /></td>
					<td align="left" valign="middle"><input type="image" src="/images/search.jpg" name="submit" class="imgsearch"></td>
					<td width="50"><img src="/bce/spacer.gif" width="1" height="1" /><input type="hidden" name="submitted" value="TRUE"></td>
					</tr>
					<tr>
					<td width="60" height="25"><img src="/bce/spacer.gif" width="1" height="1" /></td>
					<td></td>
					</tr>
					</table>
					</form>';
}

mysql_close(); // Close the database connection.

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title><?php echo $page_title; ?></title>
<script type="text/javascript">
window.onload = function(){ 
	//Get submit button
	var submitbutton = document.getElementById("tfq");
	//Add listener to submit button
	if(submitbutton.addEventListener){
		submitbutton.addEventListener("click", function() {
			if (submitbutton.value == 'Enter your Email address!'){//Customize this text string to whatever you want
				submitbutton.value = '';
			}
		});
	}
}
</script>
<style type="text/css">
<!--
@import url(/includes/css/css_global.css);
.mtext2 {
	font-family:Tahoma;
	font-size:14px;
	color: black;
	padding: 5px 0px 5px 15px;
	margin:0;
}
#greybox {
	width: 100%;
	border: 1px solid #DDDDDD;
	margin: 0;
	padding: 10px 0px 5px 0px;
}
.imagereg{
	padding: 0;
	margin: 0px 5px 0px 0px;
	text-align: right;
}
.scontent {
	font-size: 12px;
	font-family: "Times New Roman", Times, serif;
	padding: 0;
	margin:0px 0px 0px 5px;
}
.stitle {
	font-family: "Times New Roman", Times, serif;
	font-size: 16px;
	font-weight: bold;
	color: #266D77;
	padding: 0;
	margin:0px 0px 0px 5px;
}
a.stitle:link {
	text-decoration:none;
	color:#266D77;
}
a.stitle:visited {
	text-decoration:none;
	color:#266D77;
}
a.stitle:active {
	text-decoration:none;
	color:#266D77;
}
a.stitle:hover {
	text-decoration:underline;
	color:#266D77;
}
.mtitle5 { 
	FONT-FAMILY: 'Trebuchet MS', 'Lucida Grande', Verdana, Arial, Sans-Serif;
	FONT-SIZE: 1.2em;
	COLOR:black;
	font-weight:normal;
	padding: 0px 0px 15px 10px;
	margin: 5px 0px 0px 0px;
}
.searchbar {
	BORDER-RIGHT: black 1px solid; 
	BORDER-TOP: black 1px solid; 
	BACKGROUND: white; 
	FONT: 20px Tahoma, Arial, sans-serif; 
	BORDER-LEFT: black 1px solid; 
	COLOR: #666666; 
	BORDER-BOTTOM: black 1px solid;
	padding: 5px 0px 5px 5px;
	margin: 0px 0px 0px 5px;
}
.adtitle {
	color: #FFFFFF;
	font-family:Verdana, Arial, Helvetica, sans-serif;
	font-size: 14px;
	font-weight:bold;
}
.adtext {
	font-size:14px;
	font-family:Verdana, Arial, Helvetica, sans-serif;
	color:black;
	
}
.adtext a:link {
	text-decoration:none;
	color:#1C15FF;
}
.adtext a:visited {
	text-decoration:none;
	color:#1C15FF;
}
.adtext a:hover {
	text-decoration:underline;
	color:black;
}
.adtext a:active {
	text-decoration:underline;
	color:black;
}
-->
</style>
</head>

<body>

<?php include ($_SERVER['DOCUMENT_ROOT'] . '/includes/html/header.html'); ?>

<table width="940" border="0" cellspacing="0" cellpadding="0" class="lateent">
	<tr>
	<td width="100%" height="35" bgcolor="#F0F0F0"><span class="boxtopic">SEARCH<?php echo $sample_number; ?></span></td>
	</tr>
</table>
<table width="940" border="0" cellspacing="0" cellpadding="0">
 				 <tr>
				   <td align="left" valign="top">
				   
<table width="940" border="0" cellspacing="0" cellpadding="0" class="lateent">
    <tr>
		<td width="940" height="6" align="center" valign="middle"><img src="/bce/spacer.gif" width="1" height="1" /></td>
    </tr>
	<tr height="44">
		<td width="940" height="44" style="FONT-SIZE:16px; FONT-FAMILY: Verdana; FONT-WEIGHT:bold;"><SPAN style="COLOR: black">Search for Artists and Artwork:</SPAN></td>
    </tr>
    <tr>
		<td width="940" height="6" align="center" valign="middle"><img src="/bce/spacer.gif" width="1" height="1" /></td>
    </tr>
</table>
				    <?php echo $display_block; ?>
					<?php echo $search_block; ?>
					<?php echo $page_block; ?>
					<table width="940" border="0" cellspacing="0" cellpadding="0">
						<tr>
						<td width="100%" height="15"><img src="/bce/spacer.gif" width="1" height="1"></td>
						</tr>
					</table>
					<?php echo $query_block; ?>
					<table width="940" border="0" cellspacing="0" cellpadding="0">
						<tr>
						<td width="100%" height="15"><img src="/bce/spacer.gif" width="1" height="1"></td>
						</tr>
					</table>
					<?php echo $page_block; ?></td>
				   </td>
 				 </tr>
</table>
<?php include ($_SERVER['DOCUMENT_ROOT'] . '/includes/html/footer.html'); ?>
</body>

</html>
<?php
require_once ($_SERVER['DOCUMENT_ROOT'] . '/includes/php/session_end.php');
?>
