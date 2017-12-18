# Security Report

[![Build Status](https://travis-ci.org/silverstripe/silverstripe-securityreport.svg)](https://travis-ci.org/silverstripe/silverstripe-securityreport)
[![Scrutinizer Code Quality](https://img.shields.io/scrutinizer/g/silverstripe/silverstripe-securityreport.svg)](https://scrutinizer-ci.com/g/silverstripe/silverstripe-securityreport/?branch=master)
[![codecov](https://img.shields.io/codecov/c/github/silverstripe/silverstripe-securityreport.svg)](https://codecov.io/gh/silverstripe/silverstripe-securityreport)

This module adds a "Users, Groups and Permissions" report in the CMS, so that
an administrator can get a quick overview of who has access to the CMS.

## Requirements

* SilverStripe 4.0+

**Note:** For SilverStripe 3.x, please use the [1.x release line](https://github.com/silverstripe/silverstripe-securityreport/tree/1.0).


## Install

To install run `composer require silverstripe/securityreport`.

## Subsites Support

If the [Subsites](https://github.com/silverstripe/silverstripe-subsites) module is installed
then an additional column will be added, allowing you to see which subsites this user
can edit pages on.

To edit the permission to check for when filtering these subsites, you can update the
`Member.subsite_description_permission` config to any other permission. By default this
is set to `SITETREE_EDIT_ALL`.
