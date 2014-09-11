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

	/**@defgroup IPSxbmc_api IPSxbmc API
	 * @ingroup IPSxbmc
	 * @{
	 *
	 * IPSxbmc Server API
	 *
	 * @file          IPSxbmc.inc.php
	 * @author        Andreas Brauneis
	 * @version
	 * Version 2.50.1, 31.01.2012<br/>
	 *
	 * Dieses File kann von anderen Scripts per INCLUDE eingebunden werden und enthält Funktionen
	 * um alle IPSxbmc Funktionen bequem per Funktionsaufruf steueren zu können.
	 *
	 */

 	include_once 'IPSxbmc_Server.class.php';
	

	/**
	 * Ein- und Ausschalten eines einzelnen Raumes
	 *
	 * @param int $instanceId ID des IPSxbmc Servers
	 * @param int $roomId Raum der geändert werden soll (0-3)
	 * @param string $value TRUE oder '1' für An, FALSE oder '0' für Aus
	 * @return boolean Funktions Ergebnis, TRUE für OK, FALSE für Fehler
	 */
	function IPSxbmc_SetRoomPower($roomName, $value) {
		$server = IPSxbmc_GetServer();
		return $server->SendData(IPSXBMC_CMD_ROOM, $roomName, IPSXBMC_FNC_POWER, $value);
	}
	
	/**
	 * Status Raumverstärker lesen
	 *
	 * @param int $instanceId ID des IPSxbmc Servers
	 * @param int $roomId Raum der ausgelesen werden soll (0-3)
	 * @return boolean Status Raumverstärker
	 */
	function IPSxbmc_GetRoomPower($roomName) {
		$server = IPSxbmc_GetServer();
		return $server->GetData(IPSXBMC_CMD_ROOM, $roomName, IPSXBMC_FNC_POWER, null);
	}
	
	/**
	 * Ein- und Ausschalten eines einzelnen Raumes
	 *
	 * @param int $instanceId ID des IPSxbmc Servers
	 * @param int $roomId Raum der geändert werden soll (0-3)
	 * @param string $value TRUE oder '1' für An, FALSE oder '0' für Aus
	 * @return boolean Funktions Ergebnis, TRUE für OK, FALSE für Fehler
	 */
	
	/**
	 * Ein- und Ausschalten eines einzelnen Raumes
	 *
	 * @param int $instanceId ID des IPSxbmc Servers
	 * @param int $roomId Raum der geändert werden soll (0-3)
	 * @param string $value TRUE oder '1' für An, FALSE oder '0' für Aus
	 * @return boolean Funktions Ergebnis, TRUE für OK, FALSE für Fehler
	 */
	
	function IPSxbmc_GetAllRooms() {
		$server = IPSxbmc_GetServer();
		return $server->GetData(IPSXBMC_CMD_SERVER, null, IPSXBMC_FNC_ROOMS, null);
	}	

	function IPSxbmc_GetAllActiveRooms() {
		$server = IPSxbmc_GetServer();
		return $server->GetData(IPSXBMC_CMD_SERVER, null, IPSXBMC_FNC_ROOMSACTIVE, null);
	}	
	
	function IPSxbmc_Play($roomName) {
		$server = IPSxbmc_GetServer();
		return $server->SendData(IPSXBMC_CMD_PLAYER, $roomName, IPSXBMC_FNC_PLAY, null);
	}

	function IPSxbmc_Pause($roomName) {
		$server = IPSxbmc_GetServer();
		return $server->SendData(IPSXBMC_CMD_PLAYER, $roomName, IPSXBMC_FNC_PAUSE, null);
	}	
	
	function IPSxbmc_Stop($roomName) {
		$server = IPSxbmc_GetServer();
		return $server->SendData(IPSXBMC_CMD_PLAYER, $roomName, IPSXBMC_FNC_STOP, null);
	}	
	
	function IPSxbmc_Next($roomName) {
		$server = IPSxbmc_GetServer();
		return $server->SendData(IPSXBMC_CMD_PLAYER, $roomName, IPSXBMC_FNC_NEXT, null);
	}
	function IPSxbmc_Previous($roomName) {
		$server = IPSxbmc_GetServer();
		return $server->SendData(IPSXBMC_CMD_PLAYER, $roomName, IPSXBMC_FNC_PREVIOUS, null);
	}	

	function IPSxbmc_RampToVolumeMute($roomName, $value){
		$server = IPSxbmc_GetServer();
		return $server->SendData(IPSXBMC_CMD_PLAYER, $roomName, IPSXBMC_FNC_VOLUME_RMPMUTE, $value);
	}	
	
	function IPSxbmc_RampToVolumeMuteSlow($roomName, $value){
		$server = IPSxbmc_GetServer();
		return $server->SendData(IPSXBMC_CMD_PLAYER, $roomName, IPSXBMC_FNC_VOLUME_RMPMUTESLOW, $value);
	}	
	
	function IPSxbmc_RampToVolume($roomName, $value){
		$server = IPSxbmc_GetServer();
		return $server->SendData(IPSXBMC_CMD_PLAYER, $roomName, IPSXBMC_FNC_VOLUME_RMP, $value);
	}

	function IPSxbmc_Input($roomName, $value){
		$server = IPSxbmc_GetServer();
		return $server->SendData(IPSXBMC_CMD_PLAYER, $roomName, IPSXBMC_FNC_INPUT, $value);
	}	

		
	
	/**
	 * Auswahl des Eingangs, der für einen bestimmten Raum verwendet werden soll
	 *
	 * @param int $instanceId  ID des IPSxbmc Servers
	 * @param int $roomId Raum der geändert werden soll (0-3)
	 * @param int $value Eingang (1-4)
	 * @return boolean Funktions Ergebnis, TRUE für OK, FALSE für Fehler
	 */
	function IPSxbmc_SetInputSelect($instanceId, $roomId, $value) {
		$server = IPSxbmc_GetServer($instanceId);
		return $server->SendData(IPSXBMC_CMD_PLAYER, $roomId, IPSXBMC_FNC_INPUTSELECT, $value);
	}

	/**
	 * Eingangswahlschalter lesen
	 *
	 * @param int $instanceId  ID des IPSxbmc Servers
	 * @param int $roomId Raum der ausgelesen werden soll (0-3)
	 * @return int Eingangswahl (1-4)
	 */
	function IPSxbmc_GetInputSelect($instanceId, $roomId) {
		$server = IPSxbmc_GetServer($instanceId);
		return $server->GetData(IPSXBMC_CMD_PLAYER, $roomId, IPSXBMC_FNC_INPUTSELECT, null)+1;
	}

	/**
	 * Eingangsverstärkung setzen
	 *
	 * @param int $instanceId  ID des IPSxbmc Servers
	 * @param int $roomId Raum der geändert werden soll (0-3)
	 * @param int $value Verstärkung (0-15)
	 * @return boolean Funktions Ergebnis, TRUE für OK, FALSE für Fehler
	 */
//	function IPSxbmc_SetInputGain($instanceId, $roomId, $value) {
//		$server = IPSxbmc_GetServer($instanceId);
//		return $server->SendData(IPSxbmc_TYP_SET, IPSXBMC_CMD_PLAYER, $roomId, IPSXBMC_FNC_INPUTGAIN, $value);
//	}

	/**
	 * Eingangsverstärkung lesen
	 *
	 * @param int $instanceId  ID des IPSxbmc Servers
	 * @param int $roomId Raum der ausgelesen werden soll (0-3)
	 * @return int Verstärkung (0-15)
	 */
//	function IPSxbmc_GetInputGain($instanceId, $roomId) {
//		$server = IPSxbmc_GetServer($instanceId);
//		return $server->SendData(IPSxbmc_TYP_GET, IPSXBMC_CMD_PLAYER, $roomId, IPSXBMC_FNC_INPUTGAIN, null);
//	}

	/**
	 * Laustärke setzen
	 *
	 * @param int $instanceId  ID des IPSxbmc Servers
	 * @param int $roomId Raum der geändert werden soll (0-3)
	 * @param int $value Lautstärke (0-40)
	 * @return boolean Funktions Ergebnis, TRUE für OK, FALSE für Fehler
	 */
	function IPSxbmc_SetVolume($roomName, $value) {
		$server = IPSxbmc_GetServer();
		return $server->SendData(IPSXBMC_CMD_PLAYER, $roomName, IPSXBMC_FNC_VOLUME, $value);
	}
	/**
	 * Laustärke erhöhen
	 *
	 * @param int $instanceId  ID des IPSxbmc Servers
	 * @param int $roomId Raum der geändert werden soll (0-3)
	 * @param int $value Lautstärke (0-40)
	 * @return boolean Funktions Ergebnis, TRUE für OK, FALSE für Fehler
	 */
	function IPSxbmc_IncVolume($roomName, $value) {
		$server = IPSxbmc_GetServer();
		return $server->SendData(IPSXBMC_CMD_PLAYER, $roomName, IPSXBMC_FNC_VOLUME_INC, $value);
	}
	/**
	 * Laustärke verringern
	 *
	 * @param int $instanceId  ID des IPSxbmc Servers
	 * @param int $roomId Raum der geändert werden soll (0-3)
	 * @param int $value Lautstärke (0-40)
	 * @return boolean Funktions Ergebnis, TRUE für OK, FALSE für Fehler
	 */
	function IPSxbmc_DecVolume($roomName, $value) {
		$server = IPSxbmc_GetServer();
		return $server->SendData(IPSXBMC_CMD_PLAYER, $roomName, IPSXBMC_FNC_VOLUME_DEC, $value);
	}
		
	/**
	 * Laustärke lesen
	 *
	 * @param int $instanceId  ID des IPSxbmc Servers
	 * @param int $roomId Raum der ausgelesen werden soll (0-3)
	 * @return int Lautstärke (0-40)
	 */
	function IPSxbmc_GetVolume($instanceId, $roomId) {
		$server = IPSxbmc_GetServer($instanceId);
		return $server->GetData(IPSXBMC_CMD_PLAYER, $roomId, IPSXBMC_FNC_VOLUME, null);
	}

	/**
	 * Muting setzen
	 *
	 * @param int $instanceId ID des IPSxbmc Servers
	 * @param int $roomId Raum der geändert werden soll (0-3)
	 * @param string $value TRUE oder '1' für An, FALSE oder '0' für Aus
	 * @return boolean Funktions Ergebnis, TRUE für OK, FALSE für Fehler
	 */
	function IPSxbmc_SetMute($roomName, $value) {
		$server = IPSxbmc_GetServer();
		return $server->SendData(IPSXBMC_CMD_PLAYER, $roomName, IPSXBMC_FNC_MUTE, $value);
	}

	/**
	 * Status Muting lesen
	 *
	 * @param int $instanceId ID des IPSxbmc Servers
	 * @param int $roomId Raum der ausgelesen werden soll (0-3)
	 * @return boolean Status Muting
	 */
	function IPSxbmc_GetMute($roomName) {
		$server = IPSxbmc_GetServer($instanceId);
		return $server->GetData(IPSXBMC_CMD_PLAYER, $roomId, IPSXBMC_FNC_MUTE, null);
	}
	
	/**
	 * Switch Mute
	 *
	 * @param int $instanceId ID des IPSxbmc Servers
	 * @param int $roomId Raum der ausgelesen werden soll (0-3)
	 * @return boolean Status Muting
	 */
	function IPSxbmc_SwitchMute($roomName) {
		$server = IPSxbmc_GetServer($instanceId);
		return $server->GetData(IPSXBMC_CMD_PLAYER, $roomId, IPSXBMC_FNC_MUTE, null);
	}
	
	/**
	 * Get Server
	 *
	 * @param int $instanceId  ID des IPSxbmc Servers
	 * @return IPSxbmc IPSxbmc Server Object
	 */
	function IPSxbmc_GetServer() {
	   	$instanceId = IPSUtil_ObjectIDByPath('Program.IPSLibrary.data.modules.IPSxbmc.IPSxbmc_Server');
		return new IPSxbmc_Server($instanceId);
	}


   /** @}*/


?>