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
	 * @file          IPSxbmc_Room.class.php
	 * @author        Andreas Brauneis
	 *
	 */

   /**
    * @class IPSxbmc_Room
    *
    * Definiert ein IPSxbmc_Room Objekt
    *
    * @author Andreas Brauneis
    * @version
    * Version 2.50.1, 31.01.2012<br/>
    */
	class IPSxbmc_Room {

		public $IPAddr;
		public $regvarId;
	    public $instanceId;
		public $roomName;	
		public $SocketSendID;	
		
	   /**
       * @private
       * ID des IPSxbmc Server
       */

	  
	  
     	/**
       * @private
       * Variablen Mapping der Befehle
       */
		private $functionMapping;

		/**
       * @public
		 *
		 * Initialisiert einen IPSxbmc Raum
		 *
	    * @param $instanceId - ID des IPSxbmc Server.
		 */
      public function __construct($instanceId) {
		$this->instanceId = $instanceId;
		$this->roomName = IPS_GetName((int)$instanceId);

		$this->functionMapping = array(
			IPSXBMC_FNC_VOLUME 			=> IPSXBMC_VAR_VOLUME,
			IPSXBMC_FNC_VOLUME_INC 		=> IPSXBMC_VAR_VOLUME,
			IPSXBMC_FNC_VOLUME_DEC 		=> IPSXBMC_VAR_VOLUME,
			IPSXBMC_FNC_VOLUME_RMP			=> IPSXBMC_VAR_VOLUME,
			IPSXBMC_FNC_VOLUME_RMPMUTE		=> IPSXBMC_VAR_VOLUME,	
			IPSXBMC_FNC_VOLUME_RMPMUTESLOW	=> IPSXBMC_VAR_VOLUME,				
			IPSXBMC_FNC_MUTE 				=> IPSXBMC_VAR_MUTE,
			IPSXBMC_FNC_PLAY 				=> IPSXBMC_VAR_TRANSPORT,
			IPSXBMC_FNC_STOP 				=> IPSXBMC_VAR_TRANSPORT,
			IPSXBMC_FNC_PAUSE 				=> IPSXBMC_VAR_TRANSPORT,
			IPSXBMC_FNC_NEXT 				=> IPSXBMC_VAR_TRANSPORT,
			IPSXBMC_FNC_PREVIOUS			=> IPSXBMC_VAR_TRANSPORT,
			IPSXBMC_FNC_PLAYPLID			=> IPSXBMC_VAR_PLAYLIST,
			IPSXBMC_FNC_PLAYPLNAME			=> IPSXBMC_VAR_PLAYLIST,
			IPSXBMC_FNC_PLAYRDID			=> IPSXBMC_VAR_RADIOSTATION,
			IPSXBMC_FNC_PLAYRDNAME			=> IPSXBMC_VAR_RADIOSTATION,				
			IPSXBMC_FNC_SHUFFLE			=> IPSXBMC_VAR_SHUFFLE,
			IPSXBMC_FNC_REPEAT				=> IPSXBMC_VAR_REPEAT,
			);
			
		$this->IPAddr = GetValue(IPS_GetObjectIDByIdent(IPSXBMC_VAR_IPADDR, $this->instanceId));
		$this->SocketSendID = (int) GetValue(IPS_GetObjectIDByIdent(IPSXBMC_VAR_SOCKETSEND, $this->instanceId));
		$this->regvarId = (int) IPS_GetObjectIDByIdent(IPSXBMC_VAR_COMREGVAR, $this->instanceId);

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
		      case IPSXBMC_CMD_ROOM:
		         $variableName = IPSXBMC_VAR_ROOMPOWER;
		         break;
				case IPSXBMC_CMD_PLAYER:
		      	$variableName = $this->functionMapping[$function];
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
		public function ValidateValue($command, $function, $value) {
			$errorMsg = '';
			$result   = false;
			
			if( Sys_Ping( $this->IPAddr, 200 ) == false) {
				$this->LogErr('Aktion im Raum '.$this->roomName.' konnte nicht ausgeführt werden, da Gerät nicht erreichbar!');
				return false;
			}

			if( $this->GetValue(IPSXBMC_CMD_ROOM, IPSXBMC_FNC_POWER) == false) {
				$this->LogErr('Aktion im Raum '.$this->roomName.' konnte nicht ausgeführt werden, da Gerät nicht eingeschaltet ist!');
				return false;
			}			
			
 			switch($command) {

				case IPSXBMC_CMD_PLAYER:
//				   $roomOk   = $roomId>=0 and $roomId<GetValue(IPS_GetObjectIDByIdent('ROOM_COUNT', $this->instanceId));
					switch($function) {
						case IPSXBMC_FNC_VOLUME: /*0..78*/
//							$result = $roomOk and ($value>=IPSxbmc_VAL_VOLUME_MIN and $value<=IPSxbmc_VAL_VOLUME_MAX);
//							$errorMsg = "Value '$value' for Volume NOT in Range (use ".IPSxbmc_VAL_VOLUME_MIN." <= value <=".IPSxbmc_VAL_VOLUME_MAX.")";
							break;
						case IPSXBMC_FNC_MUTE: /*0..78*/
//							$result = $roomOk and ($value==true or $value==IPSxbmc_VAL_BOOLEAN_TRUE or $value==false or $value==IPSxbmc_VAL_BOOLEAN_FALSE);
//							$errorMsg = "Value '$value' for Mute NOT in Range (use 0,1 or boolean)";
							break;
						default:
//							$errorMsg = "Unknonw function '$function' for Command '$command'";
					}
					break;
				default:
//					$errorMsg = "Unknonw Command '$command'";
			}
			if (!$result) {
//				$this->LogErr($errorMsg);
			}
			return true;
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
		 * Protokollierung einer Meldung im IPSxbmc Log
		 *
		 * @param string $logType Type der Logging Meldung 
		 * @param string $msg Meldung 
		 */
		private function Log ($logType, $msg) {
			
				IPSLogger_WriteFile("", 'IPSxbmc.log', date('Y-m-d H:i:s').'  '.$logType.' - '.$msg, null);
			
		}	
	}

	/** @}*/
?>