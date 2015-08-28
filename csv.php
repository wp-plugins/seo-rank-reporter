<?php 

require('functions.php');

if (seoRankReporterGetKeywurl() != "" && $_POST['dnload-csv'] == "Download CSV") {

	header("Content-type: application/octet-stream");

	header("Content-Disposition: attachment; filename=\"rank-reporter-data.csv\"");

	

	$keywurl_array = seoRankReporterGetKeywurl();

	//array_push(array(""), $keywurl_array);

	$theDates = seoRankReporterGetDates();

	

	foreach($keywurl_array as $keywurl) {

		

		$kw_url = trim($keywurl[url]);

		$kw_keyw = trim($keywurl[keyword]);

		

		$csv .= "," . $kw_keyw . " - " . $kw_url;

	}

	

	$csv .= "\n";

	

	foreach ($theDates as $aDate) {

				

		$csv .= $aDate[date];

		

		foreach($keywurl_array as $keywurl) {

			

			$kw_url = trim($keywurl[url]);

			$kw_keyw = trim($keywurl[keyword]);

			

			$results_array = seoRankReporterGetResults($kw_keyw, $kw_url);

	

			foreach($results_array as $data) {

				

				if ($data[date] == $aDate[date]) {

					if ($data[rank] == -1) {

						$data[rank] = "Not in top 100";

					}

					$csv .= "," . $data[rank];

					$blank = FALSE;

					break;

				} else {

					$blank = TRUE;

				}

			}

			if ($blank) {

				$csv .= ",";

			}

		

		}

		

		$csv .= "\n";			

		

	}	

	echo $csv;

	

}

?>