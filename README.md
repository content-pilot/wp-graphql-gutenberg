# WPGraphQL Gutenberg

> **This is a maintained fork of [pristas-peter/wp-graphql-gutenberg](https://github.com/pristas-peter/wp-graphql-gutenberg)**, whose last release was v0.4.1 (December 2022) and which has had no commits since August 2024. All credit for the original design and implementation belongs to [pristas-peter](https://github.com/pristas-peter); this fork exists to continue maintenance for Content Pilot's projects. The code remains GPL-3.0 licensed.

Query gutenberg blocks through wp-graphql

-   <a href="https://wp-graphql-gutenberg.netlify.app/" target="_blank">Usage Docs</a>
-   <a href="https://join.slack.com/t/wp-graphql/shared_invite/zt-3vloo60z-PpJV2PFIwEathWDOxCTTLA" target="_blank">Join our community through WpGraphQL Slack</a>

## Install

-   Requires PHP 7.0+
-   Requires wp-graphql 0.9.0+
-   Requires WordPress 5.4+

### Quick Install

Download and install like any WordPress plugin.
[Details here.](https://wp-graphql-gutenberg.netlify.app/getting-started/installation)

## Development

```sh
composer install
composer run test   # PHPUnit
composer run lint   # phpcs
npm install && npm run build
```

Pull requests run the test suite and coding standards in CI.

## Release

Releases are git tags on `main`.

1. Bump `Version:` in `plugin.php` and `version` in `package.json`.
2. If JavaScript sources changed, run `npm run build` and commit `build/`.
3. Merge to `main` and tag the release commit with the semver version.
