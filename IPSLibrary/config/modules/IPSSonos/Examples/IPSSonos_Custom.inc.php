<?
	/*
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

	/**@addtogroup IPSSonos_configuration
	 * @{
	 *
	 *
	 * @file          IPSSonos_Custom.inc.php
	 * @author        joki
	 * @version
	 *   Version 1.0.2, 11.09.2014<br/>
	 *
	 * Callback Methoden für IPSSonos
	 *
	 */

	/**
	 * This function is getting called when a room is switched on
	 *
	 * Parameters:
	 *   @param string $room_name name of the room in IPSSonos
	 *   @param boolean $value value on/off
	 *   @result boolean 
	 *
	 */
	function IPSSonos_Custom_SetRoomPower($room_name, $value) {

			switch ($room_name) {
				case 'Wohnzimmer':
					if ($value==true) {
						IPS_RunScript(37562 );
						IPS_RunScript(41531 );
					}
					else {
						IPS_RunScript(18951 );
						IPS_RunScript(30649 );
					}
					break;
					
				case 'Schlafzimmer':
					
					break;

				case 'Kueche':
					IPSUtils_Include ('IPSLight.inc.php', 'IPSLibrary::app::modules::IPSLight');
					if ($value==true) {
						IPSLight_SetSwitchByName('Kueche_Powermate', true);
					}
					else {
						IPSLight_SetSwitchByName('Kueche_Powermate', false);
					}					
					break;					
			}	

		return true;
	}
	
	/**
	 * This function is getting called when IPSSonos detects that a Sonos device was switched on and is now reachable.
	 * There can be a delay of 30 sec when a power switch is used to shutdown the Sonos device.
	 *
	 * Parameters:
	 *   @param string $room_name name of the room in which the device was detected as "on"
	 *
	 */	
	function IPSSonos_Custom_RoomPowerOn($room_name) {

		switch ($room_name) {
			
		case 'Schlafzimmer':
			IPSUtils_Include ("IPSSonos.inc.php", 				"IPSLibrary::app::modules::IPSSonos");
			IPSSonos_SetVolume($room_name, '10');
			break;				
		}	
	}
	
	/**
	 * This function is getting called when IPSSonos detects a change in player type (i.e. Radio, SPDIF/TOS-Link, MP3, ...)
	 * Please note: Dependent on the query update setting in the WebFront, there can be a delay in the detection.
	 *
	 * Parameters:
	 *   @param string $room_name name of the room in which the type of player changed
	 *   @param string $PlayerType new type of player, possibly values are:
	 *       OTHER
	 *  	 SONG
	 *  	 RADIO
	 *  	 EXTERNAL
	 *  	 GROUPMEMBER
	 *
	 */		

	 
	function IPSSonos_Custom_PlayerType($roomName, $PlayerType)  {
	
	    IPSUtils_Include ("IPSLogger.inc.php", "IPSLibrary::app::core::IPSLogger");
		IPSLogger_Inf("IPSSonos_Custom", "Player in romm ".$roomName." changed to: ".$PlayerType);  
	}	
	/** @}*/
?>