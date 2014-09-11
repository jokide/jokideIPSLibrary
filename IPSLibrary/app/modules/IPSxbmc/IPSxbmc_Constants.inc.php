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
	 * IPSxbmc Server Konstanten
	 *
	 * @file          IPSXBMC_Constants.inc.php
	 * @author        Joerg Kling
	 * @version
	 * Version 0.9.4, 07.06.2014<br/>
	 *
	 */

	// Kommunikations Kommandos
	define ('IPSXBMC_CMD_PLAYER',						'AUD');
	define ('IPSXBMC_CMD_ROOM',						'ROO');
	define ('IPSXBMC_CMD_SERVER',						'SRV');	


	// Kommunikations Actions
	define ('IPSXBMC_FNC_POWER',						'PWR');
	define ('IPSXBMC_FNC_VOLUME',						'VOL');
	define ('IPSXBMC_FNC_VOLUME_INC',					'VIN');
	define ('IPSXBMC_FNC_VOLUME_DEC',					'VDE');
	define ('IPSXBMC_FNC_VOLUME_RMP',					'VOLUMERMP');	
	define ('IPSXBMC_FNC_VOLUME_RMPMUTE',				'VOLUMERMPMUTE');
	define ('IPSXBMC_FNC_VOLUME_RMPMUTESLOW',			'VOLUMERMPMUTESLOW');		
	define ('IPSXBMC_FNC_MUTE',						'MUT');
	define ('IPSXBMC_FNC_PLAY',						'PLA');
	define ('IPSXBMC_FNC_PAUSE',						'PAU');
	define ('IPSXBMC_FNC_STOP',						'STP');	
	define ('IPSXBMC_FNC_NEXT',						'NXT');
	define ('IPSXBMC_FNC_PREVIOUS',					'PRV');	
	define ('IPSXBMC_FNC_SYNCPL',						'SYNCPL');
	define ('IPSXBMC_FNC_PLAYPLID',					'PLAYPLID');
	define ('IPSXBMC_FNC_PLAYPLNAME',					'PLAYPLNAME');
	define ('IPSXBMC_FNC_SYNCRD',						'SYNCRD');
	define ('IPSXBMC_FNC_PLAYRDID',					'PLAYRDID');
	define ('IPSXBMC_FNC_PLAYRDNAME',					'PLAYRDNAME');		
	define ('IPSXBMC_FNC_ROOMS',						'ROOMS');
	define ('IPSXBMC_FNC_ROOMSACTIVE',					'ROOMSACTIVE');	
	define ('IPSXBMC_FNC_SHUFFLE',						'SHUFFLE');	
	define ('IPSXBMC_FNC_REPEAT',						'REPEAT');	
	define ('IPSXBMC_FNC_INPUT',						'INPUT');		
	

	//Define Transport
	define ('IPSXBMC_TRA_PREVIOUS',					'0');	
	define ('IPSXBMC_TRA_PLAY',						'1');
	define ('IPSXBMC_TRA_PAUSE',						'2');	
	define ('IPSXBMC_TRA_STOP',						'3');
	define ('IPSXBMC_TRA_NEXT',						'4');	

	//Define Variavle Values 
	define ('IPSXBMC_VAL_BOOLEAN_FALSE',				0);
	define ('IPSXBMC_VAL_BOOLEAN_TRUE',				1);
	define ('IPSXBMC_VAL_VOLUME_DEFAULT',				20);

	define ('IPSXBMC_VAL_MUTE_OFF',					IPSXBMC_VAL_BOOLEAN_FALSE);
	define ('IPSXBMC_VAL_MUTE_ON',						IPSXBMC_VAL_BOOLEAN_TRUE);
	define ('IPSXBMC_VAL_MUTE_DEFAULT',				IPSXBMC_VAL_BOOLEAN_FALSE);

//	define ('IPSXBMC_VAL_POWER_OFF',					IPSXBMC_VAL_BOOLEAN_FALSE);
//	define ('IPSXBMC_VAL_POWER_ON',					IPSXBMC_VAL_BOOLEAN_TRUE);
	define ('IPSXBMC_VAL_POWER_DEFAULT',				IPSXBMC_VAL_BOOLEAN_FALSE);
	define ('IPSXBMC_VAL_TRANSPORT',				    3);
	define ('IPSXBMC_VAL_PLAYLIST',				    0);
	define ('IPSXBMC_VAL_RADIOSTATION',			  	999);	


	// Variablen Definitionen
	define ('IPSXBMC_VAR_ROOMCOUNT',					'ROOM_COUNT');
	define ('IPSXBMC_VAR_ROOMIDS',						'ROOM_IDS');
	define ('IPSXBMC_VAR_MODESERVERDEBUG',				'MODE_SERVERDEBUG');
	define ('IPSXBMC_VAR_IPADDR',						'IPADDR');
	define ('IPSXBMC_VAR_RINCON',						'RINCON');
	define ('IPSXBMC_VAR_REMOTE',						'REMOTE');
	define ('IPSXBMC_VAR_PLAYLIST',						'PLAYLIST');
	define ('IPSXBMC_VAR_RADIOSTATION',					'RADIOSTATION');	
	define ('IPSXBMC_VAR_ROOMPOWER',					'ROOMPOWER');
	define ('IPSXBMC_VAR_MUTE',							'MUTE');
	define ('IPSXBMC_VAR_VOLUME',						'VOLUME');
	define ('IPSXBMC_VAR_TRANSPORT',					'TRANSPORT');
	define ('IPSXBMC_VAR_SHUFFLE',						'SHUFFLE');
	define ('IPSXBMC_VAR_REPEAT',						'REPEAT');	
	define ('IPSXBMC_VAR_POSITION',						'POSITION');
	define ('IPSXBMC_VAR_TITLE',						'TITLE');	
	define ('IPSXBMC_VAR_COMREGVAR',					'COMREGVAR');	
	define ('IPSXBMC_VAR_SOCKETSEND',					'SOCKETSEND');	
	define ('IPSXBMC_VAR_FILE',							'FILE');	
	define ('IPSXBMC_VAR_STATUS',						'STATUS');		
	/** @}*/
?>