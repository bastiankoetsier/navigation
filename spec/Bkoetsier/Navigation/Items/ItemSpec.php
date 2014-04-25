<?php

namespace spec\Bkoetsier\Navigation\Items;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class ItemSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('Bkoetsier\Navigation\Items\Item');
    }
}
