<?php

class Proto1 {
	use \Rapd\Prototype;
}

ob_start();
Proto1::fooBar();
$output = ob_get_clean();
assert($output == "TODO: implement Proto1::fooBar() <br>\n");

ob_start();
(new Proto1())->kamehameha("str", 1, (new Proto1()));
$output = ob_get_clean();
assert($output == "TODO: implement Proto1->kamehameha(string:str, integer:1, object:Proto1 Obj) <br>\n");


class Proto2 {
	use \Rapd\Controller\Prototype;
}

assert(Proto2::fooBar() == "TODO: implement Proto2::fooBar()");

assert(Proto2::fooBar("str", 1, (new Proto1())) == "TODO: implement Proto2::fooBar(string:str, integer:1, object:Proto1 Obj)");
