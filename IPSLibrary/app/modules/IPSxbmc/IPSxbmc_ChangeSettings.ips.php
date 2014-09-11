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

	 /**@addtogroup IPSxbmc
	 * @{
	 *
	 * @file          IPSxbmcChangeSettings.ips.php
	 * @author        Jörg Kling
	 *
	 * @version
	 * Version 2.50.1, 31.01.2012<br/>
	 * AudioMax Action Script
	 *
	 * Dieses Script ist als Action Script für diverse IPSxbmc Variablen hinterlegt, um
	 * eine Änderung über das WebFront zu ermöglichen.
	 *
	 */

 	include_once 'IPSxbmc.inc.php';
	IPSUtils_Include ("IPSLogger.inc.php",              "IPSLibrary::app::core::IPSLogger");
	
	if ($_IPS['SENDER'] == "WebFront") {

		$variableId    = $_IPS['VARIABLE'];
		$variableValue = $_IPS['VALUE'];
		$variableIdent = IPS_GetIdent($variableId);
	
		$serverId = IPSUtil_ObjectIDByPath('Program.IPSLibrary.data.modules.IPSxbmc.IPSxbmc_Server');
		$instanceId = IPS_GetParent($variableId);

		if ($serverId<>$instanceId) {
			$roomName = IPS_GetIdent($instanceId);
		}
	
		switch($variableIdent) {

			case IPSXBMC_VAR_ROOMPOWER:
				IPSxbmc_SetRoomPower($roomName, $variableValue);			
				break;
			case IPSXBMC_VAR_VOLUME:
				IPSxbmc_SetVolume($roomName, $variableValue);
				break;
			case IPSXBMC_VAR_MUTE:
				IPSxbmc_SetMute($roomName, $variableValue);
				break;
			case IPSXBMC_VAR_TRANSPORT:
			
				switch($variableValue) {
					case IPSxbmc_TRA_PREVIOUS:
						IPSxbmc_PREVIOUS($roomName);				
						break;			
					case IPSxbmc_TRA_PLAY:
						IPSxbmc_Play($roomName);				
						break;
					case IPSxbmc_TRA_PAUSE:
						IPSxbmc_Pause($roomName);
						break;
					case IPSxbmc_TRA_STOP:
						IPSxbmc_Stop($roomName);						
						break;
					case IPSxbmc_TRA_NEXT:
						IPSxbmc_NEXT($roomName);				
						break;					
					default:
						break;
				}
				break;
			case IPSXBMC_VAR_PLAYLIST:
				IPSxbmc_PlayPlaylistByID($roomName, $variableValue);
				break;
			case IPSXBMC_VAR_RADIOSTATION:
				IPSxbmc_PlayRadiostationByID($roomName, $variableValue);
				break;			
			case IPSXBMC_VAR_SHUFFLE:
				IPSxbmc_SetShuffle($roomName, $variableValue);
				break;
			case IPSXBMC_VAR_REPEAT:
				IPSxbmc_SetRepeat($roomName, $variableValue);
				break;			
			default:
				break;
		};
	}

	elseif ($_IPS['SENDER'] == "RegisterVariable") {
	
		$instanceId = IPS_GetParent($IPS_INSTANCE);
		$statusID = GetVariableByIdent(IPSXBMC_VAR_STATUS, $instanceId);
		$titleID = GetVariableByIdent(IPSXBMC_VAR_TITLE, $instanceId);
		$seekID = GetVariableByIdent(IPSXBMC_VAR_SEEK, $instanceId);
		$positionID = GetVariableByIdent(IPSXBMC_VAR_POSITION, $instanceId);

		$response = json_decode($_IPS['VALUE']);
		$I=10/0;
		


		if(isset($response->result)) {
			$result = $response->result;
			if(isset($result->percentage))
//			   SetValue($seekID, $result->percentage);
			if(isset($result->totaltime))
			{
				if(isset($result->time))
				{
				   $time = sprintf("%02d:%02d:%02d", $result->time->hours, $result->time->minutes, $result->time->seconds);
				   $totaltime = sprintf("%02d:%02d:%02d", $result->totaltime->hours, $result->totaltime->minutes, $result->totaltime->seconds);
//					SetValue($positionID, $time." / ".$totaltime);
				}
			}
			if(isset($result->item))
				if(isset($result->item->label)) {
//					SetValue($titleID, $result->item->label);
				}
			if( isset($result['0']) ) {
				SetValue($titleID, $result->item->label);
			}
		}
		if(isset($response->method)) {
			switch($response->method) {
				case "Player.OnPause":
//					SetValue($statusID, 1);
//					IPS_SetScriptTimer($_IPS['SELF'], 0);
					break;
				case "Player.OnPlay":
//					SetValue($statusID, 0);
//					IPS_SetScriptTimer($_IPS['SELF'], 5);
//						SendCommand("Player.GetItem", Array("playerid" => 1));
					break;
				case "Player.OnSeek":
					break;
				case "Player.OnStop":
//					SetValue($titleID, "-");
//					SetValue($positionID, "00:00 / 00:00");
//					SetValue($statusID, 2);
//					SetValue($seekID, 0);
//					IPS_SetScriptTimer($_IPS['SELF'], 0);
					break;
			}
		}
	}

	else if ($_IPS['SENDER'] == "TimerEvent") {

		if (IPS_GetInstance($Id_ClientSocket)['InstanceStatus'] != 102) {
					  IPS_SetScriptTimer($IPS_SELF,0);  //arm Timer for retry
					}
					else {
		   SendCommand("Player.GetProperties", Array("playerid" => 1, "properties"=>Array('time', 'totaltime', 'percentage')));
		}
	}
	/** @}*/
?>
