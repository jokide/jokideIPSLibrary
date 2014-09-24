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

	 /**@defgroup IPSSonos_protocol IPSIPSSonos Kommunikations Protokoll
	 * @ingroup IPSIPSSonos
	 * @{
	 *
	 */
	/** @}*/

	 /**@defgroup IPSSonos_install IPSSonos Installation
	 * @ingroup IPSSonos
	 * @{
	 *
	 * IPSSonos Installations File
	 *
	 * @file          IPSSonos_Installation.ips.php
	 * @author        joki
	 * @version
	 * Version 1.0.5, 21.09.2014<br/>
	 *
	 * Script zur kompletten Installation der IPSSonos Steuerung.
	 *
	 * Vor der Installation sollte noch das File IPSSonos_Configuration.inc.php an die persönlichen
	 * Bedürfnisse angepasst werden.
	 *
	 * @page rquirements_IPSSonos Installations Voraussetzungen
	 * - IPS Kernel >= 2.50
	 * - IPSModuleManager >= 2.50.1
	 * - IPSLogger >= 2.50.1
	 *
	 * @page install_IPSSonos Installations Schritte
	 * Folgende Schritte sind zur Installation der IPSSonos Ansteuerung nötig:
	 * - Laden des Modules (siehe IPSModuleManager)
	 * - Konfiguration (Details siehe Konfiguration)
	 * - Installation (siehe IPSModuleManager)
	 *
	 */

	if (!isset($moduleManager)) {
		IPSUtils_Include ('IPSModuleManager.class.php', 'IPSLibrary::install::IPSModuleManager');

		echo 'ModuleManager Variable not set --> Create "default" ModuleManager'."\n";
		$moduleManager = new IPSModuleManager('IPSSonos');
	}

	$moduleManager->VersionHandler()->CheckModuleVersion('IPS','2.50');
	$moduleManager->VersionHandler()->CheckModuleVersion('IPSLogger','2.50.1');
	$moduleManager->VersionHandler()->CheckModuleVersion('IPSModuleManager','2.50.1');

	IPSUtils_Include ("IPSInstaller.inc.php",             "IPSLibrary::install::IPSInstaller");
	IPSUtils_Include ("IPSSonos_Constants.inc.php",       "IPSLibrary::app::modules::IPSSonos");
	IPSUtils_Include ("IPSSonos_Configuration.inc.php",   "IPSLibrary::config::modules::IPSSonos");

	$WFC10_Enabled        = $moduleManager->GetConfigValue('Enabled', 'WFC10');
	$WFC10_ConfigId       = $moduleManager->GetConfigValueIntDef('ID', 'WFC10', GetWFCIdDefault());
	$WFC10_Path           = $moduleManager->GetConfigValue('Path', 'WFC10');
	$WFC10_TabPaneItem    = $moduleManager->GetConfigValue('TabPaneItem', 'WFC10');
	$WFC10_TabPaneParent  = $moduleManager->GetConfigValue('TabPaneParent', 'WFC10');
	$WFC10_TabPaneName    = $moduleManager->GetConfigValue('TabPaneName', 'WFC10');
	$WFC10_TabPaneIcon    = $moduleManager->GetConfigValue('TabPaneIcon', 'WFC10');
	$WFC10_TabPaneOrder   = $moduleManager->GetConfigValueInt('TabPaneOrder', 'WFC10');

	/* ---------------------------------------------------------------------- */
	/* IPSSonos Installation                                                  */
	/* ---------------------------------------------------------------------- */
	$CategoryIdData     = $moduleManager->GetModuleCategoryID('data');
	$CategoryIdApp      = $moduleManager->GetModuleCategoryID('app');


 	$id_ScriptSyncPlaylists    	= IPS_GetScriptIDByName('IPSSonos_SyncPlaylists',  		$CategoryIdApp);
 	$id_ScriptSyncRadiostations	= IPS_GetScriptIDByName('IPSSonos_SyncRadiostations',  	$CategoryIdApp);	
	$id_ScriptSettings       	= IPS_GetScriptIDByName('IPSSonos_ChangeSettings', 		$CategoryIdApp);
	$id_ScriptSwitchAllOff      = IPS_GetScriptIDByName('IPSSonos_SwitchAllRoomsOff', 	$CategoryIdApp);

	CreateProfile_Count 		('IPSSonos_Volume',       		0,       1, 100,       "", "%");
	CreateProfile_Switch 		('IPSSonos_Mute',            	'Aus', 'An', "", -1, 0x00ff00);
	CreateProfile_Associations 	('IPSSonos_Transport',       	array("|<", "Play", "Pause", "Stop",">|"));
	CreateProfile_Associations 	('IPSSonos_Playlists',       	array("Bitte Playlists auf Tab Config synchronisieren!"));
	CreateProfile_Associations 	('IPSSonos_Radiostations',      array("Bitte Radiostationen auf Tab Config synchronisieren!"));	
	CreateProfile_Switch 		('IPSSonos_Shuffle',            'Aus', 'An', "", -1, 0x00ff00);
	CreateProfile_Switch 		('IPSSonos_Repeat',            	'Aus', 'An', "", -1, 0x00ff00);
	CreateProfile_Associations 	('IPSSonos_Query',       		array("1", "3", "5", "10", "20", "30", "60", "180", "300"));
	CreateProfile_Switch 		('IPSSonos_Power',            	'Aus', 'An', "", -1, 0x00ff00);
	CreateProfile_Switch 		('IPSSonos_PowerOnDelay',       'Aus', 'An', "", -1, 0xff9900);
	CreateProfile_Count 		('IPSSonos_PositionPercent',   		0,	1, 100,       "", "%");
	
	$id_IPSSonosServerId 	= CreateDummyInstance("IPSSonos_Server", $CategoryIdData, 10);
	$id_RoomIds          	= CreateVariable(IPSSONOS_VAR_ROOMIDS,         	3 /*String*/,  $id_IPSSonosServerId, 10, '',    				null,              		'',		'');
	$id_RoomCount        	= CreateVariable(IPSSONOS_VAR_ROOMCOUNT,       	1 /*Integer*/, $id_IPSSonosServerId, 20, '',    				null,              		0,		'');
	$id_IPAddr        	 	= CreateVariable(IPSSONOS_VAR_IPADDR,       	3 /*String*/,  $id_IPSSonosServerId, 30, '',					null,              		'',		'');
	$id_ServerDetail		= CreateVariable(IPSSONOS_VAR_SERVERDETAILS,   	3 /*String*/,  $id_IPSSonosServerId, 40, '', 					null, 					'', 	'');
	$id_Query        	 	= CreateVariable(IPSSONOS_VAR_QUERY,       		0 /*Boolean*/, $id_IPSSonosServerId, 50, '~Switch',				$id_ScriptSettings,		false,	'');
	$id_QueryTime      	 	= CreateVariable(IPSSONOS_VAR_QUERYTIME,      	1 /*Integer*/, $id_IPSSonosServerId, 60, 'IPSSonos_Query',		$id_ScriptSettings,		1,		'');

	// Create Timer for Query Sonos
	$id_EventQuery		 	= CreateTimer_CyclicBySeconds (IPSSONOS_EVT_QUERY, $id_ScriptSettings, 1, $Active=false);
	$id_EventPowerOnDelay	= CreateTimer_CyclicBySeconds (IPSSONOS_EVT_POWERONDELAY, $id_ScriptSettings, 5, $Active=false);
	
	//Populate values from config
	$ServerConfig = IPSSonos_GetServerConfiguration();
	SetValue($id_IPAddr, $ServerConfig[IPSSONOS_VAR_IPADDR]);
	// Post V1.0 parameters
	$LevelPlayerDetails = @$ServerConfig[IPSSONOS_VAR_PLAYERDETAILS];  // Error message is suppressed in case parameter is not set in config
	if ($LevelPlayerDetails != "High")  {
		$LevelPlayerDetails = "Standard";	
	}
	SetValue($id_ServerDetail, $LevelPlayerDetails);
	
	/* ---------------------------------------------------------------------- */
	/* Add Rooms                                                              */
	/* ---------------------------------------------------------------------- */
	$RoomId=0;
	$RoomConfig = IPSSonos_GetRoomConfiguration();

	foreach ($RoomConfig as $GroupName=>$GroupData) {
		$RoomId = $RoomId + 1;
		$RoomInstanceId = CreateDummyInstance($GroupName, $CategoryIdData, 100+$RoomId);
		$RoomIds[]      = $RoomInstanceId;

		$PowerId        	= CreateVariable(IPSSONOS_VAR_ROOMPOWER,		0 /*Boolean*/, $RoomInstanceId,  10, '~Switch',              	$id_ScriptSettings, IPSSONOS_VAL_POWER_DEFAULT, 'Power');
		$IPAdrr         	= CreateVariable(IPSSONOS_VAR_IPADDR,			3 /*String*/,  $RoomInstanceId,  20, '', 						null, 				'', '');
		$RINCON         	= CreateVariable(IPSSONOS_VAR_RINCON,			3 /*String*/,  $RoomInstanceId,  30, '', 						null, 				'', '');
		$VolumeId      		= CreateVariable(IPSSONOS_VAR_VOLUME,			1 /*Integer*/, $RoomInstanceId,  40, 'IPSSonos_Volume',      	$id_ScriptSettings, IPSSONOS_VAL_VOLUME_DEFAULT, 'Intensity');
		$TransportId    	= CreateVariable(IPSSONOS_VAR_TRANSPORT,		1 /*Integer*/, $RoomInstanceId,  50, 'IPSSonos_Transport',   	$id_ScriptSettings, IPSSONOS_VAL_TRANSPORT, 'Speaker');
		$PLAYLIST         	= CreateVariable(IPSSONOS_VAR_PLAYLIST,    		1 /*Integer*/, $RoomInstanceId,  60, 'IPSSonos_Playlists', 		$id_ScriptSettings, IPSSONOS_VAL_PLAYLIST, '');
		$RADIOSTATION       = CreateVariable(IPSSONOS_VAR_RADIOSTATION,		1 /*Integer*/, $RoomInstanceId,  70, 'IPSSonos_Radiostations',	$id_ScriptSettings, IPSSONOS_VAL_RADIOSTATION, '');
		$MutingId       	= CreateVariable(IPSSONOS_VAR_MUTE,				0 /*Boolean*/, $RoomInstanceId,  80, 'IPSSonos_Mute',        	$id_ScriptSettings, IPSSONOS_VAL_MUTE_DEFAULT, '');
		$ShuffleId       	= CreateVariable(IPSSONOS_VAR_SHUFFLE,			0 /*Boolean*/, $RoomInstanceId,  90, 'IPSSonos_Shuffle',    	$id_ScriptSettings, false, '');
		$RepeatId       	= CreateVariable(IPSSONOS_VAR_REPEAT,			0 /*Boolean*/, $RoomInstanceId,  100, 'IPSSonos_Repeat',     	$id_ScriptSettings, false, '');
		$RemoteControlId  	= CreateVariable(IPSSONOS_VAR_REMOTE,   		3 /*String*/,  $RoomInstanceId,  110 , '~HTMLBox', 				null, '');
		$PlayerDetailsid  	= CreateVariable(IPSSONOS_VAR_PLAYERDETAILS,   	3 /*String*/,  $RoomInstanceId,  120 ,	'', 					null, 				'', '');

		if ($LevelPlayerDetails == "High")  {
			$CoverURIId  		= CreateVariable(IPSSONOS_VAR_COVERURI,   		3 /*String*/,  $RoomInstanceId,  310 ,	'~HTMLBox', 						null, 				'', '');
			$Titelid 		 	= CreateVariable(IPSSONOS_VAR_TITLE,   			3 /*String*/,  $RoomInstanceId,  320 ,	'', 								null, 				'', '');
			$Albumid  			= CreateVariable(IPSSONOS_VAR_ALBUM,   			3 /*String*/,  $RoomInstanceId,  330 ,	'', 								null, 				'', '');
			$Artistid  			= CreateVariable(IPSSONOS_VAR_ARTIST,   		3 /*String*/,  $RoomInstanceId,  340 ,	'', 								null, 				'', '');
			$AlbumArtistid  	= CreateVariable(IPSSONOS_VAR_ALBUMARTIST,   	3 /*String*/,  $RoomInstanceId,  350 ,	'', 								null, 				'', '');
			$Positionid  		= CreateVariable(IPSSONOS_VAR_POSITION,   		3 /*String*/,  $RoomInstanceId,  360 ,	'', 								null, 				'', '');
			$Positionpercentid	= CreateVariable(IPSSONOS_VAR_POSITIONPERCENT,  1 /*Integer*/, $RoomInstanceId,  370 ,	'IPSSonos_PositionPercent', 		$id_ScriptSettings, 0, '');
			$Durationid  		= CreateVariable(IPSSONOS_VAR_DURATION,   		3 /*String*/,  $RoomInstanceId,  380 ,	'', 								null, 				'', '');
		}
		
		// Werte aus Config zuweisen
		SetValue($IPAdrr, $GroupData[IPSSONOS_VAR_IPADDR]);
		SetValue($RINCON, $GroupData[IPSSONOS_VAR_RINCON]);
		}

	SetValue($id_RoomIds, implode(',',$RoomIds));
	SetValue($id_RoomCount, $RoomId);
	
	/* ---------------------------------------------------------------------- */
	/* Webfront Installation                                                  */
	/* ---------------------------------------------------------------------- */
	if ($WFC10_Enabled) {
		
		$categoryIdWebFront       = CreateCategoryPath($WFC10_Path);
		EmptyCategory($categoryIdWebFront);
		DeleteWFCItems($WFC10_ConfigId, $WFC10_TabPaneItem);
			
		$categoryIdWebFrontLeft   = CreateCategory('Left',   $categoryIdWebFront, 100);
		$categoryIdWebFrontRight  = CreateCategory('Right',  $categoryIdWebFront, 200);

		// Add Tab to Webfront
		CreateWFCItemSplitPane ($WFC10_ConfigId, $WFC10_TabPaneItem.'SP',    $WFC10_TabPaneParent,    $WFC10_TabPaneOrder,     $WFC10_TabPaneName,     $WFC10_TabPaneIcon, 1 /*Vertical*/, 30 /*Width*/, 0 /*Target=Pane1*/, 0/*UsePerc*/, 'true');
		CreateWFCItemCategory  ($WFC10_ConfigId, $WFC10_TabPaneItem.'SP_Left',   $WFC10_TabPaneItem.'SP',   10, '', '', $categoryIdWebFrontLeft   /*BaseId*/, 'false' /*BarBottomVisible*/);
		CreateWFCItemTabPane   ($WFC10_ConfigId, $WFC10_TabPaneItem.'SP_Right',  $WFC10_TabPaneItem.'SP',  $WFC10_TabPaneOrder,     $WFC10_TabPaneName,     $WFC10_TabPaneIcon, 1 /*Vertical*/, 30 /*Width*/, 0 /*Target=Pane1*/, 0/*UsePerc*/, 'true'); // 20, '', '', $categoryIdWebFrontRight   /*BaseId*/, 'true' /*BarBottomVisible*/);		
		
		// Server-Tab
		$instanceIdServer_Power  	= CreateDummyInstance('Räume', $categoryIdWebFrontLeft, 10);
		$instanceIdServer_Commands  = CreateDummyInstance('Aktionen', $categoryIdWebFrontLeft, 20);	
		CreateLink('Alle Räume ausschalten', $id_ScriptSwitchAllOff, $instanceIdServer_Commands, 120);
		
		// Room-Tabs
		$RoomId = 1;
		foreach ($RoomConfig as $GroupName=>$GroupData) {
			$roomCategoryId = CreateCategory($GroupName, $categoryIdWebFrontRight, 10*$RoomId);
			$roomInstanceId = IPS_GetObjectIdByName($GroupName, $CategoryIdData);
			CreateWFCItemCategory  ($WFC10_ConfigId, $WFC10_TabPaneItem.'SP_Right'.$GroupName,   $WFC10_TabPaneItem.'SP_Right',   10*$RoomId, $GroupName, '', $roomCategoryId /*BaseId*/, 'false' /*BarBottomVisible*/);
			
			// Power-Switch in Server-Tab
			CreateLink($GroupName,             	IPS_GetObjectIDByIdent(IPSSONOS_VAR_ROOMPOWER,   	$roomInstanceId),   $instanceIdServer_Power, 10*$RoomId);
			CreateLink('Power',                	IPS_GetObjectIDByIdent(IPSSONOS_VAR_ROOMPOWER,   	$roomInstanceId),   $roomCategoryId, 10);
			CreateLink('Remote',               	IPS_GetObjectIDByIdent(IPSSONOS_VAR_REMOTE, 		$roomInstanceId),   $roomCategoryId, 20);
			$roomInstanceId_Player  			= CreateDummyInstance('Player', $roomCategoryId, 30);
			CreateLink('Lautstärke',           	IPS_GetObjectIDByIdent(IPSSONOS_VAR_VOLUME,      	$roomInstanceId),   $roomInstanceId_Player, 10);
			CreateLink('Player',               	IPS_GetObjectIDByIdent(IPSSONOS_VAR_TRANSPORT,   	$roomInstanceId),   $roomInstanceId_Player, 20);
			CreateLink('Muting',               	IPS_GetObjectIDByIdent(IPSSONOS_VAR_MUTE,        	$roomInstanceId),   $roomInstanceId_Player, 30);
			CreateLink('Shuffle',              	IPS_GetObjectIDByIdent(IPSSONOS_VAR_SHUFFLE,     	$roomInstanceId),   $roomInstanceId_Player, 40);
			CreateLink('Repeat',               	IPS_GetObjectIDByIdent(IPSSONOS_VAR_REPEAT,      	$roomInstanceId),   $roomInstanceId_Player, 50);
			$roomInstanceId_Commands  			= CreateDummyInstance('Quellen', $roomCategoryId, 40);			
			CreateLink('Playlist',             	IPS_GetObjectIDByIdent(IPSSONOS_VAR_PLAYLIST,   		$roomInstanceId),   $roomInstanceId_Commands, 10);
			CreateLink('Radiostation',         	IPS_GetObjectIDByIdent(IPSSONOS_VAR_RADIOSTATION,   	$roomInstanceId),   $roomInstanceId_Commands, 20);
			if ($LevelPlayerDetails == "High")  {
				$roomInstanceId_GUIPlayerDet  		= CreateDummyInstance('Player Details', $roomCategoryId, 50);
				CreateLink('Cover',         	  	IPS_GetObjectIDByIdent(IPSSONOS_VAR_COVERURI,      		$roomInstanceId),   $roomInstanceId_GUIPlayerDet, 10);
				CreateLink('PositionP',            	IPS_GetObjectIDByIdent(IPSSONOS_VAR_POSITIONPERCENT,	$roomInstanceId),   $roomInstanceId_GUIPlayerDet, 20);
				CreateLink('Titel',               	IPS_GetObjectIDByIdent(IPSSONOS_VAR_TITLE,   			$roomInstanceId),   $roomInstanceId_GUIPlayerDet, 30);
				CreateLink('Album',               	IPS_GetObjectIDByIdent(IPSSONOS_VAR_ALBUM,        		$roomInstanceId),   $roomInstanceId_GUIPlayerDet, 40);
				CreateLink('Artist',              	IPS_GetObjectIDByIdent(IPSSONOS_VAR_ARTIST,     		$roomInstanceId),   $roomInstanceId_GUIPlayerDet, 50);			
				CreateLink('AlbumArtist',         	IPS_GetObjectIDByIdent(IPSSONOS_VAR_ALBUMARTIST,   		$roomInstanceId),   $roomInstanceId_GUIPlayerDet, 60);
				CreateLink('Position',            	IPS_GetObjectIDByIdent(IPSSONOS_VAR_POSITION,       	$roomInstanceId),   $roomInstanceId_GUIPlayerDet, 70);
				CreateLink('Laufzeit',            	IPS_GetObjectIDByIdent(IPSSONOS_VAR_DURATION,     		$roomInstanceId),   $roomInstanceId_GUIPlayerDet, 80);				
			}
			$RoomId = $RoomId + 1;
		}
			
		// Config-Tab
		$ConfigCategoryId = CreateCategory('Config', $categoryIdWebFrontRight, 10*$RoomId, 'Gear');
		CreateWFCItemCategory  ($WFC10_ConfigId, $WFC10_TabPaneItem.'SP_Right_Config'.$GroupName,   $WFC10_TabPaneItem.'SP_Right',   10*$RoomId, '', 'Gear', $ConfigCategoryId /*BaseId*/, 'false' /*BarBottomVisible*/);
		
		$instanceIdConfig_Query  	= CreateDummyInstance('Periodisches Abfragen der Sonos-Geräte', $ConfigCategoryId, 10);
		CreateLink('Status',  							IPS_GetObjectIDByIdent(IPSSONOS_VAR_QUERY,   	$id_IPSSonosServerId),   $instanceIdConfig_Query, 10);		
		CreateLink('Periode in Sekunden',  				IPS_GetObjectIDByIdent(IPSSONOS_VAR_QUERYTIME,  $id_IPSSonosServerId),   $instanceIdConfig_Query, 20);		

		$instanceIdConfig_Sync  	= CreateDummyInstance('Playlists synchchronisieren', 			$ConfigCategoryId, 20);
		CreateLink('Playlists synchronisieren',   		$id_ScriptSyncPlaylists, 		$instanceIdConfig_Sync, 10);
		CreateLink('Radiostationen synchronisieren',   	$id_ScriptSyncRadiostations, 	$instanceIdConfig_Sync, 20);	

		ReloadAllWebFronts();
	}


	/** @}*/
?>
