<?
	/**@defgroup IPSSonos_configuration IPSSonos Konfiguration
	 * @ingroup IPSSonos
	 * @{
	 *
	 * IPSSonos Konfiguration
	 *
	 * @file          IPSSonos_Configuration.inc.php
	 * @author        joki
	 * @version
	 * Version 1.1.0, 12.10.2014<br/>
	 *
	 */
	IPSUtils_Include ("IPSSonos_Constants.inc.php",      "IPSLibrary::app::modules::IPSSonos");
	
	function IPSSonos_GetServerConfiguration() {
		return array(
			IPSSONOS_VAR_IPADDR			=>	'192.168.2.110',					// Mandatory: 	IP Adresse eines Players (keine Bridge!), dass möglichst immer an ist. Steuert zentrale Funktionen wie z.B. Synchronisation der Playlists
			IPSSONOS_VAR_PLAYERDETAILS 	=> 'High',								// Optional:	[High/Low] Change to "High" if detailed information on the players are needed (creates variavles for i.e. song, interpret, album, ... ) 
			);
	}

	function IPSSonos_GetRoomConfiguration() {									// Mandatory:	Array with details on your Sonos installation
		return array(
			'Wohnzimmer'	=>	array(											//	Mandatory:	Name of room 
				IPSSONOS_VAR_IPADDR		=>	'192.168.20.108', 					//	Mandatory:	IP Address
				IPSSONOS_VAR_RINCON		=>	'RINCON_000E5829F33A01400',			//	Mandatory:	RINCON ID of the player, can be found i.e. with the SONOS controller software.
				IPSSONOS_VAL_MAXVOL		=> 	'80'),								//	Mandatory:	Maximum allowed volume for the room
			'Schlafzimmer'	=>	array(
				IPSSONOS_VAR_IPADDR		=>	'192.168.2.110', 
				IPSSONOS_VAR_RINCON		=>	'RINCON_000E5872E10801400',
				IPSSONOS_VAL_MAXVOL		=> 	'25'),				
			'Küche'		=>	array(
				IPSSONOS_VAR_IPADDR		=>	'192.168.2.127', 
				IPSSONOS_VAR_RINCON		=>	'RINCON_000E582732C001400',
				IPSSONOS_VAL_MAXVOL		=> 	'25'),				
			);
	}
	
	function IPSSonos_GetMessageConfiguration() {
		return array(
			IPSSONOS_VAR_LPATH		=> 	'D:\\IPS-Config\\Sounds\\',				// Mandatory: Local folder to store wav and mp3 files 
			IPSSONOS_VAR_SMBPATH	=> 	'//192.168.2.3/IPS-Config/Sounds/',		// Mandatory: SMB share of the local path specified above
			IPSSONOS_VAR_TTSID		=> 	'37725',								// Optional:  ID of TTS (Text To Speech) instance
			
			IPSSONOS_VAR_SOUNDS 	=> array (									// Optional:  List of wav/mp3 files stored in the local folder
				"ringin"			=> "ringin.wav",							
				"notify"			=> "notify.wav",
				"chimes"			=> "chimes.wav",
				"bell"				=> "ding.wav")
		);
	}
	
	/** @}*/
?>