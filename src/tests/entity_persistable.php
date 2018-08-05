<?php

require __DIR__."/_namespacedEntity.php";

use \Rapd\PersistableEntity;

class Foo extends PersistableEntity {}
class TestEntity1 extends PersistableEntity {}
class TestNumberTwo extends PersistableEntity {}

assert(Foo::getTable() == "foo");
assert(TestEntity1::getTable() == "test_entity_1");
assert(TestNumberTwo::getTable() == "test_number_two");
assert(Test\ThreeEntity::getTable() == "three_entity");
