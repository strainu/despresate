<?
// +------------------------------------------------------------------------+
// | class.SimpleSQL.php                                                       |
// +------------------------------------------------------------------------+
// | Copyright (c) Andrei Cipu (Strainu) 2007. All rights reserved.         |
// | Version       1.0									    |
// | Last modified 24/02/2007                                               |
// | Email         strainu@strainu.ro                                       |
// | Web           http://www.strainu.ro                                    |
// +------------------------------------------------------------------------+
// | This program is free software; you can redistribute it and/or modify   |
// | it under the terms of the GNU General Public License version 2 as      |
// | published by the Free Software Foundation.                             |
// |                                                                        |
// | This program is distributed in the hope that it will be useful,        |
// | but WITHOUT ANY WARRANTY; without even the implied warranty of         |
// | MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the          |
// | GNU General Public License for more details.                           |
// |                                                                        |
// | You should have received a copy of the GNU General Public License      |
// | along with this program; if not, write to the                          |
// |   Free Software Foundation, Inc., 59 Temple Place, Suite 330,          |
// |   Boston, MA 02111-1307 USA                                            |
// |                                                                        |
// | Please give credit on sites that use class.SimpleSQL and submit changes|
// | of the script so other people can use them as well.                    |
// | This script is free to use, don't abuse.                               |
// +------------------------------------------------------------------------+
//
/**
 * Class SimpleSQL
 *
 * @version   1.0
 * @author    Andrei Cipu <strainu@strainu.ro>
 * @license   http://opensource.org/licenses/gpl-license.php GNU Public License
 * @copyright Andrei Cipu
 */
/**
 * Class SimpleSQL
 *
 * <b>Ce face?</b>
 *
 * usureaza folosirea MySQL in programele php
 *
 * <b>Cum o folosesc?</b><br>
 * Creati o baza de date care sa contina un tabel numit "cod" si un fisier cu urmatorul continut:
 * <pre>
 *  <?
 *  $MyObject = new SimpleSQL( "localhost", "strainu", "pass", "strainu_1", 1 );
 *  $MyObject->Query("SELECT * FROM cod");
 *  print_r($MyObject->getCurrentLine());
 *  ?>
 * </pre>
 * Rulati-l si daca totul merge bine va va arata prima linie din tabelul cod
 *
 *
 * <b>Requirements</b>
 *
 * PHP4, MySQL server
 *
 * <b>Changelog</b>
 *  v1.0 2007/02/24
 *    - prima versiune
 */

class SimpleSQL{
	/** Rezultatul ultimului query
	 *  E un array de forma: Array('row'=>Array(column0,column1,.....))
	 */
	var $result;
	/** Rezultatul ultimului query in format nativ */
	var $raw_result;
	/** Ultimul query */
	var $query;
	/** unde suntem in array */
	var $index;
	/** aratam erorile? */
	var $showErrors;
	/** fac debugging? */
	var $debug;
	/** numar de elemente */
	var $nrLines;
	
	/** constructor */
	function SimpleSQL( $server, $username, $password, $database, $showError = 1, $debug = 0 )
	{
		/** */
		$this->showErrors = $showError;
		$this->debug = $debug;
		/** connect to the database*/
		if( $showError )
		{
			mysql_connect ( $server, $username, $password ) or die( "Connection failed:".mysql_error() );
  			mysql_select_db( $database ) or die("Database selection failed:".mysql_error());
 		}
 		else
 		{
  			@mysql_connect( $server, $username, $password );
  			@mysql_select_db( $database );
 		}
 		mysql_query("SET NAMES utf8");
	}
	
	/** query SQL
	 *  Parametri: query-ul care trebuie executat
	 *  Intoarce: 
	 */
	function Query( $query )
	{
		if($this->debug) echo "<div class=\"error\">".$query."</div>";
		$this->query = $query;
		$this->raw_result = mysql_query( $query );
		$this->result = Array();
		$this->index = 0;
		$this->nrLines = @mysql_num_rows($this->raw_result);
		while ($line = @mysql_fetch_array( $this->raw_result, MYSQL_ASSOC )) 
		{
			array_push( $this->result, $line );
		}
	}
	
	/** Recuperare tabel 
	 *  Parametri:
	 *  Intoarce: tabelul cu raspunsul la ultimul query
	 */
	function getTable()
	{
		return $this->result;
	}
	
	/** Recuperare linie curenta
	 *  Parametri:
	 *  Intoarce: linia curenta din raspunsul la ultimul query
	 */
	function getCurrentLine()
	{
		if( $this->index < count( $this->result ) )
		{
			return $this->result[$this->index++];
		}
		else 
			return -1;
	}
	
	/** Recuperare linia cu indexul $lin
	 *  Parametri: linia care se doreste intoarsa
	 *  Intoarce: linia $lin din raspunsul la ultimul query
	 */
	function getLine( $lin )
	{
		if( $lin < count( $this->result ) )
		{
			return $this->result[$lin]?$this->result[$lin]:-1;
		}
		else 
			return -1;
	}
	
	/** Recuperare coloana cu numele $col
	 *  Parametri: numele coloanei $col
	 *  Intoarce: tabelul coloana $col
	 */
	function getColumn( $col )
	{
		$rezultat = Array();
		foreach( $this->result as $linie )
			array_push( $rezultat, $linie[$col] );
		return $rezultat;
	}
	
	/** Recuperare elementul de pe linia $lin si coloana $col
	 *  Parametri: - numarul liniei $lin
	 *             - numele coloanei $col
	 *  Intoarce: elementul cautat
	 */
	function getElement( $lin, $col )
	{
		if( $lin < count( $this->result ) )
		{
			return ($this->result[$lin][$col]!='')?$this->result[$lin][$col]:-1;
		}
		else 
			return -1;
	}
	
	/** Recuperare mai multe coloane
	 *  Parametri: tabel cu numele coloanelor
	 *  Intoarce: tabelul cu continutul coloanelor
	 */
	function getColumns( $cols_array )
	{
		$rezultat = Array();
		foreach( $cols_array as $col )
		{
			$e = $this->getColumn( $col );
			if( count($e)>0 )
				$rezultat[$col] = $e;
		}
		return $rezultat;
	}
	
	/** Numar de elemente intoarse
	 */
	function getNrLines()
	{
		return $this->nrLines;
	}
}
?>