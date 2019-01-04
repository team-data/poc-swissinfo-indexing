# SwissInfo indexing - PoC

This proof of concept has been developed to crawl and index the primary pages available in the
SwissInfo website gathered via their https://www.swissinfo.ch/webservice/swi-eng-2.0 APIs.

## Installation

This PoC uses [Drifter](https://github.com/liip/drifter) for provisioning a VM.

Assuming that Vagrant is installed on your machine:

```bash
git submodule update --init
vagrant plugin install vagrant-hostmanager
vagrant up
```

The API endpoint will be available as [swissinfo-indexing.test](http://swissinfo-indexing.test)

## Running Solr
A Solr instance should be configured and running as soon as the VM is provisioned.
It is reachable at [swissinfo-indexing.test:8983](http://swissinfo-indexing.test:8983)

## Indexing Data
The crawling system has been build to crawl from a specific page, and queue all the related pages.

To start the crawling:
1. `bin/console page:detail:queue-index 42579872/44625394 -v --recursive` to start the crawling from `detail/42579872/44625394` page
2. `bin/console messenger:consume-messages -v` to start the recursive crawling and indexing
3. the command `bin/console messenger:consume-messages -v` can be run multiple times from multiple terminals to process
    the crawling and indexing in parallel.

## API Endpoint

Examples:
- Search for [EU tax](http://swissinfo-indexing.test/search?q=EU+tax)

- Get all indexed docs <http://swissinfo-indexing.test/search?q=>

### Example Result
This example shows the first three results when requesting data for [tax](http://swissinfo-indexing.test/search?q=tax)

```json5
{
  "numFound": 275,
  "items": [
    {
      "id": "42579872/3762400",
      "canonicalUrl": "https://www.swissinfo.ch/eng/switzerland-rejects-eu-tax-demand/3762400",
      "score": 9.264545,
      "language": "en",
      "title": "Switzerland rejects EU tax demand",
      "date": "2004-02-10T19:08:38Z"
    },
    {
      "id": "42579872/2609120",
      "canonicalUrl": "https://www.swissinfo.ch/eng/tax-row-overshadows-rome-talks/2609120",
      "score": 9.264545,
      "language": "en",
      "title": "Tax row overshadows Rome talks",
      "date": "2002-03-20T16:16:00Z"
    },
    // ...
}
```

## Development
This project uses `phive` for libraries management (beta), it is installed in the VM by Drifter.

Ensure to run `phive install` from the root project dir, inside the VM to download all the libraries.

The code is analyzed with both `php-cs-fixer` and `phpstan`: run the script `./scripts/php-prereq.sh` to check the
current code.

To manually check the PHP CS, run: `./tools/php-cs-fixer fix --dry-run`.

# Learnings and outcome of this PoC
Though we as developers for the portal see a lot of value in being able to search also in the
primary data, the client's main goal remains to only focus on a search that queries the meta-data.
