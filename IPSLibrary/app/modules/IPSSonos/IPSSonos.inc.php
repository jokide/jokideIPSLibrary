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

	/**@defgroup IPSSonos_api IPSSonos API
	 * @ingroup IPSSonos
	 * @{
	 *
	 * IPSSonos Server API
	 *
	 * @file          IPSSonos.inc.php
	 * @author        joki
	 * @version
	 * Version 1.0.0, 01.09.2014<br/>
	 *
	 * This include has all functions to control the IPSSonos application. The individual functions can
	 * also be used in scripts outside the IPSLibrary allowing easy access of the SONOS devices.
	 * 
	 * Please find examples in the Wiki: http://www.ip-symcon.de/wiki/IPSSonos
	 *
	 */

 	include_once 'IPSSonos_Server.class.php';


/*********************************************************************************************
 *  
 *  Room Functions
 *  
 **********************************************************************************************/
	/**
	 *  @brief Powers specific room on or off
	 *  
	 *  @param [in] $roomName Name of room as character
	 *  @param [in] $value true to switch room on, false to switch room off
	 *  @return Result as boolean
	 */
	function IPSSonos_SetRoomPower($roomName, $value) {
		$server = IPSSonos_GetServer();
		return $server->SendData(IPSSONOS_CMD_ROOM, $roomName, IPSSONOS_FNC_POWER, $value);
	}
	
	/**
	 * @brief Returns the power status of specific room
	 *
	 * @param [in] $roomName Name of room as character
	 * @return Result as boolean
	 */
	function IPSSonos_GetRoomPower($roomName) {
		$server = IPSSonos_GetServer();
		return $server->GetData(IPSSONOS_CMD_ROOM, $roomName, IPSSONOS_FNC_POWER, null);
	}
	
/*********************************************************************************************
 *  
 *  Player Functions
 *  
 **********************************************************************************************/
	
	/**
	 *  @brief Brief
	 *  
	 *  @param [in] $roomName Name of room as character
	 *  @return Result as boolean
	 */
	function IPSSonos_Play($roomName) {
		$server = IPSSonos_GetServer();
		return $server->SendData(IPSSONOS_CMD_AUDIO, $roomName, IPSSONOS_FNC_PLAY, null);
	}

	/**
	 *  @brief Brief
	 *  
	 *  @param [in] $roomName Name of room as character
	 *  @return Result as boolean
	 */
	function IPSSonos_Pause($roomName) {
		$server = IPSSonos_GetServer();
		return $server->SendData(IPSSONOS_CMD_AUDIO, $roomName, IPSSONOS_FNC_PAUSE, null);
	}	
	
	/**
	 *  @brief Brief
	 *  
	 *  @param [in] $roomName Name of room as character
	 *  @return Result as boolean
	 */
	function IPSSonos_Stop($roomName) {
		$server = IPSSonos_GetServer();
		return $server->SendData(IPSSONOS_CMD_AUDIO, $roomName, IPSSONOS_FNC_STOP, null);
	}	
	
	/**
	 *  @brief Brief
	 *  
	 *  @param [in] $roomName Name of room as character
	 *  @return Result as boolean
	 */
	function IPSSonos_Next($roomName) {
		$server = IPSSonos_GetServer();
		return $server->SendData(IPSSONOS_CMD_AUDIO, $roomName, IPSSONOS_FNC_NEXT, null);
	}

	/**
	 *  @brief Brief
	 *  
	 *  @param [in] $roomName Name of room as character
	 *  @return Result as boolean
	 */
	function IPSSonos_Previous($roomName) {
		$server = IPSSonos_GetServer();
		return $server->SendData(IPSSONOS_CMD_AUDIO, $roomName, IPSSONOS_FNC_PREVIOUS, null);
	}	

	/**
	 *  @brief Brief
	 *  
	 *  @param [in] $roomName Name of room as character
	 *  @param [in] $value Parameter_Description
	 *  @return Result as boolean
	 */
	function IPSSonos_RampToVolumeMute($roomName, $value){
		$server = IPSSonos_GetServer();
		return $server->SendData(IPSSONOS_CMD_AUDIO, $roomName, IPSSONOS_FNC_VOLUME_RMPMUTE, $value);
	}	
	
	/**
	 *  @brief Brief
	 *  
	 *  @param [in] $roomName Name of room as character
	 *  @param [in] $value Parameter_Description
	 *  @return Result as boolean
	 */
	function IPSSonos_RampToVolumeMuteSlow($roomName, $value){
		$server = IPSSonos_GetServer();
		return $server->SendData(IPSSONOS_CMD_AUDIO, $roomName, IPSSONOS_FNC_VOLUME_RMPMUTESLOW, $value);
	}	
	
	/**
	 *  @brief Brief
	 *  
	 *  @param [in] $roomName Name of room as character
	 *  @param [in] $value Parameter_Description
	 *  @return Result as boolean
	 */
	function IPSSonos_RampToVolume($roomName, $value){
		$server = IPSSonos_GetServer();
		return $server->SendData(IPSSONOS_CMD_AUDIO, $roomName, IPSSONOS_FNC_VOLUME_RMP, $value);
	}

	/**
	 *  @brief Brief
	 *  
	 *  @param [in] $roomName Name of room as character
	 *  @param [in] $value Parameter_Description
	 *  @return Result as boolean
	 */
	function IPSSonos_SetShuffle($roomName, $value){
		$server = IPSSonos_GetServer();
		return $server->SendData(IPSSONOS_CMD_AUDIO, $roomName, IPSSONOS_FNC_SHUFFLE, $value);
	}
	
	/**
	 *  @brief Brief
	 *  
	 *  @param [in] $roomName Name of room as character
	 *  @return Result as boolean
	 */
	function IPSSonos_GetShuffle($roomName){
		$server = IPSSonos_GetServer();
		return $server->GetData(IPSSONOS_CMD_AUDIO, $roomName, IPSSONOS_FNC_SHUFFLE, null);
	}
	
	/**
	 *  @brief Brief
	 *  
	 *  @param [in] $roomName Name of room as character
	 *  @param [in] $value Parameter_Description
	 *  @return Result as boolean
	 */
	function IPSSonos_SetRepeat($roomName, $value){
		$server = IPSSonos_GetServer();
		return $server->SendData(IPSSONOS_CMD_AUDIO, $roomName, IPSSONOS_FNC_REPEAT, $value);
	}
	
	/**
	 *  @brief Brief
	 *  
	 *  @param [in] $roomName Name of room as character
	 *  @return Result as boolean
	 */
	function IPSSonos_GetRepeat($roomName){
		$server = IPSSonos_GetServer();
		return $server->GetData(IPSSONOS_CMD_AUDIO, $roomName, IPSSONOS_FNC_REPEAT, null);
	}		

	/**
	 *  @brief Brief
	 *  
	 *  @param [in] $roomName Name of room as character
	 *  @param [in] $value Parameter_Description
	 *  @return Result as boolean
	 */
	function IPSSonos_SetVolume($roomName, $value) {
		$server = IPSSonos_GetServer();
		return $server->SendData(IPSSONOS_CMD_AUDIO, $roomName, IPSSONOS_FNC_VOLUME, $value);
	}
	
	/**
	 *  @brief Brief
	 *  
	 *  @param [in] $roomName Name of room as character
	 *  @param [in] $value Parameter_Description
	 *  @return Result as boolean
	 */
	function IPSSonos_IncVolume($roomName, $value) {
		$server = IPSSonos_GetServer();
		return $server->SendData(IPSSONOS_CMD_AUDIO, $roomName, IPSSONOS_FNC_VOLUME_INC, $value);
	}
	
	/**
	 *  @brief Brief
	 *  
	 *  @param [in] $roomName Name of room as character
	 *  @param [in] $value Parameter_Description
	 *  @return Result as boolean
	 */
	function IPSSonos_DecVolume($roomName, $value) {
		$server = IPSSonos_GetServer();
		return $server->SendData(IPSSONOS_CMD_AUDIO, $roomName, IPSSONOS_FNC_VOLUME_DEC, $value);
	}
		
	/**
	 *  @brief Brief
	 *  
	 *  @param [in] $roomName Name of room as character
	 *  @param [in] $value Parameter_Description
	 *  @return Result as boolean
	 */
//	function IPSSonos_GetVolume($instanceId, $roomId) {
//		$server = IPSSonos_GetServer($instanceId);
//		return $server->GetData(IPSSONOS_CMD_AUDIO, $roomId, IPSSONOS_FNC_VOLUME, null);
//	}

	/**
	 *  @brief Set status of the mute function
	 *  
	 *  @param [in] $roomName Name of room as character
	 *  @param [in] $value True to switch on, false to switch off
	 *  @return Result as boolean
	 */
	function IPSSonos_SetMute($roomName, $value) {
		$server = IPSSonos_GetServer();
		return $server->SendData(IPSSONOS_CMD_AUDIO, $roomName, IPSSONOS_FNC_MUTE, $value);
	}

	/**
	 *  @brief Returns the status of the mute function
	 *  
	 *  @param [in] $roomName Name of room as character
	 *  @return Result as boolean
	 */
//	function IPSSonos_GetMute($roomName) {
//		$server = IPSSonos_GetServer($instanceId);
//		return $server->GetData(IPSSONOS_CMD_AUDIO, $roomId, IPSSONOS_FNC_MUTE, null);
//	}
	
	/**
	 *  @brief Seek
	 *  
	 *  @param [in] $roomName Name of room as character
	 *  @return Result as boolean
	 */
	function IPSSonos_SeekPosition($roomName, $value) {
		$server = IPSSonos_GetServer();
		return $server->SendData(IPSSONOS_CMD_AUDIO, $roomName, IPSSONOS_FNC_SEEKPOSITION, $value);
	}
	
	/**
	 *  @brief Starts playing of a specified Sonos playlist
	 *  
	 *  @param [in] $roomName Name of room as character
	 *  @param [in] $value ID as integer of the playlist
	 *  @return Result as boolean
	 */
	function IPSSonos_PlayPlaylistByID($roomName, $value) {
		$server = IPSSonos_GetServer();
		return $server->SendData(IPSSONOS_CMD_AUDIO, $roomName, IPSSONOS_FNC_PLAYPLID, $value);
	}	

	/**
	 *  @brief Starts playing of a specified Sonos playlist
	 *  
	 *  @param [in] $roomName Name of room as character
	 *  @param [in] $value Name as character of the playlist as defined in the Sonos controller
	 *  @return Result as boolean
	 */
	function IPSSonos_PlayPlaylistByName($roomName, $value) {
		$server = IPSSonos_GetServer();
		return $server->SendData(IPSSONOS_CMD_AUDIO, $roomName, IPSSONOS_FNC_PLAYPLNAME, $value);
	}
	
	/**
	 *  @brief Starts playing of a specified radio station
	 *  
	 *  @param [in] $roomName Name of room as character
	 *  @param [in] $value ID as integer of the radio station
	 *  @return Result as boolean
	 */
	function IPSSonos_PlayRadiostationByID($roomName, $value) {
		$server = IPSSonos_GetServer();
		return $server->SendData(IPSSONOS_CMD_AUDIO, $roomName, IPSSONOS_FNC_PLAYRDID, $value);
	}	

	/**
	 *  @brief Starts playing of a specified radio station
	 *  
	 *  @param [in] $roomName Name of room as character
	 *  @param [in] $value Name as character of radio station as defined in the Sonos controller
	 *  @return Result as boolean
	 */
	function IPSSonos_PlayRadiostationByName($roomName, $value) {
		$server = IPSSonos_GetServer();
		return $server->SendData(IPSSONOS_CMD_AUDIO, $roomName, IPSSONOS_FNC_PLAYRDNAME, $value);
	}		
	
/*********************************************************************************************
 *  
 *  Server Functions
 *  
 **********************************************************************************************/
	/**
	 *  @brief Powers all rooms off
	 *  
	 *  @return Result as boolean
	 */
	function IPSSonos_SwitchAllRoomsOff() {
		$server = IPSSonos_GetServer();
		return $server->SendData(IPSSONOS_CMD_SERVER, null, IPSSONOS_FNC_ALLROOMSOFF, null);
	}		

	/**
	 *  @brief Get a list of all rooms
	 *  
	 *  @return Array listing all defined rooms
	 */	
	function IPSSonos_GetAllRooms() {
		$server = IPSSonos_GetServer();
		return $server->GetData(IPSSONOS_CMD_SERVER, null, IPSSONOS_FNC_ROOMS, null);
	}	

	/**
	 *  @brief Get a list of rooms currently powered on
	 *  
	 *  @return Array listing all rooms currently active
	 */	
	function IPSSonos_GetAllActiveRooms() {
		$server = IPSSonos_GetServer();
		return $server->GetData(IPSSONOS_CMD_SERVER, null, IPSSONOS_FNC_ROOMSACTIVE, null);
	}

	/**
	 *  @brief Sets status of the query functionality, retrieving details from players like current song, player state, ...
	 *  
	 *  @param [in] $value On/Off as boolean 
	 *  @return Result as boolean
	 */	
	function IPSSonos_SetQuery($value) {
		$server = IPSSonos_GetServer();
		return $server->SendData(IPSSONOS_CMD_SERVER, null, IPSSONOS_FNC_SETQUERY, $value);
	}	

	/**
	 *  @brief Sets the period of querying the player details 
	 *  
	 *  @param [in] $value Period in seconds
	 *  @return Result as boolean
	 */	
	function IPSSonos_SetQueryTime($value) {
		$server = IPSSonos_GetServer();
		return $server->SendData(IPSSONOS_CMD_SERVER, null, IPSSONOS_FNC_SETQUERYTIME, $value);
	}	
	
	/**
	 *  @brief Brief
	 *  
	 *  @return Synchronize of playlists stored in "Sonos Playlists" section of the Sonos system
	 */
	function IPSSonos_SyncPlaylists() {
		$server = IPSSonos_GetServer();
		return $server->SendData(IPSSONOS_CMD_SERVER, null, IPSSONOS_FNC_SYNCPL, null);
	}	
	/**
	 *  @brief Synchronize of radio stations stored in "My Radio Stations" section of the Sonos system
	 *  
	 *  @return Result as boolean
	 */
	function IPSSonos_SyncRadiostations() {
		$server = IPSSonos_GetServer();
		return $server->SendData(IPSSONOS_CMD_SERVER, null, IPSSONOS_FNC_SYNCRD, null);
	}
		
	/**
	 *  @brief Triggers a query for player details of the currently active rooms
	 *  
	 *  @return Result as boolean
	 */
	function IPSSonos_QueryData() {
		$server = IPSSonos_GetServer();
		return $server->SendData(IPSSONOS_CMD_SERVER, null, IPSSONOS_FNC_QUERYDATA, null);
	}	
	
/*********************************************************************************************
 *  
 *  Additional Functions
 *  
 **********************************************************************************************/
	 
	/**
	 *  @brief Get Server ID of IPSSonos
	 *  
	 *  @return IPSSonos Server Object
	 *  
	 */	 
	function IPSSonos_GetServer() {
	   	$instanceId = IPSUtil_ObjectIDByPath('Program.IPSLibrary.data.modules.IPSSonos.IPSSonos_Server');
		return new IPSSonos_Server($instanceId);
	}
	
	function WriteBoolean($value) {
	   	if ($value)  {
			$result = 'an';
		}
		else {
			$result = 'aus';
		}
		return $result;
	}

   /** @}*/


?>