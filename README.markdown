# Advance PHP Scraper

**Advance PHP Scraper** is a powerful, modular, and extensible PHP library designed for web scraping. It simplifies extracting data from websites, such as links, images, meta tags, structured data, and more, while offering advanced features like plugin support, rate limiting, and asynchronous scraping. Whether you're a beginner or an experienced developer, this library provides a flexible and user-friendly interface to scrape web content efficiently.

This document is crafted to be beginner-friendly, with detailed explanations and examples to help you get started, even if you're new to PHP or web scraping. By the end, you'll know how to install, use, and extend the library with ease.


---

## Table of Contents
1. [What is Advance PHP Scraper?](#what-is-advance-php-scraper)
   - [Why Use This Library?](#why-use-this-library)
   - [Who Should Use It?](#who-should-use-it)
2. [Key Features](#key-features)
   - [Core Scraping Features](#core-scraping-features)
   - [Advanced Features](#advanced-features)
   - [Plugin System](#plugin-system)
3. [Getting Started](#getting-started)
   - [Prerequisites](#prerequisites)
   - [Installation](#installation)
   - [Verifying Installation](#verifying-installation)
4. [Basic Usage: Your First Scrape](#basic-usage-your-first-scrape)
   - [Scraping a Simple Website](#scraping-a-simple-website)
   - [Extracting Links](#extracting-links)
   - [Extracting Images](#extracting-images)
   - [Extracting Meta Tags](#extracting-meta-tags)
   - [Using the Command-Line Interface (CLI)](#using-the-command-line-interface-cli)
5. [Intermediate Usage: Leveling Up](#intermediate-usage-leveling-up)
   - [Scraping Sitemaps](#scraping-sitemaps)
   - [Scraping RSS Feeds](#scraping-rss-feeds)
   - [Parsing Assets (CSV, JSON, XML)](#parsing-assets-csv-json-xml)
   - [Checking HTTP Status Codes](#checking-http-status-codes)
6. [Advanced Usage: Power User Mode](#advanced-usage-power-user-mode)
   - [Rate Limiting: Playing Nice with Servers](#rate-limiting-playing-nice-with-servers)
   - [Queue System: Scraping Multiple URLs](#queue-system-scraping-multiple-urls)
   - [API Integration: Combining Scraping with APIs](#api-integration-combining-scraping-with-apis)
   - [Custom CSS Selectors](#custom-css-selectors)
7. [Plugins: Supercharging Your Scraper](#plugins-supercharging-your-scraper)
   - [What Are Plugins?](#what-are-plugins)
   - [Available Plugins](#available-plugins)
   - [How to Use Plugins](#how-to-use-plugins)
   - [Learn More About Plugins](#learn-more-about-plugins)
8. [Configuration: Customizing Your Scraper](#configuration-customizing-your-scraper)
   - [Setting User Agent](#setting-user-agent)
   - [Adjusting Timeout](#adjusting-timeout)
   - [Following Redirects](#following-redirects)
   - [Using Constructor Configuration](#using-constructor-configuration)
9. [Testing: Ensuring Everything Works](#testing-ensuring-everything-works)
   - [Running Tests](#running-tests)
   - [Writing Your Own Tests](#writing-your-own-tests)
10. [Troubleshooting: Solving Common Problems](#troubleshooting-solving-common-problems)
    - [Installation Issues](#installation-issues)
    - [Scraping Errors](#scraping-errors)
    - [Plugin Problems](#plugin-problems)
11. [Contributing: Joining the Community](#contributing-joining-the-community)
12. [License: Understanding Usage Rights](#license-understanding-usage-rights)
13. [Resources: Further Learning](#resources-further-learning)

---

## What is Advance PHP Scraper?
**Advance PHP Scraper** is a PHP library that helps you extract data from websites, like a super-smart librarian who can quickly find and summarize books for you. Web scraping is like copying information from a webpage (e.g., product names, prices, or blog titles) using code instead of manually copying and pasting. This library makes it easy to navigate websites, grab specific data, and even handle tricky tasks like scraping JavaScript-heavy pages or processing thousands of URLs at once.

Imagine you’re at a giant library (the internet), and you need to collect all book titles from a specific shelf (a website). Doing this by hand would take forever, but **Advance PHP Scraper** is like a magical robot that does it for you in seconds. It’s designed to be:
- **Easy**: Simple commands to get data, even if you’re new to coding.
- **Powerful**: Handles complex tasks like async scraping or cloud deployment.
- **Flexible**: Add your own features using plugins, like customizing a Lego set.

### Why Use This Library?
There are other scraping tools out there, but here’s why **Advance PHP Scraper** is special:
- **Beginner-Friendly**: The code is straightforward, and this guide explains everything like you’re five.
- **Modular**: Only use the features you need, keeping your project lightweight.
- **Robust**: Built-in error handling, logging, and rate limiting prevent crashes or bans.
- **Extensible**: Plugins let you add custom features without touching the core code.
- **Free and Open-Source**: Use it, modify it, share it—under the MIT License.

### Who Should Use It?
- **New Coders**: If you’re learning PHP and want to try web scraping, this is a great starting point.
- **Hobbyists**: Want to scrape your favorite blog’s headlines or collect product prices? This is for you.
- **Professionals**: Need to scrape thousands of pages for data analysis? The library’s advanced features have you covered.
- **Educators**: Teaching PHP or web scraping? Use this library for hands-on examples.

---

## Key Features
Let’s explore what **Advance PHP Scraper** can do. Think of these features as tools in a toolbox, each designed for a specific job.

### Core Scraping Features
These are the basic tools you’ll use most often:
- **Extract Common Data**:
  - **Links**: Grab all `<a>` tags (e.g., URLs and their text).
  - **Images**: Collect `<img>` tags (e.g., source URLs and alt text).
  - **Meta Tags**: Extract `<meta>` tags (e.g., description, Open Graph data).
  - **Headings**: Get `<h1>` to `<h6>` tags for page structure.
  - **Paragraphs**: Pull `<p>` tag content for text.
  - **Structured Data**: Extract JSON-LD, Microdata, and RDFa (e.g., schema.org data).
- **Sitemap Parsing**: Read XML sitemaps to discover all pages on a site.
- **RSS Feed Parsing**: Extract news or blog feeds.
- **Asset Parsing**: Process CSV, JSON, or XML files linked on pages.
- **Custom Selectors**: Use CSS selectors to target specific elements (e.g., `div.content`).

### Advanced Features
These tools are for power users:
- **Rate Limiting**: Control how fast you scrape to avoid server bans (like driving at the speed limit).
- **Queue System**: Scrape multiple URLs in batches, like a to-do list for your scraper.
- **API Integration**: Combine scraped data with external APIs (e.g., fetch product details).
- **CLI Interface**: Run scraping tasks from the command line, perfect for quick jobs.
- **Multilingual Support**: Handle non-English text with proper encoding (e.g., Spanish, Chinese).
- **Error Handling**: Logs errors and checks HTTP status codes to keep scraping smooth.

### Plugin System
Plugins are like optional upgrades for your toolbox:
- **Headless Browsing**: Scrape JavaScript-rendered pages (e.g., React apps).
- **Async Scraping**: Scrape multiple pages at once for speed.
- **NLP Analysis**: Extract keywords and entities from text.
- **PDF Parsing**: Read text from linked PDFs.
- **Caching**: Save scraped data to reduce server load.
- **Cloud Deployment**: Run scraping tasks on AWS Lambda.
- **Custom Plugins**: Add your own features (e.g., custom logging).

---

## Getting Started
Let’s set up the library and run your first scrape. This section is like a cooking recipe: follow each step, and you’ll have a working scraper in no time.

### Prerequisites
Before you start, you need:
- **PHP 7.4 or Higher**: The library works with PHP 7.4, 8.0, or 8.1. Check your version:
  ```bash
  php -v
  ```
  If it’s lower, download a newer version from [php.net](https://www.php.net/).
- **Composer**: This is a tool to manage PHP dependencies (like a grocery delivery service for code). Install it:
  ```bash
  php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
  php composer-setup.php
  php -r "unlink('composer-setup.php');"
  mv composer.phar /usr/local/bin/composer
  ```
- **A Text Editor**: Use VS Code, Sublime Text, or any editor to write PHP code.
- **Internet Connection**: Needed to download the library and scrape websites.

### Installation
Here’s how to install the library:

1. **Create a Project Folder**:
   Make a new directory for your scraping project:
   ```bash
   mkdir my-scraper
   cd my-scraper
   ```

2. **Install Advance PHP Scraper**:
   Run this Composer command to download the library and its dependencies:
   ```bash
   composer require rajpurohithitesh/advance-phpscraper
   ```
   This creates a `vendor/` folder with the library and dependencies like `symfony/browser-kit` and `guzzlehttp/guzzle`.

3. **Check the Files**:
   After installation, you’ll see:
   - `vendor/`: Contains the library and dependencies.
   - `composer.json`: Lists the project’s dependencies.
   - `composer.lock`: Locks dependency versions.

### Verifying Installation
Let’s make sure everything works. Create a file named `test.php`:

```php
<?php
require 'vendor/autoload.php';
use AdvancePHPSraper\Core\Scraper;

$scraper = new Scraper();
echo "Hooray! Advance PHP Scraper is ready to roll!\n";
```

Run it:
```bash
php test.php
```

**Expected Output**:
```
Hooray! Advance PHP Scraper is ready to roll!
```

If you see this, you’re good to go! If you get an error, check the [Troubleshooting](#troubleshooting-solving-common-problems) section.

---

## Basic Usage: Your First Scrape
Now, let’s scrape some real data! Think of this as your first adventure with the library, like learning to ride a bike with training wheels.

### Scraping a Simple Website
Let’s scrape the title of a webpage. Create a file named `scrape_title.php`:

```php
<?php
require 'vendor/autoload.php'; // Load the library
use AdvancePHPSraper\Core\Scraper;

// Create a new scraper instance
$scraper = new Scraper();

// Go to the website
$scraper->go('https://example.com');

// Get the page title
$title = $scraper->title();

// Print the title
echo "The page title is: $title\n";
```

Run it:
```bash
php scrape_title.php
```

**Expected Output**:
```
The page title is: Example Domain
```

**Line-by-Line Explanation**:
- `require 'vendor/autoload.php'`: This line is like opening your toolbox, loading all the library’s tools.
- `use AdvancePHPSraper\Core\Scraper`: This tells PHP you want to use the `Scraper` class, like picking a specific tool from the toolbox.
- `$scraper = new Scraper()`: Creates a new scraper, like turning on your robot assistant.
- `$scraper->go('https://example.com')`: Tells the scraper to visit the website, like sending your robot to a library shelf.
- `$title = $scraper->title()`: Asks the scraper to find the `<title>` tag, like asking for the book’s title.
- `echo "The page title is: $title\n"`: Prints the result, like showing off the book you found.

**What’s Happening Behind the Scenes?**
- The library sends an HTTP request to `https://example.com` using `Symfony BrowserKit`.
- It loads the HTML into a `Crawler` object (like a super-smart librarian who can read the page).
- The `title()` method searches for the `<title>` tag and returns its text.

### Extracting Links
Let’s grab all the links on a page. Create `scrape_links.php`:

```php
<?php
require 'vendor/autoload.php';
use AdvancePHPSraper\Core\Scraper;

$scraper = new Scraper();
$scraper->go('https://example.com');

// Get all links
$links = $scraper->links();

// Loop through links and print them
echo "Found " . count($links) . " links:\n";
foreach ($links as $link) {
    echo "- URL: {$link['href']}\n";
    echo "  Text: {$link['text']}\n";
    echo "  Nofollow: " . ($link['is_nofollow'] ? 'Yes' : 'No') . "\n";
}
```

Run it:
```bash
php scrape_links.php
```

**Expected Output**:
```
Found 1 links:
- URL: https://www.iana.org/domains/example
  Text: More information...
  Nofollow: No
```

**Line-by-Line Explanation**:
- `$links = $scraper->links()`: Finds all `<a>` tags and returns an array of link details (like a list of book references).
- `foreach ($links as $link)`: Loops through each link, like flipping through a list.
- `$link['href']`: The URL (e.g., `https://www.iana.org/domains/example`).
- `$link['text']`: The clickable text (e.g., “More information...”).
- `$link['is_nofollow']`: Checks if the link has a `rel="nofollow"` attribute (used by search engines).

**Why This is Cool**:
- You get detailed info about each link, like whether it’s nofollow (important for SEO).
- The library handles relative URLs (e.g., `/page` becomes `https://example.com/page`).

### Extracting Images
Now, let’s grab images. Create `scrape_images.php`:

```php
<?php
require 'vendor/autoload.php';
use AdvancePHPSraper\Core\Scraper;

$scraper = new Scraper();
$scraper->go('https://example.com');

// Get all images
$images = $scraper->images();

// Print images
echo "Found " . count($images) . " images:\n";
foreach ($images as $image) {
    echo "- Source: {$image['src']}\n";
    echo "  Alt Text: {$image['alt']}\n";
    echo "  Dimensions: {$image['width']}x{$image['height']}\n";
}
```

Run it:
```bash
php scrape_images.php
```

**Expected Output**:
```
Found 0 images:
```

**Explanation**:
- `$images = $scraper->images()`: Finds all `<img>` tags.
- Since `https://example.com` has no images, the output is empty.
- Try a different site (e.g., `https://www.wikipedia.org`) for images:
  ```php
  $scraper->go('https://www.wikipedia.org');
  ```
  You might see:
  ```
  Found 2 images:
  - Source: /static/images/logo.png
    Alt Text: Wikipedia Logo
    Dimensions: 200x200
  - Source: /static/images/search.png
    Alt Text: Search Icon
    Dimensions: 24x24
  ```

**Why This is Useful**:
- You can filter images by size or attributes (e.g., `$scraper->images()->filterByMinDimensions(100, 100)`).
- The library handles lazy-loaded images (e.g., `data-src` attributes).

### Extracting Meta Tags
Meta tags contain SEO and social media data. Create `scrape_meta.php`:

```php
<?php
require 'vendor/autoload.php';
use AdvancePHPSraper\Core\Scraper;

$scraper = new Scraper();
$scraper->go('https://example.com');

// Get meta tags
$meta = $scraper->meta();

// Print meta tags
echo "Meta Tags:\n";
foreach ($meta as $type => $tags) {
    echo "$type:\n";
    foreach ($tags as $name => $content) {
        echo "  - $name: $content\n";
    }
}
```

Run it:
```bash
php scrape_meta.php
```

**Expected Output**:
```
Meta Tags:
standard:
  - description: This domain is for use in illustrative examples in documents...
og:
  - og:title: Example Domain
  - og:description: This domain is for use...
```

**Explanation**:
- `$meta = $scraper->meta()`: Returns a categorized array of meta tags (`standard`, `og`, `twitter`, `charset`, `viewport`).
- `$type`: Groups like `standard` (regular meta tags) or `og` (Open Graph for social media).
- Useful for SEO analysis or social media previews.

### Using the Command-Line Interface (CLI)
The CLI lets you scrape without writing PHP code. Run:

```bash
php bin/scraper scrape https://example.com --extract=links,meta,content
```

**Expected Output** (JSON):
```json
{
  "links": [
    {
      "href": "https://www.iana.org/domains/example",
      "text": "More information...",
      "rel": null,
      "protocol": "https",
      "is_nofollow": false
    }
  ],
  "meta": {
    "standard": {
      "description": "This domain is for use in illustrative examples..."
    },
    "og": {
      "og:title": "Example Domain"
    }
  },
  "content": {
    "headings": [
      {
        "tag": "h1",
        "text": "Example Domain",
        "level": 1
      }
    ],
    "paragraphs": [
      "This domain is for use in illustrative examples..."
    ],
    "keywords": ["example", "domain"]
  }
}
```

**Explanation**:
- `scrape`: The CLI command to scrape a URL.
- `--extract=links,meta,content`: Specifies what to extract (options: `links`, `images`, `meta`, `content`, `sitemap`, `rss`).
- The JSON output is easy to parse for scripts or tools.
- Great for quick tasks or automation (e.g., in a cron job).

---

## Intermediate Usage: Leveling Up
Now that you’ve mastered the basics, let’s explore more features to make your scraper smarter.

### Scraping Sitemaps
Sitemaps list all pages on a website, like a table of contents for a book. Create `scrape_sitemap.php`:

```php
<?php
require 'vendor/autoload.php';
use AdvancePHPSraper\Core\Scraper;

$scraper = new Scraper();
$scraper->go('https://example.com');

// Get sitemap URLs
$sitemap = $scraper->sitemap();

echo "Sitemap URLs:\n";
foreach ($sitemap as $url) {
    echo "- {$url['loc']} (Last Modified: {$url['lastmod']})\n";
}
```

Run it:
```bash
php scrape_sitemap.php
```

**Expected Output**:
```
Sitemap URLs:
- (none found)
```

**Explanation**:
- `$scraper->sitemap()`: Finds the sitemap URL from `robots.txt` and parses it.
- Since `https://example.com` may not have a sitemap, try a site like `https://www.wikipedia.org`:
  ```php
  $scraper->go('https://www.wikipedia.org');
  ```
  Output might be:
  ```
  Sitemap URLs:
  - https://en.wikipedia.org/sitemap.xml (Last Modified: 2025-05-01)
  ```

**Why This is Awesome**:
- Sitemaps help you discover all pages on a site, perfect for large-scale scraping.
- Includes metadata like `lastmod` (last modified date) and `priority`.

### Scraping RSS Feeds
RSS feeds are like news tickers for websites. Create `scrape_rss.php`:

```php
<?php
require 'vendor/autoload.php';
use AdvancePHPSraper\Core\Scraper;

$scraper = new Scraper();
$scraper->go('https://example.com');

// Get RSS feeds
$feeds = $scraper->rssFeed();

echo "RSS Feeds:\n";
foreach ($feeds as $feed) {
    echo "- Feed: {$feed['title']} ({$feed['url']})\n";
    foreach ($feed['items'] as $item) {
        echo "  - {$item['title']} ({$item['pubDate']})\n";
    }
}
```

Run it:
```bash
php scrape_rss.php
```

**Expected Output**:
```
RSS Feeds:
- (none found)
```

**Explanation**:
- `$scraper->rssFeed()`: Finds `<link type="application/rss+xml">` tags and parses RSS feeds.
- Try a news site like `https://www.bbc.com` for feeds:
  ```php
  $scraper->go('https://www.bbc.com');
  ```
  Output might be:
  ```
  RSS Feeds:
  - Feed: BBC News (https://feeds.bbci.co.uk/news/rss.xml)
    - Breaking News (2025-05-19 10:00:00)
    - World Update (2025-05-19 09:00:00)
  ```

**Why This is Handy**:
- Great for scraping news, blogs, or podcasts.
- Returns structured data (title, link, description, date).

### Parsing Assets (CSV, JSON, XML)
You can parse files linked on pages. Create `parse_asset.php`:

```php
<?php
require 'vendor/autoload.php';
use AdvancePHPSraper\Core\Scraper;

$scraper = new Scraper();
$scraper->go('https://example.com');

// Parse a CSV file (assuming a link exists)
$content = $scraper->fetchAsset('https://example.com/data.csv');
$data = $scraper->parseCsv($content, true);

echo "CSV Data:\n";
foreach ($data as $row) {
    echo "- {$row['name']}: {$row['value']}\n";
}
```

**Explanation**:
- `fetchAsset($url)`: Downloads the file content.
- `parseCsv($content, true)`: Parses CSV, using the first row as headers.
- For JSON or XML, use `parseJson()` or `parseXml()`.

**Why This is Useful**:
- Extract data from linked files (e.g., product lists in CSV).
- Handles multiple formats for flexibility.

### Checking HTTP Status Codes
Ensure a page loaded correctly with `getStatusCode()`:

```php
<?php
require 'vendor/autoload.php';
use AdvancePHPSraper\Core\Scraper;

$scraper = new Scraper();
$scraper->go('https://example.com');

$status = $scraper->getStatusCode();
if ($status === 200) {
    echo "Page loaded successfully!\n";
} else {
    echo "Error: HTTP $status\n";
}

if ($scraper->isErrorPage()) {
    echo "This is an error page (e.g., 404 or 500).\n";
}
```

**Expected Output**:
```
Page loaded successfully!
```

**Explanation**:
- `getStatusCode()`: Returns the HTTP status (e.g., 200 for success, 404 for not found).
- `isErrorPage()`: Returns `true` for status codes >= 400.
- Helps you skip broken pages or handle errors gracefully.

---

## Advanced Usage: Power User Mode
Ready to take your scraper to the next level? These features are like rocket boosters for your scraping adventures.

### Rate Limiting: Playing Nice with Servers
Rate limiting prevents your scraper from overwhelming servers, which could lead to bans. Think of it as pacing yourself while eating cookies so you don’t get kicked out of the kitchen. Create `rate_limit.php`:

```php
<?php
require 'vendor/autoload.php';
use AdvancePHPSraper\Core\Scraper;

$scraper = new Scraper();
$scraper->setRateLimit(3, 1); // 3 requests per second

$urls = [
    'https://example.com',
    'https://example.org',
    'https://iana.org',
    'https://wikipedia.org'
];

foreach ($urls as $url) {
    $scraper->go($url);
    echo "Scraped: $url\n";
}
```

Run it:
```bash
php rate_limit.php
```

**Expected Output**:
```
Scraped: https://example.com
Scraped: https://example.org
Scraped: https://iana.org
Scraped: https://wikipedia.org
```

**Explanation**:
- `setRateLimit(3, 1)`: Limits to 3 requests per second.
- The library pauses between requests (e.g., after 3 requests, it waits 1 second).
- Prevents server overload and IP bans, especially for large-scale scraping.

**Tip**:
- Start with a conservative limit (e.g., 5 requests/second) and adjust based on the target site’s policies.
- Check the site’s `robots.txt` for crawling guidelines.

### Queue System: Scraping Multiple URLs
The queue system lets you scrape multiple URLs efficiently, like a conveyor belt processing orders. Create `queue_scrape.php`:

```php
<?php
require 'vendor/autoload.php';
use AdvancePHPSraper\Core\Scraper;

$scraper = new Scraper();
$urls = [
    'https://example.com',
    'https://example.org',
    'https://iana.org'
];

// Define a callback to extract titles
$callback = function ($crawler) {
    return $crawler->filter('title')->count() ? $crawler->filter('title')->text() : 'No title';
};

// Queue URLs
$scraper->queueUrls($urls, $callback);

// Process the queue
$results = $scraper->processQueue();

// Print results
echo "Scraping Results:\n";
foreach ($results as $url => $title) {
    echo "- $url: $title\n";
}
```

Run it:
```bash
php queue_scrape.php
```

**Expected Output**:
```
Scraping Results:
- https://example.com: Example Domain
- https://example.org: Example Domain
- https://iana.org: Internet Assigned Numbers Authority
```

**Line-by-Line Explanation**:
- `$urls`: An array of URLs to scrape, like a to-do list.
- `$callback`: A function that processes each page (here, it extracts the title).
- `queueUrls($urls, $callback)`: Adds URLs to the queue with the callback.
- `processQueue()`: Runs the scraper on each URL and returns results as `$url => $callback_result`.
- The `foreach` loop displays the results, like checking off your to-do list.

**Why This is Powerful**:
- Handles errors gracefully (e.g., failed URLs return `null`).
- Scales to thousands of URLs without overwhelming your script.
- Customizable callbacks let you extract any data.

### API Integration: Combining Scraping with APIs
You can fetch data from APIs to complement your scraped data, like adding extra toppings to a pizza. Create `api_scrape.php`:

```php
<?php
require 'vendor/autoload.php';
use AdvancePHPSraper\Core\Scraper;

$scraper = new Scraper();
$scraper->go('https://example.com');

// Scrape the title
$title = $scraper->title();

// Fetch related data from an API
$apiData = $scraper->apiRequest('https://jsonplaceholder.typicode.com/posts/1', [
    'query' => 'example'
], 'POST');

echo "Page Title: $title\n";
echo "API Data:\n";
echo json_encode($apiData, JSON_PRETTY_PRINT) . "\n";
```

Run it:
```bash
php api_scrape.php
```

**Expected Output**:
```
Page Title: Example Domain
API Data:
{
    "userId": 1,
    "id": 1,
    "title": "sunt aut facere repellat provident occaecati excepturi...",
    "body": "quia et suscipit\nsuscipit recusandae consequuntur..."
}
```

**Explanation**:
- `apiRequest($endpoint, $params, $method)`: Sends an HTTP request (GET or POST) to an API and returns the JSON response.
- `$params`: Optional data to send (e.g., query parameters or POST body).
- `$method`: HTTP method (default: GET).
- Here, we scrape the page title and fetch a sample post from a public API.

**Use Case**:
- Scrape a product page and use an API to get additional details (e.g., stock status).
- Combine scraped news headlines with an API for sentiment analysis.

### Custom CSS Selectors
Want to extract something specific, like a div with class `content`? Use `filter()`:

```php
<?php
require 'vendor/autoload.php';
use AdvancePHPSraper\Core\Scraper;

$scraper = new Scraper();
$scraper->go('https://example.com');

// Extract content from a specific div
$content = $scraper->filter('div.content')->count()
    ? $scraper->filter('div.content')->text()
    : 'No content found';

echo "Content: $content\n";
```

**Explanation**:
- `filter($selector)`: Uses CSS selectors to target elements (like `div.content`, `.header`, `#main`).
- `count()`: Checks if the element exists.
- `text()`: Gets the text inside the element.
- Powerful for custom scraping when built-in methods (`links()`, `images()`) aren’t enough.

---

## Plugins: Supercharging Your Scraper
Plugins are like apps you install on your phone to add new features. They let you extend **Advance PHP Scraper** without changing its core code.

### What Are Plugins?
A plugin is a PHP class that adds functionality, like rendering JavaScript pages or caching responses. Plugins live in `src/Plugins/custom/` and are managed via `plugins.json`. You can enable/disable them or create your own.

### Available Plugins
The library includes six plugins, each explained in detail in the [PLUGIN_README.md](PLUGIN_README.md). Here’s a quick overview:
- **HeadlessPlugin**: Scrapes JavaScript-rendered content (e.g., React apps).
- **AsyncPlugin**: Scrapes multiple URLs at once for speed.
- **NLPPlugin**: Extracts keywords and entities for text analysis.
- **DocumentPlugin**: Parses PDFs linked on pages.
- **CachePlugin**: Saves scraped data to reduce server load.
- **CloudPlugin**: Runs scraping tasks on AWS Lambda.

### How to Use Plugins
To use a plugin, enable it and call its methods. Example with `CachePlugin`:

```php
<?php
require 'vendor/autoload.php';
use AdvancePHPSraper\Core\Scraper;

$scraper = new Scraper();
$scraper->getPluginManager()->enablePlugin('CachePlugin');
$scraper->enableCache();
$scraper->go('https://example.com'); // Cached after first request
```

For a complete guide on plugins, including how to enable, disable, or create them, check out the [PLUGIN_README.md](PLUGIN_README.md).

---

## Configuration: Customizing Your Scraper
You can tweak the scraper’s settings to fit your needs, like adjusting a car’s mirrors before driving.

### Setting User Agent
The user agent tells servers who’s scraping (like showing your ID at a library). Default is a bot-like string, but you can mimic a browser:

```php
$scraper->setUserAgent('Mozilla/5.0 (Windows NT 10.0; Win64; x64) Chrome/91.0.4472.124');
```

### Adjusting Timeout
Set how long the scraper waits for a response:

```php
$scraper->setTimeout(30); // 30 seconds
```

### Following Redirects
Choose whether to follow HTTP redirects:

```php
$scraper->setFollowRedirects(true); // Follow redirects
```

### Using Constructor Configuration
Pass settings when creating the scraper:

```php
$scraper = new Scraper([
    'user_agent' => 'MyBot/1.0',
    'timeout' => 30,
    'follow_redirects' => true,
]);
```

**Explanation**:
- These settings make your scraper behave differently, like choosing a fast or cautious driving mode.
- Use them to avoid blocks, handle slow servers, or follow redirects.

---

## Testing: Ensuring Everything Works
The library comes with tests to make sure it works perfectly. Think of tests as a quality check, like tasting a cake before serving it.

### Running Tests
Install development dependencies:
```bash
composer install
```

Run tests:
```bash
vendor/bin/phpunit --configuration phpunit.xml
```

**Expected Output**:
```
PHPUnit 9.6.23 by Sebastian Bergmann and contributors.
.................... 20 / 20 (100%)
Time: 00:01.123, Memory: 10.00 MB
OK (20 tests, 30 assertions)
```

### Writing Your Own Tests
Add tests in `tests/`. Example for a custom method:

```php
<?php
namespace AdvancePHPSraper\Tests;
use AdvancePHPSraper\Core\Scraper;
use PHPUnit\Framework\TestCase;

class CustomTest extends TestCase
{
    public function testCustomMethod()
    {
        $scraper = new Scraper();
        $scraper->go('https://example.com');
        $this->assertNotEmpty($scraper->title());
    }
}
```

---

## Troubleshooting: Solving Common Problems
Even the best tools can hit snags. Here’s how to fix common issues:

### Installation Issues
- **Error: Composer not found**:
  Install Composer (see [Installation](#installation)).
- **Error: PHP version too low**:
  Upgrade to PHP 7.4+:
  ```bash
  sudo apt-get install php7.4
  ```

### Scraping Errors
- **Error: Could not resolve host**:
  Check your internet connection or URL spelling.
- **Error: HTTP 403 Forbidden**:
  Set a browser-like user agent:
  ```php
  $scraper->setUserAgent('Mozilla/5.0...');
  ```

### Plugin Problems
- **Plugin not loading**:
  Ensure `"enabled": true` in `plugins.json`.
- **Dependency missing**:
  Install required packages (e.g., `composer require symfony/panther`).

---

## Contributing: Joining the Community
Love the library? Help make it better! Contribute by fixing bugs, adding features, or improving docs. Read the [CONTRIBUTING.md](CONTRIBUTING.md) for a detailed guide.

---

## License: Understanding Usage Rights
**Advance PHP Scraper** is licensed under the MIT License, meaning you can use, modify, and share it freely. See the [LICENSE](LICENSE) file for details.

---

## Resources: Further Learning
- **PHP Basics**: [PHP The Right Way](https://phptherightway.com/)
- **Web Scraping**: [ScrapingBee Blog](https://www.scrapingbee.com/blog/)
- **Symfony BrowserKit**: [Symfony Docs](https://symfony.com/doc/current/components/browser_kit.html)
- **GitHub Repo**: [github.com/rajpurohithitesh/advance-phpscraper](https://github.com/rajpurohithitesh/advance-phpscraper)

---