<?php # Script 12.4 - ftp_connect.php

// This file contains the FTP access information.
// This file also establishes a connection to FTP with the user information.

// Set the FTP access information as constants.
DEFINE ('FTP_USER', '***username***');
DEFINE ('FTP_PASSWORD', '***password***');
DEFINE ('FTP_SERVER', 'ftp26.bravehost.com');

// set up a connection or die
if ($ftpc = ftp_ssl_connect(FTP_SERVER)) {

	// try to login
	if (!ftp_login($ftpc, FTP_USER, FTP_PASSWORD)) {
		echo "Couldn't connect as FTP_USER\n";
		exit();
	}
	
} else { // If it couldn't connect to FTP.

	// Print a message to the user, include the footer, and kill the script.
	echo "Couldn't connect to FTP_SERVER"; 
	exit();
	
} // End of $dbc IF.

?>
