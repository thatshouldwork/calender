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
	<link rel="stylesheet" href="/_Tools/css/font-awesome-4.3.0/css/font-awesome.min.css">
</head>

<body>

<div class="wrapper">
	<?php

	$calender = new Calender();

	// Termin hinzufügen & ändern
	if(isset($_GET['action']))
	{
		$action = filter_input(INPUT_GET, 'action', FILTER_SANITIZE_SPECIAL_CHARS);
		$confirm = filter_input(INPUT_GET, 'confirm', FILTER_SANITIZE_SPECIAL_CHARS);
		$btn_send = filter_input(INPUT_POST, 'btn_send', FILTER_SANITIZE_SPECIAL_CHARS);

		if(isset($_GET['event_id']))
			$cleared_event_id = filter_input(INPUT_GET, 'event_id', FILTER_VALIDATE_INT);
		if(isset($_POST['event_id']))
			$cleared_event_id = filter_input(INPUT_POST, 'event_id', FILTER_VALIDATE_INT);


		if($action == "add_event" && !isset($confirm))
		{
			$calender->showEventForm();
		}
		elseif( $action == "edit_event" && isset($_GET['event_id']) && !isset($confirm))
		{
			$event = $calender->readEventFromDB($cleared_event_id);

			$calender->showEventForm($event);
		}
		elseif(($action == "add_event" || $action == "edit_event") && $confirm == "check")
		{
			$calender->showEventCheckForm($action);
		}
		elseif($action == "del_event" && $confirm == "check")
		{
			$calender->showEventCheckForm($action);
		}
		elseif(($action == "add_event" || $action == "edit_event") && $confirm == "true")
		{
			$calender->writeEventToDB();
		}
		elseif($action == "del_event" && $confirm == "true")
		{
			$calender->deleteEventFromDB($cleared_event_id);
		}

	}
	// Termin & Monat anzeigen
	else
	{
		$show = filter_input(INPUT_GET, 'show', FILTER_SANITIZE_SPECIAL_CHARS);

		if(isset($show) == "event")
		{

		}
		elseif(isset($show) == "day")
		{

		}
		else
		{

		?>
			<div class="calender clearfix">
				<div class="navigationRow row">
					<div class="navigationRowItem rowItem"><a href=""><i class="fa fa-angle-double-left"></i> Jahr</a></div>
					<div class="navigationRowItem rowItem"><a href=""><i class="fa fa-angle-left"></i> Monat</div>
					<div class="navigationRowItem rowItem"><a href="">Monat <i class="fa fa-angle-right"></i></div>
					<div class="navigationRowItem rowItem"><a href="">Jahr <i class="fa fa-angle-double-right"></i></div>
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

				if(isset($_GET['month']) && isset($_GET['year']))
				{
					$month	= filter_input(INPUT_GET, 'month', FILTER_VALIDATE_INT);
					$year	= filter_input(INPUT_GET, 'year', FILTER_VALIDATE_INT);

					$calender->showMonth($month, $year);
				}
				else
				{
					$calender->showMonth();
				}


				?>
			</div>
		<?php

		}
	}

	?>
</div>
	
</body>

</html>
