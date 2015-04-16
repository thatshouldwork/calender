<?php

require_once $_SERVER['DOCUMENT_ROOT'].'/MyProjects/_MyClasses/DBConnection.class.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/MyProjects/_MyClasses/FilterExternalInput.class.php';
require_once 'config.inc.php';


class Calender
{
	private $hour;
	private $minute;
	private $year;
	private $month;
	private $day;


	public function __construct($year = NULL, $month = NULL, $day = NULL)
	{
		//if(isset($_GET['view']))
		$now = $this->getActualDateTimeObj();
		//echo "<pre>";
		//var_dump($now);
		//echo "</pre>";

		$filterExternalInput = new FilterExternalInput();

		$filteredYear	= $filterExternalInput->filterInput($year);
		$filteredMonth	= $filterExternalInput->filterInput($month);
		$filteredDay	= $filterExternalInput->filterInput($day);

		echo $filteredDay;

		if($filteredYear	!= NULL) { $this->year = $filteredYear; }
		else { $this->year = $now->format('Y'); }

		if($filteredMonth	!= NULL) { $this->month = $filteredMonth; }
		else { $this->month = $now->format('m'); }

		if($filteredDay		!= NULL) { $this->day = $filteredDay; }
		else{ $this->day = 01; }
	}

	private function pullSingleEvent()
	{

	}
	private function pullDailyEvents()
	{

	}

	public function showEvent($eventId)
	{

	}

	public function showDay()
	{

	}

	public function showWeek()
	{

	}

	public function showMonth()
	{
		// Wochen Grid anzeigen
		if( $this->year == NULL || $this->month == NULL || $this->day == NULL )
		{
			$this->showErrorMsg("Da ist entweder der Monat und/oder das Jahr falsch.");
		}
		else
		{
			$dbConnection = new DBConnection();

			$dbConnection->openConnection();

			$clearedYear	= $dbConnection->SqlInjectionStopper($this->year);
			$clearedMonth	= $dbConnection->SqlInjectionStopper($this->month);

			$sqlQuery = "
						SELECT
							entry_id, entry_inserted, entry_start_datetime, entry_end_datetime, entry_header, entry_body
						FROM
							entries
						WHERE
							YEAR(entry_start_datetime) = '$clearedYear'
						AND
							MONTH(entry_start_datetime) = '$clearedMonth'
						ORDER BY
							entry_start_datetime
						ASC
						";

			$sqlResult = $dbConnection->sendSqlQuery($sqlQuery);

			$monthDayCount = $this->monthDayCount($this->year.'-'.$this->month.'-'.$this->day);

			$monthStartDayName = $this->monthStartDayName($this->year.'-'.$this->month.'-'.$this->day);

			if($monthDayCount > 28)
				$rowCount = 5;
			else
				$rowCount = 4;

			/*
			if($monthStartDayName > 5)
			{
				$rowCount = 6;
			}
			else
			{
				$rowCount = 5;
			}
			*/
			//echo $monthStartDayName;
			//echo $this->month;

			$daycount = 1;

			$dayNameNumeric = $monthStartDayName;

			$now = $this->getActualDateTimeObj();

			$monthName = $this->monthName($this->year.'-'.$this->month.'-'.$this->day);

			echo '<div class="row">'.$monthName.' '.$this->year.' | <a href="?action=add_event">Termin hinzuf&uuml;gen</a> | </div>';

			for($i = 1; $i <= $rowCount; $i++) // 6x7 Zellen (1 Zeile fest für Tagesnamen)
			{
				echo '<div class="weekDayRow row">';

				for($j = 1; $j <= 7; $j++)
				{
					echo '<div class="weekDayRowItem rowItem clearfix';
					if($i == 1)
						echo ' firstRowBorder';
					if($this->year == $now->format('Y') && $this->month == $now->format('n') && $daycount == $now->format('j') )
						echo ' actualDay';
					echo '">';

					if($daycount <= $monthDayCount)
					{
						$dayName = $this->dayName($dayNameNumeric);

						echo '<a href="?show=day&year='.$this->year.'&month='.$this->month.'&day='.$daycount.'"><span class="dayNumber">'.$daycount.'</span></a>';
						//echo ' <a href="?action=add&" title="Termin am '.$daycount.'. hinzuf&uuml;gen" class="addEntry"><i class="fa fa-plus-circle"></i></a>';
						echo '<span class="dayName">'.$dayName.'</span>';

						if($dayNameNumeric <= 6)
							$dayNameNumeric++;
						else
							$dayNameNumeric = 1;
					}

					if($daycount >= $monthStartDayName)
					{
						foreach ($sqlResult as $entry)
						{
							$entry_start_datetimeObj = $this->getSpecificDateTimeObj($entry['entry_start_datetime']);

							if( $entry_start_datetimeObj->format('d') == $daycount)
							{
							?>
								<div class="weekDayEntry">
									<?php

									$startTime = $this->formatTime($entry['entry_start_datetime']);

									echo '<a href="" title="'.$entry['entry_body'].'">'.$startTime.' '.$entry['entry_header'].'</a> <a href="?action=edit_event&event_id='.$entry['entry_id'].'" title="Eintrag &auml;ndern"><i class="fa fa-pencil-square-o"></i></a> <a href="?action=del_event&event_id='.$entry['entry_id'].'" title="Eintrag l&ouml;schen"><i class="fa fa-eraser"></i></a>';

									?>
								</div>
							<?php
							}
						}
					}

					echo '</div>';

					$daycount++;
				}

			echo '</div>';
			}


			// Anderer Ansatz für Darstellung
			/*
			for($i = 1; $i <= $rowCount; $i++) // 6x7 Zellen (1 Zeile fest für Tagesnamen)
			{
				echo '<div class="weekDayRow row">';

				for($j = 1; $j <= 7; $j++)
				{
					if( (($j >= $monthStartDayName) && $i == 1) || $i > 1)
					{
						echo '<div class="weekDayRowItem rowItem">';

						if($daycount <= $monthDayCount)
						{
							echo $daycount;
						}

						if($daycount >= $monthStartDayName)
						{
							foreach ($sqlResult as $entry)
							{
								$entry_start_datetimeObj = $this->getSpecificDateTimeObj($entry['entry_start_datetime']);

								if( $entry_start_datetimeObj->format('d') == $daycount)
								{
								?>
									<div class="weekDayEntry">
										<?php

										$startTime = $this->formatTime($entry['entry_start_datetime']);

										echo $startTime.' '.$entry['entry_header'];

										?>
									</div>
								<?php
								}
							}
						}

						echo '</div>';

						$daycount++;
					}
					else
					{
						echo '<div class="weekDayRowItem rowItem"></div>';
					}
				}

			echo '</div>';
			}


			*/

			$dbConnection->closeConnection();
		}
	}

	public function showYear()
	{

	}

	private function showErrorMsg($msg)
	{
		echo '<p class="errorMsg">Ups das ist wohl was schief gelaufen. '.$msg;
	}



	// Database Interaction

	public function addEvent(	&$ref_event_start_day,
								&$ref_event_start_month,
								&$ref_event_start_year,
								&$ref_event_start_hour,
								&$ref_event_start_minute,
								&$ref_event_end_day,
								&$ref_event_end_month,
								&$ref_event_end_year,
								&$ref_event_end_hour,
								&$ref_event_end_minute,
								&$ref_event_header,
								&$ref_event_body)
	{
		$dbConnection = new DBConnection();

		$dbConnection->openConnection();


		$cleared_event_start_day		= $dbConnection->SqlInjectionStopper($ref_event_start_day);
		$cleared_event_start_month		= $dbConnection->SqlInjectionStopper($ref_event_start_month);
		$cleared_event_start_year		= $dbConnection->SqlInjectionStopper($ref_event_start_year);
		$cleared_event_start_hour		= $dbConnection->SqlInjectionStopper($ref_event_start_hour);
		$cleared_event_start_minute		= $dbConnection->SqlInjectionStopper($ref_event_start_minute);
		$cleared_event_end_day			= $dbConnection->SqlInjectionStopper($ref_event_end_day);
		$cleared_event_end_month		= $dbConnection->SqlInjectionStopper($ref_event_end_month);
		$cleared_event_end_year			= $dbConnection->SqlInjectionStopper($ref_event_end_year);
		$cleared_event_end_hour			= $dbConnection->SqlInjectionStopper($ref_event_end_hour);
		$cleared_event_end_minute		= $dbConnection->SqlInjectionStopper($ref_event_end_minute);
		$cleared_event_header			= $dbConnection->SqlInjectionStopper($ref_event_header);
		$cleared_event_body				= $dbConnection->SqlInjectionStopper($ref_event_body);

		$now					= $this->getActualDateTimeObj();
		$entry_inserted			= $now->format('Y-m-d H:i:s');
		$entry_start_datetime	= $cleared_event_start_year.'-'.$cleared_event_start_month.'-'.$cleared_event_start_day.' '.$cleared_event_start_hour.':'.$cleared_event_start_minute.':00';
		$entry_end_datetime		= $cleared_event_end_year.'-'.$cleared_event_end_month.'-'.$cleared_event_end_day.' '.$cleared_event_end_hour.':'.$cleared_event_end_minute.':00';

		$sqlQuery = "
					INSERT
						entries (entry_inserted, entry_start_datetime, entry_end_datetime, entry_header, entry_body)
					VALUES
						('$entry_inserted', '$entry_start_datetime', '$entry_end_datetime', '$cleared_event_header', '$cleared_event_body')
					";

		$sqlResult = $dbConnection->sendSqlQuery($sqlQuery);

		if($sqlResult)
			echo 'Eintragung erfolgreich. <a href="index.php">Zur&uuml;ck</a>';
	}

	private function editEntry(	&$ref_event_id,
								&$ref_event_start_day,
								&$ref_event_start_month,
								&$ref_event_start_year,
								&$ref_event_start_hour,
								&$ref_event_start_minute,
								&$ref_event_end_day,
								&$ref_event_end_month,
								&$ref_event_end_year,
								&$ref_event_end_hour,
								&$ref_event_end_minute,
								&$ref_event_header,
								&$ref_event_body)
	{
		$dbConnection = new DBConnection();

		$dbConnection->openConnection();


		$cleared_event_id				= $dbConnection->SqlInjectionStopper($ref_event_id);
		$cleared_event_start_day		= $dbConnection->SqlInjectionStopper($ref_event_start_day);
		$cleared_event_start_month		= $dbConnection->SqlInjectionStopper($ref_event_start_month);
		$cleared_event_start_year		= $dbConnection->SqlInjectionStopper($ref_event_start_year);
		$cleared_event_start_hour		= $dbConnection->SqlInjectionStopper($ref_event_start_hour);
		$cleared_event_start_minute		= $dbConnection->SqlInjectionStopper($ref_event_start_minute);
		$cleared_event_end_day			= $dbConnection->SqlInjectionStopper($ref_event_end_day);
		$cleared_event_end_month		= $dbConnection->SqlInjectionStopper($ref_event_end_month);
		$cleared_event_end_year			= $dbConnection->SqlInjectionStopper($ref_event_end_year);
		$cleared_event_end_hour			= $dbConnection->SqlInjectionStopper($ref_event_end_hour);
		$cleared_event_end_minute		= $dbConnection->SqlInjectionStopper($ref_event_end_minute);
		$cleared_event_header			= $dbConnection->SqlInjectionStopper($ref_event_header);
		$cleared_event_body				= $dbConnection->SqlInjectionStopper($ref_event_body);

		$now					= $this->getActualDateTimeObj();
		$entry_edited			= $now->format('Y-m-d H:i:s');
		$entry_start_datetime	= $cleared_event_start_year.'-'.$cleared_event_start_month.'-'.$cleared_event_start_day.' '.$cleared_event_start_hour.':'.$cleared_event_start_minute.':00';
		$entry_end_datetime		= $cleared_event_end_year.'-'.$cleared_event_end_month.'-'.$cleared_event_end_day.' '.$cleared_event_end_hour.':'.$cleared_event_end_minute.':00';

		$sqlQuery = "
					UPDATE
						entries
					SET
						entry_edited = '$entry_edited', entry_start_datetime = '$entry_start_datetime', entry_end_datetime = '$entry_end_datetime', entry_header = '$cleared_event_header', entry_body = '$cleared_event_body'
					WHERE
						entry_id = '$cleared_event_id'
					";

		$sqlResult = $dbConnection->sendSqlQuery($sqlQuery);

		if($sqlResult)
			echo 'Eintragung erfolgreich. <a href="index.php">Zur&uuml;ck</a>';

	}



	// Date und Time Funktionen

	// Ermittelt den DateTime Wert des aktuellen Tages
	private function getActualDateTimeObj()
	{
		return new DateTime();
	}
	private function getSpecificDateTimeObj($dateTimeString)
	{
		return new DateTime($dateTimeString);
	}

	// Ermittelt die Anzahl der Tage des angegebenen Monats
	private function monthDayCount($dateString)
	{
		$monthDayCount = $this->getSpecificDateTimeObj($dateString);

		return $monthDayCount->format('t');
	}

	// Ermittelt den Wochentag an dem der Monat startet
	private function monthStartDayName($dateString)
	{
		$monthStart = $this->getSpecificDateTimeObj($dateString);
		return $monthStart->format('N');
	}
	private function monthName($dateString)
	{
		$monthName = $this->getSpecificDateTimeObj($dateString);

		return $monthName->format('M');
	}

	// Gibt den Tagesnamen zurück
	private function dayName($dayNumeric)
	{
		switch($dayNumeric)
		{
			case 1: return "Mo";
					break;

			case 2: return "Di";
					break;

			case 3: return "Mi";
					break;

			case 4: return "Do";
					break;

			case 5: return "Fr";
					break;

			case 6: return "Sa";
					break;

			default: return "So";
		}
	}

	private function formatDate($dateTimeString)
	{
		$formattedDate = $this->getSpecificDateTimeObj($dateTimeString);
		return $formattedDate->format('d.M.Y');
	}
	private function formatTime($dateTimeString)
	{
		$formattedTime = $this->getSpecificDateTimeObj($dateTimeString);
		return $formattedTime->format('H:i');
	}






	// Security Methods


}


?>