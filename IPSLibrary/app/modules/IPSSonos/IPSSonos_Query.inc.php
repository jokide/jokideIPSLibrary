<?
	/**
	 * This file is part of the IPSLibrary.
	 *
	 * The IPSLibrary is free software: you can redistribute it and/or modify
	 * it under the terms of the GNU General Public License as published
	 * by the Free Software Foundation, either version 3 of the License, or
	 * (at your option) any later version.
	 *
	 * The IPSLibrary is distributed in the hope that it will be useful,
	 * but WITHOUT ANY WARRANTY; without even the implied warranty of
	 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
	 * GNU General Public License for more details.
	 *
	 * You should have received a copy of the GNU General Public License
	 * along with the IPSLibrary. If not, see http://www.gnu.org/licenses/gpl.txt.
	 */    

	 /**@addtogroup IPSSonos
 	 * @{
	 *
	 * @file          IPSSonos_Room.class.php
	 * @author        joki
	 *
	 */

   /**
    * @class IPSSonos_Query
    *
    * 
    *
    * @author joki
    * @version
    * Version 1.0.6, 26.09.2014<br/>
    */
	include_once 'IPSSonos_Server.class.php';
	IPSUtils_Include ("IPSSonos.inc.php", 				"IPSLibrary::app::modules::IPSSonos");
	IPSUtils_Include ("IPSLogger.inc.php",              "IPSLibrary::app::core::IPSLogger");
	
	function IPSSonos_QuerySonos($Event_Triggered) {
		
		$server 	= IPSSonos_GetServer();
		$allRooms 	= IPSSonos_GetAllRooms();
		
		if ($Event_Triggered == IPSSONOS_EVT_POWERONDELAY) {
		
			$unArmCounter = 0;
			
			foreach ($allRooms as $roomName) {
			
				$room 		= $server->GetRoom($roomName);
				$roomPower 	= $room->GetValue(IPSSONOS_CMD_ROOM, IPSSONOS_FNC_POWER);
				
				if ($roomPower) {
					//Switch profile of variable to show OnDelay and arm timer
					$variableId  	= IPS_GetObjectIDByIdent(IPSSONOS_VAR_ROOMPOWER, $room->instanceId);
					$variableInfo	= IPS_GetVariable($variableId);
					if ( $variableInfo["VariableCustomProfile"] == "IPSSonos_PowerOnDelay" ) {
						$unArmCounter = $unArmCounter + 1;
						if (Sys_Ping( $room->IPAddr, 200 )) {
							IPS_SetVariableCustomProfile($variableId , "IPSSonos_Power");
							$unArmCounter = $unArmCounter - 1;
							// Set HTML Remote
							$HTMLRemote = "<table border=\"0\" width=\"100%\"><tr><td colspan=\"2\" width =\"110\"></td>";
							$room->setvalue(IPSSONOS_CMD_SERVER, 	IPSSONOS_VAR_REMOTE, 	$HTMLRemote);
							// Execute call-back method in IPSSonos_Custom
							IPSUtils_Include ("IPSSonos_Custom.inc.php",        "IPSLibrary::config::modules::IPSSonos");	
							IPSSonos_Custom_RoomPowerOn($roomName);							
						}
					}
				}
			}
		
			if ($unArmCounter == 0) {
				//unarm timer
				IPSUtils_Include ('IPSModuleManager.class.php', 'IPSLibrary::install::IPSModuleManager');
				$moduleManager 		= new IPSModuleManager('IPSSonos');	
				$CategoryIdApp      = $moduleManager->GetModuleCategoryID('app');
				$id_ScriptSettings  = IPS_GetScriptIDByName('IPSSonos_ChangeSettings', 		$CategoryIdApp);
				$TimerId = IPS_GetEventIDByName(IPSSONOS_EVT_POWERONDELAY, $id_ScriptSettings);
				IPS_SetEventActive($TimerId, false);			
			}
		}
		elseif ($Event_Triggered == IPSSONOS_EVT_QUERY) {

			foreach ($allRooms as $roomName) {
			
				$room 		= $server->GetRoom($roomName);
				$roomPower 	= $room->GetValue(IPSSONOS_CMD_ROOM, IPSSONOS_FNC_POWER);
				
				//If Sonos switched off, continue with next room ----------------------------------------------------------------------				
				if($roomPower == false) {

					$room->setvalue(IPSSONOS_CMD_AUDIO, IPSSONOS_FNC_STOP, IPSSONOS_TRA_STOP);
					$room->setvalue(IPSSONOS_CMD_ROOM, IPSSONOS_FNC_POWER, false);
					// Set HTML Remote
					$HTMLRemote = "<table border=\"0\" width=\"100%\"><tr><td colspan=\"2\" width =\"110\">Raum ist ausgeschaltet!</td>";
					$room->setvalue(IPSSONOS_CMD_SERVER, 	IPSSONOS_VAR_REMOTE, 	$HTMLRemote);	
					continue; // Next foreach - loop
				}				
				// Check that Sonos device is reachable					
				if( Sys_Ping( $room->IPAddr, 200 ) == false) {
//					IPSLogger_Wrn(__file__, 'Raum '.$room->roomName.' konnte nicht abgefragt werden, da Sonos-Gerät nicht erreichbar!');
					continue; // Next foreach - loop
				}
				
				//Sonos is reachable, update variables of webfront ----------------------------------------------------------------------			
				$Title			=	"";
				$AlbumArtURI	=	"";
				$Artist			=	"";
				$Album			=	"";	
				$Position		=	"";
				$Duration		= 	"";	
				$Percent_Played = 	0;
				
					
				// Sonos initialisieren
				$room = $server->GetRoom($roomName);
				$sonos = new PHPSonos($room->IPAddr);
				
				// Get Sonos information
//				$ZoneAttributes 		= $sonos->GetZoneAttributes();
				$PosInfo 				= $sonos->GetPositionInfo(); 
				$Status 				= $sonos->GetTransportInfo();	// gibt den aktuellen Status des Sonos-Players als Integer zurück, 1: PLAYING, 2: PAUSED, 3: STOPPED
				$MediaInfo 				= $sonos->GetMediaInfo();		// gibt den Namen der Radiostation zurück. Der key ist "title"				
				$VolumeInfo 			= $sonos->GetVolume();
				$MuteInfo 				= $sonos->GetMute();
//				$TransportSettingsInfo 	= $sonos->GetTransportSettings();	
				
				// Update status ------------------------------------------------------------------------------------------------------
				switch ($Status) {

					case 1:		//Playing
						$room->setvalue(IPSSONOS_CMD_AUDIO, IPSSONOS_FNC_PLAY, IPSSONOS_TRA_PLAY);
						break;
					case 2:		//Paused
						$room->setvalue(IPSSONOS_CMD_AUDIO, IPSSONOS_FNC_PAUSE, IPSSONOS_TRA_PAUSE);
						break;
					case 3:		//Stopped
						$room->setvalue(IPSSONOS_CMD_AUDIO, IPSSONOS_FNC_STOP, IPSSONOS_TRA_STOP);
						break;					
				}
				
				$room->setvalue(IPSSONOS_CMD_AUDIO, IPSSONOS_FNC_VOLUME, $VolumeInfo); // Update Volume
				$room->setvalue(IPSSONOS_CMD_AUDIO, IPSSONOS_FNC_MUTE, $MuteInfo);     // Update Mute

	
				// Identify player type  ------------------------------------------------------------------------------------------------------	
				// Spotify:			[URI] => x-sonos-spotify
				// Intunes Radio:	[URI] => x-rincon-mp3radio
				// Library:			[URI] => x-file-cifs
				// External input:	[TrackURI] => x-sonos-htastream:RINCON_000E58B264B501400:spdif
				// Group Slave:		[TrackURI] => x-rincon:RINCON_000E5872E10801400
				
				// Identify type of player
				$PlayerType = "OTHER";

				if (($PosInfo["URI"] != "") and 
						(substr($PosInfo["URI"], 0, 17) != "x-rincon-mp3radio") and 
						(substr($PosInfo["URI"], 0, 6)  != "mms://")) {
				
					$PlayerType = "SONG";
					
					$Title			= utf8_decode($PosInfo["title"]);
					$AlbumArtURI	= $PosInfo["albumArtURI"];
					$Artist			= utf8_decode($PosInfo["artist"]);
					$Album			= utf8_decode($PosInfo["album"]);	
					$Position		= sec_to_time(time_to_sec($PosInfo["position"]));
					$Duration		= sec_to_time(time_to_sec($PosInfo["duration"]));	
				
					// Calculate Percent Played Bar
					@$Percent_Played= (int) (time_to_sec($PosInfo["position"]) / time_to_sec($PosInfo["duration"]) *100);
					$PercentBar= "[";
					for ($i=1; $i<=(0.25*$Percent_Played-1);$i++) $PercentBar=$PercentBar. "-";
					$PercentBar=$PercentBar. "|";
					for ($i=(0.25*$Percent_Played-1); $i<=25;$i++) $PercentBar=$PercentBar. "-";
					$PercentBar=$PercentBar . "]";	
				}
				elseif ((substr($PosInfo["URI"], 0, 17) === "x-rincon-mp3radio")) {
				
					 $PlayerType = "RADIO"; 
					 
					 if (isset($MediaInfo["title"])&&($MediaInfo["title"]!="")){
							$Title	= $MediaInfo["title"];
							$ar=$sonos->RadiotimeGetNowPlaying();
							if($ar['logo']!="") {
								// Intune Return
								$AlbumArtURI = $ar['logo'];
							}else{
								// No return
									$AlbumArtURI="";
							}

						// do not set buffering info
						if($PosInfo["streamContent"]!="ZPSTR_BUFFERING" &&
							$PosInfo["streamContent"]!="ZPSTR_CONNECTING"
							&& $PosInfo["streamContent"]!="") {
								$Artist  = utf8_decode(utf8_decode(preg_replace('#(.*?)\|(.*)#is','$1',$PosInfo["streamContent"]))); // Tunein sends additional Information which could be sperated by a |
						 } 
					}
				}
				elseif ((substr($PosInfo["URI"], 0, 6) === "mms://")) {
					
					$PlayerType = "RADIO"; 
					$ar=$sonos->RadiotimeGetNowPlaying();
					$AlbumArtURI = $ar['logo'];
					$Title  = utf8_decode($MediaInfo["title"]);

				}				
				elseif ($PosInfo["URI"] === "") {
					if ((substr($PosInfo["TrackURI"], 0, 17) === "x-sonos-htastream"))
					 $PlayerType = "EXTERNAL";
					elseif ((substr($PosInfo["TrackURI"], 0, 8) === "x-rincon")) {
					 $PlayerType = "GROUPMEMBER"; } 
					}
				else { $PlayerType = "OTHER"; } ;
				
				// Check if player type has changed and if yes, execute callback ------------------------------------------------------------------------------------------------------		
				$PreviousPlayerType = $room->getvalue(IPSSONOS_CMD_AUDIO, IPSSONOS_VAR_PLAYERDETAILS);
				if ($PlayerType != $PreviousPlayerType) {
					IPSUtils_Include ("IPSSonos_Custom.inc.php",        "IPSLibrary::config::modules::IPSSonos");
					if (function_exists("IPSSonos_Custom_PlayerType")) {
						IPSSonos_Custom_PlayerType($roomName, $PlayerType);
					}
				}
				$room->setvalue(IPSSONOS_CMD_VARIABLE, IPSSONOS_VAR_PLAYERDETAILS, $PlayerType);
				
				if ($server->ConfPlayerDetails == "High")  {
					$CoverArtURL = "<img alt=\"\" src=\"".$AlbumArtURI."\" />";//  "<p><a href=\"".$AlbumArtURI."\"><img alt=\"src=\"".$AlbumArtURI."\" /></a></p>";
					$room->setvalue(IPSSONOS_CMD_VARIABLE, IPSSONOS_VAR_COVERURI, $CoverArtURL);
					$room->setvalue(IPSSONOS_CMD_VARIABLE, IPSSONOS_VAR_TITLE, $Title);
					$room->setvalue(IPSSONOS_CMD_VARIABLE, IPSSONOS_VAR_ALBUM, $Album);
					$room->setvalue(IPSSONOS_CMD_VARIABLE, IPSSONOS_VAR_ARTIST, $Artist);
	//				$room->setvalue(IPSSONOS_CMD_VARIABLE, IPSSONOS_VAR_ALBUMARTIST, $PlayerType);
					$room->setvalue(IPSSONOS_CMD_VARIABLE, IPSSONOS_VAR_POSITION, $Position);
					$room->setvalue(IPSSONOS_CMD_VARIABLE, IPSSONOS_VAR_DURATION, $Duration);
					$room->setvalue(IPSSONOS_CMD_VARIABLE, IPSSONOS_VAR_POSITIONPERCENT, $Percent_Played);
				}
				
				// Update remote ------------------------------------------------------------------------------------------------------						
				$HTMLRemote = 				"<table border=\"0\" cellpadding=\"1\" cellspacing=\"1\" style=\"width: 200;\">";
				$HTMLRemote = $HTMLRemote.		"<tbody>";	

				switch ($PlayerType) {
				
					case "STOP":
						$HTMLRemote = $HTMLRemote.			"<tr>";
						$HTMLRemote = $HTMLRemote.				"<td>Player angehalten</td>"; 
						$HTMLRemote = $HTMLRemote.			"</tr>";				
					break;
					
					case "SONG":
						$HTMLRemote = $HTMLRemote. 			"<tr>";
						$HTMLRemote = $HTMLRemote.				"<td rowspan=\"3\"><img alt=\"\" height=\"150\" src=\"".$AlbumArtURI."\" width=\"150\" /></td>";
						$HTMLRemote = $HTMLRemote.				"<td>&nbsp;<b>".$Title."</b></td>";
						$HTMLRemote = $HTMLRemote.			"</tr>";
						$HTMLRemote = $HTMLRemote.			"<tr>";
						$HTMLRemote = $HTMLRemote. 				"<td>";
						$HTMLRemote = $HTMLRemote.				"<table border=\"0\">";
						$HTMLRemote = $HTMLRemote.					"<tbody>";
						$HTMLRemote = $HTMLRemote.						"<tr>";
						$HTMLRemote = $HTMLRemote.							"<td>".$Artist."  </td>";
						$HTMLRemote = $HTMLRemote.							"<td>-  ".$Album."</td>";
						$HTMLRemote = $HTMLRemote.						"</tr>";
						$HTMLRemote = $HTMLRemote.						"<td> </td>"; // Empty row
						$HTMLRemote = $HTMLRemote.						"<tr>";
						$HTMLRemote = $HTMLRemote.							"<td colspan=\"2\">".$Position."/".$Duration."</td>";
						$HTMLRemote = $HTMLRemote.						"</tr>";
						$HTMLRemote = $HTMLRemote.						"<tr>";
						$HTMLRemote = $HTMLRemote.							"<td colspan=\"2\">".$PercentBar."</td>";
						$HTMLRemote = $HTMLRemote.						"</tr>";
						$HTMLRemote = $HTMLRemote.					"</tbody>";
						$HTMLRemote = $HTMLRemote.				"</table>";
						$HTMLRemote = $HTMLRemote.				"</td>";
						$HTMLRemote = $HTMLRemote.			"</tr>";
						$HTMLRemote = $HTMLRemote.			"<tr>";
						$HTMLRemote = $HTMLRemote.				"<td> </td>"; // Additional row for enhancements
						$HTMLRemote = $HTMLRemote.			"</tr>";		
					break;
					
					case "RADIO":	
						$HTMLRemote = $HTMLRemote. 			"<tr>";
						$HTMLRemote = $HTMLRemote.    			"<td rowspan=\"3\"><img alt=\"\" src=\"".$AlbumArtURI."\" /></td>";  
						$HTMLRemote = $HTMLRemote.				"<td style=\"text-align: left; vertical-align: top;\">&nbsp;<b>".$Title."</b></td>";
						$HTMLRemote = $HTMLRemote.			"</tr>";
						$HTMLRemote = $HTMLRemote.			"<tr>";
						$HTMLRemote = $HTMLRemote. 				"<td style=\"text-align: left; vertical-align: top;\">".$Artist."  </td>";
						$HTMLRemote = $HTMLRemote.			"</tr>";
						$HTMLRemote = $HTMLRemote.			"<tr>";
						$HTMLRemote = $HTMLRemote.				"<td> </td>"; // Additional row for enhancements
						$HTMLRemote = $HTMLRemote.			"</tr>";		
					break;
					
					case "GROUPMEMBER":
						$HTMLRemote = $HTMLRemote.			"<tr>";
						$HTMLRemote = $HTMLRemote.				"<td>Player ist in Gruppe</td>"; 
						$HTMLRemote = $HTMLRemote.			"</tr>";
						$HTMLRemote = $HTMLRemote.			"<tr>";
						$HTMLRemote = $HTMLRemote.				"<td> </td>"; // Additional row for enhancements
						$HTMLRemote = $HTMLRemote.			"</tr>";						
					break;
					
					case "EXTERNAL":
						$HTMLRemote = $HTMLRemote.			"<tr>";
						$HTMLRemote = $HTMLRemote.				"<td>Player verwendet externen Eingang</td>"; 
						$HTMLRemote = $HTMLRemote.			"</tr>";
						$HTMLRemote = $HTMLRemote.			"<tr>";
						$HTMLRemote = $HTMLRemote.				"<td> </td>"; // Additional row for enhancements
						$HTMLRemote = $HTMLRemote.			"</tr>";						
					break;
					
					case "OTHER":
						$HTMLRemote = $HTMLRemote.			"<tr>";
						$HTMLRemote = $HTMLRemote.				"<td>Player verwendet andere Quelle</td>"; 
						$HTMLRemote = $HTMLRemote.			"</tr>";
						$HTMLRemote = $HTMLRemote.			"<tr>";
						$HTMLRemote = $HTMLRemote.				"<td> </td>"; // Additional row for enhancements
						$HTMLRemote = $HTMLRemote.			"</tr>";						
					break;							
				}					

				$HTMLRemote = $HTMLRemote.		"</tbody>";
				$HTMLRemote = $HTMLRemote.		"</table>";	

				$room->setvalue(IPSSONOS_CMD_SERVER, 	IPSSONOS_VAR_REMOTE, 	$HTMLRemote);
			}
		}
	}
	
	/** @}*/
?>
