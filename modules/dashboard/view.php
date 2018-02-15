<script type="text/javascript" src="//code.highcharts.com/highcharts.js"></script>

<div class="c-2">
	<div class="c-2-1">
		<div class="chart visitors-line-chart"></div>
	</div>
	
	<div class="c-2-2">
		<div class="c-small red">
			<?php
			$last_month = date("m");
			$last_year = date("Y") - 1;
				
			$profit_current = $mb->_runFunction("reports", "viewArticleGroups", array($_SESSION['merchantID'], date("m"), date("Y")));
			$profit_last = $mb->_runFunction("reports", "viewArticleGroups", array($_SESSION['merchantID'], $last_month, $last_year));
			
			$cnt = 0;
			
			foreach($profit_current AS $key => $value)
			{
				$cnt += $value['grand_total'];
			}
			
			$profit_current = $cnt;
			
			
			$cnt = 0;
			
			foreach($profit_last AS $key => $value)
			{
				$cnt += $value['grand_total'];
			}
			
			$profit_last = $cnt;
			
			
			$percentage = ceil((($profit_current/$profit_last)*100));
			?>
			
			<div class="title">Maandelijkse omzet</div>
			
			<div class="value">
				&euro;&nbsp;<span class="animate-number"><?= $profit_current ?></span>
			</div>
			
			<div class="extra">
				<?= $percentage ?>% van dezelfde maand in <?= $last_year ?>
			</div>
			
			<div class="progress-container">
				<div class="progress" percentage="<?= $percentage ?>"></div>
			</div>
		</div>
		
		<div class="c-small dark-gray">
			<?php
			$last_year = date("Y") - 1;
				
			$profit_current = $mb->_runFunction("reports", "viewArticleGroups", array($_SESSION['merchantID'], "", date("Y")));
			$profit_last = $mb->_runFunction("reports", "viewArticleGroups", array($_SESSION['merchantID'], "", $last_year));
			
			$cnt = 0;
			
			foreach($profit_current AS $key => $value)
			{
				$cnt += $value['grand_total'];
			}
			
			$profit_current = $cnt;
			
			
			$cnt = 0;
			
			foreach($profit_last AS $key => $value)
			{
				$cnt += $value['grand_total'];
			}
			
			$profit_last = $cnt;
			
			$percentage = ceil((($profit_current/$profit_last)*100));
			?>
			
			<div class="title">Jaarlijkse omzet</div>
			
			<div class="value">
				&euro;&nbsp;<span class="animate-number"><?= $profit_current ?></span>
			</div>
			
			<div class="extra">
				<?= $percentage ?>% van de totale omzet in <?= $last_year ?>
			</div>
			
			<div class="progress-container">
				<div class="progress" percentage="<?= $percentage ?>"></div>
			</div>
		</div>
		
		<div class="c-small white">
			<?php
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
				
			$profit_current = $mb->_runFunction("dashboard", "profit", array(date("Y"), date("m"), date("d")));
			$profit_last = $mb->_runFunction("dashboard", "profit", array($last_year, $last_month, $last_day));
			
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
			$last_month = date("m") - 1;
			$last_year = date("Y");
				
			if($last_month == 0)
			{
				$last_month = 12;
				$last_year -= 1;
			}
				
			$visitors_current = $mb->_runFunction("dashboard", "visitors", array(date("Y"), date("m"), "", "DISTINCT"));
			$visitors_last = $mb->_runFunction("dashboard", "visitors", array($last_year, $last_month, "", "DISTINCT"));
			
			$percentage = ceil((($visitors_current/$visitors_last)*100));
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
		$visitors_today = $mb->_runFunction("dashboard", "visitors", array(date("Y"), date("m"), date("d"), "DISTINCT"));
		?>
		
		<div class="title">bezoekers vandaag</div>
		
		<div class="value">
			<span class="animate-number"><?= $visitors_today ?></span>
		</div>
	</div>
	
	<div class="c-small center full dark-gray">
		<?php
		$pagehits_today = $mb->_runFunction("dashboard", "visitors", array(date("Y"), date("m"), date("d"), ""));
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
		$new_visitors = $mb->_runFunction("dashboard", "newVisitors", array(date("Y"), date("m"), date("d")));
		?>
		
		<div class="title">nieuwe bezoekers</div>
		
		<div class="value">
			<span class="animate-number"><?= $new_visitors ?></span>
		</div>
	</div>
</div>

<div class="c-1 flexible">
	<div class="chart sales-line-chart"></div>
</div>

<input type="hidden" name="totalVisitorsMonthlyKeys" id="totalVisitorsMonthlyKeys" value="<?= $mb->_runFunction("dashboard", "totalVisitorsMonthlyKeys") ?>" />
<input type="hidden" name="totalVisitorsMonthlyValues" id="totalVisitorsMonthlyValues" value='<?= $mb->_runFunction("dashboard", "totalVisitorsMonthlyValues", array("DISTINCT")) ?>' />
<input type="hidden" name="totalVisitorHitsMonthlyValues" id="totalVisitorHitsMonthlyValues" value='<?= $mb->_runFunction("dashboard", "totalVisitorsMonthlyValues", array("")) ?>' />

<input type="hidden" name="salesMonthlyKeys" id="salesMonthlyKeys" value="<?= $mb->_runFunction("dashboard", "salesMonthlyKeys") ?>" />
<input type="hidden" name="salesTwoYears" id="salesTwoYears" value='<?= $mb->_runFunction("dashboard", "salesCalc", array($_SESSION['merchantID'], date("Y")-2)) ?>' />
<input type="hidden" name="salesOneYear" id="salesOneYear" value='<?= $mb->_runFunction("dashboard", "salesCalc", array($_SESSION['merchantID'], date("Y")-1)) ?>' />
<input type="hidden" name="salesThisYear" id="salesThisYear" value='<?= $mb->_runFunction("dashboard", "salesCalc", array($_SESSION['merchantID'], date("Y"))) ?>' />