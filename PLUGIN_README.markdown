# Advance PHP Scraper Plugin Guide

Welcome to the **Advance PHP Scraper Plugin Guide**! This document explains everything you need to know about plugins in the **Advance PHP Scraper** library. Plugins are a powerful way to extend the library’s functionality, allowing you to add features like headless browsing, caching, or custom data extraction. Whether you’re a beginner or an experienced developer, this guide is designed to be clear and detailed, with examples to help you understand each concept.


---

## Table of Contents
1. [What Are Plugins?](#what-are-plugins)
   - [Why Plugins Matter](#why-plugins-matter)
   - [How Plugins Work](#how-plugins-work)
2. [Understanding the Plugin System](#understanding-the-plugin-system)
   - [PluginInterface](#plugininterface)
   - [PluginManager](#pluginmanager)
   - [plugins.json](#pluginsjson)
3. [Available Plugins: Your Toolkit](#available-plugins-your-toolkit)
   - [HeadlessPlugin: Scraping JavaScript Pages](#headlessplugin-scraping-javascript-pages)
   - [AsyncPlugin: Scraping Multiple Pages at Once](#asyncplugin-scraping-multiple-pages-at-once)
   - [NLPPlugin: Analyzing Text with NLP](#nlpplugin-analyzing-text-with-nlp)
   - [DocumentPlugin: Parsing PDFs](#documentplugin-parsing-pdfs)
   - [CachePlugin: Saving Data for Speed](#cacheplugin-saving-data-for-speed)
   - [CloudPlugin: Scraping in the Cloud](#cloudplugin-scraping-in-the-cloud)
4. [Enabling and Disabling Plugins](#enabling-and-disabling-plugins)
   - [Using plugins.json](#using-pluginsjson)
   - [Using PluginManager Programmatically](#using-pluginmanager-programmatically)
   - [Checking Plugin Status](#checking-plugin-status)
5. [Creating a Custom Plugin: Be a Wizard](#creating-a-custom-plugin-be-a-wizard)
   - [Step 1: Plan Your Plugin](#step-1-plan-your-plugin)
   - [Step 2: Create the Plugin File](#step-2-create-the-plugin-file)
   - [Step 3: Implement PluginInterface](#step-3-implement-plugininterface)
   - [Step 4: Add Functionality](#step-4-add-functionality)
   - [Step 5: Update plugins.json](#step-5-update-pluginsjson)
   - [Step 6: Test Your Plugin](#step-6-test-your-plugin)
   - [Example 1: LoggingPlugin (Simple)](#example-1-loggingplugin-simple)
   - [Example 2: ProxyPlugin (Advanced)](#example-2-proxyplugin-advanced)
6. [Code Changes for New Plugins](#code-changes-for-new-plugins)
   - [Updating composer.json](#updating-composerjson)
   - [Modifying PluginManager](#modifying-pluginmanager)
   - [Adding to Scraper.php](#adding-to-scraperphp)
   - [Writing Tests](#writing-tests)
7. [Best Practices for Plugin Development](#best-practices-for-plugin-development)
   - [Keep It Focused](#keep-it-focused)
   - [Use Events](#use-events)
   - [Handle Errors Gracefully](#handle-errors-gracefully)
   - [Document Everything](#document-everything)
   - [Optimize Performance](#optimize-performance)
8. [Troubleshooting Plugins](#troubleshooting-plugins)
   - [Plugin Not Loading](#plugin-not-loading)
   - [Dependency Errors](#dependency-errors)
   - [Runtime Errors](#runtime-errors)
   - [Debugging Tips](#debugging-tips)
9. [FAQ: Common Questions](#faq-common-questions)
10. [Resources: Learn More](#resources-learn-more)

---

## What Are Plugins?
Plugins are like apps you install on your smartphone to add new features. In **Advance PHP Scraper**, a plugin is a PHP class that extends the library’s functionality without changing its core code. For example, if you want to scrape a website that uses JavaScript (like a modern app), you can enable the `HeadlessPlugin` to render the page. If you want to save scraped data to avoid repeated requests, use the `CachePlugin`.

Imagine your scraper as a basic car. The core library gives you wheels, an engine, and a steering wheel—enough to get around. Plugins are upgrades like a GPS (`HeadlessPlugin`), turbo boost (`AsyncPlugin`), or a fancy stereo (`NLPPlugin`). You choose which upgrades to add, keeping your car lightweight and tailored to your needs.

### Why Plugins Matter
- **Customization**: Add only the features you need, like picking toppings for a pizza.
- **Reusability**: Write a plugin once, use it in multiple projects.
- **Separation**: Keep custom code separate from the core library, like organizing tools in a toolbox.
- **Community**: Share plugins with others, growing the library’s ecosystem.

### How Plugins Work
Plugins are stored in the `src/Plugins/custom/` directory and follow these steps:
1. **Definition**: A plugin is a PHP class (e.g., `HeadlessPlugin.php`) that implements the `PluginInterface`.
2. **Registration**: The plugin registers itself with the `Scraper` using the `register()` method, adding new methods or modifying behavior.
3. **Management**: The `PluginManager` loads plugins based on `plugins.json`, which lists enabled plugins.
4. **Execution**: When you enable a plugin, it hooks into the scraper’s workflow (e.g., listening for events like `scraper.page_loaded`).

---

## Understanding the Plugin System
Let’s break down the plugin system’s components, like explaining the parts of a bicycle before you ride it.

### PluginInterface
The `PluginInterface` is a contract that every plugin must follow. It’s like a rulebook saying, “If you want to be a plugin, you need these two methods.” The interface is defined in `src/Plugins/PluginInterface.php`:

```php
<?php
namespace AdvancePHPSraper\Plugins;
use AdvancePHPSraper\Core\Scraper;

interface PluginInterface
{
    public function register(Scraper $scraper): void;
    public function getName(): string;
}
```

- **register(Scraper $scraper)**: This is where you define what the plugin does. You get access to the `Scraper` object and can add new methods, listen for events, or modify behavior.
- **getName()**: Returns the plugin’s name (e.g., `HeadlessPlugin`), used to identify it in `plugins.json`.

**Analogy**: Think of `PluginInterface` as a job application form. Every plugin must fill out the `register` section (what it can do) and the `getName` section (its name).

### PluginManager
The `PluginManager` is like a librarian who manages all plugins. It’s defined in `src/Plugins/PluginManager.php` and handles:
- **Loading Plugins**: Reads `plugins.json` and loads enabled plugins.
- **Enabling/Disabling**: Turns plugins on or off via `enablePlugin()` or `disablePlugin()`.
- **Registration**: Calls each plugin’s `register()` method to integrate it with the scraper.

**Example**:
```php
$scraper->getPluginManager()->enablePlugin('CachePlugin');
```

**Explanation**:
- `getPluginManager()`: Gets the `PluginManager` from the `Scraper`.
- `enablePlugin('CachePlugin')`: Loads the `CachePlugin` and registers it.

### plugins.json
The `plugins.json` file is like a control panel, listing which plugins are enabled. It’s located in the project root:

```json
{
    "plugins": {
        "HeadlessPlugin": {
            "enabled": false
        },
        "AsyncPlugin": {
            "enabled": false
        },
        "NLPPlugin": {
            "enabled": false
        },
        "DocumentPlugin": {
            "enabled": false
        },
        "CachePlugin": {
            "enabled": false
        },
        "CloudPlugin": {
            "enabled": false
        }
    }
}
```

**Explanation**:
- Each plugin has a key (e.g., `HeadlessPlugin`) with an `enabled` field (`true` or `false`).
- When you enable a plugin, `PluginManager` loads its class from `src/Plugins/custom/`.

---

## Available Plugins: Your Toolkit
The library comes with six built-in plugins, each like a specialized tool in your scraping toolbox. Below, we explain each plugin in **extreme detail**, including what it does, how it works, dependencies, configuration options, usage examples, and potential use cases.

### HeadlessPlugin: Scraping JavaScript Pages
**What It Does**:
The `HeadlessPlugin` lets you scrape websites that use JavaScript to load content, like modern apps built with React or Angular. Normally, the scraper only sees static HTML, but this plugin uses a headless Chrome browser (via Symfony Panther) to render the full page, including dynamic content like AJAX-loaded data or JavaScript-generated elements.

**Think of It Like**:
Imagine a website as a pop-up book. The static HTML is the flat pages, but JavaScript adds 3D pop-ups. The `HeadlessPlugin` is like a pair of magic glasses that lets you see the pop-ups.

**Dependencies**:
- `symfony/panther`: Install with:
  ```bash
  composer require symfony/panther
  ```
- Chrome/Chromium browser installed on your system (Panther runs Chrome in headless mode).

**Configuration Options**:
- `browser`: Browser type (default: `chrome`, only Chrome supported currently).
- `headless`: Run without a visible browser window (default: `true`).
- `timeout`: Max time for rendering (default: 30 seconds).
- `window_size`: Browser viewport size (default: `[1920, 1080]`).

**How It Works**:
- The plugin listens for the `scraper.page_loaded` event (triggered when a page is loaded).
- It creates a headless Chrome instance, visits the same URL, and renders the JavaScript.
- The rendered HTML replaces the static HTML in the scraper’s `Crawler`.
- The browser closes to save resources.

**Usage Example**:
Create `scrape_js.php`:

```php
<?php
require 'vendor/autoload.php';
use AdvancePHPSraper\Core\Scraper;

// Create a scraper
$scraper = new Scraper();

// Enable the HeadlessPlugin
$scraper->getPluginManager()->enablePlugin('HeadlessPlugin');

// Configure the plugin (optional)
$scraper->getPluginManager()->getPlugins()['HeadlessPlugin']->configure([
    'headless' => true, // Run without showing browser
    'window_size' => [1280, 720], // Set viewport
    'timeout' => 20 // 20-second timeout
]);

// Scrape a JavaScript-heavy site
$scraper->go('https://example.com'); // Replace with a JS site

// Extract dynamic content
$title = $scraper->title();
$dynamicContent = $scraper->filter('.dynamic-content')->count()
    ? $scraper->filter('.dynamic-content')->text()
    : 'No dynamic content found';

echo "Page Title: $title\n";
echo "Dynamic Content: $dynamicContent\n";
```

Run it:
```bash
php scrape_js.php
```

**Expected Output** (varies by site):
```
Page Title: Example App
Dynamic Content: Welcome to our JavaScript-powered app!
```

**Line-by-Line Explanation**:
- `enablePlugin('HeadlessPlugin')`: Loads the plugin, like installing a new app.
- `configure([...])`: Sets options, like adjusting settings in an app.
- `go('https://example.com')`: Loads the page, and the plugin renders JavaScript content.
- `filter('.dynamic-content')`: Targets elements loaded by JavaScript.

**Use Case**:
- Scraping single-page applications (SPAs) like Twitter or Instagram.
- Extracting Open Graph tags populated by JavaScript.

**Tips**:
- Use `headless: false` for debugging to see the browser in action.
- Increase `timeout` for slow-loading sites.
- Ensure Chrome is installed (`chromium-browser` on Linux).

### AsyncPlugin: Scraping Multiple Pages at Once
**What It Does**:
The `AsyncPlugin` lets you scrape multiple URLs simultaneously, like cooking several dishes at once to save time. It uses Guzzle Promises to send HTTP requests concurrently, making it much faster than scraping one page at a time.

**Think of It Like**:
Imagine you’re mailing letters to 10 friends. Instead of waiting for each letter to be delivered before sending the next, you drop them all in the mailbox at once. The `AsyncPlugin` does this for web requests.

**Dependencies**:
- `guzzlehttp/guzzle`: Included in the core library.

**Configuration Options**:
- `max_concurrent`: Max simultaneous requests (default: 10).
- `timeout`: Request timeout (default: 30 seconds).
- `proxy`: Optional proxy server (default: null).
- `rate_limit`: Requests per second (default: `[10, 1]`).

**How It Works**:
- Adds a `goAsync($urls)` method to the `Scraper`.
- Sends HTTP requests for all URLs at once, up to `max_concurrent`.
- Returns an array of `Crawler` objects (`$url => $crawler`).
- Respects rate limits to avoid server bans.

**Usage Example**:
Create `scrape_async.php`:

```php
<?php
require 'vendor/autoload.php';
use AdvancePHPSraper\Core\Scraper;

// Create a scraper
$scraper = new Scraper();

// Enable the AsyncPlugin
$scraper->getPluginManager()->enablePlugin('AsyncPlugin');

// Configure the plugin
$scraper->getPluginManager()->getPlugins()['AsyncPlugin']->configure([
    'max_concurrent' => 5, // Scrape 5 pages at a time
    'timeout' => 15, // 15-second timeout
    'rate_limit' => [3, 1] // 3 requests per second
]);

// List of URLs to scrape
$urls = [
    'https://example.com',
    'https://example.org',
    'https://iana.org'
];

// Scrape URLs concurrently
$crawlers = $scraper->goAsync($urls);

// Process results
echo "Scraped Pages:\n";
foreach ($crawlers as $url => $crawler) {
    $title = $crawler->filter('title')->count() ? $crawler->filter('title')->text() : 'No title';
    echo "- $url: $title\n";
}
```

Run it:
```bash
php scrape_async.php
```

**Expected Output**:
```
Scraped Pages:
- https://example.com: Example Domain
- https://example.org: Example Domain
- https://iana.org: Internet Assigned Numbers Authority
```

**Line-by-Line Explanation**:
- `enablePlugin('AsyncPlugin')`: Activates the plugin, adding `goAsync()`.
- `configure([...])`: Sets limits to control speed and avoid bans.
- `$urls`: Array of URLs to scrape, like a shopping list.
- `goAsync($urls)`: Sends requests for all URLs at once, returning `Crawler` objects.
- `foreach ($crawlers as $url => $crawler)`: Loops through results, extracting titles.

**Use Case**:
- Scraping product pages from an e-commerce site.
- Collecting news headlines from multiple sources.

**Tips**:
- Keep `max_concurrent` low (e.g., 5–10) to avoid server overload.
- Use a proxy if scraping heavily restricted sites.

### NLPPlugin: Analyzing Text with NLP
**What It Does**:
The `NLPPlugin` analyzes page text to extract keywords and their importance, like a librarian summarizing a book’s main ideas. It uses natural language processing (NLP) to identify key terms and their density.

**Think of It Like**:
Imagine reading a long article and highlighting the most important words. The `NLPPlugin` does this automatically, telling you what the page is about.

**Dependencies**:
- `donatello-za/rake-php-plus`: Included in the core library.

**Configuration Options**:
- `min_keyword_score`: Minimum score for keywords (default: 1.5).
- `max_keywords`: Maximum keywords to return (default: 20).

**How It Works**:
- Adds an `extractEntities()` method to the `Scraper`.
- Uses RAKE (Rapid Automatic Keyword Extraction) to analyze text.
- Returns keywords with scores (importance) and density (frequency).

**Usage Example**:
Create `scrape_nlp.php`:

```php
<?php
require 'vendor/autoload.php';
use AdvancePHPSraper\Core\Scraper;

// Create a scraper
$scraper = new Scraper();

// Enable the NLPPlugin
$scraper->getPluginManager()->enablePlugin('NLPPlugin');

// Configure the plugin
$scraper->getPluginManager()->getPlugins()['NLPPlugin']->configure([
    'min_keyword_score' => 2.0, // Only high-scoring keywords
    'max_keywords' => 10 // Limit to 10 keywords
]);

// Scrape a page
$scraper->go('https://example.com');

// Extract keywords
$entities = $scraper->extractEntities();

echo "Keywords:\n";
foreach ($entities as $entity) {
    echo "- {$entity['keyword']} (Score: {$entity['score']}, Density: {$entity['density']}%\n";
}
```

Run it:
```bash
php scrape_nlp.php
```

**Expected Output**:
```
Keywords:
- example domain (Score: 2.5, Density: 1.2%)
- illustrative examples (Score: 2.3, Density: 0.9%)
```

**Line-by-Line Explanation**:
- `enablePlugin('NLPPlugin')`: Activates the plugin, adding `extractEntities()`.
- `configure([...])`: Sets filters for keyword quality and quantity.
- `go('https://example.com')`: Loads the page for analysis.
- `extractEntities()`: Analyzes text and returns keywords.
- `foreach ($entities as $entity)`: Displays each keyword with its score and density.

**Use Case**:
- SEO analysis to find key terms on a page.
- Summarizing articles or blogs for content analysis.

**Tips**:
- Increase `min_keyword_score` for more specific keywords.
- Use with content-heavy sites for best results.

### DocumentPlugin: Parsing PDFs
**What It Does**:
The `DocumentPlugin` extracts text from PDF files linked on pages, like reading a scanned book and summarizing its content.

**Think of It Like**:
Imagine finding a PDF on a website, like a report. The `DocumentPlugin` opens it and pulls out the text, saving you from doing it manually.

**Dependencies**:
- `smalot/pdfparser`: Install with:
  ```bash
  composer require smalot/pdfparser
  ```

**Configuration Options**:
- `max_size_mb`: Maximum PDF size (default: 10 MB).
- `validate_schema`: Validate PDF structure (default: false).

**How It Works**:
- Adds a `parseDocument($url)` method to the `Scraper`.
- Downloads the PDF and extracts text using `smalot/pdfparser`.
- Optionally validates the PDF’s schema (placeholder for future use).

**Usage Example**:
Create `scrape_pdf.php`:

```php
<?php
require 'vendor/autoload.php';
use AdvancePHPSraper\Core\Scraper;

// Create a scraper
$scraper = new Scraper();

// Enable the DocumentPlugin
$scraper->getPluginManager()->enablePlugin('DocumentPlugin');

// Configure the plugin
$scraper->getPluginManager()->getPlugins()['DocumentPlugin']->configure([
    'max_size_mb' => 5, // Limit to 5 MB
    'validate_schema' => true // Validate PDF structure
]);

// Scrape a page and parse a PDF
$scraper->go('https://example.com');
$result = $scraper->parseDocument('https://example.com/sample.pdf');

echo "PDF Text: {$result['text']}\n";
if ($result['schema_valid']) {
    echo "Schema is valid!\n";
}
```

Run it:
```bash
php scrape_pdf.php
```

**Expected Output** (assuming a valid PDF):
```
PDF Text: This is a sample PDF document...
Schema is valid!
```

**Line-by-Line Explanation**:
- `enablePlugin('DocumentPlugin')`: Activates the plugin, adding `parseDocument()`.
- `configure([...])`: Sets size limits and schema validation.
- `parseDocument($url)`: Downloads and extracts text from the PDF.
- `$result['text']`: The extracted text.
- `$result['schema_valid']`: Whether the PDF passed validation (placeholder).

**Use Case**:
- Extracting text from annual reports or whitepapers linked on sites.
- Analyzing PDF-based data in research projects.

**Tips**:
- Keep `max_size_mb` low to avoid memory issues.
- Test with small PDFs first.

### CachePlugin: Saving Data for Speed
**What It Does**:
The `CachePlugin` saves scraped pages to disk, like storing leftovers in the fridge so you don’t have to cook again. It reduces server requests and speeds up scraping.

**Think of It Like**:
Imagine you visit a library and copy a book. Next time, you read your copy instead of going back. The `CachePlugin` does this for web pages.

**Dependencies**:
- `symfony/cache`: Install with:
  ```bash
  composer require symfony/cache
  ```

**Configuration Options**:
- `ttl`: Cache duration in seconds (default: 3600, or 1 hour).
- `cache_dir`: Directory for cache files (default: `cache`).

**How It Works**:
- Adds `enableCache()` and `clearCache()` methods to the `Scraper`.
- Caches page HTML in `cache_dir` with a key based on URL and method.
- Reuses cached content for identical requests within `ttl`.

**Usage Example**:
Create `scrape_cache.php`:

```php
<?php
require 'vendor/autoload.php';
use AdvancePHPSraper\Core\Scraper;

// Create a scraper
$scraper = new Scraper();

// Enable the CachePlugin
$scraper->getPluginManager()->enablePlugin('CachePlugin');

// Configure the plugin
$scraper->getPluginManager()->getPlugins()['CachePlugin']->configure([
    'ttl' => 7200, // Cache for 2 hours
    'cache_dir' => 'my_cache' // Custom cache folder
]);

// Enable caching
$scraper->enableCache();

// Scrape a page (first request)
$scraper->go('https://example.com');
echo "First scrape: " . $scraper->title() . "\n";

// Scrape again (uses cache)
$scraper->go('https://example.com');
echo "Second scrape (cached): " . $scraper->title() . "\n";

// Clear cache
$scraper->clearCache();
```

Run it:
```bash
php scrape_cache.php
```

**Expected Output**:
```
First scrape: Example Domain
Second scrape (cached): Example Domain
```

**Line-by-Line Explanation**:
- `enablePlugin('CachePlugin')`: Activates the plugin.
- `configure([...])`: Sets cache duration and directory.
- `enableCache()`: Turns on caching for requests.
- `go('https://example.com')`: First request fetches from the server, second uses cache.
- `clearCache()`: Deletes cached files.

**Use Case**:
- Scraping the same page repeatedly (e.g., monitoring price changes).
- Reducing server load for large projects.

**Tips**:
- Use a short `ttl` for frequently updated pages.
- Check `cache_dir` permissions (must be writable).

### CloudPlugin: Scraping in the Cloud
**What It Does**:
The `CloudPlugin` runs scraping tasks on AWS Lambda, like hiring a cloud-based assistant to do your work. It’s ideal for distributed or high-volume scraping.

**Think of It Like**:
Imagine outsourcing your laundry to a service that does it faster. The `CloudPlugin` sends scraping tasks to the cloud, saving your local resources.

**Dependencies**:
- `aws/aws-sdk-php`: Install with:
  ```bash
  composer require aws/aws-sdk-php
  ```
- AWS Lambda function configured for scraping.

**Configuration Options**:
- `provider`: Cloud provider (default: `aws`).
- `region`: AWS region (default: `us-east-1`).
- `function_name`: Lambda function name (default: `scraper`).
- `credentials`: AWS key and secret.

**How It Works**:
- Adds a `deployToCloud($url, $params)` method to the `Scraper`.
- Sends the URL and parameters to an AWS Lambda function.
- Returns the function’s response (assumed to be JSON).

**Usage Example**:
Create `scrape_cloud.php`:

```php
<?php
require 'vendor/autoload.php';
use AdvancePHPSraper\Core\Scraper;

// Create a scraper
$scraper = new Scraper();

// Enable the CloudPlugin
$scraper->getPluginManager()->enablePlugin('CloudPlugin');

// Configure the plugin
$scraper->getPluginManager()->getPlugins()['CloudPlugin']->configure([
    'region' => 'us-east-1',
    'function_name' => 'myScraperFunction',
    'credentials' => [
        'key' => 'YOUR_AWS_KEY',
        'secret' => 'YOUR_AWS_SECRET'
    ]
]);

// Run a cloud scraping task
$result = $scraper->deployToCloud('https://example.com', ['extract' => 'title']);

echo "Cloud Result: " . json_encode($result, JSON_PRETTY_PRINT) . "\n";
```

**Expected Output** (assuming Lambda returns JSON):
```
Cloud Result: {
    "title": "Example Domain"
}
```

**Line-by-Line Explanation**:
- `enablePlugin('CloudPlugin')`: Activates the plugin.
- `configure([...])`: Sets AWS credentials and function details.
- `deployToCloud($url, $params)`: Sends the task to Lambda.
- `$result`: The Lambda function’s response (depends on your setup).

**Use Case**:
- Running large-scale scraping tasks without local server limits.
- Integrating with serverless architectures.

**Tips**:
- Set up an AWS Lambda function first (see AWS docs).
- Test locally with small tasks before scaling.

---

## Enabling and Disabling Plugins
Enabling or disabling plugins is like turning on/off apps on your phone. You can do this via `plugins.json` or programmatically.

### Using plugins.json
The `plugins.json` file is your control panel. Open it in the project root:

```json
{
    "plugins": {
        "HeadlessPlugin": {
            "enabled": false
        },
        "AsyncPlugin": {
            "enabled": false
        },
        "NLPPlugin": {
            "enabled": false
        },
        "DocumentPlugin": {
            "enabled": false
        },
        "CachePlugin": {
            "enabled": false
        },
        "CloudPlugin": {
            "enabled": false
        }
    }
}
```

**How to Enable a Plugin**:
1. Find the plugin (e.g., `CachePlugin`).
2. Change `"enabled": false` to `"enabled": true`.
3. Save the file.

**Example**:
To enable `AsyncPlugin`:
```json
"AsyncPlugin": {
    "enabled": true
}
```

**How It Works**:
- When you create a `Scraper`, the `PluginManager` reads `plugins.json` and loads enabled plugins.
- No code changes needed—just edit the file.

**Why Use plugins.json?**
- Persistent: Settings stay across script runs.
- Simple: No need to modify PHP code.
- Centralized: Manage all plugins in one place.

### Using PluginManager Programmatically
You can enable/disable plugins in your PHP code, like flipping a switch. Create `manage_plugins.php`:

```php
<?php
require 'vendor/autoload.php';
use AdvancePHPSraper\Core\Scraper;

// Create a scraper
$scraper = new Scraper();

// Enable CachePlugin
$scraper->getPluginManager()->enablePlugin('CachePlugin');
$scraper->enableCache();
$scraper->go('https://example.com');
echo "Scraped with cache: " . $scraper->title() . "\n";

// Disable CachePlugin
$scraper->getPluginManager()->disablePlugin('CachePlugin');
$scraper->go('https://example.com');
echo "Scraped without cache: " . $scraper->title() . "\n";
```

Run it:
```bash
php manage_plugins.php
```

**Expected Output**:
```
Scraped with cache: Example Domain
Scraped without cache: Example Domain
```

**Line-by-Line Explanation**:
- `$scraper->getPluginManager()`: Gets the `PluginManager`, like opening the control panel.
- `enablePlugin('CachePlugin')`: Turns on the plugin and updates `plugins.json`.
- `enableCache()`: Activates the plugin’s caching feature.
- `disablePlugin('CachePlugin')`: Turns off the plugin, removing its functionality.

**Why Use PluginManager?**
- Dynamic: Enable plugins only when needed in your script.
- Flexible: Change plugin states without editing files.
- Immediate: Takes effect instantly in your code.

### Checking Plugin Status
To see which plugins are enabled, use `getPlugins()`:

```php
<?php
require 'vendor/autoload.php';
use AdvancePHPSraper\Core\Scraper;

$scraper = new Scraper();
$plugins = $scraper->getPluginManager()->getPlugins();

echo "Enabled Plugins:\n";
foreach ($plugins as $name => $plugin) {
    echo "- $name\n";
}
```

**Expected Output** (if `CachePlugin` is enabled):
```
Enabled Plugins:
- CachePlugin
```

**Explanation**:
- `getPlugins()`: Returns an array of active plugins.
- Useful for debugging or confirming plugin states.

---

## Creating a Custom Plugin: Be a Wizard
Creating a plugin is like building your own Lego creation to add to the library’s set. You can make plugins for anything—logging, proxy rotation, or even integrating with a new API. Let’s go through the process step-by-step, assuming you’re a beginner.

### Step 1: Plan Your Plugin
Decide what your plugin will do. Ask yourself:
- What feature do I want to add? (e.g., logging URLs, rotating proxies)
- Does it need dependencies? (e.g., a logging library)
- Should it add new methods to the `Scraper`? (e.g., `logUrl()`)
- Will it use events? (e.g., listen for `scraper.page_loaded`)

**Example Plan**:
- **Plugin Name**: `LoggingPlugin`
- **Purpose**: Log every scraped URL and timestamp to a file.
- **New Method**: `clearLog()` to clear the log file.
- **Event**: Listen for `scraper.page_loaded` to log URLs.
- **Config**: Allow custom log file path.

### Step 2: Create the Plugin File
Create a new file in `src/Plugins/custom/`, named `LoggingPlugin.php`. The `custom/` folder is like a workshop where you build your plugins.

**File Structure**:
```
advance-phpscraper/
├── src/
│   ├── Plugins/
│   │   ├── custom/
│   │   │   ├── LoggingPlugin.php  <- Your new plugin
```

### Step 3: Implement PluginInterface
Your plugin must follow the `PluginInterface`, which requires `register()` and `getName()`. Here’s the initial code:

```php
<?php
namespace AdvancePHPSraper\Plugins\custom;
use AdvancePHPSraper\Core\Scraper;
use AdvancePHPSraper\Plugins\PluginInterface;

class LoggingPlugin implements PluginInterface
{
    public function register(Scraper $scraper): void
    {
        // Add plugin logic here
    }

    public function getName(): string
    {
        return 'LoggingPlugin';
    }
}
```

**Explanation**:
- `namespace`: Places the plugin in the `AdvancePHPSraper\Plugins\custom` namespace, like a folder in a filing cabinet.
- `implements PluginInterface`: Promises to follow the interface’s rules.
- `register()`: Where you’ll add the plugin’s magic.
- `getName()`: Returns `LoggingPlugin`, used in `plugins.json`.

### Step 4: Add Functionality
Let’s make the plugin log URLs to a file when pages are scraped. We’ll use the `scraper.page_loaded` event and add a `clearLog()` method.

**Full Code**:
```php
<?php
namespace AdvancePHPSraper\Plugins\custom;
use AdvancePHPSraper\Core\Scraper;
use AdvancePHPSraper\Plugins\PluginInterface;

/**
 * Logs every scraped URL and timestamp to a file
 */
class LoggingPlugin implements PluginInterface
{
    /**
     * Configuration options
     * @var array
     */
    protected $config = [
        'log_file' => 'scraper.log', // Default log file
    ];

    /**
     * Register the plugin with the scraper
     * @param Scraper $scraper The scraper instance
     */
    public function register(Scraper $scraper): void
    {
        // Listen for the page_loaded event
        $scraper->getDispatcher()->addListener('scraper.page_loaded', function () use ($scraper) {
            try {
                $url = $scraper->getCrawler()->getUri();
                $logMessage = date('Y-m-d H:i:s') . " - Scraped: $url\n";
                file_put_contents($this->config['log_file'], $logMessage, FILE_APPEND);
            } catch (\Exception $e) {
                $scraper->getLogger()->error('LoggingPlugin error: ' . $e->getMessage());
            }
        });

        // Add a method to clear the log
        $scraper->clearLog = function () {
            try {
                file_put_contents($this->config['log_file'], '');
            } catch (\Exception $e) {
                $this->logger->error('Clear log error: ' . $e->getMessage());
            }
        };
    }

    /**
     * Configure the plugin
     * @param array $config Configuration options
     * @return self
     */
    public function configure(array $config): self
    {
        $this->config = array_merge($this->config, $config);
        return $this;
    }

    /**
     * Get the plugin name
     * @return string
     */
    public function getName(): string
    {
        return 'LoggingPlugin';
    }
}
```

**Line-by-Line Explanation**:
- `namespace`: Places the plugin in the right folder of the library’s code.
- `class LoggingPlugin`: Defines the plugin, like naming your Lego creation.
- `$config`: Stores settings, like a notepad for plugin options (default log file is `scraper.log`).
- `register($scraper)`:
  - `$scraper->getDispatcher()`: Gets the event system, like a message board.
  - `addListener('scraper.page_loaded', ...)`: Subscribes to the `page_loaded` event, triggered after a page is scraped.
  - `$url = $scraper->getCrawler()->getUri()`: Gets the current URL.
  - `file_put_contents(...)`: Appends the log message to the file.
  - `try-catch`: Handles errors (e.g., file permission issues).
  - `$scraper->clearLog`: Adds a method to clear the log file, using a closure.
- `configure($config)`: Updates settings (e.g., custom log file path).
- `getName()`: Returns `LoggingPlugin` for identification.

### Step 5: Update plugins.json
Add your plugin to `plugins.json`:

```json
{
    "plugins": {
        "LoggingPlugin": {
            "enabled": true
        }
    }
}
```

**Explanation**:
- `"enabled": true`: Tells the `PluginManager` to load the plugin.
- Save the file to make the plugin active.

### Step 6: Test Your Plugin
Create a test script, `test_logging.php`:

```php
<?php
require 'vendor/autoload.php';
use AdvancePHPSraper\Core\Scraper;

// Create a scraper
$scraper = new Scraper();

// Enable the LoggingPlugin
$scraper->getPluginManager()->enablePlugin('LoggingPlugin');

// Configure the plugin
$scraper->getPluginManager()->getPlugins()['LoggingPlugin']->configure([
    'log_file' => 'my_logs.log'
]);

// Scrape a page
$scraper->go('https://example.com');

// Check the log file
$log = file_get_contents('my_logs.log');
echo "Log Content:\n$log\n";

// Clear the log
$scraper->clearLog();
```

Run it:
```bash
php test_logging.php
```

**Expected Output**:
```
Log Content:
2025-05-19 18:00:00 - Scraped: https://example.com
```

**Explanation**:
- `configure(['log_file' => 'my_logs.log'])`: Sets a custom log file.
- `go('https://example.com')`: Triggers the `page_loaded` event, logging the URL.
- `file_get_contents('my_logs.log')`: Reads the log to verify it worked.
- `clearLog()`: Empties the log file.

**Why This Plugin is Cool**:
- Tracks scraping activity for debugging or auditing.
- Easy to extend (e.g., log more details like page title).
- Lightweight, with no external dependencies.

### Example 2: ProxyPlugin (Advanced)
Let’s create a more advanced plugin, `ProxyPlugin`, to rotate proxies for scraping restricted sites. This shows how to handle dependencies and complex logic.

**Plan**:
- **Purpose**: Rotate proxies to bypass IP restrictions.
- **New Method**: `setProxy($proxy)` to set a proxy for requests.
- **Dependency**: `guzzlehttp/guzzle` (already included).
- **Event**: Modify HTTP requests via `scraper.page_loaded`.

**File**: `src/Plugins/custom/ProxyPlugin.php`
```php
<?php
namespace AdvancePHPSraper\Plugins\custom;
use AdvancePHPSraper\Core\Scraper;
use AdvancePHPSraper\Plugins\PluginInterface;
use GuzzleHttp\Client;

/**
 * Rotates proxies for HTTP requests to bypass restrictions
 */
class ProxyPlugin implements PluginInterface
{
    /**
     * Configuration options
     * @var array
     */
    protected $config = [
        'proxies' => [], // List of proxies
        'current_proxy' => null // Current proxy
    ];

    /**
     * Register the plugin with the scraper
     * @param Scraper $scraper The scraper instance
     */
    public function register(Scraper $scraper): void
    {
        // Add a method to set a proxy
        $scraper->setProxy = function (string $proxy = null) use ($scraper) {
            try {
                $this->config['current_proxy'] = $proxy ?: $this->get