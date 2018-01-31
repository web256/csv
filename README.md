# csv 

### use composer.json

~~~
{
"minimum-stability": "dev",
"require": {},
"require-dev":{
"web256/csv":"dev-master"
}
}
~~~

### demo
~~~
<?php
require './vendor/autoload.php';
use csv\phpcsv;
$csv = new phpcsv();
var_dump($csv);
$csv->downloadCsv(date('YmdH'), [
    '工号',
    '员工姓名',
    '子公司', 
    '部门', 
    '部门编号', 

], $list,
    [
    'empl_id', 
    'name', 
    'company_name', 
    'depart_name', 
    'depart_id', 
]);
