<?php

require __DIR__."/_namespacedEntity.php";

use \Rapd\BaseEntity;

class Foo extends BaseEntity {}
class TestEntity1 extends BaseEntity {}
class TestNumberTwo extends BaseEntity {}

assert(Foo::getTable() == "foo");
assert(TestEntity1::getTable() == "test_entity_1");
assert(TestNumberTwo::getTable() == "test_number_two");
assert(Test\ThreeEntity::getTable() == "three_entity");

