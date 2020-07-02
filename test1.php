<?php
require_once("zapcallib.php");
//213
$examples = array(
	array(
		"name" => "🇳🇱 Verjaardag prinses Beatrix (1938)",
		"description" => "Wimpel: ✅\nHalfstok: ❌",
		"day" => "31",
		"month" => "01",
		"dayreplacement" => "01",
		"monthreplacement" => "02",
	),
	array(
		"name" => "🇳🇱 Koningsdag, verjaardag Koning Willem Alexander",
		"description" => "Wimpel: ✅\nHalfstok: ❌",
		"day" => "27",
		"month" => "04",
		"dayreplacement" => "28",
		"monthreplacement" => "04",
	),
	array(
		"name" => "🇳🇱 Dodenherdenking",
		"description" => "Wimpel: ❌\nHalfstok: ✅\nVlaggen vanaf 18:00 tot zonsondergang",
		"day" => "04",
		"month" => "05",
		"dayreplacement" => "04",
		"monthreplacement" => "05",
	),
	array(
		"name" => "🇳🇱 Bevrijdingsdag",
		"description" => "Wimpel: ✅\nHalfstok: ❌",
		"day" => "05",
		"month" => "05",
		"dayreplacement" => "05",
		"monthreplacement" => "05",
	),
	array(
		"name" => "🇳🇱 Verjaardag koningin Máxima",
		"description" => "Wimpel: ✅\nHalfstok: ❌",
		"day" => "17",
		"month" => "05",
		"dayreplacement" => "18",
		"monthreplacement" => "05",
	),
	array(
		"name" => "🇳🇱 Formeel einde Tweede Wereldoorlog",
		"description" => "Wimpel: ❌\nHalfstok: ❌",
		"day" => "15",
		"month" => "08",
		"dayreplacement" => "16",
		"monthreplacement" => "08",
	),
	array(
		"name" => "🇳🇱 Verjaardag prinses Catharina-Amalia",
		"description" => "Wimpel: ✅\nHalfstok: ❌\n",
		"day" => "07",
		"month" => "12",
		"dayreplacement" => "08",
		"monthreplacement" => "12",
	)
);

/*array(
		"name" => "🇳🇱 Prinsjesdag",
		"description" => "Wimpel: ❌\nHalfstok: ❌\nAlleen in Den Haag vlaggen)",
		"day" => "",
		"month" => "",
		"dayreplacement" => "",
		"monthreplacement" => "",
	),*/

$icalobj = new ZCiCal();
$counter = 0;
$startYear = date("Y");
$amountYearToCreate = 2;

for ($i=0; $i<$amountYearToCreate; $i++) {
	$startYear = $startYear + $i;
	foreach($examples as $example) {

		// Is date on a sunday, and replacementdate is filled. Use that.
		//echo "i: ".$i;
		//echo '<br />';
		//echo ($startYear."-".$example["month"]."-".$example["day"]);
		//echo '<br />';

		$tmpDate = ZCiCal::fromSqlDateTime($startYear."-".$example["month"]."-".$example["day"]);
		//echo '<br />';
		

		$eventobj = new ZCiCalNode("VEVENT", $icalobj->curnode);
		$eventobj->addNode(new ZCiCalDataNode("SUMMARY:" . $example["name"]));
		if (date('N',$tmpDate) == 7) {
			if ($example["dayreplacement"] != '') {
			$eventobj->addNode(new ZCiCalDataNode("DTSTART:" . ZCiCal::fromSqlDateTime($startYear."-".$example["monthreplacement"]."-".$example["dayreplacement"])));
			$eventobj->addNode(new ZCiCalDataNode("DTEND:" . ZCiCal::fromSqlDateTime($startYear."-".$example["monthreplacement"]."-".$example["dayreplacement"])));
			}
		} else {
			$eventobj->addNode(new ZCiCalDataNode("DTSTART:" . ZCiCal::fromSqlDateTime($startYear."-".$example["month"]."-".$example["day"])));
			$eventobj->addNode(new ZCiCalDataNode("DTEND:" . ZCiCal::fromSqlDateTime($startYear."-".$example["month"]."-".$example["day"])));	
		}
		
		$uid = date('Y-m-d-H-i-s') . "-" . $counter . "@WALGEMOED.COM";
		$eventobj->addNode(new ZCiCalDataNode("UID:" . $uid));
		$eventobj->addNode(new ZCiCalDataNode("DTSTAMP:" . ZCiCal::fromSqlDateTime()));
		$eventobj->addNode(new ZCiCalDataNode("Description:" . ZCiCal::formatContent($example["description"])));
		$counter = $counter + 1;
	}
}
echo $icalobj->export();
