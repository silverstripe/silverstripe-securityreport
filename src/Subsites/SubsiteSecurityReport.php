<?php

namespace SilverStripe\SecurityReport\Subsites;

use SilverStripe\Core\Extension;

/**
 * User Security Report extension for Subsites
 *
 * @author Damian Mooyman <damian@silverstripe.com>
 */
class SubsiteSecurityReport extends Extension
{

    /**
     * Columns in the report
     *
     * @var array
     * @config
     */
    private static $columns = array(
        'SubsiteDescription' => 'Subsites (edit permissions)',
    );
}
