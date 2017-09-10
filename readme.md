# Security Report

[![Build Status](https://travis-ci.org/silverstripe/silverstripe-securityreport.svg)](https://travis-ci.org/silverstripe/silverstripe-securityreport)
[![codecov](https://codecov.io/gh/silverstripe/silverstripe-securityreport/branch/master/graph/badge.svg)](https://codecov.io/gh/silverstripe/silverstripe-securityreport)

This module adds a "Users, Groups and Permissions" report in the CMS, so that
an administrator can get a quick overview of who has access to the CMS.

## Install

To install run `composer require silverstripe/securityreport ^2@dev`.

## Subsites Support

If the [Subsites](https://github.com/silverstripe/silverstripe-subsites) module is installed
then an additional column will be added, allowing you to see which subsites this user
can edit pages on.

To edit the permission to check for when filtering these subsites, you can update the
`Member.subsite_description_permission` config to any other permission. By default this
is set to `SITETREE_EDIT_ALL`.
