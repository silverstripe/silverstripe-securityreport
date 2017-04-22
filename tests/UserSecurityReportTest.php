<?php
use SilverStripe\Dev\Debug;
use SilverStripe\Dev\SapphireTest;
use SilverStripe\Reports\Report;
use SilverStripe\Security\Member;
use SilverStripe\SecurityReport\MemberReportExtension;
use SilverStripe\SecurityReport\UserSecurityReport;

/**
 * User Security Report Tests.
 *
 * @package securityreport
 * @subpackage tests
 * @author Michael Parkhill <mike@silverstripe.com>
 */
class UserSecurityReportTest extends SapphireTest {

	protected static $fixture_file = 'UserSecurityReportTest.yml';
	
	protected $records;
	protected $report;
	
	protected $requiredExtensions = array(
		Member::class => [MemberReportExtension::class]
	);
	
	/**
	 * Utility method for all tests to use.
	 * 
	 * @return \ArrayList
	 * @todo pre-fill the report with fixture-defined users
	 */
	public function setUp() {
		parent::setUp();
		$reports = Report::get_reports();
		$report = $reports[UserSecurityReport::class];
		$this->report = $report;
		$this->records = $report->sourceRecords()->toArray();
	}

	public function testSourceRecords() {
		$this->assertNotEmpty($this->records);
	}

	public function testGetMemberGroups() {
		//getMemberGroups(&$member) returns string
		$member = $this->objFromFixture(Member::class, 'member-has-0-groups');
		$groups = $member->GroupsDescription;
		$this->assertEquals('Not in a Security Group', $groups);		
		
		$member = $this->objFromFixture(Member::class, 'member-has-1-groups');
		$groups = $member->GroupsDescription;
		$this->assertEquals('Group Test 01', $groups);
	}

	public function testGetMemberPermissions() {
		$member = $this->objFromFixture(Member::class, 'member-has-0-permissions');
		$perms = $member->PermissionsDescription;
		$this->assertEquals('No Permissions', $perms);		
		
		$member = $this->objFromFixture(Member::class, 'member-has-1-permissions');
		$perms = $member->PermissionsDescription;
		$this->assertEquals('Full administrative rights', $perms);
		
		$member = $this->objFromFixture(Member::class, 'member-has-n-permissions');
		$perms = $member->PermissionsDescription;
		$this->assertEquals('Full administrative rights, Edit any page', $perms);		
	}
}
