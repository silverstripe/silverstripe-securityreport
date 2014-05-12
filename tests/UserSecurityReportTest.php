<?php

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
		'Member' => array('MemberReportExtension')
	);
	
	/**
	 * Utility method for all tests to use.
	 * 
	 * @return \ArrayList
	 * @todo pre-fill the report with fixture-defined users
	 */
	public function setUp() {
		parent::setUp();
		$reports = SS_Report::get_reports();
		$report = $reports['UserSecurityReport'];
		$this->report = $report;
		$this->records = $report->sourceRecords()->toArray();
	}

	public function testSourceRecords() {
		$this->assertNotEmpty($this->records);
	}

	public function testGetLastVisitedStatus() {
		$member = $this->objFromFixture('Member', 'member-last-visited-is-string');
		$lastVisited = $member->LastVisitedDescription;
		$this->assertNotNull($lastVisited);
		$this->assertEquals('2013-02-26 11:22:10', $lastVisited);
		
		$member = $this->objFromFixture('Member', 'member-last-visited-is-empty');
		$lastVisited = $member->LastVisitedDescription;
		$this->assertEquals('never', $lastVisited);		
	}

	public function testGetMemberGroups() {
		//getMemberGroups(&$member) returns string
		$member = $this->objFromFixture('Member', 'member-has-0-groups');
		$groups = $member->GroupsDescription;
		$this->assertEquals('Not in a Security Group', $groups);		
		
		$member = $this->objFromFixture('Member', 'member-has-1-groups');
		$groups = $member->GroupsDescription;
		$this->assertEquals('Group Test 01 (global group)', $groups);	
	}

	public function testGetMemberPermissions() {
		$member = $this->objFromFixture('Member', 'member-has-0-permissions');
		$perms = $member->PermissionsDescription;
		$this->assertEquals('No Permissions', $perms);		
		
		$member = $this->objFromFixture('Member', 'member-has-1-permissions');
		$perms = $member->PermissionsDescription;
		$this->assertEquals('Full administrative rights', $perms);
		
		$member = $this->objFromFixture('Member', 'member-has-n-permissions');
		$perms = $member->PermissionsDescription;
		$this->assertEquals('Full administrative rights, Edit any page', $perms);		
	}
}
