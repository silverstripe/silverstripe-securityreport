# Security Report

This module adds a "Users, Groups and Permissions" report in the CMS, so that
an administrator can get a quick overview of who has access to the CMS.

## Install

To install run `composer require silverstripe/securityreport 1.0.*-dev`, or download
and unzip into a folder named 'securityreport' in your SilverStripe project root.

## Subsites Support

If the [Subsites](https://github.com/silverstripe/silverstripe-subsites) module is installed
then an additional column will be added, allowing you to see which subsites this user 
can edit pages on.

To edit the permission to check for when filtering these subsites, you can update the
`Member.subsite_description_permission` config to any other permission. By default this
is set to `SITETREE_EDIT_ALL`.
