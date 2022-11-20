<?php

namespace SilverStripe\SecurityReport\Subsites;

use SilverStripe\Dev\Deprecation;
use SilverStripe\Core\Extension;

/**
 * User Security Report extension for Subsites
 *
 * @deprecated 2.1.0 Will be removed without equivalent functionality to replace it
 * @author Damian Mooyman <damian@silverstripe.com>
 */
class SubsiteSecurityReport extends Extension
{
    public function __construct()
    {
        Deprecation::notice(
            '2.1.0',
            'Will be removed without equivalent functionality to replace it',
            Deprecation::SCOPE_CLASS
        );
    }
}
