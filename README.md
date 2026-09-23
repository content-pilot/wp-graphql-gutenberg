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

This fork is consumed as a Composer dependency, so `vendor/` is committed.
The PHPUnit dependency tree is gitignored and restored by `composer install`.

```sh
composer install
composer run test   # PHPUnit
composer run lint   # phpcs
npm install && npm run build
```

Pull requests run the test suite and coding standards in CI.

## Release

Consumers resolve this package by git tag, so a release is a version bump and
a tag on `main`. Nothing is built or packaged in CI.

1. Bump `Version:` in `plugin.php` and `version` in `package.json`.
2. Run `npm run build` and commit the updated `build/` output (it is tracked;
   consumers receive the source tree as-is).
3. Commit, tag with the semver version (`git tag 0.4.11`), and push both.
4. Update the version constraint in the consuming project.
