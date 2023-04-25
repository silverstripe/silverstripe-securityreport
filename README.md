# Security Report

[![CI](https://github.com/silverstripe/silverstripe-securityreport/actions/workflows/ci.yml/badge.svg)](https://github.com/silverstripe/silverstripe-securityreport/actions/workflows/ci.yml)
[![Silverstripe supported module](https://img.shields.io/badge/silverstripe-supported-0071C4.svg)](https://www.silverstripe.org/software/addons/silverstripe-commercially-supported-module-list/)

This module adds a "Users, Groups and Permissions" report in the CMS, so that
an administrator can get a quick overview of who has access to the CMS.

## Installation

```sh
composer require silverstripe/securityreport
```

## Subsites Support

If the [Subsites](https://github.com/silverstripe/silverstripe-subsites) module is installed
then an additional column will be added, allowing you to see which subsites this user
can edit pages on.

To edit the permission to check for when filtering these subsites, you can update the
`Member.subsite_description_permission` config to any other permission. By default this
is set to `SITETREE_EDIT_ALL`.
