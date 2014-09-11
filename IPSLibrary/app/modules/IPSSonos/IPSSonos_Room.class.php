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
    * @class IPSSonos_Room
    *
    * Definiert ein IPSSonos_Room Objekt
    *
	* @author        joki
	* @version
	* Version 1.0.2, 11.09.2014<br/>
    */
	IPSUtils_Include ("IPSSonos_Constants.inc.php",       "IPSLibrary::app::modules::IPSSonos");
	IPSUtils_Include ("IPSSonos_Configuration.inc.php",   "IPSLibrary::config::modules::IPSSonos");
	
	class IPSSonos_Room {

		public $IPAddr;
		public $RINCON;
	    public $instanceId;
		public $roomName;	
	  
     	/**
       * @private
       * Variablen Mapping der Befehle
       */
		private $functionMapping;

		/**
       * @public
		 *
		 * Initialisiert einen IPSSonos Raum
		 *
	    * @param $instanceId - ID des IPSSonos Server.
		 */
      public function __construct($instanceId) {
		$this->instanceId = $instanceId;
		$this->roomName = IPS_GetName((int)$instanceId);

		$this->functionMapping = array(
			IPSSONOS_FNC_VOLUME 			=> IPSSONOS_VAR_VOLUME,
			IPSSONOS_FNC_VOLUME_INC 		=> IPSSONOS_VAR_VOLUME,
			IPSSONOS_FNC_VOLUME_DEC 		=> IPSSONOS_VAR_VOLUME,
			IPSSONOS_FNC_VOLUME_RMP			=> IPSSONOS_VAR_VOLUME,
			IPSSONOS_FNC_VOLUME_RMPMUTE		=> IPSSONOS_VAR_VOLUME,	
			IPSSONOS_FNC_VOLUME_RMPMUTESLOW	=> IPSSONOS_VAR_VOLUME,				
			IPSSONOS_FNC_MUTE 				=> IPSSONOS_VAR_MUTE,
			IPSSONOS_FNC_PLAY 				=> IPSSONOS_VAR_TRANSPORT,
			IPSSONOS_FNC_STOP 				=> IPSSONOS_VAR_TRANSPORT,
			IPSSONOS_FNC_PAUSE 				=> IPSSONOS_VAR_TRANSPORT,
			IPSSONOS_FNC_NEXT 				=> IPSSONOS_VAR_TRANSPORT,
			IPSSONOS_FNC_PREVIOUS			=> IPSSONOS_VAR_TRANSPORT,
			IPSSONOS_FNC_PLAYPLID			=> IPSSONOS_VAR_PLAYLIST,
			IPSSONOS_FNC_PLAYPLNAME			=> IPSSONOS_VAR_PLAYLIST,
			IPSSONOS_FNC_PLAYRDID			=> IPSSONOS_VAR_RADIOSTATION,
			IPSSONOS_FNC_PLAYRDNAME			=> IPSSONOS_VAR_RADIOSTATION,				
			IPSSONOS_FNC_SHUFFLE			=> IPSSONOS_VAR_SHUFFLE,
			IPSSONOS_FNC_REPEAT				=> IPSSONOS_VAR_REPEAT,
			IPSSONOS_VAR_PLAYERDETAILS		=> IPSSONOS_VAR_PLAYERDETAILS,
			);
			
		$this->IPAddr = GetValue(IPS_GetObjectIDByIdent('IPADDR', $this->instanceId));
		$this->RINCON = GetValue(IPS_GetObjectIDByIdent('RINCON', $this->instanceId));
		}

		/**
       * @public
		 *
		 * Liefert den zugehörigen Variablen Namen für eine Message
		 *
	    * @param string $command Kommando
	    * @param string $function Funktion
		 */
		private function GetVariableName($command, $function) {
		   switch($command) {
		      case IPSSONOS_CMD_ROOM:
		         $variableName = IPSSONOS_VAR_ROOMPOWER;
		         break;
				case IPSSONOS_CMD_AUDIO:
		      	$variableName = $this->functionMapping[$function];
		         break;
				case IPSSONOS_CMD_SERVER:
		      	$variableName = $function;
		         break;				 
            default:
               throw new Exception('Unknown Command "'.$command.'", VariableName could NOT be found !!!');
		   }
	      return $variableName;
		}

		/**
       * @public
		 *
		 * Liefert den aktuellen Wert für eine Message
		 *
	    * @param string $command Kommando
	    * @param string $function Funktion
	    * @return string Wert
		 */
		public function GetValue ($command, $function) {
		   $name = $this->GetVariableName($command, $function);
			return GetValue(IPS_GetObjectIDByIdent($name, $this->instanceId));
		}

		/**
       * @public
		 *
		 * Setzt den Wert einer Variable auf den Wert einer Message
		 *
	    * @param string $command Kommando
	    * @param string $function Funktion
	    * @param string $value Wert
		 */
		public function SetValue ($command, $function, $value) {
		  $name        = $this->GetVariableName($command, $function);
	      $variableId  = IPS_GetObjectIDByIdent($name, $this->instanceId);
	      if (GetValue($variableId)<>$value) {
		 		SetValue($variableId, $value);
			}
		}
		/**
		 * @private
		 *
		 * Validierung der Daten
		 *
	    * @param string $type Kommando Type
	    * @param string $command Kommando
	    * @param integer $roomId Raum (1-4)
	    * @param string $function Funktion
	    * @param string $value Wert
	    * @return boolean TRUE für OK, FALSE bei Fehler
		 */
		public function ValidateValue($sonos, $command, $function, $value) {
			$errorMsg = '';
			$result   = false;
	
 			switch($command) {
		      case IPSSONOS_CMD_ROOM:
					switch ($function) {
						case IPSSONOS_FNC_POWER:						
							//Switch Room
 							if ($value == false) {
								// Check that room is on
								if( $this->GetValue(IPSSONOS_CMD_ROOM, IPSSONOS_FNC_POWER) == false) {
									$this->LogWrn('Raum '.$this->roomName.' konnte ausgeschaltet werden, da Gerät bereits ausgeschaltet ist!');
									return false;
								}
								// Check that Sonos device is reachable					
								if( Sys_Ping( $this->IPAddr, 200 ) == false) {
									$this->LogWrn('Raum '.$this->roomName.' konnte nicht ausgeschaltet werden, da Sonos-Gerät nicht erreichbar!');
									return false;
								}
							}
							$result   = true; 
							break;							
						default:
							break;
					}					

					break;				 
				case IPSSONOS_CMD_AUDIO:
				
					// Check that room is on
					if( $this->GetValue(IPSSONOS_CMD_ROOM, IPSSONOS_FNC_POWER) == false) {
						$this->LogWrn('Aktion im Raum '.$this->roomName.' konnte nicht ausgeführt werden, da Gerät nicht eingeschaltet ist!');
						return false;
					}
					// Check that Sonos device is reachable					
					if( Sys_Ping( $this->IPAddr, 200 ) == false) {
						$this->LogWrn('Aktion im Raum '.$this->roomName.' konnte nicht ausgeführt werden, da Gerät nicht erreichbar!');
						return false;
					}		
					switch($function) {
						case IPSSONOS_FNC_VOLUME:
							$result = $this->CheckVolume($value);
							$errorMsg = 'Lautstärkewert '.$value.' zu hoch für Raum '.$this->roomName;
							break;
						case IPSSONOS_FNC_VOLUME_RMP:
							$result = $this->CheckVolume($value);
							$errorMsg = 'Lautstärkewert '.$value.' zu hoch für Raum '.$this->roomName;
							break;
						case IPSSONOS_FNC_VOLUME_RMPMUTE:
							$result = $this->CheckVolume($value);
							$errorMsg = 'Lautstärkewert '.$value.' zu hoch für Raum '.$this->roomName;
							break;
						case IPSSONOS_FNC_VOLUME_RMPMUTESLOW:
							$result = $this->CheckVolume($value);
							$errorMsg = 'Lautstärkewert '.$value.' zu hoch für Raum '.$this->roomName;
							break;
						case IPSSONOS_FNC_VOLUME_INC:
							$sonos = new PHPSonos($this->IPAddr); 	
							$current_volume = $sonos->GetVolume();
							$new_volume = $current_volume + $value;
							$result = $this->CheckVolume($new_volume);
							$errorMsg = 'Erhöhung Lautstärke um '.$value.' zu hoch für Raum '.$this->roomName;
							break;									
						case IPSSONOS_FNC_MUTE: /*0..78*/
//							$result = $roomOk and ($value==true or $value==IPSSONOS_VAL_BOOLEAN_TRUE or $value==false or $value==IPSSONOS_VAL_BOOLEAN_FALSE);
//							$errorMsg = "Value '$value' for Mute NOT in Range (use 0,1 or boolean)";
							$result   = true;
							break;
						default:
							$result   = true;							
//							$errorMsg = "Unknonw function '$function' for Command '$command'";
					}
					break;
				default:
//					$errorMsg = "Unknonw Command '$command'";
			}
			if (!$result) {
				$this->LogWrn($errorMsg);
			}
			return $result;
		}
		
		/**
		 * @private
		 *
		 * Protokollierung einer Warning Meldung
		 *
		 * @param string $msg Meldung 
		 */
		private function LogWrn($msg) {
			IPSLogger_Wrn("IPSSonos", $msg);
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
		}

		/**
		 * @private
		 *
		 * Protokollierung einer Error Meldung
		 *
		 * @param string $msg Meldung 
		 */
		private function CheckVolume($value) {
		
			$RoomConfig = IPSSonos_GetRoomConfiguration();
			$GroupData = $RoomConfig[$this->roomName];
			$val_max = $GroupData[IPSSONOS_VAL_MAXVOL];
			$result = ($value>=0 and $value<=$val_max);
			return $result;
		}

	}

	/** @}*/
?>