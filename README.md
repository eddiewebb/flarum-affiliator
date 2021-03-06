# Affiliator Affiliate Link Manager for Flarum

![License](https://img.shields.io/github/license/eddiewebb/flarum-affiliator) [![Latest Stable Version](https://img.shields.io/packagist/v/webbinaro/flarum-affiliator.svg)](https://packagist.org/packages/webbinaro/flarum-affiliator) [![Total Downloads](https://img.shields.io/packagist/dt/webbinaro/flarum-affiliator.svg)](https://packagist.org/packages/webbinaro/flarum-affiliator)  [![Monthly Downloads](https://img.shields.io/packagist/dm/webbinaro/flarum-affiliator)](https://packagist.org/packages/webbinaro/flarum-affiliator)

A [Flarum](http://flarum.org) extension that appends affiliate tracking information to all matching urls.

![provide sample URL in settings to define host and affiliate info](https://github.com/eddiewebb/flarum-affiliator/raw/main/assets/settings.png)![Users add/edit URLs normally, with no concern for affiliate](https://github.com/eddiewebb/flarum-affiliator/raw/main/assets/edit.png)![replaces matching host, reghardless of url](https://github.com/eddiewebb/flarum-affiliator/raw/main/assets/matching.png)

## Installation

Install with composer:

```sh
composer require webbinaro/flarum-affiliator:"*"
```

## Updating

```sh
composer update webbinaro/flarum-affiliator:"*"
php flarum migrate
php flarum cache:clear
```

## Links

- [Packagist](https://packagist.org/packages/webbinaro/flarum-affiliator)
- [GitHub](https://github.com/webbinaro/flarum-affiliator)
- [Discuss](https://discuss.flarum.org/d/30000-affiliate-link-manager)
