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

	/**@defgroup IPSSonos IPSSonos Steuerung
	 * @ingroup modules
	 * @{
	 *
	 * Klasse zur Kommunikation mit dem IPSSonos Device 
	 *
	 * @file          IPSSonos_Server.class.php
	 * @author        joki
	 *
	 */

	IPSUtils_Include ("IPSLogger.inc.php",              "IPSLibrary::app::core::IPSLogger");
	IPSUtils_Include ("IPSSonos_Constants.inc.php",     "IPSLibrary::app::modules::IPSSonos");
	IPSUtils_Include ("IPSSonos_Configuration.inc.php", "IPSLibrary::config::modules::IPSSonos");
	IPSUtils_Include ("IPSSonos_Room.class.php",        "IPSLibrary::app::modules::IPSSonos");
	IPSUtils_Include ("IPSSonos_Custom.inc.php",        "IPSLibrary::config::modules::IPSSonos");
	IPSUtils_Include ("IPSSonos.inc.php", 				"IPSLibrary::app::modules::IPSSonos");
	IPSUtils_Include ("PHPSonos.inc.php", 				"IPSLibrary::app::modules::IPSSonos");

   /**
    * @class IPSSonos_Server
    *
    * Definiert ein IPSSonos_Server Objekt
    *
	* @author        joki
	* @version
	* Version 1.1.1, 25.10.2014<br/>
    */
	class IPSSonos_Server {

		/**
		 * @private
		 * ID des IPSSonos Server
		 */
		private $instanceId;

		/**
		 * @private
		 * IPadress
		 */
		private $IPAddr;

		/**
		 * @private
		 * Debugging of IPSSonos Server Enabled/Disabled
		 */
		private $debugEnabled;
		
		/**
		 * @private
		 * Debugging of IPSSonos Server Enabled/Disabled
		 */		
		public $ConfPlayerDetails;
		
		/** 
		 * @public
		 *
		 * Initializes the IPSSonos Server
		 *
		 * @param integer $instanceId - ID des IPSSonos Server.
		 */
		public function __construct($instanceId) {
			$this->instanceId   		= $instanceId;
			$this->IPAddr				= GetValue(IPS_GetObjectIDByIdent(IPSSONOS_VAR_IPADDR, $this->instanceId));
//			$this->retryCount  = 0;
			$this->ConfPlayerDetails	= GetValue(IPS_GetObjectIDByIdent(IPSSONOS_VAR_SERVERDETAILS, $this->instanceId));
		}

		/**
		 * @private
		 *
		 * Protokollierung einer Meldung im IPSSonos Log
		 *
		 * @param string $logType Type der Logging Meldung 
		 * @param string $msg Meldung 
		 */
		private function Log ($logType, $msg) {
			if ($this->debugEnabled) {
				IPSLogger_WriteFile("", 'IPSSonos.log', date('Y-m-d H:i:s').'  '.$logType.' - '.$msg, null);
			}
		}
		
		/**
		 * @private
		 *
		 * Protokollierung einer Error Meldung
		 *
		 * @param string $msg Meldung 
		 */
		private function LogErr($msg) {
			IPSLogger_Err("IPSSonos", $msg);
			$this->Log('Err', $msg);
		}
		
		/**
		 * @private
		 *
		 * Protokollierung einer Info Meldung
		 *
		 * @param string $msg Meldung 
		 */
		private function LogInf($msg) {
			IPSLogger_Inf("IPSSonos", $msg);
			$this->Log('Inf', $msg);
		}
		
		/**
		 * @private
		 *
		 * Protokollierung einer Debug Meldung
		 *
		 * @param string $msg Meldung 
		 */
		private function LogDbg($msg) {
			IPSLogger_Dbg("IPSSonos", $msg);
			$this->Log('Dbg', $msg);
		}

		/**
		 * @private
		 *
		 * Protokollierung einer Kommunikations Meldung
		 *
		 * @param string $msg Meldung 
		 */
		private function LogCom($msg) {
			IPSLogger_Com("IPSSonos", $msg);
			$this->Log('Com', $msg);
		}
		
		/**
		 * @private
		 *
		 * Protokollierung einer Trace Meldung
		 *
		 * @param string $msg Meldung 
		 */
		private function LogTrc($msg) {
			IPSLogger_Trc("IPSSonos", $msg);
			$this->Log('Trc', $msg);
		}
			
		/**
		 * @private
		 *
		 * Liefert ein IPSSonosRoom Objekt für eine Raum Nummer, sind keine Räume vorhanden
		 * liefert die Funktion false.
		 *
		 * @param integer $roomId Nummer des Raumes (1-4).
		 * @return IPSSonos_Room IPSSonos Room Object
		 */
		public function GetRoom($roomName) {
			$roomIds = GetValue(IPS_GetObjectIDByIdent(IPSSONOS_VAR_ROOMIDS, $this->instanceId));
			if ($roomIds=="") {
				return false;
			}
			$roomIds        = explode(',',  $roomIds);
			$roomInstanceId = false;
			$IPSSonosRoom   = false;
			foreach ($roomIds as $roomId){
				if ($roomName == IPS_GetName((int)$roomId)) {
					$roomInstanceId = (int)$roomId;
					$IPSSonosRoom = new IPSSonos_Room($roomInstanceId);
				}
			}
			
			if ($IPSSonosRoom) {
				$result = $IPSSonosRoom;
			}
			else {
				$result = false;
				$this->LogErr('Raum '.$roomName.' nicht in der Konfiguration gefunden!');
			}
			return $result;
		}

		/**
		 * @private
		 *
		 * Lesen der IPSSonos Werte aus den Instanz Variablen
		 *
		 * @param string $type Kommando Type
		 * @param string $command Kommando
		 * @param integer $roomId Nummer des Raumes
		 * @param string $function Funktion
		 * @return string Wert
		 */
		public function GetData ($command, $roomName, $function) {
			$result = '';
			switch ($command) {

				case IPSSONOS_CMD_ROOM:		
					
					$room = $this->GetRoom($roomName);				
					$result = $room->GetValue ($command, $function);

					break;
				case IPSSONOS_CMD_AUDIO:
						$room = $this->GetRoom($roomName);				
						$result = $room->GetValue ($command, $function);
					break;
				
				case IPSSONOS_CMD_SERVER:
					
					switch ($function) {
						case IPSSONOS_FNC_ROOMS:
							$room_array = [];
							$roomIds = GetValue(IPS_GetObjectIDByIdent(IPSSONOS_VAR_ROOMIDS, $this->instanceId));
							if ($roomIds=="") {
								return false;
							}
							$roomIds        = explode(',',  $roomIds);
							foreach ($roomIds as $roomId){
								$room_array[] = IPS_GetName((int)$roomId);
							}

							return $room_array;
							break;	
						case IPSSONOS_FNC_ROOMSACTIVE:
							$room_array = [];
							$allRooms = IPSSonos_GetAllRooms();
							foreach ($allRooms as $roomName){
								$room = $this->GetRoom($roomName);
								if ($room->GetValue (IPSSONOS_CMD_ROOM, '')) {
								$room_array[] = $roomName;
								}
							}							
							return $room_array;
							break;									
						default:
							break;
					}							
					break;					
				default:
					$this->LogErr('Unknown Command '.$command);
			}
			return $result;
		}


		/**
		 * @public
		 *
		 * Senden von Befehlen zum IPSSonos Server
		 *
	    * @param string $type Kommando Type
	    * @param string $command Kommando
	    * @param integer $roomId Raum (0-15)
	    * @param string $function Funktion
	    * @param string $value Wert
	    * @return boolean TRUE für OK, FALSE bei Fehler
		 */
		public function SendData($command, $roomName, $function, $value) {
			$result = false;
					
			//*-------------------------------------------------------------*
			switch ($command) {
				case IPSSONOS_CMD_AUDIO:
				
					// Sonos initialisieren
					$room = $this->GetRoom($roomName);
					$sonos = new PHPSonos($room->IPAddr); 	
					
					if ($room->ValidateValue($sonos, $command, $function, $value) == false) {
						return false;
					}					
					switch ($function) {
						case IPSSONOS_FNC_VOLUME:
							$sonos->SetVolume($value);
							$this->LogDbg('Lautstärke im Raum '.$roomName.' gesetzt auf: '.$value);
							$result = true;
							break;
						case IPSSONOS_FNC_VOLUME_RMP:
							$ramp_type =  "SLEEP_TIMER_RAMP_TYPE";
							$sonos->RampToVolume( $ramp_type, $value);
							$this->LogDbg('Lautstärke im Raum '.$roomName.' gesetzt auf: '.$value);
							$result = true;
							break;
						case IPSSONOS_FNC_VOLUME_RMPMUTE: 
							$sonos->RampToVolume("AUTOPLAY_RAMP_TYPE", $value);
							$this->LogDbg('Lautstärke im Raum '.$roomName.' gesetzt auf: '.$value);
							$result = true;
							break;
						case IPSSONOS_FNC_VOLUME_RMPMUTESLOW: 
							$sonos->RampToVolume("ALARM_RAMP_TYPE", $value);
							$this->LogDbg('Lautstärke im Raum '.$roomName.' gesetzt auf: '.$value);
							$result = true;
							break;									
						case IPSSONOS_FNC_VOLUME_INC:	
								$current_volume = $sonos->GetVolume();
								$new_volume = $current_volume + $value;
								$sonos->SetVolume($new_volume);
								$this->LogDbg('Lautstärke im Raum '.$roomName.' um '.$value.' erhöht auf: '.$new_volume);
								$value = $new_volume;
								$result = true;
							break;	
						case IPSSONOS_FNC_VOLUME_DEC:	
								$current_volume = $sonos->GetVolume();
								$new_volume = $current_volume - $value;
								$sonos->SetVolume($new_volume);
								$this->LogDbg('Lautstärke im Raum '.$roomName.' um '.$value.' verringert auf: '.$new_volume);
								$value = $new_volume;
								$result = true;								
							break;
						case IPSSONOS_FNC_PLAY:	
								$sonos->Play();
								$this->LogDbg('Abspielen im Raum '.$roomName.' gestartet.');
								$value = IPSSONOS_TRA_PLAY;
								$result = true;
							break;
						case IPSSONOS_FNC_STOP:	
								$sonos->Stop();
								$this->LogDbg('Abspielen im Raum '.$roomName.' gestopt.');
								$value = IPSSONOS_TRA_STOP;	
								$result = true;
							break;
						case IPSSONOS_FNC_PAUSE:	
								$sonos->Pause();
								$this->LogDbg('Abspielen im Raum '.$roomName.' pausiert.');
								$value = IPSSONOS_TRA_PAUSE;
								$result = true;
							break;
						case IPSSONOS_FNC_NEXT:	
								$sonos->Next();
								$this->LogDbg('Nächstes Lied im Raum '.$roomName.' abspielen.');
								$function 	= IPSSONOS_FNC_PLAY;								
								$value 		= IPSSONOS_TRA_PLAY;
								$result = true;
							break;		
						case IPSSONOS_FNC_PREVIOUS:	
								$sonos->Previous();
								$this->LogDbg('Vorheriges Lied im Raum '.$roomName.' abspielen.');
								$function 	= IPSSONOS_FNC_PLAY;								
								$value 		= IPSSONOS_TRA_PLAY;
								$result = true;
							break;
						case IPSSONOS_FNC_MUTE:	
								$sonos->SetMute($value);
								$this->LogDbg('Mute im Raum '.$roomName.' gesetzt auf: '.$value);
								$function 	= IPSSONOS_FNC_MUTE;
								$result = true;
							break;	
						case IPSSONOS_FNC_SEEKPOSITION:
								$PositionPercent = $room->GetValue(IPSSONOS_CMD_VARIABLE, IPSSONOS_VAR_DURATION);
								$SongDuration 	= time_to_sec($PositionPercent);
								$PositionSeek 	= sec_to_time($SongDuration * $value / 100);
								$sonos->Seek("REL_TIME",$PositionSeek);
								$this->LogDbg('Seek im Raum '.$roomName.' gesetzt auf: '.$PositionSeek);
								$result = true;
							break;									
						case IPSSONOS_FNC_PLAYPLNAME:
								if ($this->SetQueuePlaylistByName($room, $sonos, $value)== true) {
									$sonos->Play();
									$this->LogDbg('Playlist '.$value.' im Raum '.$roomName.' gestartet.');
									$function 	= IPSSONOS_FNC_PLAY;
									$value 		= IPSSONOS_TRA_PLAY;
									$result = true;
								}
								else {
								$this->LogErr('Playlist '.$value.' konnte im Raum '.$roomName.' nicht gestartet werden!');
								$result = false;
								}	
							break;
						case IPSSONOS_FNC_PLAYPLID:	
								if ($this->SetQueuePlaylistByID($room, $sonos, $value)== true) {
									$sonos->Play();
									$this->LogDbg('Playlist mit ID '.$value.' im Raum '.$roomName.' gestartet.');
									$function 	= IPSSONOS_FNC_PLAY;
									$value 		= IPSSONOS_TRA_PLAY;
									$result = true;
								}
								else {
								$this->LogErr('Playlist mit ID '.$value.' konnte im Raum '.$roomName.' nicht gestartet werden!');
								$result = false;
								}	
							break;
						case IPSSONOS_FNC_PLAYRDNAME:	
								if ($this->SetQueueRadiostationByName($room, $sonos, $value)== true) {
									$sonos->Play();
									$this->LogDbg('Radiostation '.$value.' im Raum '.$roomName.' gestartet.');
									$function 	= IPSSONOS_FNC_PLAY;
									$value 		= IPSSONOS_TRA_PLAY;
									$result = true;
								}
								else {
								$this->LogErr('Radiostation '.$value.' konnte im Raum '.$roomName.' nicht gestartet werden!');
								$result = false;
								}	
							break;
						case IPSSONOS_FNC_PLAYRDID:	
								if ($this->SetQueueRadiostationByID($room, $sonos, $value)== true) {
									$sonos->Play();
									$this->LogDbg('Playlist mit ID '.$value.' im Raum '.$roomName.' gestartet.');
									$function 	= IPSSONOS_FNC_PLAY;
									$value 		= IPSSONOS_TRA_PLAY;
									$result = true;
								}
								else {
								$this->LogErr('Radiostation mit ID '.$value.' konnte im Raum '.$roomName.' nicht gestartet werden!');
								$result = false;
								}	
							break;							
						case IPSSONOS_FNC_SHUFFLE:
								$repeat = IPSSonos_GetRepeat($roomName);
								if ($repeat == true) {
									if ($value == true) { // SHUFFLE == true
										$mode = "SHUFFLE";
									}
									else { // SHUFFLE == false
										$mode = "REPEAT_ALL";
									}
								}
								else { //$repeat == false
									if ($value == true) { // SHUFFLE == true
										$mode = "SHUFFLE_NOREPEAT";
									}
									else { // SHUFFLE == false
										$mode = "NORMAL";
									}								
								}
								$sonos->SetPlayMode($mode);
								$this->LogDbg('Mode im Raum '.$roomName.' gesetzt auf: '.$mode);
								$result = true;
							break;								
						case IPSSONOS_FNC_REPEAT:
								$shuffle = IPSSonos_GetShuffle($roomName);
								if ($shuffle == true) {
									if ($value == true) { // Repeat == true
										$mode = "SHUFFLE";
									}
									else { // Repeat == false
										$mode = "SHUFFLE_NOREPEAT";
									}
								}
								else { //$shuffle == false
									if ($value == true) { // Repeat == true
										$mode = "REPEAT_ALL";
									}
									else { // Repeat == false
										$mode = "NORMAL";
									}								
								}					
								$sonos->SetPlayMode($mode);
								$this->LogDbg('Mode im Raum '.$roomName.' gesetzt auf: '.$mode);
								$result = true;
							break;							
						default:
							break;
					}

					// Update variables of room
					if ($result) $room->setvalue($command, $function, $value);						
					break;
				
				case IPSSONOS_CMD_ROOM:

					// Sonos initialisieren
					$room = $this->GetRoom($roomName);
					$sonos = new PHPSonos($room->IPAddr);
					
					if ($room->ValidateValue($sonos, $command, $function, $value) == false) {
						return false;
					}
					
					switch ($function) {
						case IPSSONOS_FNC_POWER:
							
							//Raum ausschalten
							if ($value == false) {
								$result = $sonos->Stop();
								//Reset profile of variable to show OnDelay
								$variableId  = IPS_GetObjectIDByIdent(IPSSONOS_VAR_ROOMPOWER, $room->instanceId);
								IPS_SetVariableCustomProfile($variableId , "IPSSonos_Power");
							}
							// Raum schalten
							if( IPSSonos_Custom_SetRoomPower($roomName, $value) == true) {
								
								//Switch profile of variable to show OnDelay and arm timer when switched on
								if( $value == true) {
									$variableId  = IPS_GetObjectIDByIdent(IPSSONOS_VAR_ROOMPOWER, $room->instanceId);
									IPS_SetVariableCustomProfile($variableId , "IPSSonos_PowerOnDelay");
									
									//Arm timer
									IPSUtils_Include ('IPSModuleManager.class.php', 'IPSLibrary::install::IPSModuleManager');
									$moduleManager 		= new IPSModuleManager('IPSSonos');	
									$CategoryIdApp      = $moduleManager->GetModuleCategoryID('app');
									$id_ScriptSettings  = IPS_GetScriptIDByName('IPSSonos_ChangeSettings', 		$CategoryIdApp);
									$TimerId = IPS_GetEventIDByName(IPSSONOS_EVT_POWERONDELAY, $id_ScriptSettings);
									IPS_SetEventActive($TimerId, true);
								}
								$this->LogDbg('Power im Raum '.$roomName.' gesetzt auf: '.$value.'!');
								$result = true;
							}
							else {
								$this->LogErr('Raum '.$roomName.' konnte nicht geschaltet werden, Fehler in benutzerdefinierten Funktione IPSSonos_Custom_SetRoomPower!');
								$result = false;
								}
							break;							
						default:
							break;
					}
					
					// Update variables of room
					if ($result)
						$room->setvalue($command, $function, $value);
					break;
					
				case IPSSONOS_CMD_SERVER:
					
					switch ($function) {
						case IPSSONOS_FNC_SYNCPL:
							$result = $this -> Sync_Playlists();
							break;
						case IPSSONOS_FNC_SYNCRD:
							$result = $this -> Sync_Radiostations();
							break;
						case IPSSONOS_FNC_ALLROOMSOFF: 
							$activeRooms = IPSSonos_GetAllActiveRooms();
							foreach ($activeRooms as $roomName) {
								IPSSonos_SetRoomPower($roomName, false);
							}
							break;						
						case IPSSONOS_FNC_SETQUERY:
							$result = $this -> QuerySwitch($value);
							break;								
						case IPSSONOS_FNC_SETQUERYTIME:
							$result = $this -> QuerySetTime($value);
							break;
						case IPSSONOS_FNC_MSGTTSRS:
							$params =  array (
									"Rooms"					=>  $roomName,              
									"Volume_Ramp"     		=>  'slow',
									"Text"					=>  $value,
									"Type"					=>  'TTS_Simple', 
									"TTS_Simple_Language"	=>  'de',          						
							);
							$result = $this -> PlayMessage($params);
							break;
						case IPSSONOS_FNC_MSGTTSAS:
							$activeRooms = IPSSonos_GetAllActiveRooms();
							$l_first_loop = true;
							foreach ($activeRooms as $roomName) {
								if ($l_first_loop == true)  {
									$rooms = $roomName;
									$l_first_loop = false;
								}
								else {
									$rooms = $rooms.",".$roomName;								
								}
							}
							$params =  array (
									"Rooms"					=>  $rooms,              
									"Volume_Ramp"     		=>  'slow',
									"Text"					=>  $value,
									"Type"					=>  'TTS_Simple', 
									"TTS_Simple_Language"	=>  'de',          						
							);
							$result = $this -> PlayMessage($params);
							break;	
						case IPSSONOS_FNC_MSGGEN:
							$result = $this -> PlayMessage($value);
							break;								
						default:
							break;
					}						
					break;					
				default:
					break;
			}			
			return $result;
		}
		
		
		private function SetQueuePlaylistByName($room, $sonos, $playlist) {
			
			$result			= false;
			$sonos_lists 	= $sonos->GetSONOSPlaylists();
			$playlistid		= 0;

			if ($sonos_lists <> null) {
				foreach ($sonos_lists as $playlist_key => $ls_playlist) {

					$Playlist_File 		= urldecode($ls_playlist['file']);
					$Playlist_Title 	= utf8_decode($ls_playlist['title']);
					
					if ($Playlist_Title === $playlist) {
						$sonos->ClearQueue();
						$sonos->AddToQueue($Playlist_File);
						$sonos->SetQueue("x-rincon-queue:".$room->RINCON."#0");
						$result = true;						
						// Update variables of room
						$room->setvalue(IPSSONOS_CMD_AUDIO, IPSSONOS_FNC_PLAYPLNAME, $playlistid);							
					}
					$playlistid = $playlistid + 1;
				}
			}
			else $this->LogErr('Fehler beim Lesen der Sonsos Playlist: '.$playlist);	
			return $result;
		}
		
		private function SetQueuePlaylistByID($room, $sonos, $playlistid) {
			$result = false;
			$sonos_lists = $sonos->GetSONOSPlaylists();
		
			if ($sonos_lists <> null) {

				$Playlist_File 		= urldecode($sonos_lists[$playlistid]['file']);				
				$sonos->ClearQueue();
				$sonos->AddToQueue($Playlist_File);
				$sonos->SetQueue("x-rincon-queue:".$room->RINCON."#0");
				$result = true;
				
				// Update variables of room
				$room->setvalue(IPSSONOS_CMD_AUDIO, IPSSONOS_FNC_PLAYPLID, $playlistid);	
			}
			else $this->LogErr('Fehler beim Lesen der Sonsos PlaylistID: '.$playlistid);		
			return $result;
		}				
		private function SetQueueRadiostationByName($room, $sonos, $radiostation) {

			$result			= false;
			$sonos_lists  	= $sonos->Browse("R:0/0","c");
			$listid		= 0;
			
			if ($sonos_lists <> null) {
				foreach ($sonos_lists as $list_key => $ls_list) {

					$List_File 		= urldecode($ls_list['res']);
					$List_Title 	= utf8_decode($ls_list['title']);
					
					if ($List_Title === $radiostation) {					
						$sonos->ClearQueue();
						$sonos->SetRadio(urldecode($List_File));
						$result = true;
						// Update variables of room
						$room->setvalue(IPSSONOS_CMD_AUDIO, IPSSONOS_FNC_PLAYRDNAME, $listid);						
					}
					$listid = $listid + 1;
				}
			}
			else $this->LogErr('Fehler beim Lesen der Sonsos Radiostation: '.$radiostation);	
			return $result;
		}
		
		private function SetQueueRadiostationByID($room, $sonos, $radiostationid) {
			$result = false;
			$browselist = $sonos->Browse("R:0/0","c");
		
			if ($browselist <> null) {
				$sonos->ClearQueue();
				$sonos->SetRadio(urldecode($browselist[$radiostationid]['res'])); 
				$result = true;
				
				// Update variables of room
				$room->setvalue(IPSSONOS_CMD_AUDIO, IPSSONOS_FNC_PLAYRDID, $radiostationid);	
			}
			else $this->LogErr('Fehler beim Lesen der Sonsos RadiostationID: '.$radiostationid);		
			return $result;
		}

		/**
		 *  @brief Brief
		 *  
		 *  @return Return_Description
		 *  
		 *  @details Details
		 */
		private function Sync_Playlists() {
		
			// Delete old entries		
			$varprofile = IPS_GetVariableProfile('IPSSonos_Playlists');

			foreach ($varprofile["Associations"] as $Variable => $Line) {
					$value = $Line["Value"];
				IPS_SetVariableProfileAssociation('IPSSonos_Playlists', $value, null, null, null);
			};
			
			//Get new Playlists from Sonos Server
			$playlist_key	= 0;
			$sonos 			= new PHPSonos($this->IPAddr); 
			$sonos_lists 	= $sonos->GetSONOSPlaylists();
			if ($sonos_lists != null) {
				foreach ($sonos_lists as $playlist_key => $ls_playlist) {
	//				$Playlist_Sonos_ID 	= $ls_playlist['id'];
					$Playlist_Title 	= utf8_decode($ls_playlist['title']);
					IPS_SetVariableProfileAssociation('IPSSonos_Playlists', $playlist_key, $Playlist_Title, null, null);
					$playlist_key 		= $playlist_key + 1;
				}
				$this->LogInf('Playlists erfolgreich synchronisiert, '.$playlist_key.' Einträge vorhanden.');
				return true;
			}	
			else {
				$this->LogErr('Fehler beim Synchronisieren der Sonsos Playlists!');	
				return false;
			}			
		}
		
		/**
		 * @public
		 *
		 * Liefert ein IPSSonosRoom Objekt für eine Raum Nummer, sind keine Räume vorhanden
		 * liefert die Funktion false.
		 *
		 * @param integer $roomId Nummer des Raumes (1-4).
		 * @return IPSSonos_Room IPSSonos Room Object
		 */
		private function Sync_Radiostations() {

			// Delete old entries		
			$varprofile = IPS_GetVariableProfile('IPSSonos_Radiostations');

			foreach ($varprofile["Associations"] as $Variable => $Line) {
					$value = $Line["Value"];
					IPS_SetVariableProfileAssociation('IPSSonos_Radiostations', $value, null, null, null);
			};

			//Get new Playlists from Sonos Server
			$list_key	= 0;
			$sonos 			= new PHPSonos($this->IPAddr); 
			$radiostations_lists 	= $sonos->Browse("R:0/0","c"); 
			if ($radiostations_lists != null) {
				foreach ($radiostations_lists as $radiostation_key => $ls_radiostation) {
					$Radiostation_Title 	= utf8_decode($ls_radiostation['title']);
					IPS_SetVariableProfileAssociation('IPSSonos_Radiostations', $list_key, $Radiostation_Title, null, null);
					$list_key 		= $list_key + 1;
				}
				$this->LogInf('Radiostations erfolgreich synchronisiert, '.$list_key.' Einträge vorhanden.');
				return true;
			}	
			else {	
				$this->LogErr('Fehler beim Synchronisieren der Sonsos Radiostations!');	
				return false;
			}
		}	
		
		private function QuerySwitch($value) {		
			$variableId  = IPS_GetObjectIDByIdent(IPSSONOS_VAR_QUERY, $this->instanceId);
			if (GetValue($variableId)<>$value) {
				SetValue($variableId, $value);
				IPSUtils_Include ('IPSModuleManager.class.php', 'IPSLibrary::install::IPSModuleManager');
				$moduleManager 		= new IPSModuleManager('IPSSonos');	
				$CategoryIdApp      = $moduleManager->GetModuleCategoryID('app');
				$id_ScriptSettings  = IPS_GetScriptIDByName('IPSSonos_ChangeSettings', 		$CategoryIdApp);
				$TimerId = IPS_GetEventIDByName(IPSSONOS_EVT_QUERY, $id_ScriptSettings);
				IPS_SetEventActive($TimerId, $value);
				$this->LogInf('Abfrage der Sonos-Geräte gesetzt auf: '.WriteBoolean($value));
			}
		}
	
		private function QuerySetTime($value) {	
			$result = false;
			$variableId  = IPS_GetObjectIDByIdent(IPSSONOS_VAR_QUERYTIME, $this->instanceId);
			$Profile=IPS_GetVariableProfile('IPSSonos_Query');
			$duration = (int) $Profile['Associations'][$value]['Name'];
			if (GetValue($variableId)<>$value) {
				SetValue($variableId, $value);
				IPSUtils_Include ('IPSModuleManager.class.php', 'IPSLibrary::install::IPSModuleManager');
				$moduleManager 		= new IPSModuleManager('IPSSonos');	
				$CategoryIdApp      = $moduleManager->GetModuleCategoryID('app');
				$id_ScriptSettings  = IPS_GetScriptIDByName('IPSSonos_ChangeSettings', 		$CategoryIdApp);
				$TimerId = IPS_GetEventIDByName(IPSSONOS_EVT_QUERY, $id_ScriptSettings);
				if (IPS_SetEventCyclic($TimerId, 2 /*Daily*/, 1 /*Int*/,0 /*Days*/,0/*DayInt*/,1/*TimeType Sec*/,$duration/*Sec*/)) {
					$this->LogInf('Abstand zwischen den Abfragen der Sonos-Geräte gesetzt auf: '.$duration);
					$result = true;
				}
				if (!$result) $this->LogErr('Fehler beim setzen des zyklischen Events für Query');
			}
			return $result;	
		}
		
		private function PlayMessage($params) {
			
			// Initialize ---------------------------------------------------------------
//			set_time_limit(100);
//IPSLogger_Inf("IPSSonos", "Start");			
			$MessageConfig = IPSSonos_GetMessageConfiguration();
			$l_volume_ramp	= @$params["Volume_Ramp"]; 	if ($l_volume_ramp!='fast') $l_volume_ramp='slow';
			$l_sound_repeat = @$params["Sound_Repeat"];	if ($l_sound_repeat=='') $l_sound_repeat = 1;
			$l_sound_delay =  @$params["Sound_Delay"];	if ($l_sound_delay=='')  $l_sound_delay = 0;

			$rooms 			= explode(',',$params["Rooms"]);
			$count_rooms 	= count($rooms);
			$volume_max 	= 0;

			// Save volume settings of players ------------------------------------------
			for ($i = 0; $i < $count_rooms; $i++) {
				$room 					= $this->GetRoom($rooms[$i]);
				$sonos[$i] 				= new PHPSonos($room->IPAddr);
				$volume_start[$i] 		= $sonos[$i]->GetVolume();
				$volume_current[$i]		= $volume_start[$i];

				if ($volume_max < $volume_start[$i]) $volume_max = $volume_start[$i];
			}

			// Ramp-Down volume ---------------------------------------------------------
			if ($l_volume_ramp!='fast') {
				$volume = $volume_max;
				while ($volume>=1)
				{
				   for ($i = 0; $i < $count_rooms; $i++) {
						if($volume_current[$i] > 1) {
						   $volume_current[$i] = $volume_current[$i] - 2;
							$sonos[$i]->SetVolume($volume_current[$i]);
						}
					}
				   $volume = $volume - 1;
				   IPS_Sleep(75);
				}
			} else {
//			   $ramp_type = "AUTOPLAY_RAMP_TYPE";
//				for ($i = 0; $i < $count_rooms; $i++) {
//					$sonos[$i]->RampToVolume($ramp_type, 0);
//					$sonos[$i]->SetVolume($volume_start[$i]);
//				}
			}
//IPSLogger_Inf("IPSSonos", "Ramp-Down fertig");				
		   // Save status of players ---------------------------------------------------
			for ($i = 0; $i < $count_rooms; $i++) {
				$oldpi[$i] = $sonos[$i]->GetPositionInfo();
				$oldmi[$i] = $sonos[$i]->GetMediaInfo();
				$radio[$i] = (strpos($oldmi[$i]['CurrentURI'],"x-sonosapi-stream:")>0)===false;
				$oldti[$i] = $sonos[$i]->GetTransportInfo();
				$sonos[$i]->stop();
			}

			// Play Sound ---------------------------------------------------------------
		   if (@$params["Sound"]!='') {
		   
				// Set Volume for playing sound
				for ($i = 0; $i < $count_rooms; $i++) {
					$l_volume = $volume_start[$i] + @$params["Sound_Volume_Offset"];
					$sonos[$i]->SetVolume($l_volume);
					$l_song = "x-file-cifs:".$MessageConfig[IPSSONOS_VAR_SMBPATH].$params["Sound"];
					$sonos[$i]->SetAVTransportURI($l_song);
				}
				
				// Start loop
				for ($h = 0; $h < $l_sound_repeat; $h++) {
					for ($i = 0; $i < $count_rooms; $i++) {
						$sonos[$i]->Play();
					}
					//Wait till first player ended with playing text
					while ($sonos[0]->GetTransportInfo()==1)
					{
					   IPS_Sleep(200);
					}
					// Wait specified time, but not in the last loop.
					if ($h < ($l_sound_repeat - 1)) IPS_Sleep((int) $l_sound_delay);
			  }
			}
//IPSLogger_Inf("IPSSonos", "Play Sound fertig");	
		   // Play Text ----------------------------------------------------------------
		   if (@$params["Text"]!='') {
				switch ($params["Type"]) {
					case "TTS_Simple":
							$filename 	= "IPSSonos_Speech.mp3";
							$file		= $MessageConfig[IPSSONOS_VAR_LPATH].$filename;
							$text_utf8 	= urlencode(utf8_encode($params["Text"]));
							$mp3 		= @file_get_contents('http://translate.google.com/translate_tts?tl='.$params["TTS_Simple_Language"].'&ie=UTF-8&q='.$text_utf8);
							if((strpos($http_response_header[0], "200") != false)) {
									file_put_contents($file, $mp3);
							}
							break;
					case "TTS":
							$filename 	= "IPSSonos_Speech.wav";
							$file 		= $MessageConfig[IPSSONOS_VAR_LPATH].$filename;
							TTS_GenerateFile( (int) $MessageConfig[IPSSONOS_VAR_TTSID] , $params["Text"], $file, 39);
							IPS_Sleep(500);
						break;
					default:
						break;
				}
				
				for ($i = 0; $i < $count_rooms; $i++) {
					$sonos[$i]->SetVolume($volume_start[$i]);
					$sonos[$i]->SetAVTransportURI("x-file-cifs:".$MessageConfig[IPSSONOS_VAR_SMBPATH].$filename);
					$sonos[$i]->Play();
				}
				
				//Wait till first player ended with playing text
				IPS_Sleep(500);
				while ($sonos[0]->GetTransportInfo()==1)
				{
					IPS_Sleep(200); 
				}
			}
//IPSLogger_Inf("IPSSonos", "Play Text fertig");		
			//Reset players to initial state and start playing with volume 0
			for ($i = 0; $i < $count_rooms; $i++) {
				if ($radio[$i])
				{
					$sonos[$i]->SetRadio($oldmi[$i]['CurrentURI']);
				}
				else
				{
					$sonos[$i]->SetAVTransportURI($oldmi[$i]['CurrentURI'],$oldmi[$i]['CurrentURIMetaData']);
				}

				try
				{
					// Seek TRack_Nr
				   $sonos[$i]->Seek("TRACK_NR",$oldpi[$i]['Track']);
				   // Seek REl_time
				   $sonos[$i]->Seek("REL_TIME",$oldpi[$i]['RelTime']);
				}
				catch (Exception $e)
				{

				}
				$sonos[$i]->SetVolume(0);
				if ($oldti[$i]==1) $sonos[$i]->Play();
			}
//IPSLogger_Inf("IPSSonos", "Alter Zustand herstellen fertig");
			//Ramp-Up volume to initial level -------------------------------------------
		   if ($l_volume_ramp!='fast') {
				$volume = 0;
				IPS_Sleep(1000);
				while ($volume < $volume_max) {
				   for ($i = 0; $i < $count_rooms; $i++) {
						 if($volume_current[$i] < $volume_start[$i]) {
						$volume_current[$i] = $volume_current[$i] +1;
							$sonos[$i]->SetVolume($volume_current[$i]);
						 }
					}
				   $volume = $volume + 1;
				   IPS_Sleep(50);
				}
			} else {
			   $ramp_type = "AUTOPLAY_RAMP_TYPE";
				for ($i = 0; $i < $count_rooms; $i++) {
				//	$sonos[$i]->RampToVolume($ramp_type, $volume_start[$i]);
					$sonos[$i]->SetVolume($volume_start[$i]);
				}
			}
//IPSLogger_Inf("IPSSonos", "Ramp Up fertig");			
		}		
	}
	
	function time_to_sec($time) {
		$hours = substr($time, 0, -6);
		$minutes = substr($time, -5, 2);
		$seconds = substr($time, -2);
		return $hours * 3600 + $minutes * 60 + $seconds;
	}

	function sec_to_time($seconds) {
		$hours = floor($seconds / 3600);
		$minutes = floor($seconds % 3600 / 60);
		$seconds = $seconds % 60;
		return sprintf("%02d:%02d:%02d", $hours, $minutes, $seconds);
	}	
	/** @}*/
?>