<?php

namespace SilverStripe\SecurityReport\Tests;

use SilverStripe\Dev\SapphireTest;
use SilverStripe\ORM\FieldType\DBDatetime;
use SilverStripe\Security\Member;
use SilverStripe\SecurityReport\MemberReportExtension;

class MemberReportExtensionTest extends SapphireTest
{
    protected static $fixture_file = 'MemberReportExtensionTest.yml';

    protected static $required_extensions = [
        Member::class => [
            MemberReportExtension::class,
        ],
    ];

    protected function setUp(): void
    {
        DBDatetime::set_mock_now('2018-05-03 00:00:00');

        parent::setUp();
    }

    public function testGetLastLoggedIn()
    {
        /** @var Member $member */
        $member = $this->objFromFixture(Member::class, 'has_logged_in');
        $result = $member->getLastLoggedIn();
        $this->assertStringContainsString('2018-05-03', $result, 'Last logged in date is shown');
    }

    public function testGetLastLoggedInReturnsNever()
    {
        $member = new Member();
        $member->write();
        $this->assertSame('Never', $member->getLastLoggedIn());
    }
}
