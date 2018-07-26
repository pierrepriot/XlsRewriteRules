<?php
include('vendor/autoload.php');

include('src/XlsRewriteRules.php');


/*------------------------------------------------*/

$inputFileName = './somerules.xlsx';
$colOld='A'; // spreadsheet col featuring the old URLs
$colNew='B'; // new URLs to redirect to
/*------------------------------------------------*/

$oRW = new XlsRewriteRules($inputFileName, $colOld, $colNew);
$oRW->generate();
