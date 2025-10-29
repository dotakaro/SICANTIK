<?php
$xml= '<persons>
<person>
<name>Jaspreet Chahal</name>
<age>33</age>
<city>Melbourne</city>
</person>
<person>
<name>Yuvraaj Chahal</name>
<age>2</age>
<city>Adelaide</city>
</person>
</persons>';


$xmlObj = simplexml_load_string($xml);
$json = json_encode($xmlObj);
$arr= json_decode($json);


print_r($arr);
?>
