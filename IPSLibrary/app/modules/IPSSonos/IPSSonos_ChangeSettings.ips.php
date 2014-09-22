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
	 * @file          IPSSonosChangeSettings.ips.php
	 * @author        joki
	 * @version
	 * Version 1.0.0, 01.09.2014<br/>
	 *
	 * Dieses Script ist als Action Script für diverse IPSSonos Variablen hinterlegt, um
	 * eine Änderung über das WebFront zu ermöglichen.
	 *
	 */

 	include_once 'IPSSonos.inc.php';
	IPSUtils_Include ("IPSSonos_Query.inc.php",      	"IPSLibrary::app::modules::IPSSonos");
	IPSUtils_Include ("IPSSonos_Constants.inc.php",     "IPSLibrary::app::modules::IPSSonos");
	
	if ($_IPS['SENDER'] == 'WebFront') {
	
		$variableId    = $_IPS['VARIABLE'];
		$variableValue = $_IPS['VALUE'];
		$variableIdent = IPS_GetIdent($variableId);

		$serverId = IPSUtil_ObjectIDByPath('Program.IPSLibrary.data.modules.IPSSonos.IPSSonos_Server');
		$instanceId = IPS_GetParent($variableId);
		
		if ($serverId<>$instanceId) {
			$roomName = IPS_GetName($instanceId);
		}
		
		switch($variableIdent) {

			case IPSSONOS_VAR_ROOMPOWER:
				IPSSonos_SetRoomPower($roomName, $variableValue);			
				break;
			case IPSSONOS_VAR_VOLUME:
				IPSSonos_SetVolume($roomName, $variableValue);
				break;
			case IPSSONOS_VAR_MUTE:
				IPSSonos_SetMute($roomName, $variableValue);
				break;
			case IPSSONOS_VAR_TRANSPORT:
			
				switch($variableValue) {
					case IPSSONOS_TRA_PREVIOUS:
						IPSSonos_Previous($roomName);				
						break;			
					case IPSSONOS_TRA_PLAY:
						IPSSonos_Play($roomName);				
						break;
					case IPSSONOS_TRA_PAUSE:
						IPSSonos_Pause($roomName);
						break;
					case IPSSONOS_TRA_STOP:
						IPSSonos_Stop($roomName);						
						break;
					case IPSSONOS_TRA_NEXT:
						IPSSonos_Next($roomName);				
						break;					
					default:
						break;
				}
				break;
			case IPSSONOS_VAR_PLAYLIST:
				IPSSonos_PlayPlaylistByID($roomName, $variableValue);
				break;
			case IPSSONOS_VAR_RADIOSTATION:
				IPSSonos_PlayRadiostationByID($roomName, $variableValue);
				break;			
			case IPSSONOS_VAR_SHUFFLE:
				IPSSonos_SetShuffle($roomName, $variableValue);
				break;
			case IPSSONOS_VAR_REPEAT:
				IPSSonos_SetRepeat($roomName, $variableValue);
				break;
			case IPSSONOS_VAR_QUERY:
				IPSSonos_SetQuery($variableValue);
				break;	
			case IPSSONOS_VAR_QUERYTIME:
				IPSSonos_SetQueryTime($variableValue);
				break;					
			case IPSSONOS_VAR_POSITIONPERCENT:
				IPSSonos_SeekPosition($roomName, $variableValue);
				break;				
			default:
				break;
		}
	} elseif ($_IPS['SENDER'] == 'TimerEvent') {

		$event = IPS_GetName($_IPS['EVENT']);
		
		switch($event) {
			case IPSSONOS_EVT_POWERONDELAY:
				IPSSonos_QuerySonos(IPSSONOS_EVT_POWERONDELAY);		//function is in an own inlcude to reduce overhead due to class concept				
				break;			
			case IPSSONOS_EVT_QUERY:
				IPSSonos_QuerySonos(IPSSONOS_EVT_QUERY);		//function is in an own inlcude to reduce overhead due to class concept		
				break;				
			default:
				break;
		}
	
	}		
	
	;
	/** @}*/
?>
