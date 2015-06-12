<?php

//echo $_SERVER['DOCUMENT_ROOT'];

//require_once ($_SERVER['DOCUMENT_ROOT']."../_MyClasses/DBConnection.class.php");
require_once ("_MyClasses/DBConnection.class.php");
require_once 'C:/WebDev/Websites/MyProjects/_MyClasses/FilterExternalInput.class.php';
require_once 'C:/WebDev/Websites/MyProjects/calender/config.inc.php';


class Calender
{
	//private $hour;
	//private $minute;
	private $year;
	private $month;
	private $day;

	private $dbConnection;


	public function __construct($year = NULL, $month = NULL, $day = NULL)
	{
		$this->dbConnection = new DBConnection();

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



	public function showDay()
	{

	}

	public function showWeek()
	{

	}

	public function showMonth($month = NULL, $year = NULL)
	{
		// Wochen Grid anzeigen
		if( $this->year == NULL || $this->month == NULL || $this->day == NULL )
		{
			$this->showErrorMsg("Da ist entweder der Monat und/oder das Jahr falsch.");
		}
		else
		{
			//$dbConnection = new DBConnection();

			$this->dbConnection->openConnection();

            if($month == NULL && $year == NULL)
            {
                $clearedYear	= $this->dbConnection->SqlInjectionStopper($this->year);
                $clearedMonth	= $this->dbConnection->SqlInjectionStopper($this->month);
            }
            else
            {
                $clearedYear	= $this->dbConnection->SqlInjectionStopper($year);
                $clearedMonth	= $this->dbConnection->SqlInjectionStopper($month);
            }


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

			$sqlResult = $this->dbConnection->sendSqlQuery($sqlQuery);

			$monthDayCount = $this->monthDayCount($clearedYear.'-'.$clearedMonth.'-'.$this->day);

			$monthStartDayName = $this->monthStartDayName($clearedYear.'-'.$clearedMonth.'-'.$this->day);

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

			$monthName = $this->monthName($clearedYear.'-'.$clearedMonth.'-'.$this->day);

            if($clearedMonth == 1)
            {
                $subMonth = "?year=". ($clearedYear - 1) ."&month=12";

                $addMonth = "?year=". $clearedYear ."&month=". ($clearedMonth + 1);


            }
            elseif($clearedMonth == 12)
            {
                $subMonth = "?year=". $clearedYear ."&month=". ($clearedMonth - 1);

                $addMonth = "?year=". ($clearedYear + 1) ."&month=1";
            }
            else
            {
                $subMonth = "?year=". $clearedYear ."&month=". ($clearedMonth - 1);

                $addMonth = "?year=". $clearedYear ."&month=". ($clearedMonth + 1);
            }
            ?>

            <div class="navigationRow row">
                <div class="navigationRowItem rowItem"><a href="?year=<?php echo $clearedYear - 1; ?>&month=<?php echo $clearedMonth *= 1; ?>"><i class="fa fa-angle-double-left"></i> Jahr</a></div>
                <div class="navigationRowItem rowItem"><a href="<?php echo $subMonth ?>"><i class="fa fa-angle-left"></i> Monat</div>
                <div class="navigationRowItem rowItem"><a href="<?php echo $addMonth ?>">Monat <i class="fa fa-angle-right"></i></div>
                <div class="navigationRowItem rowItem"><a href="?year=<?php echo $clearedYear + 1; ?>&month=<?php echo $clearedMonth; ?>">Jahr <i class="fa fa-angle-double-right"></i></a></div>
            </div>

            <?php

			echo '<div class="row">'.$monthName.' '.$clearedYear.' | <a href="?action=add_event">Termin hinzuf&uuml;gen</a>';
            if($clearedYear != $now->format('Y') || $clearedMonth != $now->format('m'))
            {
                echo ' | <a href="?year='. $now->format('Y') .'&month='. ($now->format('m') * 1) .'">Zum aktuellen Monat wechseln</a>';
            }
            echo '</div>';

			for($i = 1; $i <= $rowCount; $i++) // 6x7 Zellen (1 Zeile fest f�r Tagesnamen)
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

                    echo '<br><br>';

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

									$startTime	= $this->formatTime($entry['entry_start_datetime']);
									$endTime	= $this->formatTime($entry['entry_end_datetime']);

									echo '<a href="" title="'.$entry['entry_body'].'">'.$startTime.' - '.$endTime.'<br>'.$entry['entry_header'].'</a><br><a href="?action=edit_event&event_id='.$entry['entry_id'].'" title="Eintrag &auml;ndern"><i class="fa fa-pencil-square-o"></i></a> <a href="?action=del_event&confirm=check&event_id='.$entry['entry_id'].'" title="Eintrag l&ouml;schen"><i class="fa fa-eraser"></i></a>';

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


			// Anderer Ansatz f�r Darstellung
			/*
			for($i = 1; $i <= $rowCount; $i++) // 6x7 Zellen (1 Zeile fest f�r Tagesnamen)
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

			$this->dbConnection->closeConnection();
		}
	}

	public function showYear()
	{

        return;
	}

	private function showErrorMsg($msg)
	{
		echo '<p class="errorMsg">Ups das ist wohl was schief gelaufen. '.$msg;

        return;
	}




	public function showEventForm($event = NULL)
	{
		if($event != NULL)
		{
			$event_start = $this->getSpecificDateTimeObj($event['entry_start_datetime']);
			$event_end = $this->getSpecificDateTimeObj($event['entry_end_datetime']);

			$event_start_day	= $event_start->format('d');
			$event_start_month	= $event_start->format('m');
			$event_start_year	= $event_start->format('Y');
			$event_start_hour	= $event_start->format('H');
			$event_start_minute	= $event_start->format('i');
			$event_end_day		= $event_end->format('d');
			$event_end_month	= $event_end->format('m');
			$event_end_year		= $event_end->format('Y');
			$event_end_hour		= $event_end->format('H');
			$event_end_minute	= $event_end->format('i');
		}

	    ?>

		<div class="event_form">
				<fieldset name="eventForm">
					<legend>Tragen Sie die Termindetails ein</legend>
					<br>
					<form action="?action=add_event&confirm=check" method="post">
						<fieldset name="event_start">
							<legend>Beginn</legend>
							<label for="date" class="label_width">Datum: </label>
                            <label>
                                <select name="event_start_day">
                                    <?php
									for($i = 1; $i <= 31; $i++)
									{
										echo '<option value="'. $i.'"';
										if($event != NULL && $event_start_day == $i)	echo ' selected';
										echo '>'. str_pad($i, 2, '0', STR_PAD_LEFT) .'</option>';
                                    }
                                    ?>
                                </select>
                            </label>
                            .
							<select name="event_start_month">
								<?php
									for($i = 1; $i <= 12; $i++)
									{
										echo '<option value="'. $i .'"';
										if($event != NULL && $event_start_month*1 == $i )	echo ' selected';
										echo '>'. str_pad($i, 2, '0', STR_PAD_LEFT) .'</option>';
									}
								?>
							</select>
							.
							<select name="event_start_year">
								<?php
									for($i = 2014; $i <= 2050; $i++)
									{
										echo '<option value="'.$i.'"';
										if($event != NULL && $event_start_year == $i)	echo ' selected';
										echo '>'.$i.'</option>';
									}
								?>
							</select>
							<br>
							<label for="time">Uhrzeit: </label>
							<select name="event_start_hour">
								<?php
									for($i = 0; $i <= 23; $i++)
									{
										echo '<option value="'. $i .'"';
										if($event != NULL && $event_start_hour == $i)	echo ' selected';
										echo '>'. str_pad($i, 2, '0', STR_PAD_LEFT) .'</option>';
									}
								?>
							</select>
							:
							<select name="event_start_minute">
								<?php
									for($i = 0; $i < 60; $i += 15)
									{
										echo '<option value="'. $i .'"';
										if($event != NULL && $event_start_minute == $i)	echo ' selected';
										echo '>'. str_pad($i, 2, '0', STR_PAD_LEFT) .'</option>';
									}
								?>
							</select>
						</fieldset>

						<fieldset name="event_end">
							<legend>Ende</legend>
							<label for="date" class="label_width">Datum: </label>
							<select name="event_end_day">
								<?php
									for($i = 1; $i <= 31; $i++)
									{
										echo '<option value="'. $i.'"';
										if($event != NULL && $event_end_day == $i)	echo ' selected';
										echo '>'. str_pad($i, 2, '0', STR_PAD_LEFT) .'</option>';
									}
								?>
							</select>
							.
							<select name="event_end_month">
								<?php
									for($i = 1; $i <= 12; $i++)
									{
										echo '<option value="'. $i .'"';
										if($event != NULL && $event_end_month == $i)	echo ' selected';
										echo '>'. str_pad($i, 2, '0', STR_PAD_LEFT) .'</option>';
									}
								?>
							</select>
							.
							<select name="event_end_year">
								<?php
									for($i = 2014; $i <= 2050; $i++)
									{
										echo '<option value="'.$i.'"';
										if($event != NULL && $event_end_year == $i)	echo ' selected';
										echo '>'.$i.'</option>';
									}
								?>
							</select>
							<br>
							<label for="time">Uhrzeit: </label>
							<select name="event_end_hour">
								<?php
									for($i = 0; $i <= 23; $i++)
									{
										echo '<option value="'. $i .'"';
										if($event != NULL && $event_end_hour == $i)	echo ' selected';
										echo '>'. str_pad($i, 2, '0', STR_PAD_LEFT) .'</option>';
									}
								?>
							</select>
							:
							<select name="event_end_minute">
								<?php
									for($i = 0; $i < 60; $i += 15)
									{
										echo '<option value="'. $i .'"';
										if($event != NULL && $event_end_minute == $i)	echo ' selected';
										echo '>'. str_pad($i, 2, '0', STR_PAD_LEFT) .'</option>';
									}
								?>
							</select>
						</fieldset>

						<br>
						<label for="event_header">&Uuml;berschrift: </label><input type="text" name="event_header" maxlength="200"
						<?php	if($event != NULL)	echo 'value="'.$event['entry_header'].'"';	?>>
						<br>
						<label for="event_body">Beschreibung: </label><textarea name="event_body"><?php	if($event != NULL)	echo $event['entry_body'];	?></textarea>
						<?php
							if($event != NULL)
								echo '<input type="hidden" name="event_id" value="'.$event['entry_id'].'">';
						?>
						<br>
						<button type="submit" name="btn_send">Absenden</button>
						&nbsp;&nbsp;&nbsp;&nbsp;<a href="index.php">zur&uuml;ck zum Kalender</a>
					</form>
				</fieldset>
			</div>

	    <?php

        return;
	}

	public function showEventCheckForm($action)
	{
		if(isset($_POST['event_id']))
				$event_id		= filter_input(INPUT_POST, 'event_id', FILTER_VALIDATE_INT);
		if(isset($_GET['event_id']))
				$event_id		= filter_input(INPUT_GET, 'event_id', FILTER_VALIDATE_INT);

		if($action == "del_event")
		{
			$event = $this->readEventFromDB($event_id);

			$event_start = $this->getSpecificDateTimeObj($event['entry_start_datetime']);
			$event_end = $this->getSpecificDateTimeObj($event['entry_end_datetime']);

			$event_start_day	= $event_start->format('d');
			$event_start_month	= $event_start->format('m');
			$event_start_year	= $event_start->format('Y');
			$event_start_hour	= $event_start->format('H');
			$event_start_minute	= $event_start->format('i');
			$event_end_day		= $event_end->format('d');
			$event_end_month	= $event_end->format('m');
			$event_end_year		= $event_end->format('Y');
			$event_end_hour		= $event_end->format('H');
			$event_end_minute	= $event_end->format('i');
			$event_header		= $event['entry_header'];
			$event_body			= $event['entry_body'];
		}
		else
		{
			$event_start_day	= filter_input(INPUT_POST, 'event_start_day', FILTER_VALIDATE_INT);
			$event_start_month	= filter_input(INPUT_POST, 'event_start_month', FILTER_VALIDATE_INT);
			$event_start_year	= filter_input(INPUT_POST, 'event_start_year', FILTER_VALIDATE_INT);
			$event_start_hour	= filter_input(INPUT_POST, 'event_start_hour', FILTER_VALIDATE_INT);
			$event_start_minute	= filter_input(INPUT_POST, 'event_start_minute', FILTER_VALIDATE_INT);
			$event_end_day		= filter_input(INPUT_POST, 'event_end_day', FILTER_VALIDATE_INT);
			$event_end_month	= filter_input(INPUT_POST, 'event_end_month', FILTER_VALIDATE_INT);
			$event_end_year		= filter_input(INPUT_POST, 'event_end_year', FILTER_VALIDATE_INT);
			$event_end_hour		= filter_input(INPUT_POST, 'event_end_hour', FILTER_VALIDATE_INT);
			$event_end_minute	= filter_input(INPUT_POST, 'event_end_minute', FILTER_VALIDATE_INT);
			$event_header		= filter_input(INPUT_POST, 'event_header', FILTER_SANITIZE_SPECIAL_CHARS);
			$event_body			= filter_input(INPUT_POST, 'event_body', FILTER_SANITIZE_SPECIAL_CHARS);
		}

		if(isset($event_id) && ($action == "edit_event") )
			echo '<form action="index.php?action=edit_event&confirm=true" method="post">';
		elseif(isset($event_id) && ($action == "del_event") )
			echo '<form action="index.php?action=del_event&confirm=true" method="post">';
		else
			echo '<form action="index.php?action=add_event&confirm=true" method="post">';

	    ?>

			<fieldset>
				<legend>Sind die Daten korrekt ?</legend>
				<label>&Uuml;berschrift: </label><span><?php echo $event_header; ?></span><br>
				<label>Beschreibung: </label><span><?php echo $event_body; ?></span><br>
				<label>Von: </label><span><?php echo str_pad($event_start_day, 2, '0', STR_PAD_LEFT).
														'.'.str_pad($event_start_month, 2, '0', STR_PAD_LEFT).
														'.'.str_pad($event_start_year, 2, '0', STR_PAD_LEFT).
														' '.str_pad($event_start_hour, 2, '0', STR_PAD_LEFT).
														':'.str_pad($event_start_minute, 2, '0', STR_PAD_LEFT); ?></span><br>
				<label>Bis: </label><span><?php echo str_pad($event_end_day, 2, '0', STR_PAD_LEFT).
														'.'.str_pad($event_end_month, 2, '0', STR_PAD_LEFT).
														'.'.str_pad($event_end_year, 2, '0', STR_PAD_LEFT).
														' '.str_pad($event_end_hour, 2, '0', STR_PAD_LEFT).
														':'.str_pad($event_end_minute, 2, '0', STR_PAD_LEFT); ?></span><br><br>
                <label>Paswort: </label><input type="password" value="passwort" name="password"><br><br>

				<?php
				if(isset($event_id) && $action == "del_event")
				{
					echo '<button type="submit" name="btn_send" value="add_event_checked">Ja, l&ouml;schen</button>';
					echo '&nbsp;&nbsp;&nbsp;&nbsp;<a href="index.php">zur&uuml;ck zum Kalender</a>';
				}
				else
				{
					echo '<button type="submit" name="btn_send" value="add_event_checked">Ja, eintragen</button>';
				}
				?>
				
				<!--<button type="submit" name="btn_send" value="edit_event">Nein, korriegieren</button>
				<button type="button" name="btn_send" value="cancel">Abbrechen</button>-->

				<?php
				if(isset($event_id))
					echo '<input type="hidden" name="event_id" value="'. $event_id .'">';
				
				if($action == "add_event" || $action == "edit_event")
				{?>
					<input type="hidden" name="event_header" value="<?php echo $event_header; ?>">
					<input type="hidden" name="event_body" value="<?php echo $event_body; ?>">
					<input type="hidden" name="event_start_day" value="<?php echo $event_start_day; ?>">
					<input type="hidden" name="event_start_month" value="<?php echo $event_start_month; ?>">
					<input type="hidden" name="event_start_year" value="<?php echo $event_start_year; ?>">
					<input type="hidden" name="event_start_hour" value="<?php echo $event_start_hour; ?>">
					<input type="hidden" name="event_start_minute" value="<?php echo $event_start_minute; ?>">
					<input type="hidden" name="event_end_day" value="<?php echo $event_end_day; ?>">
					<input type="hidden" name="event_end_month" value="<?php echo $event_end_month; ?>">
					<input type="hidden" name="event_end_year" value="<?php echo $event_end_year; ?>">
					<input type="hidden" name="event_end_hour" value="<?php echo $event_end_hour; ?>">
					<input type="hidden" name="event_end_minute" value="<?php echo $event_end_minute; ?>">
				<?php
				}
				?>
			</fieldset>
		</form>

	    <?php

        return;
	}

	


	// Database Interaction

	public function writeEventToDB()
	{
		$this->dbConnection->openConnection();

		if(isset($_POST['event_id']))
			$cleared_event_id				= $this->dbConnection->SqlInjectionStopper(filter_input(INPUT_POST, 'event_id', FILTER_VALIDATE_INT));

		$cleared_event_start_day		= $this->dbConnection->SqlInjectionStopper(filter_input(INPUT_POST, 'event_start_day', FILTER_VALIDATE_INT));
		$cleared_event_start_month		= $this->dbConnection->SqlInjectionStopper(filter_input(INPUT_POST, 'event_start_month', FILTER_VALIDATE_INT));
		$cleared_event_start_year		= $this->dbConnection->SqlInjectionStopper(filter_input(INPUT_POST, 'event_start_year', FILTER_VALIDATE_INT));
		$cleared_event_start_hour		= $this->dbConnection->SqlInjectionStopper(filter_input(INPUT_POST, 'event_start_hour', FILTER_VALIDATE_INT));
		$cleared_event_start_minute		= $this->dbConnection->SqlInjectionStopper(filter_input(INPUT_POST, 'event_start_minute', FILTER_VALIDATE_INT));
		$cleared_event_end_day			= $this->dbConnection->SqlInjectionStopper(filter_input(INPUT_POST, 'event_end_day', FILTER_VALIDATE_INT));
		$cleared_event_end_month		= $this->dbConnection->SqlInjectionStopper(filter_input(INPUT_POST, 'event_end_month', FILTER_VALIDATE_INT));
		$cleared_event_end_year			= $this->dbConnection->SqlInjectionStopper(filter_input(INPUT_POST, 'event_end_year', FILTER_VALIDATE_INT));
		$cleared_event_end_hour			= $this->dbConnection->SqlInjectionStopper(filter_input(INPUT_POST, 'event_end_hour', FILTER_VALIDATE_INT));
		$cleared_event_end_minute		= $this->dbConnection->SqlInjectionStopper(filter_input(INPUT_POST, 'event_end_minute', FILTER_VALIDATE_INT));
		$cleared_event_header			= trim( $this->dbConnection->SqlInjectionStopper(filter_input(INPUT_POST, 'event_header', FILTER_SANITIZE_SPECIAL_CHARS)) );
		$cleared_event_body				= trim( $this->dbConnection->SqlInjectionStopper(filter_input(INPUT_POST, 'event_body', FILTER_SANITIZE_SPECIAL_CHARS)) );

		$now					= $this->getActualDateTimeObj();
		$entry_edited			= $now->format('Y-m-d H:i:s');
		$entry_start_datetime	= $cleared_event_start_year.'-'.$cleared_event_start_month.'-'.$cleared_event_start_day.' '.$cleared_event_start_hour.':'.$cleared_event_start_minute.':00';
		$entry_end_datetime		= $cleared_event_end_year.'-'.$cleared_event_end_month.'-'.$cleared_event_end_day.' '.$cleared_event_end_hour.':'.$cleared_event_end_minute.':00';

		//echo $cleared_event_id;

		if(isset($cleared_event_id))
		{
			$sqlQuery = "
					UPDATE
						entries
					SET
						entry_edited = '$entry_edited', entry_start_datetime = '$entry_start_datetime', entry_end_datetime = '$entry_end_datetime', entry_header = '$cleared_event_header', entry_body = '$cleared_event_body'
					WHERE
						entry_id = '$cleared_event_id'
					";

			//echo $sqlQuery;

			$sqlResult = $this->dbConnection->sendSqlQuery($sqlQuery);

			/*
			echo '<pre>';
			var_dump($sqlResult);
			echo '</pre>';
			*/

			if($sqlResult)
			{
				echo 'Datensatz wurde ge&auml;ndert. <a href="index.php">Zur&uuml;ck</a>';
			}
			else
			{
				echo 'Datensatz konnte nicht ge&auml;ndert, bzw. gefunden werden. <a href="index.php">Zur&uuml;ck</a>';
			}
		}
		else
		{
			$sqlQuery = "
					INSERT
						entries (entry_inserted, entry_start_datetime, entry_end_datetime, entry_header, entry_body)
					VALUES
						('$entry_edited', '$entry_start_datetime', '$entry_end_datetime', '$cleared_event_header', '$cleared_event_body')
					";

			$sqlResult = $this->dbConnection->sendSqlQuery($sqlQuery);

			/*
			echo '<pre>';
			var_dump($sqlResult);
			echo '</pre>';
			*/

			if($sqlResult)
				echo 'Datensatz wurde hinzugef&uuml;gt. <a href="index.php">Zur&uuml;ck</a>';
			else
				echo 'Daten konnten nicht eingetragen werden, bitte versuchen Sie es sp�ter erneut. <a href="index.php">Zur&uuml;ck</a>';
		}

		$this->dbConnection->closeConnection();

        return;
	}

	public function deleteEventFromDB($event_id)
	{
		$this->dbConnection->openConnection();

		$cleared_event_id = $this->dbConnection->SqlInjectionStopper($event_id);

		$sqlQuery = "
					DELETE FROM
						entries
					WHERE
						entry_id = '$cleared_event_id'
					";

		$sqlResult = $this->dbConnection->sendSqlQuery($sqlQuery);

		/*
		echo '<pre>';
		var_dump($sqlResult);
		echo '</pre>';
		*/

		if($sqlResult)
		{
			echo 'Datensatz wurde gel&ouml;scht. <a href="index.php">Zur&uuml;ck</a>';
		}
		else
		{
			echo 'Datensatz konnte nicht gel&ouml;scht, bzw. gefunden werden. <a href="index.php">Zur&uuml;ck</a>';
		}

		$this->dbConnection->closeConnection();
	}

	public function readEventFromDB($event_id)
	{
		$this->dbConnection->openConnection();

		$cleared_event_id = $this->dbConnection->SqlInjectionStopper($event_id);

		$sqlQuery = "
					SELECT
						entry_id, entry_inserted, entry_start_datetime, entry_end_datetime, entry_header, entry_body
					FROM
						entries
					WHERE
						entry_id = '".$cleared_event_id."'
					";

		$sqlResult = $this->dbConnection->sendSqlQuery($sqlQuery);

		if($sqlResult->num_rows > 0)
		{
			return $sqlResult->fetch_assoc();
		}
		else
		{
			echo 'Datensatz konnte nicht gelesen, bzw. gefunden werden.';
		}

		$this->dbConnection->closeConnection();
	}

    public function checkPW()
    {
        if(isset($_POST['password']))
        {
            $pass = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_SPECIAL_CHARS);

            $this->dbConnection->openConnection();

            $sqlQuery = "
                    SELECT
                        password
                    FROM
                      woooot
                    ";

            $sqlResult = $this->dbConnection->sendSqlQuery($sqlQuery);

            if($sqlResult->num_rows > 0)
            {
                $data =  $sqlResult->fetch_assoc();

                if($pass == $data['password'])
                {
                    $erg = true;
                }
                else
                {
                    $erg = false;
                }
            }
            else
            {
                echo 'Da stimmt etwas nicht. <a href="index.php">zur&uuml;ck zum Kalender</a>';

                $erg = false;
            }

            $this->dbConnection->closeConnection();

            return $erg;
        }
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

	// Gibt den Tagesnamen zur�ck
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