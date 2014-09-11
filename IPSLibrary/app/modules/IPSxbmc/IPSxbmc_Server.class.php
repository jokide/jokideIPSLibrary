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

	/**@defgroup IPSxbmc IPSxbmc Multiroom Steuerung
	 * @ingroup hardware
	 * @{
	 *
	 * Klasse zur Kommunikation mit dem IPSxbmc Device 
	 *
	 * @file          IPSxbmc_Server.class.php
	 * @author        Andreas Brauneis
	 *
	 */

	IPSUtils_Include ("IPSLogger.inc.php",              "IPSLibrary::app::core::IPSLogger");
	IPSUtils_Include ("IPSxbmc_Constants.inc.php",     "IPSLibrary::app::modules::IPSxbmc");
	IPSUtils_Include ("IPSxbmc_Configuration.inc.php", "IPSLibrary::config::modules::IPSxbmc");
	IPSUtils_Include ("IPSxbmc_Room.class.php",        "IPSLibrary::app::modules::IPSxbmc");
	IPSUtils_Include ("IPSxbmc_Custom.inc.php",        "IPSLibrary::config::modules::IPSxbmc");
	IPSUtils_Include ("IPSxbmc.inc.php", 				"IPSLibrary::app::modules::IPSxbmc");

   /**
    * @class IPSxbmc_Server
    *
    * Definiert ein IPSxbmc_Server Objekt
    *
    * @author Andreas Brauneis
    * @version
    * Version 2.50.1, 31.01.2012<br/>
    */
	class IPSxbmc_Server {

		/**
		 * @private
		 * ID des IPSxbmc Server
		 */
		private $instanceId;

		/**
		 * @private
		 * IPadress
		 */
		private $IPAddr;

		/**
		 * @private
		 * Debugging of IPSxbmc Server Enabled/Disabled
		 */
		private $debugEnabled;

		/** 
		 * @public
		 *
		 * Initializes the IPSxbmc Server
		 *
		 * @param integer $instanceId - ID des IPSxbmc Server.
		 */
		public function __construct($instanceId) {
			$this->instanceId   = $instanceId;
//			$this->IPAddr		= GetValue(IPS_GetObjectIDByIdent(IPSXBMC_VAR_IPADDR, $this->instanceId));
//			$this->debugEnabled = GetValue(IPS_GetObjectIDByIdent(IPSXBMC_VAR_MODESERVERDEBUG, $this->instanceId));
			$this->retryCount  = 0;
		}

		/**
		 * @private
		 *
		 * Protokollierung einer Meldung im IPSxbmc Log
		 *
		 * @param string $logType Type der Logging Meldung 
		 * @param string $msg Meldung 
		 */
		private function Log ($logType, $msg) {
			if ($this->debugEnabled) {
				IPSLogger_WriteFile("", 'IPSxbmc.log', date('Y-m-d H:i:s').'  '.$logType.' - '.$msg, null);
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
			IPSLogger_Err(__file__, $msg);
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
			IPSLogger_Inf(__file__, $msg);
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
			IPSLogger_Dbg(__file__, $msg);
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
			IPSLogger_Com(__file__, $msg);
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
			IPSLogger_Trc(__file__, $msg);
			$this->Log('Trc', $msg);
		}
		

	
		
		/**
		 * @private
		 *
		 * Liefert ein IPSxbmcRoom Objekt für eine Raum Nummer, sind keine Räume vorhanden
		 * liefert die Funktion false.
		 *
		 * @param integer $roomId Nummer des Raumes (1-4).
		 * @return IPSxbmc_Room IPSxbmc Room Object
		 */
		private function GetRoom($roomName) {
			$roomIds = GetValue(IPS_GetObjectIDByIdent(IPSXBMC_VAR_ROOMIDS, $this->instanceId));
			if ($roomIds=="") {
				return false;
			}
			$roomIds        = explode(',',  $roomIds);
			$roomInstanceId = false;
			$IPSxbmcRoom   = false;
			foreach ($roomIds as $roomId){
				if ($roomName == IPS_GetName((int)$roomId)) {
					$roomInstanceId = (int)$roomId;
					$IPSxbmcRoom = new IPSxbmc_Room($roomInstanceId);
				}
			}
			
			if ($IPSxbmcRoom) {
				$result = $IPSxbmcRoom;
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
		 * Liefert ein IPSxbmcRoom Objekt für eine Raum Nummer, sind keine Räume vorhanden
		 * liefert die Funktion false.
		 *
		 * @param integer $roomId Nummer des Raumes (1-4).
		 * @return IPSxbmc_Room IPSxbmc Room Object
		 */
		private function GetAllRooms() {
			$roomIds = GetValue(IPS_GetObjectIDByIdent(IPSXBMC_VAR_ROOMIDS, $this->instanceId));
			if ($roomIds=="") {
				return false;
			}
			$roomIds        = explode(',',  $roomIds);
			foreach ($roomIds as $roomId){
				$room_array[] = IPS_GetName((int)$roomId);
			}

			return $room_array;
		}

		/**
		 * @private
		 *
		 * Lesen der IPSxbmc Werte aus den Instanz Variablen
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

				case IPSXBMC_CMD_ROOM:		
					
					$room = $this->GetRoom($roomName);				
					$result = $room->GetValue ($command, $function);

					break;
				case IPSXBMC_CMD_PLAYER:
						$room = $this->GetRoom($roomName);				
						$result = $room->GetValue ($command, $function);
					break;
				
				case IPSXBMC_CMD_SERVER:
					
					switch ($function) {
						case IPSXBMC_FNC_ROOMS:
							$result = $this->GetAllRooms();
							break;	
						case IPSXBMC_FNC_ROOMSACTIVE:
							$result = $this->GetActiveRooms();
							break;									
						default:
							break;
					}						
					break;					
				default:
					break;	
				default:
					$this->LogErr('Unknown Command '.$command);
			}
			return $result;
		}


		/**
		 * @public
		 *
		 * Senden von Befehlen zum IPSxbmc Server
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
				case IPSXBMC_CMD_PLAYER:
				
					// xbmc initialisieren
					$room = $this->GetRoom($roomName);
					
					if ($room->ValidateValue($command, $function, $value) == false) {
						return false;
					}					
					switch ($function) {
						case IPSXBMC_FNC_VOLUME:
							break;								
						case IPSXBMC_FNC_VOLUME_INC:	
							break;	
						case IPSXBMC_FNC_VOLUME_DEC:							
							break;
						case IPSXBMC_FNC_PLAY:	
								RegVar_SendText($room->regvarId, '{"jsonrpc": "2.0", "method": "Player.PlayPause", "params": { "playerid": 1 }, "id": 1}');
								$result = true;
							break;
						case IPSXBMC_FNC_STOP:	
							break;
						case IPSXBMC_FNC_PAUSE:	
							break;
						case IPSXBMC_FNC_NEXT:	
							break;
						case IPSXBMC_FNC_MUTE:	
							break;
						case IPSXBMC_FNC_INPUT:
							switch ($value) {
								case "ContextMenu":	
									RegVar_SendText($room->regvarId, '{"jsonrpc": "2.0", "method": "Input.ContextMenu", "id": 1}');
									break;
								case "Select":	
									RegVar_SendText($room->regvarId, '{"jsonrpc": "2.0", "method": "Input.Select", "id": 1}');
									break;
								case "Right":	
									RegVar_SendText($room->regvarId, '{"jsonrpc": "2.0", "method": "Input.Right", "id": 1}');
									break;	
								case "Left":	
									RegVar_SendText($room->regvarId, '{"jsonrpc": "2.0", "method": "Input.Left", "id": 1}');
									break;	
								case "Up":	
									RegVar_SendText($room->regvarId, '{"jsonrpc": "2.0", "method": "Input.Up", "id": 1}');
									break;	
								case "Down":	
									RegVar_SendText($room->regvarId, '{"jsonrpc": "2.0", "method": "Input.Down", "id": 1}');
									break;
								case "Back":	
									RegVar_SendText($room->regvarId, '{"jsonrpc": "2.0", "method": "Input.Back", "id": 1}');
									break;											
								case "ShowOSD":	
									RegVar_SendText($room->regvarId, '{"jsonrpc": "2.0", "method": "Input.ShowOSD", "id": 1}');
									break;											
								case "smallforward":	
									RegVar_SendText($room->regvarId, '{"jsonrpc": "2.0", "method": "Player.Seek", "params": { "playerid": 1,"value":"smallforward" }, "id": 1}');
									break;											
								case "smallbackward":	
									RegVar_SendText($room->regvarId, '{"jsonrpc": "2.0", "method": "Player.Seek", "params": { "playerid": 1,"value":"smallbackward" }, "id": 1}');
									break;																					
								default:
									break;
							}
							break;						
						default:
							break;
					}

					// Update variables of room
					if ($result) $room->setvalue($command, $function, $value);						
					break;
				
				case IPSXBMC_CMD_ROOM:

					// xbmc initialisieren
					$room = $this->GetRoom($roomName);
					
					switch ($function) {
						case IPSXBMC_FNC_POWER:
							
							//Switch Room
							if ($value == false) {
								$result = IPSxbmc_Stop($roomName);
							}
							//
							if( IPSxbmc_Custom_SetRoomPower($roomName, $value) == true) {
								IPS_SetProperty($room->SocketSendID, 'Open', $value);
								IPS_ApplyChanges($room->SocketSendID);
								$this->LogInf('Power im Raum '.$roomName.' gesetzt auf: '.$value.'!');
								$result = true;
							}
							else {
								$this->LogErr('Raum '.$roomName.' konnte nicht geschaltet werden, Fehler in benutzerdefinierten Funktione IPSxbmc_Custom_SetRoomPower!');
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
					
				case IPSXBMC_CMD_SERVER:
					
					switch ($function) {							
						default:
							break;
					}						
					break;					
				default:
					break;
			}	
			

			
			return $result;
		}
}

	/** @}*/
?>