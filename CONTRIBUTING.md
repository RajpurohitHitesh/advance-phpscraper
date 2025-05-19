# Contributing to Advance PHP Scraper

Thank you for considering contributing to **Advance PHP Scraper**! This library thrives on community contributions, and whether you’re fixing bugs, adding features, or improving documentation, your efforts are greatly appreciated. This guide explains how to contribute, making it easy for beginners and experienced developers alike.

---

## Table of Contents
1. [Getting Started](#getting-started)
2. [Setting Up the Development Environment](#setting-up-the-development-environment)
3. [Finding Issues to Work On](#finding-issues-to-work-on)
4. [Submitting a Pull Request](#submitting-a-pull-request)
5. [Coding Guidelines](#coding-guidelines)
6. [Writing Tests](#writing-tests)
7. [Reporting Bugs](#reporting-bugs)
8. [Suggesting Features](#suggesting-features)
9. [Improving Documentation](#improving-documentation)
10. [Code of Conduct](#code-of-conduct)

---

## Getting Started
Contributing to open-source projects like **Advance PHP Scraper** is a great way to learn, share knowledge, and improve the library. You can contribute by:
- Fixing bugs (e.g., resolving errors in plugins).
- Adding features (e.g., new extractors or plugins).
- Improving documentation (e.g., adding examples to README).
- Writing tests to ensure reliability.

Before you start, familiarize yourself with the project by reading the [README.md](README.md) and [PLUGIN_README.md](PLUGIN_README.md).

---

## Setting Up the Development Environment
To contribute, you need to set up the project locally. Follow these steps:

1. **Fork the Repository**:
   - Go to [github.com/rajpurohithitesh/advance-phpscraper](https://github.com/rajpurohithitesh/advance-phpscraper).
   - Click the “Fork” button to create a copy in your GitHub account.

2. **Clone Your Fork**:
   ```bash
   git clone https://github.com/YOUR_USERNAME/advance-phpscraper.git
   cd advance-phpscraper
   ```

3. **Install Dependencies**:
   Ensure you have PHP 7.4+ and Composer installed. Then run:
   ```bash
   composer install
   ```

4. **Set Up a Branch**:
   Create a new branch for your changes:
   ```bash
   git checkout -b my-feature
   ```

5. **Verify Setup**:
   Run the tests to ensure everything works:
   ```bash
   vendor/bin/phpunit --configuration phpunit.xml
   ```

---

## Finding Issues to Work On
Browse the [GitHub Issues](https://github.com/rajpurohithitesh/advance-phpscraper/issues) page to find tasks. Look for:
- **Bugs**: Issues labeled `bug` (e.g., a plugin failing to load).
- **Enhancements**: Issues labeled `enhancement` (e.g., adding a new extractor).
- **Good First Issues**: Issues labeled `good first issue` for beginners.

If you have an idea, create a new issue to discuss it before starting work.

---

## Submitting a Pull Request
Once you’ve made changes, submit a pull request (PR):

1. **Commit Changes**:
   ```bash
   git add .
   git commit -m "Add feature: describe your changes"
   ```

2. **Push to Your Fork**:
   ```bash
   git push origin my-feature
   ```

3. **Create a Pull Request**:
   - Go to your fork on GitHub.
   - Click “Compare & pull request.”
   - Describe your changes clearly, referencing any related issues (e.g., “Fixes #123”).
   - Submit the PR.

4. **Respond to Feedback**:
   - Reviewers may request changes. Update your branch and push new commits:
     ```bash
     git commit -m "Address review comments"
     git push
     ```

---

## Coding Guidelines
Follow these guidelines to ensure consistent code:
- **PHP Standards**: Use PSR-12 coding style (run `vendor/bin/php-cs-fixer fix` if available).
- **Type Safety**: Use type hints and return types where possible:
  ```php
  public function go(string $url): self
  ```
- **Documentation**: Add PHPDoc comments for methods and classes:
  ```php
  /**
   * Scrapes a URL and loads content
   * @param string $url The URL to scrape
   * @return self
   */
  ```
- **Error Handling**: Use try-catch and log errors:
  ```php
  try {
      // Code
  } catch (\Exception $e) {
      $this->logger->error('Error: ' . $e->getMessage());
  }
  ```
- **Naming**: Use descriptive names (e.g., `extractLinks` instead of `getLinks`).

---

## Writing Tests
All changes must include tests to maintain reliability. Tests are located in the `tests/` directory and use PHPUnit.

**Example Test** (for a new extractor):
```php
<?php
namespace AdvancePHPSraper\Tests;
use AdvancePHPSraper\Core\Scraper;
use PHPUnit\Framework\TestCase;
class MyExtractorTest extends TestCase
{
    public function testExtract(): void
    {
        $scraper = new Scraper();
        $scraper->go('https://example.com');
        $data = $scraper->myExtractor();
        $this->assertIsArray($data);
    }
}
```

**Running Tests**:
```bash
vendor/bin/phpunit --configuration phpunit.xml
```

---

## Reporting Bugs
If you find a bug, create an issue on GitHub:
1. Go to [Issues](https://github.com/rajpurohithitesh/advance-phpscraper/issues).
2. Click “New Issue.”
3. Use the bug report template and include:
   - Description of the bug.
   - Steps to reproduce (e.g., code snippet).
   - Expected vs. actual behavior.
   - PHP version and environment details.

**Example**:
```
**Bug**: CachePlugin fails to clear cache.
**Steps**:
1. Enable CachePlugin.
2. Run `$scraper->clearCache()`.
**Expected**: Cache file is deleted.
**Actual**: Cache file remains.
**Environment**: PHP 8.0, Ubuntu 20.04.
```

---

## Suggesting Features
To suggest a feature, create an issue:
1. Describe the feature and its benefits.
2. Provide a use case or example.
3. Mention any dependencies or challenges.

**Example**:
```
**Feature**: Add proxy support to AsyncPlugin.
**Description**: Allow users to configure proxies for async requests to bypass IP restrictions.
**Use Case**: Scraping sites that block frequent requests.
**Example**:
$scraper->configurePlugin('AsyncPlugin', ['proxy' => 'http://proxy.example.com']);
```

---

## Improving Documentation
Documentation is critical for new users. You can improve:
- [README.md](README.md): Add examples or clarify sections.
- [PLUGIN_README.md](PLUGIN_README.md): Expand plugin explanations.
- Inline PHPDoc comments in code.

Submit changes via a PR, ensuring clarity and beginner-friendliness.

---

## Code of Conduct
We strive for a welcoming and inclusive community. Please:
- Be respectful and constructive.
- Avoid offensive language or behavior.
- Collaborate openly and support others.

Report any issues to the maintainers via GitHub.

---