<?php
namespace SilverStripe\SecurityReport;

use SilverStripe\Control\Director;
use SilverStripe\Forms\FormField;
use SilverStripe\Forms\GridField\GridField;
use SilverStripe\Forms\GridField\GridFieldExportButton;
use SilverStripe\Forms\GridField\GridFieldPrintButton;
use SilverStripe\ORM\DataList;
use SilverStripe\Reports\Report;
use SilverStripe\Security\Member;
use SilverStripe\Security\Permission;
use SilverStripe\Security\Security;
use SilverStripe\SecurityReport\Forms\GridFieldExportReportButton;
use SilverStripe\SecurityReport\Forms\GridFieldPrintReportButton;

/**
 * User Security Report
 *
 * @author Michael Parkhill <mike@silverstripe.com>
 */
class UserSecurityReport extends Report
{

    /**
     * Columns in the report
     *
     * @var array
     * @config
     */
    private static $columns = array(
        'ID' => 'User ID',
        'FirstName' => 'First Name',
        'Surname' => 'Surname',
        'Email' => 'Email',
        'Created' => 'Date Created',
        'LastLoggedIn' => 'Last Logged In',
        'GroupsDescription' => 'Groups',
        'PermissionsDescription' => 'Permissions',
    );

    protected $dataClass = Member::class;

    /**
     * Returns the report title
     *
     * @return string
     */
    public function title()
    {
        return _t(__CLASS__ . '.REPORTTITLE', 'Users, Groups and Permissions');
    }

    /**
     * Builds a report description which is the current hostname with the current date and time
     *
     * @return string e.g. localhost/sitename - 21/12/2112
     */
    public function description()
    {
        return str_replace(
            array('http://', 'https://'),
            '',
            Director::protocolAndHost() . ' - ' . date('d/m/Y H:i:s')
        );
    }

    /**
     * Returns the column names of the report
     *
     * @return array
     */
    public function columns()
    {
        $columns = self::config()->columns;
        if (!Security::config()->get('login_recording')) {
            unset($columns['LastLoggedIn']);
        }
        return $columns;
    }

    /**
     * Alias of columns(), to support the export to csv action
     * in {@link GridFieldExportButton} generateExportFileData method.
     * @return array
     */
    public function getColumns()
    {
        return $this->columns();
    }

    /**
     * @return array
     */
    public function summaryFields()
    {
        return $this->columns();
    }

    /**
     * Defines the sortable columns on the report gridfield
     *
     * @return array
     */
    public function sortColumns()
    {
        return array_keys($this->columns() ?? []);
    }

    /**
     * Get the source records for the report gridfield
     *
     * @return DataList<Member>
     */
    public function sourceRecords()
    {
        // Get members sorted by ID
        return Member::get()->sort('ID');
    }

    /**
     * Restrict access to this report to users with security admin access
     *
     * @param Member $member
     * @return boolean
     */
    public function canView($member = null)
    {
        return (bool)Permission::checkMember($member, "CMS_ACCESS_SecurityAdmin");
    }

    /**
     * Return a field, such as a {@link GridField} that is
     * used to show and manipulate data relating to this report.
     *
     * @return GridField subclass
     */
    public function getReportField()
    {
        $gridField = parent::getReportField();
        $gridField->setModelClass(self::class);
        $gridConfig = $gridField->getConfig();
        $gridConfig->removeComponentsByType([GridFieldPrintButton::class, GridFieldExportButton::class]);
        $gridConfig->addComponents(
            new GridFieldPrintReportButton('buttons-before-left'),
            new GridFieldExportReportButton('buttons-before-left')
        );
        return $gridField;
    }
}
