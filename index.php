<?php

require_once 'classes.inc.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/MyProjects/_MyClasses/FilterExternalInput.class.php';

?>

<!DOCTYPE HTML>
<html>
<head>
	<title>Kalender</title>
	<meta charset="utf-8">
	<link rel="stylesheet" href="css/default.css">
	<link rel="stylesheet" href="font-awesome-4.3.0/css/font-awesome.min.css">
</head>

<body>

<div class="wrapper">
	<?php

	// Termin hinzufügen & ändern
	if(isset($_GET['action']))
	{
		$action = filter_input(INPUT_GET, 'action', FILTER_SANITIZE_SPECIAL_CHARS);
		$confirm = filter_input(INPUT_GET, 'confirm', FILTER_SANITIZE_SPECIAL_CHARS);
		$btn_send = filter_input(INPUT_POST, 'btn_send', FILTER_SANITIZE_SPECIAL_CHARS);

		if($action == "add_event" && !isset($confirm))
		{
		?>
			<div class="event_form">
				<fieldset name="new_event">
					<legend>Neuen Termin hinzuf&uuml;gen</legend>
					<br>
					<form action="?action=add_event&confirm=check" method="post">
						<fieldset name="event_start">
							<legend>Beginn</legend>
							<label for="date" class="label_width">Datum: </label>
							<select name="event_start_day">
								<?php
									for($i = 1; $i <= 31; $i++)
									{
										echo '<option value="'. $i .'">'. str_pad($i, 2, '0', STR_PAD_LEFT) .'</option>';
									}
								?>
							</select>
							<select name="event_start_month">
								<?php
									for($i = 1; $i <= 12; $i++)
									{
										echo '<option value="'. $i .'">'. str_pad($i, 2, '0', STR_PAD_LEFT) .'</option>';
									}
								?>
							</select>
							<select name="event_start_year">
								<?php
									for($i = 2014; $i <= 2050; $i++)
										echo '<option value="'.$i.'">'.$i.'</option>';
								?>
							</select>
							<br>
							<label for="time">Uhrzeit: </label>
							<select name="event_start_hour">
								<?php
									for($i = 0; $i <= 23; $i++)
									{
										echo '<option value="'. $i .'">'. str_pad($i, 2, '0', STR_PAD_LEFT) .'</option>';
									}
								?>
							</select>
							<select name="event_start_minute">
								<?php
									for($i = 0; $i < 60; $i += 15)
									{
										echo '<option value="'. $i .'">'. str_pad($i, 2, '0', STR_PAD_LEFT) .'</option>';
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
										echo '<option value="'. $i .'">'. str_pad($i, 2, '0', STR_PAD_LEFT) .'</option>';
									}
								?>
							</select>
							<select name="event_end_month">
								<?php
									for($i = 1; $i <= 12; $i++)
									{
										echo '<option value="'. $i .'">'. str_pad($i, 2, '0', STR_PAD_LEFT) .'</option>';
									}
								?>
							</select>
							<select name="event_end_year">
								<?php
									for($i = 2014; $i <= 2050; $i++)
										echo '<option value="'.$i.'">'.$i.'</option>';
								?>
							</select>
							<br>
							<label for="time">Uhrzeit: </label>
							<select name="event_end_hour">
								<?php
									for($i = 0; $i <= 23; $i++)
									{
										echo '<option value="'. $i .'">'. str_pad($i, 2, '0', STR_PAD_LEFT) .'</option>';
									}
								?>
							</select>
							<select name="event_end_minute">
								<?php
									for($i = 0; $i < 60; $i += 15)
									{
										echo '<option value="'. $i .'">'. str_pad($i, 2, '0', STR_PAD_LEFT) .'</option>';
									}
								?>
							</select>
						</fieldset>

						<br>
						<label for="event_header">&Uuml;berschrift: </label><input type="text" name="event_header" maxlength="200">
						<br>
						<label for="event_body">Beschreibung: </label><textarea name="event_body"></textarea>
						<br>
						<button type="submit" name="btn_send" value="add_event">Absenden</button>
						&nbsp;&nbsp;&nbsp;&nbsp;<a href="index.php">zur&uuml;ck zum Kalender</a>
					</form>
				</fieldset>
			</div>

		<?php
		}
		elseif($action == "edit_event" && !isset($confirm))
		{
			$cleared_event_id = filter_input(INPUT_GET, 'entry_id', FILTER_VALIDATE_INT);

			$calender = new Calender();
			


		?>
			<div class="event_form">
				<fieldset name="new_event">
					<legend>Neuen Termin hinzuf&uuml;gen</legend>
					<br>
					<form action="?action=add_event&confirm=check" method="post">
						<fieldset name="event_start">
							<legend>Beginn</legend>
							<label for="date" class="label_width">Datum: </label>
							<select name="event_start_day">
								<?php
									for($i = 1; $i <= 31; $i++)
									{
										echo '<option value="'. $i .'">'. str_pad($i, 2, '0', STR_PAD_LEFT) .'</option>';
									}
								?>
							</select>
							<select name="event_start_month">
								<?php
									for($i = 1; $i <= 12; $i++)
									{
										echo '<option value="'. $i .'">'. str_pad($i, 2, '0', STR_PAD_LEFT) .'</option>';
									}
								?>
							</select>
							<select name="event_start_year">
								<?php
									for($i = 2014; $i <= 2050; $i++)
										echo '<option value="'.$i.'">'.$i.'</option>';
								?>
							</select>
							<br>
							<label for="time">Uhrzeit: </label>
							<select name="event_start_hour">
								<?php
									for($i = 0; $i <= 23; $i++)
									{
										echo '<option value="'. $i .'">'. str_pad($i, 2, '0', STR_PAD_LEFT) .'</option>';
									}
								?>
							</select>
							<select name="event_start_minute">
								<?php
									for($i = 0; $i < 60; $i += 15)
									{
										echo '<option value="'. $i .'">'. str_pad($i, 2, '0', STR_PAD_LEFT) .'</option>';
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
										echo '<option value="'. $i .'">'. str_pad($i, 2, '0', STR_PAD_LEFT) .'</option>';
									}
								?>
							</select>
							<select name="event_end_month">
								<?php
									for($i = 1; $i <= 12; $i++)
									{
										echo '<option value="'. $i .'">'. str_pad($i, 2, '0', STR_PAD_LEFT) .'</option>';
									}
								?>
							</select>
							<select name="event_end_year">
								<?php
									for($i = 2014; $i <= 2050; $i++)
										echo '<option value="'.$i.'">'.$i.'</option>';
								?>
							</select>
							<br>
							<label for="time">Uhrzeit: </label>
							<select name="event_end_hour">
								<?php
									for($i = 0; $i <= 23; $i++)
									{
										echo '<option value="'. $i .'">'. str_pad($i, 2, '0', STR_PAD_LEFT) .'</option>';
									}
								?>
							</select>
							<select name="event_end_minute">
								<?php
									for($i = 0; $i < 60; $i += 15)
									{
										echo '<option value="'. $i .'">'. str_pad($i, 2, '0', STR_PAD_LEFT) .'</option>';
									}
								?>
							</select>
						</fieldset>

						<br>
						<label for="event_header">&Uuml;berschrift: </label><input type="text" name="event_header" maxlength="200" value="<?php  ?>">
						<br>
						<label for="event_body">Beschreibung: </label><textarea name="event_body"></textarea>
						<br>
						<button type="submit" name="btn_send" value="add_event">Absenden</button>
						&nbsp;&nbsp;&nbsp;&nbsp;<a href="index.php">zur&uuml;ck zum Kalender</a>
					</form>
				</fieldset>
			</div>

		<?php
		}
		elseif($action == "del_event" && !isset($confirm))
		{

		}
		elseif(($action == "add_event" || $action == "edit_event") && $confirm == "check")
		{
			if(isset($_POST[event_id]))
				$event_id		= filter_input(INPUT_POST, 'event_id', FILTER_VALIDATE_INT);

			$event_start_day	= filter_input(INPUT_POST, 'event_start_day', FILTER_VALIDATE_INT);
			$event_start_month	= filter_input(INPUT_POST, 'event_start_month', FILTER_VALIDATE_INT);
			$event_start_year	= filter_input(INPUT_POST, 'event_start_year', FILTER_VALIDATE_INT);
			$event_start_hour	= filter_input(INPUT_POST, 'event_start_hour', FILTER_VALIDATE_INT);
			$event_start_minute	= filter_input(INPUT_POST, 'event_start_minute', FILTER_VALIDATE_INT);
			$event_end_day		= filter_input(INPUT_POST, 'event_start_day', FILTER_VALIDATE_INT);
			$event_end_month	= filter_input(INPUT_POST, 'event_start_month', FILTER_VALIDATE_INT);
			$event_end_year		= filter_input(INPUT_POST, 'event_start_year', FILTER_VALIDATE_INT);
			$event_end_hour		= filter_input(INPUT_POST, 'event_start_hour', FILTER_VALIDATE_INT);
			$event_end_minute	= filter_input(INPUT_POST, 'event_start_minute', FILTER_VALIDATE_INT);
			$event_header		= filter_input(INPUT_POST, 'event_header', FILTER_SANITIZE_SPECIAL_CHARS);
			$event_body			= filter_input(INPUT_POST, 'event_body', FILTER_SANITIZE_SPECIAL_CHARS);

			if(isset($event_id))
				echo '<form action="index.php?action=edit_event&confirm=true" method="post">';
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
															':'.str_pad($event_end_minute, 2, '0', STR_PAD_LEFT); ?></span><br>

					<button type="submit" name="btn_send" value="add_event_checked">Ja, eintragen</button>
					<!--<button type="submit" name="btn_send" value="edit_event">Nein, korriegieren</button>
					<button type="button" name="btn_send" value="cancel">Abbrechen</button>-->
					<?php
					if(isset($event_id))
						echo '<input type="hidden" name="event_id" value="<?php echo $event_id; ?>">';
					?>

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
				</fieldset>
			</form>

		<?php
		}
		elseif($action == "add_event" && $confirm == "true")
		{
			$event_start_day = filter_input(INPUT_POST, 'event_start_day', FILTER_VALIDATE_INT);
			$event_start_month = filter_input(INPUT_POST, 'event_start_month', FILTER_VALIDATE_INT);
			$event_start_year = filter_input(INPUT_POST, 'event_start_year', FILTER_VALIDATE_INT);
			$event_start_hour = filter_input(INPUT_POST, 'event_start_hour', FILTER_VALIDATE_INT);
			$event_start_minute = filter_input(INPUT_POST, 'event_start_minute', FILTER_VALIDATE_INT);
			$event_end_day = filter_input(INPUT_POST, 'event_start_day', FILTER_VALIDATE_INT);
			$event_end_month = filter_input(INPUT_POST, 'event_start_month', FILTER_VALIDATE_INT);
			$event_end_year = filter_input(INPUT_POST, 'event_start_year', FILTER_VALIDATE_INT);
			$event_end_hour = filter_input(INPUT_POST, 'event_start_hour', FILTER_VALIDATE_INT);
			$event_end_minute = filter_input(INPUT_POST, 'event_start_minute', FILTER_VALIDATE_INT);
			$event_header = filter_input(INPUT_POST, 'event_header', FILTER_SANITIZE_SPECIAL_CHARS);
			$event_body = filter_input(INPUT_POST, 'event_body', FILTER_SANITIZE_SPECIAL_CHARS);

			$calender = new Calender();
			//echo "aujujnduasnudnasdn";
			$calender->addEvent($event_start_day,
								$event_start_month,
								$event_start_year,
								$event_start_hour,
								$event_start_minute,
								$event_end_day,
								$event_end_month,
								$event_end_year,
								$event_end_hour,
								$event_end_minute,
								$event_header,
								$event_body);

		}
		elseif($action == "edit_event" && $confirm == "true")
		{
			$event_start_day = filter_input(INPUT_POST, 'event_start_day', FILTER_VALIDATE_INT);
			$event_start_month = filter_input(INPUT_POST, 'event_start_month', FILTER_VALIDATE_INT);
			$event_start_year = filter_input(INPUT_POST, 'event_start_year', FILTER_VALIDATE_INT);
			$event_start_hour = filter_input(INPUT_POST, 'event_start_hour', FILTER_VALIDATE_INT);
			$event_start_minute = filter_input(INPUT_POST, 'event_start_minute', FILTER_VALIDATE_INT);
			$event_end_day = filter_input(INPUT_POST, 'event_start_day', FILTER_VALIDATE_INT);
			$event_end_month = filter_input(INPUT_POST, 'event_start_month', FILTER_VALIDATE_INT);
			$event_end_year = filter_input(INPUT_POST, 'event_start_year', FILTER_VALIDATE_INT);
			$event_end_hour = filter_input(INPUT_POST, 'event_start_hour', FILTER_VALIDATE_INT);
			$event_end_minute = filter_input(INPUT_POST, 'event_start_minute', FILTER_VALIDATE_INT);
			$event_header = filter_input(INPUT_POST, 'event_header', FILTER_SANITIZE_SPECIAL_CHARS);
			$event_body = filter_input(INPUT_POST, 'event_body', FILTER_SANITIZE_SPECIAL_CHARS);

			$calender = new Calender();

			$calender->addEvent($event_start_day,
								$event_start_month,
								$event_start_year,
								$event_start_hour,
								$event_start_minute,
								$event_end_day,
								$event_end_month,
								$event_end_year,
								$event_end_hour,
								$event_end_minute,
								$event_header,
								$event_body);
		}

	}
	// Termin & Monat anzeigen
	else
	{

		if(isset($_GET['show']) == "entry")
		{

		}
		else
		{

		?>
			<div class="calender clearfix">
				<div class="navigationRow row">
					<div class="navigationRowItem rowItem"><i class="fa fa-angle-double-left"></i> Jahr</div>
					<div class="navigationRowItem rowItem"><i class="fa fa-angle-left"></i> Monat</div>
					<div class="navigationRowItem rowItem">Monat <i class="fa fa-angle-right"></i></div>
					<div class="navigationRowItem rowItem">Jahr <i class="fa fa-angle-double-right"></i></div>
				</div>

				<div class="weekDayNamesRow row">
					<?php
					/*
					$daynames = ["Montag", "Dienstag", "Mittwoch", "Donnerstag", "Freitag", "Samstag", "Sonntag"];

					foreach($daynames as $value)
					{
						echo '<div class="weekDayNamesRowItem rowItem">'.$value.'</div>';
					}
					*/
					?>
				</div>

				<?php

					$filterExternalInput = new FilterExternalInput();

					if(isset($_GET['show']))
					{
						$show = filter_input(INPUT_GET, 'show', FILTER_SANITIZE_SPECIAL_CHARS);
					}
					if(isset($_GET['year']))
					{

					}
					if(isset($_GET['month']))
					{

					}
					if(isset($_GET['day']))
					{

					}

					$calender = new Calender(2015, 4);

					$calender->showMonth();

				?>
			</div>
		<?php

		}
	}

	?>
</div>
	
</body>

</html>
