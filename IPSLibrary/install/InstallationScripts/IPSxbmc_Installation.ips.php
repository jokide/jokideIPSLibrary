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

	 /**@defgroup IPSxbmc_protocol IPSIPSxbmc Kommunikations Protokoll
	 * @ingroup IPSIPSxbmc
	 * @{
	 *
	 */
	/** @}*/

	 /**@defgroup IPSxbmc_install IPSxbmc Installation
	 * @ingroup IPSxbmc
	 * @{
	 *
	 * IPSxbmc Installations File
	 *
	 * @file          IPSxbmc_Installation.ips.php
	 * @author        Andreas Brauneis
	 * @version
	 *  Version 2.50.1, 18.02.2012<br/>
	 *
	 * Script zur kompletten Installation der IPSxbmc Entertainment Steuerung.
	 *
	 * Vor der Installation sollte noch das File IPSxbmc_Configuration.inc.php an die persönlichen
	 * Bedürfnisse angepasst werden.
	 *
	 * @page rquirements_IPSxbmc Installations Voraussetzungen
	 * - IPS Kernel >= 2.50
	 * - IPSModuleManager >= 2.50.1
	 * - IPSLogger >= 2.50.1
	 *
	 * @page install_IPSxbmc Installations Schritte
	 * Folgende Schritte sind zur Installation der IPSxbmc Ansteuerung nötig:
	 * - Laden des Modules (siehe IPSModuleManager)
	 * - Konfiguration (Details siehe Konfiguration)
	 * - Installation (siehe IPSModuleManager)
	 *
	 */

	if (!isset($moduleManager)) {
		IPSUtils_Include ('IPSModuleManager.class.php', 'IPSLibrary::install::IPSModuleManager');

		echo 'ModuleManager Variable not set --> Create "default" ModuleManager'."\n";
		$moduleManager = new IPSModuleManager('IPSxbmc');
	}

	$moduleManager->VersionHandler()->CheckModuleVersion('IPS','2.50');
	$moduleManager->VersionHandler()->CheckModuleVersion('IPSLogger','2.50.1');
	$moduleManager->VersionHandler()->CheckModuleVersion('IPSModuleManager','2.50.1');

	IPSUtils_Include ("IPSInstaller.inc.php",             "IPSLibrary::install::IPSInstaller");
	IPSUtils_Include ("IPSxbmc_Constants.inc.php",        "IPSLibrary::app::modules::IPSxbmc");
	IPSUtils_Include ("IPSxbmc_Configuration.inc.php",    "IPSLibrary::config::modules::IPSxbmc");

	$WFC10_Enabled        = $moduleManager->GetConfigValue('Enabled', 'WFC10');
	$WFC10_ConfigId       = $moduleManager->GetConfigValueIntDef('ID', 'WFC10', GetWFCIdDefault());
	$WFC10_Path           = $moduleManager->GetConfigValue('Path', 'WFC10');
	$WFC10_TabPaneItem    = $moduleManager->GetConfigValue('TabPaneItem', 'WFC10');
	$WFC10_TabPaneParent  = $moduleManager->GetConfigValue('TabPaneParent', 'WFC10');
	$WFC10_TabPaneName    = $moduleManager->GetConfigValue('TabPaneName', 'WFC10');
	$WFC10_TabPaneIcon    = $moduleManager->GetConfigValue('TabPaneIcon', 'WFC10');
	$WFC10_TabPaneOrder   = $moduleManager->GetConfigValueInt('TabPaneOrder', 'WFC10');
	$WFC10_TabItem        = $moduleManager->GetConfigValue('TabItem', 'WFC10');
	$WFC10_TabName        = $moduleManager->GetConfigValue('TabName', 'WFC10');
	$WFC10_TabIcon        = $moduleManager->GetConfigValue('TabIcon', 'WFC10');
	$WFC10_TabOrder       = $moduleManager->GetConfigValueInt('TabOrder', 'WFC10');

	/* ---------------------------------------------------------------------- */
	/* IPSxbmc Installation                                                  */
	/* ---------------------------------------------------------------------- */

	//Delete previous installation
	DeleteCategory($moduleManager->GetModuleCategoryID('data'));
	
	$CategoryIdData     = $moduleManager->GetModuleCategoryID('data');
	$CategoryIdApp      = $moduleManager->GetModuleCategoryID('app');
//	$CategoryIdHardware = CreateCategoryPath('Modules.IPSxbmc');

	$id_ScriptSettings        = IPS_GetScriptIDByName('IPSxbmc_ChangeSettings', $CategoryIdApp);

	// Create variable profiles
	CreateProfile_Count (		'IPSxbmc_Volume',		0,		1, 100,       "", "%");
	CreateProfile_Switch (		'IPSxbmc_Mute',			'Aus', 'An', "", -1, 0x00ff00);
	CreateProfile_Associations ('IPSxbmc_Transport',	array("|<", "Play", "Pause", "Stop","|>"));
	CreateProfile_Count (		'IPSxbmc_Position',		0,       1, 100,       "", "%");
	
	$id_IPSxbmcServerId  = CreateDummyInstance("IPSxbmc_Server", $CategoryIdData, 10);
	$id_RoomIds          = CreateVariable(IPSXBMC_VAR_ROOMIDS,         3 /*String*/,  $id_IPSxbmcServerId, 20, '',                      null,              '',    '');
	$id_RoomCount        = CreateVariable(IPSXBMC_VAR_ROOMCOUNT,       1 /*Integer*/, $id_IPSxbmcServerId, 30, '',                      null,              0,     '');

	// ===================================================================================================
	// Add Rooms
	// ===================================================================================================
	$RoomId=0;
	$RoomConfig = IPSxbmc_GetRoomConfiguration();

	foreach ($RoomConfig as $GroupName=>$GroupData) {
		$RoomId = $RoomId + 1;
		$RoomInstanceId = CreateDummyInstance($GroupName, $CategoryIdData, 100+$RoomId);
		$RoomIds[]      = $RoomInstanceId;

		$PowerId        	= CreateVariable(IPSXBMC_VAR_ROOMPOWER,   	0 /*Boolean*/, $RoomInstanceId,  10, '~Switch',              $id_ScriptSettings, IPSXBMC_VAL_POWER_DEFAULT, 'Power');
		$VolumeId      		= CreateVariable(IPSXBMC_VAR_VOLUME,      	1 /*Integer*/, $RoomInstanceId,  20, 'IPSxbmc_Volume',      $id_ScriptSettings, IPSXBMC_VAL_VOLUME_DEFAULT, 'Intensity');
		$MutingId       	= CreateVariable(IPSXBMC_VAR_MUTE,        	0 /*Boolean*/, $RoomInstanceId,  30, 'IPSxbmc_Mute',        $id_ScriptSettings, IPSXBMC_VAL_MUTE_DEFAULT, 'Speaker');
		$TransportId    	= CreateVariable(IPSXBMC_VAR_TRANSPORT,   	1 /*Integer*/, $RoomInstanceId,  40, 'IPSxbmc_Transport',   $id_ScriptSettings, IPSXBMC_VAL_TRANSPORT, 'ArrowRight');
		$IPAdrr         	= CreateVariable(IPSXBMC_VAR_IPADDR,      	3 /*String*/,  $RoomInstanceId,  50, '', 					null, 				'', '');
		$PositionId  		= CreateVariable(IPSXBMC_VAR_POSITION,    	1 /*Integer*/, $RoomInstanceId,  60 , 'IPSxbmc_Position',   $id_ScriptSettings, 0, 'Gauge');
		$titleID        	= CreateVariable(IPSXBMC_VAR_TITLE,       	3 /*String*/,  $RoomInstanceId,  70, '', 					null, 				'', '');
		$fileID        		= CreateVariable(IPSXBMC_VAR_FILE,       	3 /*String*/,  $RoomInstanceId,  80, '', 					null, 				'', '');
		$seekID        		= CreateVariable(IPSXBMC_VAR_SEEK,       	3 /*String*/,  $RoomInstanceId,  90, '', 					null, 				'', '');
		$statusID        	= CreateVariable(IPSXBMC_VAR_STATUS,       	3 /*String*/,  $RoomInstanceId,  100, '', 					null, 				'', '');

		$VarSocketSendID    = CreateVariable(IPSXBMC_VAR_SOCKETSEND,	3 /*String*/,  $RoomInstanceId,  110, '', 					null, 				'', '');

		// Create Socket to send commands
		$SocketSendID = @IPS_GetInstanceIDByName("IPSxbmc Socket ".$GroupName." Send", 0);
		if ($SocketSendID === false)
		{
			$SocketSendID = IPS_CreateInstance("{3CFF0FD9-E306-41DB-9B5A-9D06D38576C3}");
			IPS_SetName($SocketSendID, "IPSxbmc Socket ".$GroupName." Send");
		}		
		CSCK_SetHost($SocketSendID, $GroupData[0]);
		CSCK_SetPort($SocketSendID, 8080);
		CSCK_SetOpen($SocketSendID, false);
		IPS_ApplyChanges($SocketSendID);

		// Create Socket to reveive commands
		$SocketReceiveID = @IPS_GetInstanceIDByName("IPSxbmc Socket ".$GroupName." Receive", 0);
		if ($SocketReceiveID === false)
		{
			$SocketReceiveID = IPS_CreateInstance("{3CFF0FD9-E306-41DB-9B5A-9D06D38576C3}");
			IPS_SetName($SocketReceiveID, "IPSxbmc Socket ".$GroupName." Receive");
		}		
		CSCK_SetHost($SocketReceiveID, $GroupData[0]);
		CSCK_SetPort($SocketReceiveID, 9000);
		CSCK_SetOpen($SocketReceiveID, false);
		IPS_ApplyChanges($SocketReceiveID);		
		
		//Create Register Variable
		$CommRegVarID	= CreateRegisterVariable(IPSXBMC_VAR_COMREGVAR, $RoomInstanceId, $id_ScriptSettings, $SocketReceiveID, $Position=0);

//		IPS_ConnectInstance($rvID, $socketID);
		
		
		// Werte aus Config zuweisen
		SetValue($IPAdrr, $GroupData[0]);
		SetValue($VarSocketSendID, $SocketSendID);
	}

	SetValue($id_RoomIds, implode(',',$RoomIds));
	SetValue($id_RoomCount, $RoomId);

	// ----------------------------------------------------------------------------------------------------------------------------
	// Webfront Installation
	// ----------------------------------------------------------------------------------------------------------------------------
	if ($WFC10_Enabled) {
		$categoryIdWebFront         = CreateCategoryPath($WFC10_Path);
		EmptyCategory($categoryIdWebFront);
		$categoryIdWebFrontLeft   = CreateCategory('Left',   $categoryIdWebFront, 100);
		$categoryIdWebFrontRight  = CreateCategory('Right', $categoryIdWebFront, 200);
		
		$instanceIdServer  		  = CreateDummyInstance('IPSxbmc Server', $categoryIdWebFrontLeft, 10);

		$RoomId = 1;

		foreach ($RoomConfig as $GroupName=>$GroupData) {
			$roomCategoryId = CreateCategory($GroupName, $categoryIdWebFrontRight, 10*$RoomId);
			$roomInstanceId = IPS_GetObjectIdByIdent($GroupName, $CategoryIdData);

			CreateLink('Power',                IPS_GetObjectIDByIdent(IPSXBMC_VAR_ROOMPOWER,   $roomInstanceId),   $roomCategoryId, 10);
			CreateLink('Lautstärke',           IPS_GetObjectIDByIdent(IPSXBMC_VAR_VOLUME,      $roomInstanceId),   $roomCategoryId, 40);
			CreateLink('Muting',               IPS_GetObjectIDByIdent(IPSXBMC_VAR_MUTE,        $roomInstanceId),   $roomCategoryId, 50);
			CreateLink('Player',               IPS_GetObjectIDByIdent(IPSXBMC_VAR_TRANSPORT,   $roomInstanceId),   $roomCategoryId, 50);
			CreateLink('Position',             IPS_GetObjectIDByIdent(IPSXBMC_VAR_POSITION,    $roomInstanceId),   $roomCategoryId, 90);

			$RoomId = $RoomId + 1;
		}


		$tabItem = $WFC10_TabPaneItem.$WFC10_TabItem;
		DeleteWFCItems($WFC10_ConfigId, $tabItem);
		CreateWFCItemTabPane   ($WFC10_ConfigId, $WFC10_TabPaneItem, $WFC10_TabPaneParent,  $WFC10_TabPaneOrder, $WFC10_TabPaneName, $WFC10_TabPaneIcon);
		CreateWFCItemSplitPane ($WFC10_ConfigId, $tabItem,           $WFC10_TabPaneItem,    $WFC10_TabOrder,     $WFC10_TabName,     $WFC10_TabIcon, 1 /*Vertical*/, 20 /*Width*/, 0 /*Target=Pane1*/, 0/*UsePerc*/, 'true');
		CreateWFCItemCategory  ($WFC10_ConfigId, $tabItem.'_Left',   $tabItem,   10, '', '', $categoryIdWebFrontLeft   /*BaseId*/, 'false' /*BarBottomVisible*/);
		CreateWFCItemCategory  ($WFC10_ConfigId, $tabItem.'_Right',  $tabItem,   20, '', '', $categoryIdWebFrontRight   /*BaseId*/, 'true' /*BarBottomVisible*/);

		ReloadAllWebFronts();
	}

	/** @}*/
?>
