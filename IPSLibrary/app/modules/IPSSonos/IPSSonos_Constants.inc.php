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
	 * IPSSonos Server Konstanten
	 *
	 * @file          IPSSonos_Constants.inc.php
	 * @author        joki
	 * @version
	 * Version 1.0.5, 21.09.2014<br/>
	 *
	 */

	// Kommunikations Kommandos
	define ('IPSSONOS_CMD_AUDIO',						'AUD');
	define ('IPSSONOS_CMD_ROOM',						'ROO');
	define ('IPSSONOS_CMD_SERVER',						'SRV');	
	define ('IPSSONOS_CMD_VARIABLE',					'VAR');	

	// Kommunikations Actions
	define ('IPSSONOS_FNC_POWER',						'PWR');
	define ('IPSSONOS_FNC_VOLUME',						'VOL');
	define ('IPSSONOS_FNC_VOLUME_INC',					'VIN');
	define ('IPSSONOS_FNC_VOLUME_DEC',					'VDE');
	define ('IPSSONOS_FNC_VOLUME_RMP',					'VOLUMERMP');	
	define ('IPSSONOS_FNC_VOLUME_RMPMUTE',				'VOLUMERMPMUTE');
	define ('IPSSONOS_FNC_VOLUME_RMPMUTESLOW',			'VOLUMERMPMUTESLOW');		
	define ('IPSSONOS_FNC_MUTE',						'MUT');
	define ('IPSSONOS_FNC_PLAY',						'PLA');
	define ('IPSSONOS_FNC_PAUSE',						'PAU');
	define ('IPSSONOS_FNC_STOP',						'STP');	
	define ('IPSSONOS_FNC_NEXT',						'NXT');
	define ('IPSSONOS_FNC_PREVIOUS',					'PRV');	
	define ('IPSSONOS_FNC_SYNCPL',						'SYNCPL');
	define ('IPSSONOS_FNC_PLAYPLID',					'PLAYPLID');
	define ('IPSSONOS_FNC_PLAYPLNAME',					'PLAYPLNAME');
	define ('IPSSONOS_FNC_SYNCRD',						'SYNCRD');
	define ('IPSSONOS_FNC_PLAYRDID',					'PLAYRDID');
	define ('IPSSONOS_FNC_PLAYRDNAME',					'PLAYRDNAME');		
	define ('IPSSONOS_FNC_ROOMS',						'ROOMS');
	define ('IPSSONOS_FNC_ROOMSACTIVE',					'ROOMSACTIVE');	
	define ('IPSSONOS_FNC_SHUFFLE',						'SHUFFLE');	
	define ('IPSSONOS_FNC_REPEAT',						'REPEAT');			
	define ('IPSSONOS_FNC_QUERYDATA',					'QUERYDATA');			
	define ('IPSSONOS_FNC_ALLROOMSOFF',					'ALLROOMSOFF');	
	define ('IPSSONOS_FNC_SETQUERY',					'SETQUERY');	
	define ('IPSSONOS_FNC_SETQUERYTIME',				'SETQUERYTIME');	
	define ('IPSSONOS_FNC_SEEKPOSITION',				'SEEKPOSITION');	
	
	
	//Define Transport
	define ('IPSSONOS_TRA_PREVIOUS',					'0');	
	define ('IPSSONOS_TRA_PLAY',						'1');
	define ('IPSSONOS_TRA_PAUSE',						'2');	
	define ('IPSSONOS_TRA_STOP',						'3');
	define ('IPSSONOS_TRA_NEXT',						'4');	

	//Define Variavle Values 
	define ('IPSSONOS_VAL_BOOLEAN_FALSE',				0);
	define ('IPSSONOS_VAL_BOOLEAN_TRUE',				1);
	define ('IPSSONOS_VAL_VOLUME_DEFAULT',				20);

	define ('IPSSONOS_VAL_MUTE_OFF',					IPSSONOS_VAL_BOOLEAN_FALSE);
	define ('IPSSONOS_VAL_MUTE_ON',						IPSSONOS_VAL_BOOLEAN_TRUE);
	define ('IPSSONOS_VAL_MUTE_DEFAULT',				IPSSONOS_VAL_BOOLEAN_FALSE);

//	define ('IPSSONOS_VAL_POWER_OFF',					IPSSONOS_VAL_BOOLEAN_FALSE);
//	define ('IPSSONOS_VAL_POWER_ON',					IPSSONOS_VAL_BOOLEAN_TRUE);
	define ('IPSSONOS_VAL_POWER_DEFAULT',				IPSSONOS_VAL_BOOLEAN_FALSE);
	define ('IPSSONOS_VAL_TRANSPORT',				    3);
	define ('IPSSONOS_VAL_PLAYLIST',				    0);
	define ('IPSSONOS_VAL_RADIOSTATION',			  	999);	
	define ('IPSSONOS_VAL_MAXVOL',			  			'MaxVol');	

	// Variablen Definitionen
	define ('IPSSONOS_VAR_ROOMCOUNT',					'ROOM_COUNT');
	define ('IPSSONOS_VAR_ROOMIDS',						'ROOM_IDS');
	define ('IPSSONOS_VAR_IPADDR',						'IPADDR');
	define ('IPSSONOS_VAR_RINCON',						'RINCON');
	define ('IPSSONOS_VAR_REMOTE',						'REMOTE');
	define ('IPSSONOS_VAR_PLAYLIST',					'PLAYLIST');
	define ('IPSSONOS_VAR_RADIOSTATION',				'RADIOSTATION');	
	define ('IPSSONOS_VAR_ROOMPOWER',					'ROOMPOWER');
	define ('IPSSONOS_VAR_MUTE',						'MUTE');
	define ('IPSSONOS_VAR_VOLUME',						'VOLUME');
	define ('IPSSONOS_VAR_TRANSPORT',					'TRANSPORT');
	define ('IPSSONOS_VAR_SHUFFLE',						'SHUFFLE');
	define ('IPSSONOS_VAR_REPEAT',						'REPEAT');	
	define ('IPSSONOS_VAR_TITLE',						'TITLE');	
	define ('IPSSONOS_VAR_ALBUM',						'ALBUM');	
	define ('IPSSONOS_VAR_ARTIST',						'ARTIST');		
	define ('IPSSONOS_VAR_ALBUMARTIST',					'ALBUMARTIST');	
	define ('IPSSONOS_VAR_POSITION',					'POSITION');	
	define ('IPSSONOS_VAR_POSITIONPERCENT',				'POSITIONP');		
	define ('IPSSONOS_VAR_DURATION',					'DURATION');	
	define ('IPSSONOS_VAR_QUERY',						'QUERY');	
	define ('IPSSONOS_VAR_QUERYTIME',					'QUERYTIME');	
	define ('IPSSONOS_VAR_COVERURI',					'COVERURI');			
	define ('IPSSONOS_VAR_PLAYERDETAILS',				'PLAYERDETAILS');
//	define ('IPSSONOS_VAR_GUISHOWDETAILS',				'GUISHOWDETAILS');	
	define ('IPSSONOS_VAR_SERVERDETAILS',				'SERVERDETAILS');	
	
	define ('IPSSONOS_EVT_QUERY',						'QUERYSONOS');	
	define ('IPSSONOS_EVT_POWERONDELAY',				'POWERONDELAY');

	
	/** @}*/
?>