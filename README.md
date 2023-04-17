# NSW Governance Arrangements

This module provides some base data files and a service class to load data from a YAML representation of the NSWDPC Governance Arrangements Chart from those files into data stuctures and return them.

The data can be used to represent form controls and lists of governance data.

### Usage

Load the v20230405 dataset

```php
// @var GovernanceArrangementsService
$service = GovernanceArrangementsService::create(20230405);
// @var array
$data = $service->getData();
```

Get the metadata from the same service:

```php
// @var array
$data = $service->getMetaData();
```


## Installation

The only supported way of installing this module is via composer:

```sh
composer require nswdpc/governance-arrangements
```

## License

[BSD-3-Clause](./LICENSE.md)

## Documentation

* [Documentation](./docs/en/001_index.md)

## Configuration

+ 

## Maintainers

+ [dpcdigital@NSWDPC:~$](https://dpc.nsw.gov.au)

## Bugtracker

We welcome bug reports, pull requests and feature requests on the Github Issue tracker for this project.

Please review the [code of conduct](./code-of-conduct.md) prior to opening a new issue.

## Security

If you have found a security issue with this module, please email digital[@]dpc.nsw.gov.au in the first instance, detailing your findings.

## Development and contribution

If you would like to make contributions to the module please ensure you raise a pull request and discuss with the module maintainers.

Please review the [code of conduct](./code-of-conduct.md) prior to completing a pull request.
