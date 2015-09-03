<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace YvesUnit\SprykerEngine\Yves\Kernel\Communication;

use SprykerEngine\Yves\Kernel\Communication\BundleControllerAction;
use SprykerEngine\Yves\Kernel\Communication\ControllerLocator;
use SprykerEngine\Yves\Kernel\Locator;

/**
 * @group SprykerEngine
 * @group Yves
 * @group Kernel
 * @group Communication
 * @group Locator
 * @group ControllerLocator
 */
class ControllerLocatorTest extends \PHPUnit_Framework_TestCase
{

    public function testCanLocateShouldReturnFalseWhenControllerCanNotBeLocated()
    {
        $bundleControllerAction = new BundleControllerAction('Kernel', 'Foo', 'index');
        $locator = new ControllerLocator($bundleControllerAction);

        $this->assertFalse($locator->canLocate());
    }

    public function testLocateShouldThrowExceptionWhenControllerCanNotBeLocated()
    {
        $this->setExpectedException('\Exception');

        $bundleControllerAction = new BundleControllerAction('Kernel', 'Foo', 'index');
        $locator = new ControllerLocator($bundleControllerAction);

        $locator->locate(new \Pimple(), Locator::getInstance());
    }

}
