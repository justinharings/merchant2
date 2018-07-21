<script type="text/javascript" src="//code.highcharts.com/highcharts.js"></script>

<div class="c-2">
	<div class="c-2-1">
		<div class="chart visitors-line-chart"></div>
	</div>
	
	<div class="c-2-2">
		<div class="c-small red">
			<?php
			$query = sprintf(
				"	SELECT		dashboard.monthly_profit
					FROM		dashboard
					WHERE		dashboard.merchantID = %d",
				$_SESSION['merchantID']
			);
			$result = $mb->query($query);
			$row = $mb->fetch_assoc($result);
			
			$monthly_profit = unserialize($row['monthly_profit']);
			
			$profit_current = $monthly_profit['profit_current'];
			$profit_last = $monthly_profit['profit_last'];
			$percentage = intval($monthly_profit['percentage']);
			?>
			
			<div class="title">Maandelijkse omzet</div>
			
			<div class="value">
				&euro;&nbsp;<span class="animate-number"><?= $profit_current ?></span>
			</div>
			
			<div class="extra">
				<?= $percentage ?>% van dezelfde maand in <?= date("Y")-1 ?>
			</div>
			
			<div class="progress-container">
				<div class="progress" percentage="<?= $percentage ?>"></div>
			</div>
		</div>
		
		<div class="c-small dark-gray">
			<?php
			$query = sprintf(
				"	SELECT		dashboard.yearly_profit
					FROM		dashboard
					WHERE		dashboard.merchantID = %d",
				$_SESSION['merchantID']
			);
			$result = $mb->query($query);
			$row = $mb->fetch_assoc($result);
			
			$yearly_profit = unserialize($row['yearly_profit']);
			
			$profit_current = $yearly_profit['profit_current'];
			$profit_last = $yearly_profit['profit_last'];
			$percentage = intval($yearly_profit['percentage']);
			?>
			
			<div class="title">Jaarlijkse omzet</div>
			
			<div class="value">
				&euro;&nbsp;<span class="animate-number"><?= $profit_current ?></span>
			</div>
			
			<div class="extra">
				<?= $percentage ?>% van de totale omzet in <?= date("Y")-1 ?>
			</div>
			
			<div class="progress-container">
				<div class="progress" percentage="<?= $percentage ?>"></div>
			</div>
		</div>
		
		<div class="c-small white">
			<?php
			// Live berekent
			
			$last_year = date("Y");
			$last_month = date("m");
			$last_day = date("d") - 1;
			
			if($last_day == 0)
			{
				$last_month -= 1;
				
				if($last_month == 0)
				{
					$last_month = 12;
					$last_year -= 1;
				}
				
				$date = new DateTime($last_year . '-' . $last_month . '-01'); 
				$last_day = $date->format('t');
			}
				
			$profit_current = $mb->_runFunction("dashboard", "profit", array($_SESSION['merchantID'], date("Y"), date("m"), date("d")));
			$profit_last = $mb->_runFunction("dashboard", "profit", array($_SESSION['merchantID'], $last_year, $last_month, $last_day));
			
			$percentage = ceil((($profit_current/$profit_last)*100));
			?>
			
			<div class="title">Omzet vandaag</div>
			
			<div class="value">
				&euro;&nbsp;<span class="animate-number"><?= $profit_current ?></span>
			</div>
			
			<div class="extra">
				<?= $percentage ?>% van de omzet gisteren
			</div>
			
			<div class="progress-container">
				<div class="progress" percentage="<?= $percentage ?>"></div>
			</div>
		</div>

		<div class="c-small light-gray">
			<?php
			$query = sprintf(
				"	SELECT		dashboard.monthly_visitors
					FROM		dashboard
					WHERE		dashboard.merchantID = %d",
				$_SESSION['merchantID']
			);
			$result = $mb->query($query);
			$row = $mb->fetch_assoc($result);
			
			$monthly_visitors = unserialize($row['monthly_visitors']);
			
			$visitors_current = $monthly_visitors['visitors_current'];
			$percentage = intval($monthly_visitors['percentage']);
			?>
			
			<div class="title">Bezoekers deze maand</div>
			
			<div class="value">
				<span class="animate-number"><?= $visitors_current ?></span>
			</div>
			
			<div class="extra">
				<?= $percentage ?>% van de vorige maand
			</div>
			
			<div class="progress-container">
				<div class="progress" percentage="<?= $percentage ?>"></div>
			</div>
		</div>
	</div>
</div>

<div class="c-1">
	<div class="c-small center full red">
		<?php
		$visitors_today = $mb->_runFunction("dashboard", "visitors", array($_SESSION['merchantID'], date("Y"), date("m"), date("d"), "DISTINCT"));
		?>
		
		<div class="title">bezoekers vandaag</div>
		
		<div class="value">
			<span class="animate-number"><?= $visitors_today ?></span>
		</div>
	</div>
	
	<div class="c-small center full dark-gray">
		<?php
		$pagehits_today = $mb->_runFunction("dashboard", "visitors", array($_SESSION['merchantID'], date("Y"), date("m"), date("d"), ""));
		?>
		
		<div class="title">pagina hits vandaag</div>
		
		<div class="value">
			<span class="animate-number"><?= $pagehits_today ?></span>
		</div>
	</div>
	
	<div class="c-small center full white">
		<div class="title">pagina's / bezoeker</div>
		
		<div class="value">
			<span class="animate-number"><?= ($pagehits_today/$visitors_today) ?></span>
		</div>
	</div>
	
	<div class="c-small center full light-gray">
		<?php
		$query = sprintf(
			"	SELECT		dashboard.visitor_countries
				FROM		dashboard
				WHERE		dashboard.merchantID = %d",
			$_SESSION['merchantID']
		);
		$result = $mb->query($query);
		$row = $mb->fetch_assoc($result);
			
		$visitor_countries = $row['visitor_countries'];
		?>
		
		<div class="title">aantal landen</div>
		
		<div class="value">
			<span class="animate-number"><?= $visitor_countries ?></span>
		</div>
	</div>
</div>

<div class="c-1 flexible">
	<div class="chart sales-line-chart"></div>
</div>

<?php
$query = sprintf(
	"	SELECT		dashboard.monthly_visitor_graph
		FROM		dashboard
		WHERE		dashboard.merchantID = %d",
	$_SESSION['merchantID']
);
$result = $mb->query($query);
$row = $mb->fetch_assoc($result);

$monthly_visitor_graph = unserialize($row['monthly_visitor_graph']);

$totalVisitorsMonthlyKeys = $monthly_visitor_graph['totalVisitorsMonthlyKeys'];
$totalVisitorsMonthlyValues = $monthly_visitor_graph['totalVisitorsMonthlyValues'];
$totalVisitorHitsMonthlyValues = $monthly_visitor_graph['totalVisitorHitsMonthlyValues'];
?>

<input type="hidden" name="totalVisitorsMonthlyKeys" id="totalVisitorsMonthlyKeys" value="<?= $totalVisitorsMonthlyKeys ?>" />
<input type="hidden" name="totalVisitorsMonthlyValues" id="totalVisitorsMonthlyValues" value='<?= $totalVisitorsMonthlyValues ?>' />
<input type="hidden" name="totalVisitorHitsMonthlyValues" id="totalVisitorHitsMonthlyValues" value='<?= $totalVisitorHitsMonthlyValues ?>' />

<?php
$query = sprintf(
	"	SELECT		dashboard.sales_graph
		FROM		dashboard
		WHERE		dashboard.merchantID = %d",
	$_SESSION['merchantID']
);
$result = $mb->query($query);
$row = $mb->fetch_assoc($result);

$sales_graph = unserialize($row['sales_graph']);

$salesMonthlyKeys = $sales_graph['salesMonthlyKeys'];
$salesTwoYears = $sales_graph['salesTwoYears'];
$salesOneYear = $sales_graph['salesOneYear'];
$salesThisYear = $sales_graph['salesThisYear'];
?>

<input type="hidden" name="salesMonthlyKeys" id="salesMonthlyKeys" value="<?= $salesMonthlyKeys ?>" />
<input type="hidden" name="salesTwoYears" id="salesTwoYears" value='<?= $salesTwoYears ?>' />
<input type="hidden" name="salesOneYear" id="salesOneYear" value='<?= $salesOneYear ?>' />
<input type="hidden" name="salesThisYear" id="salesThisYear" value='<?= $salesThisYear ?>' />

<center style="text-transform: uppercase; color: #AAA;">
	<?php
	$query = sprintf(
		"	SELECT		dashboard.date_update
			FROM		dashboard
			WHERE		dashboard.merchantID = %d",
		$_SESSION['merchantID']
	);
	$result = $mb->query($query);
	$row = $mb->fetch_assoc($result);
	
	$date = _dutchDate($row['date_update'], "date-text");
	$time = _dutchDate($row['date_update'], "time-short");
	?>

	Laatste update op <?= $date ?> om <?= $time ?> uur
</center>