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

	/**@defgroup IPSMaster_configuration IPSMaster Konfiguration
	 * @ingroup IPSMaster
	 * @{
	 *
	 * @file          IPSMaster_Configuration.inc.php
	 * @author        Andreas Brauneis
	 * @version
	 *  Version 2.50.1, 26.07.2012<br/>
	 *
	 * Konfigurations File für IPSMaster
	 *
	 */

	/**
	 *
	 * Definition der Beleuchtungs Elemente
	 *
	 * Die Konfiguration erfolgt in Form eines Arrays, für jedes Beleuchtungselement wird ein Eintrag im Array erzeugt.
	 *
	 * Für jedes Beleuchtungselement werden dann die Eigenschaften in einem gesonderten Array hinterlegt:
	 *
	 * IPSMaster_NAME  - spezifiziert den Namen der Beleuchtung in der GUI, Änderungen an dieser Eigenschaft werden erst nach einem
	 *                  erneuten Ausführen der Installationsprozedur sichtbar.
	 *
	 * IPSMaster_GROUP - beinhaltet eine Liste aller Gruppen, der das Beleuchtungselement zugeordnet ist. Diese Eigenschaft kann
	 *                  jederzeit geändert werden (vorausgesetzt die Gruppe ist bereits definiert, siehe weiter unten).
	 *
	 * IPSMaster_TYPE  - spezifiziert den Type der Beleuchtung, zur Zeit werden 3 Beleuchtungstypen unterstützt:
	 *    - IPSMaster_TYPE_SWITCH:  Normale Beleuchtung mit Ein/Aus Funktionalität
	 *    - IPSMaster_TYPE_RGB:     RGB Beleuchtung
	 *    - IPSMaster_TYPE_DIMMER:  Dimmbare Beleuchtung
	 *                  Änderungen an diesem Parameter erfordern ein Ausführen der Installations Prozedure.
	 *
	 * IPSMaster_COMPONENT - dieser Eintrag spezifiziert die Hardware, die Angabe des Component Strings muss mit dem spezifizierten
	 *                      Beleuchtungstypen (siehe oben) zusammenpassen (Type Dimmer benötigt zB eine Klasse IPSComponentDimmer).
	 *
	 * IPSMaster_POWERCIRCLE - Hier kann spezifiziert werden an welchem Stromkreis die Lampe angeschlossen ist. Dieser Parameter ist
	 *                        optional.
	 *
	 * IPSMaster_POWERWATT - Spezifiert die maximale Leistung der Beleuchtung. Zusammen mit dem Parameter IPSMaster_POWERCIRCLE ist es 
	 *                      nun möglich die aktuelle Leistung eines Stromkreises abzufragen. Details siehe auch im WIKI.
	 *
	 * Eine ausführliche Beispielliste findet sich auch im Example Ordner
	 *
	 *
	 * Beispiel:
	 * @code
        function IPSMaster_GetLightConfiguration() {
          return array(
            'Kueche'  =>  array(
               IPSMaster_NAME            => 'Küche',
               IPSMaster_GROUPS          => 'Erdgeschoss,All',
               IPSMaster_TYPE            => IPSMaster_TYPE_SWITCH',
               IPSMaster_COMPONENT       => 'IPSComponentSwitch_Dummy,12345',
               IPSMaster_POWERCIRCLE     => 1,
               IPSMaster_POWERWATT       => 60),
            'Ambiente'  =>  array(
               IPSMaster_NAME            => 'Ambiente',
               IPSMaster_GROUPS          => 'Erdgeschoss,All',
               IPSMaster_TYPE            => IPSMaster_TYPE_RGB,
               IPSMaster_COMPONENT       => 'IPSComponentRGB_IPS868,12345'),
             );
        }
	 * @endcocde
	 *
	 * @return string Liefert Array mit Beleuchtungs Elementen
	 */
	function IPSMaster_GetLightConfiguration() {
		return array(
			// ===== Erdgeschoss ==================================================================
			'Wohnzimmer_Sofa'         =>		array('Wohnzimmer_Sofa',         'Wohnzimmer,All', 		'Dimmer',   'IPSComponentDimmer_ZW,10569','0,60,0','L1',100),
			'Wohnzimmer_Ecklampe'     =>		array('Wohnzimmer_Ecklampe',     'Wohnzimmer,All', 		'Switch',   'IPSComponentSwitch_ZW,10853','L1',100),
			'Wohnzimmer_RGB_IPS868'   =>		array('Wohnzimmer_RGB_IPS868',   'Wohnzimmer,All', 		'RGB', 	  	'IPSComponentRGB_IPS868,26974','L1',100),
			'Wohnzimmer_RGB_HUE'      =>		array('Wohnzimmer_RGB_HUE',      'Wohnzimmer,All', 		'RGB',      'IPSComponentRGB_PhilipsHUE,192.168.20.114,c94b57d52a977c40e12ece0e86847f52,3,LCT001'),
			'Wohnzimmer_RGB_Hyperion' =>		array('Wohnzimmer_RGB_Hyperion', 'Wohnzimmer,All', 		'RGB',   	'IPSComponentRGB_Generic,Wohnzimmer_RGB_Hyperion'),	
			'Schlafzimmer_RGB_HUE'    =>		array('Schlafzimmer_RGB_HUE',    'Schlafzimmer,All', 	'RGB',    	'IPSComponentRGB_PhilipsHUE,192.168.20.114,c94b57d52a977c40e12ece0e86847f52,1,LCT001'),
			'Schlafzimmer_Bett'       =>		array('Schlafzimmer_Bett',       'Schlafzimmer,All', 	'Dimmer', 	'IPSComponentDimmer_ZW,52320','L2',100),
			'Kueche_RGB_HUE'    	  =>		array('Kueche_RGB_HUE',   		 'Kueche,All', 			'RGB',    	'IPSComponentRGB_PhilipsHUE,192.168.20.114,c94b57d52a977c40e12ece0e86847f52,2,LCT001'),
			'Kueche_Powermate'        =>		array('Kueche_Powermate',        'Kueche,All', 			'Dimmer',   'IPSComponentDimmer_Generic,Kueche_Powermate'),			
			);
	}


	/**
	 *
	 * Definition der Beleuchtungs Gruppen
	 *
	 * Die Konfiguration erfolgt in Form eines Arrays, für jede Beleuchtungsgruppe wird ein Eintrag im Array erzeugt.
	 *
	 * Für jede Beleuchtungsgruppe werden dann die Eigenschaften in einem gesonderten Array hinterlegt:
	 *
	 * IPSMaster_NAME  - spezifiziert den Namen der Gruppe in der GUI, Änderungen an dieser Eigenschaft werden erst nach einem
	 *                  erneuten Ausführen der Installationsprozedur sichtbar.
	 *
	 * IPSMaster_ACTIVATABLE - gibt an, ob die Gruppe über die GUI eingeschaltet werden kann
	 *
	 * Eine Liste mit diversen Beispiel Konfigurationen findet sich auch im Example Ordner
	 *
	 *
	 * Beispiel:
	 * @code
        function IPSMaster_GetGroupConfiguration() {
          return array(
            'All'  =>  array(
               IPSMaster_NAME            => 'All',
               IPSMaster_ACTIVATABLE     => false),
            'Erdgeschoss'  =>  array(
               IPSMaster_NAME            => 'Erdgeschoss',
               IPSMaster_ACTIVATABLE     => false),
             );
        }
	 * @endcocde
	 *
	 * @return string Liefert Array mit Beleuchtungs Gruppen
	 */
	function IPSMaster_GetGroupConfiguration() {
		return array('All'           =>	array('All',            IPSMaster_ACTIVATABLE => true,),
		             'Wohnzimmer'    =>	array('Wohnzimmer',     IPSMaster_ACTIVATABLE => true,),
		             'Kueche'    	 =>	array('Kueche',         IPSMaster_ACTIVATABLE => true,),
		             'Schlafzimmer'  =>	array('Schlafzimmer',   IPSMaster_ACTIVATABLE => true,),
	   );
	}

	/**
	 *
	 * Definition der Beleuchtungs Programme
	 *
	 * Die Konfiguration erfolgt in Form eines Arrays, für jedes Beleuchtungsprogramm wird ein Eintrag im Array erzeugt.
	 *
	 * Für jedes Beleuchtungsprogramm werden dann die einzelnen Programme ebenfalls als Array hinterlegt, diese wiederum haben ihre
	 * Eigenschaften nochmals in einem Array gespeichert:
	 *
	 * IPSMaster_PROGRAMON  - Liste mit Beleuchungselementen, die bei diesem Programm eingeschaltet sein sollen.
	 *
	 * IPSMaster_PROGRAMOFF  - Liste mit Beleuchungselementen, die bei diesem Programm ausgeschaltet sein sollen.
	 *
	 * IPSMaster_PROGRAMLEVEL  - Liste mit Beleuchungselementen, die auf einen bestimmten Dimm Level gestellt werden sollen
	 *
	 * Eine Liste mit diversen Beispiel Konfigurationen findet sich auch im Example Ordner
	 *
	 *
	 * Beispiel:
	 * @code
        function IPSMaster_GetProgramConfiguration() {
          return array(
				'Aus'  	=>	array(
					IPSMaster_PROGRAMOFF		=> 	'WellnessWand,WellnessDecke,WellnessSauna,WellnessDusche,WellnessAmbiente',
				),
				'TV'  	=>	array(
					IPSMaster_PROGRAMLEVEL	=> 	'WellnessWand,30',
					IPSMaster_PROGRAMOFF		=> 	'WellnessDecke,WellnessSauna,WellnessDusche,WellnessAmbiente',

				),
				'Relax'  	=>	array(
					IPSMaster_PROGRAMON		=> 	'WellnessSauna,WellnessDusche,WellnessAmbiente',
					IPSMaster_PROGRAMLEVEL	=> 	'WellnessDecke,30,WellnessWand,30',

				),
             );
        }
	 * @endcocde
	 *
	 * @return string Liefert Array mit Beleuchtungs Gruppen
	 */
	function IPSMaster_GetProgramConfiguration() {
		return array(
			// ===== Allgemein ==================================================================
			'ProgramAllgemein'  	=>	array(
				'Aus'  	=>	array(
					IPSMaster_PROGRAMOFF		=> 	'Wohnzimmer_Sofa,Wohnzimmer_Ecklampe,Wohnzimmer_RGB_IPS868,Wohnzimmer_RGB_HUE,Schlafzimmer_RGB_HUE,Schlafzimmer_Bett,Kueche_RGB_HUE,Wohnzimmer_RGB_Hyperion',
					IPSMaster_PROGRAMLEVEL	=> 	'',
				),
				'Hell'  	=>	array(
					IPSMaster_PROGRAMON		=> 	'Wohnzimmer_Ecklampe,Wohnzimmer_Sofa',
					IPSMaster_PROGRAMOFF		=> 	'Schlafzimmer_Bett,Schlafzimmer_RGB_HUE',
					IPSMaster_PROGRAMLEVEL	=> 	'70',
					IPSMaster_PROGRAMRGB		=> 	'Wohnzimmer_RGB_IPS868,90,255,192,64,Wohnzimmer_RGB_HUE,80,255,192,64,Kueche_RGB_HUE,70,255,255,0',
				),
				'Relax'  	=>	array(
					IPSMaster_PROGRAMON		=> 	'Wohnzimmer_Sofa',
					IPSMaster_PROGRAMOFF		=> 	'Wohnzimmer_Ecklampe,Schlafzimmer_RGB_HUE,Schlafzimmer_Bett,Wohnzimmer_RGB_Hyperion',
					IPSMaster_PROGRAMLEVEL	=> 	'70',
					IPSMaster_PROGRAMRGB		=> 	'Wohnzimmer_RGB_IPS868,60,128,64,64,Wohnzimmer_RGB_HUE,50,140,110,25,Kueche_RGB_HUE,40,255,255,0',
				),					
				'Schlafengehen'  	=>	array(
					IPSMaster_PROGRAMON		=> 	'Wohnzimmer_Sofa,Schlafzimmer_Bett',
					IPSMaster_PROGRAMOFF		=> 	'Wohnzimmer_Ecklampe,Kueche_RGB_HUE,Wohnzimmer_RGB_Hyperion',
					IPSMaster_PROGRAMLEVEL	=> 	'65',
					IPSMaster_PROGRAMRGB		=> 	'Schlafzimmer_RGB_HUE,57,40,120,140,Wohnzimmer_RGB_IPS868,60,255,255,0,Wohnzimmer_RGB_HUE,40,255,255,0,Wohnzimmer_RGB_HUE,60,255,255,0',	
				),			
				'Nachtlicht'  	=>	array(
					IPSMaster_PROGRAMON		=> 	'',
					IPSMaster_PROGRAMOFF		=> 	'',
					IPSMaster_PROGRAMLEVEL	=> 	'30',
					IPSMaster_PROGRAMRGB		=> 	'Wohnzimmer_RGB_IPS868,60,255,255,0,Wohnzimmer_RGB_HUE,40,255,255,0',	
				),				
			),
			// ===== Wohnzimmer ==================================================================
			'ProgramWohnzimmer'  	=>	array(
				'Aus'  	=>	array(
					IPSMaster_PROGRAMOFF		=> 	'Wohnzimmer_Sofa,Wohnzimmer_Ecklampe,Wohnzimmer_RGB_IPS868,Wohnzimmer_RGB_HUE',
					IPSMaster_PROGRAMLEVEL	=> 	'',
				),
				'Hell'  	=>	array(
					IPSMaster_PROGRAMON		=> 	'Wohnzimmer_Sofa,Wohnzimmer_Ecklampe,Wohnzimmer_RGB_IPS868,Wohnzimmer_RGB_HUE',
					IPSMaster_PROGRAMOFF		=> 	'',
					IPSMaster_PROGRAMLEVEL	=> 	'70',
					IPSMaster_PROGRAMRGB		=> 	'Wohnzimmer_RGB_IPS868,70,255,255,0,Wohnzimmer_RGB_HUE,70,255,255,0',
				),	
				'Relax'  	=>	array(
					IPSMaster_PROGRAMON		=> 	'Wohnzimmer_Sofa,Wohnzimmer_RGB_IPS868,Wohnzimmer_RGB_HUE',
					IPSMaster_PROGRAMOFF		=> 	'Wohnzimmer_Ecklampe',
					IPSMaster_PROGRAMLEVEL	=> 	'40',
					IPSMaster_PROGRAMRGB		=> 	'Wohnzimmer_RGB_IPS868,40,255,255,0,Wohnzimmer_RGB_HUE,40,255,255,0',
				),
				'Fernsehen'  	=>	array(
					IPSMaster_PROGRAMON		=> 	'Wohnzimmer_Sofa,Wohnzimmer_RGB_IPS868,Wohnzimmer_RGB_HUE',
					IPSMaster_PROGRAMOFF		=> 	'Wohnzimmer_Ecklampe',
					IPSMaster_PROGRAMLEVEL	=> 	'5',
					IPSMaster_PROGRAMRGB		=> 	'Wohnzimmer_RGB_IPS868,5,255,255,0,Wohnzimmer_RGB_HUE,5,255,255,0',
				),				
			),
			// ===== Kueche ==================================================================
			'ProgramKueche'  	=>	array(
				'Aus'  	=>	array(
					IPSMaster_PROGRAMOFF		=> 	'Kueche_RGB_HUE',
					IPSMaster_PROGRAMLEVEL	=> 	'',
				),
				'Morgen'  	=>	array(
					IPSMaster_PROGRAMON		=> 	'Wohnzimmer_Sofa,Wohnzimmer_RGB_IPS868,Wohnzimmer_RGB_HUE',
					IPSMaster_PROGRAMOFF		=> 	'',
					IPSMaster_PROGRAMLEVEL	=> 	'40',
					IPSMaster_PROGRAMRGB		=> 	'Wohnzimmer_RGB_IPS868,40,255,255,0,Wohnzimmer_RGB_HUE,40,255,255,0',
				),	
				'Arbeit'  	=>	array(
					IPSMaster_PROGRAMON		=> 	'Wohnzimmer_Sofa,Wohnzimmer_Ecklampe',
					IPSMaster_PROGRAMOFF		=> 	'',
					IPSMaster_PROGRAMRGB		=> 	'Kueche_RGB_HUE,100,255,255,50',	
				),
			),				
			// ===== Schlafzimmer ==================================================================
			'ProgramSchlafzimmer'  	=>	array(
				'Aus'  	=>	array(
					IPSMaster_PROGRAMOFF		=> 	'Schlafzimmer_Bett,Schlafzimmer_RGB_HUE',
					IPSMaster_PROGRAMLEVEL	=> 	'',
				),
				'Morgen'  	=>	array(
					IPSMaster_PROGRAMON		=> 	'Schlafzimmer_Bett,Schlafzimmer_RGB_HUE',
					IPSMaster_PROGRAMOFF		=> 	'',
					IPSMaster_PROGRAMLEVEL	=> 	'60',
					IPSMaster_PROGRAMRGB		=> 	'Schlafzimmer_RGB_HUE,40,255,255,0',
				),	
				'Relax'  	=>	array(
					IPSMaster_PROGRAMON		=> 	'Schlafzimmer_Bett,Schlafzimmer_RGB_HUE',
					IPSMaster_PROGRAMOFF		=> 	'',
					IPSMaster_PROGRAMLEVEL	=> 	'60',
					IPSMaster_PROGRAMRGB		=> 	'Schlafzimmer_RGB_HUE,40,255,255,0',	
				),
			),	
		);
	}

	/**
	 *
	 * Definition der WebFront GUI
	 *
	 * Die Konfiguration der WebFront Oberfläche ist NICHT dokumentiert
	 *
	 * Beispiele finden sich im Example Ordner
	 *
	 * @return string Liefert Array zum Aufbau des WebFronts
	 */ //
	function IPSMaster_GetWebFrontConfiguration() {
		return array(
			'Übersicht' => array(
				array(IPSMaster_WFCSPLITPANEL, 'Light_1_SPh1',       'LightTP',        'Übersicht','Bulb',0,20,0,0,'true'),
				array(IPSMaster_WFCCATEGORY,      'Light_1_CAv1h1',  'Light_1_SPh1', null,null),
				array(IPSMaster_WFCGROUP,            'Allgemein',      'Light_1_CAv1h1',  'ProgramAllgemein', 'Program'),
				array(IPSMaster_WFCSPLITPANEL,   'Light_1_SPv1h2',   'Light_1_SPh1',   null,null,1,33,0,0,'true'),
				array(IPSMaster_WFCCATEGORY,       'Light_1_CAv1h2',  'Light_1_SPv1h2', null,null),
				array(IPSMaster_WFCLINKS,            '',              	'Light_1_CAv1h2',		'Wohnzimmer'),
				array(IPSMaster_WFCGROUP,            'Program',	 'Light_1_CAv1h2',  'ProgramWohnzimmer', 'Program'),
				array(IPSMaster_WFCGROUP,            'Lampen',   	'Light_1_CAv1h2',    'Wohnzimmer_Sofa,Wohnzimmer_Ecklampe,Wohnzimmer_RGB_IPS868,Wohnzimmer_RGB_HUE,Wohnzimmer_RGB_Hyperion', 'Sofa,Ecklampe,IPS868,HUE,Hyperion'),				
				array(IPSMaster_WFCSPLITPANEL,   'Light_1_SPv2h2',     'Light_1_SPv1h2',   null,null,1,50,0,0,'true'),
				array(IPSMaster_WFCCATEGORY,       'Light_1_CAv2',    'Light_1_SPv2h2',   null,null),
				array(IPSMaster_WFCLINKS,            '',              	'Light_1_CAv2',		'Schlafzimmer'),
				array(IPSMaster_WFCGROUP,            'Program',	 	 'Light_1_CAv2',  'ProgramSchlafzimmer', 'Program'),				
				array(IPSMaster_WFCGROUP,            'Lampen',   'Light_1_CAv2',    'Schlafzimmer_RGB_HUE,Schlafzimmer_Bett', 'Hue,Bett'),
				array(IPSMaster_WFCCATEGORY,       'Light_1_CAv3',    'Light_1_SPv2h2',   null,null),
				array(IPSMaster_WFCLINKS,            '',              	'Light_1_CAv3',		'Kueche'),				
				array(IPSMaster_WFCGROUP,            'Program',	 	 'Light_1_CAv3',  'ProgramKueche', 'Program'),
				),
			'Wohzimmer' => array(
				array(IPSMaster_WFCSPLITPANEL, 'Light_2_SPv1',        	'LightTP',        	'Wohnzimmer',null,1,33,0,0,'true'),
				array(IPSMaster_WFCCATEGORY,       'Light_2_CAv1h1',  	'Light_2_SPv1', 	null,null),
				array(IPSMaster_WFCLINKS,            '',             	'Light_2_CAv1h1',	'Wohnzimmer'),
				array(IPSMaster_WFCGROUP,            'Wohnzimmer',  		'Light_2_CAv1h1',	'Wohnzimmer_Sofa,Wohnzimmer_Ecklampe,Wohnzimmer_RGB_IPS868,Wohnzimmer_RGB_HUE,Wohnzimmer_RGB_Hyperion', 'Sofa,Ecklampe,IPS868,Hue,Hyperion'),
				array(IPSMaster_WFCCATEGORY,      'Light_2_CAv2h1',  	'Light_2_SPv1',		null,null),
				array(IPSMaster_WFCGROUP,       		'Level',  			'Light_2_CAv2h1', 	'Wohnzimmer_RGB_HUE#Level,Wohnzimmer_RGB_IPS868#Level,Wohnzimmer_Sofa#Level', 'Hue,IPS868,Sofa'),
				array(IPSMaster_WFCGROUP,       		'Hue',  			'Light_2_CAv2h1', 	'Wohnzimmer_RGB_HUE#Color', 'Farbe'),
				array(IPSMaster_WFCGROUP,       		'IPS868',  			'Light_2_CAv2h1', 	'Wohnzimmer_RGB_IPS868#Color', 'Farbe'),
				array(IPSMaster_WFCGROUP,       		'Hyperion',  		'Light_2_CAv2h1', 	'Wohnzimmer_RGB_Hyperion#Color', 'Farbe'),
				),
			'Schlafzimmer' => array(
				array(IPSMaster_WFCSPLITPANEL, 'Light_3_SPv1',        	'LightTP',        	'Schlafzimmer',null,1,33,0,0,'true'),
				array(IPSMaster_WFCCATEGORY,       'Light_3_CAv1h1',  	'Light_3_SPv1', 	null,null),
				array(IPSMaster_WFCLINKS,            '',              	'Light_3_CAv1h1',  	'Schlafzimmer'),
				array(IPSMaster_WFCGROUP,            'Schlafzimmer',   	'Light_3_CAv1h1',  	'Schlafzimmer_Bett,Schlafzimmer_RGB_HUE', 'Bett,Hue'),
				array(IPSMaster_WFCCATEGORY,       'Light_3_CAv2h1',  	'Light_3_SPv1',	 	null,null),
				array(IPSMaster_WFCGROUP,       		'Level',  			'Light_3_CAv2h1', 	'Schlafzimmer_RGB_HUE#Level,Schlafzimmer_Bett#Level', 'Hue,Bett'),
				array(IPSMaster_WFCGROUP,            'Hue',      		'Light_3_CAv2h1', 	'Schlafzimmer_RGB_HUE#Color', 'Farbe'),
				),
			'Kueche' => array(
				array(IPSMaster_WFCSPLITPANEL, 'Light_4_SPv1',        	'LightTP',        	'Kueche',null,1,33,0,0,'true'),
				array(IPSMaster_WFCCATEGORY,       'Light_4_CAv1h1',  	'Light_4_SPv1', 	null,null),
				array(IPSMaster_WFCLINKS,            '',              	'Light_4_CAv1h1',  	'Kueche'),
				array(IPSMaster_WFCGROUP,            'Kueche',   		'Light_4_CAv1h1',  	'Kueche_RGB_HUE,Kueche_Powermate', 'Hue,Powermate'),
				array(IPSMaster_WFCCATEGORY,       'Light_4_CAv2h1',  	'Light_4_SPv1', 	null,null),
				array(IPSMaster_WFCGROUP,       		'Level',  			'Light_4_CAv2h1', 	'Kueche_RGB_HUE#Level', 'Hue'),				
				array(IPSMaster_WFCGROUP,            'Hue',      		'Light_4_CAv2h1', 	'Kueche_RGB_HUE#Color', 'Farbe'),
				array(IPSMaster_WFCGROUP,       		'Level',  			'Light_4_CAv2h1', 	'Kueche_Powermate#Level', 'Powermate'),								
				),				
	   );
	}

	/**
	 *
	 * Definition der Mobile GUI
	 *
	 * Die Konfiguration der Mobile GUI ist NICHT dokumentiert
	 *
	 * Beispiele finden sich im Example Ordner
	 *
	 * @return string Liefert Array zum Aufbau der Mobile GUI
	 */
	function IPSMaster_GetMobileConfiguration() {
		return array(

	   );
	}

	/** @}*/
?>