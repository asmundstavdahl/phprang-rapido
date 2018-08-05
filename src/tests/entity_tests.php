<?php

require __DIR__."/testNamespace.php";

use \Rapd\BaseEntity;

class TestEntity1 extends BaseEntity {}
class TestNumberTwo extends BaseEntity {}

assert(TestEntity1::getTable() == "test");
assert(TestNumberTwo::getTable() == "test_number_two");
assert(Test\ThreeEntity::getTable() == "three");

