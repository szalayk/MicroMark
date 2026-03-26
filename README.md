# MicroMark CMS

**A blazing-fast, database-free, flat-file Markdown CMS for PHP.**

MicroMark is a minimalist content management system designed for developers and bloggers who value speed, simplicity, and security. It eliminates the overhead of traditional databases by using a JSON-based indexing engine to serve your Markdown content almost instantly.

## Why MicroMark?

- **Zero Database Overhead:** No MySQL, no complex backups. Your content lives in simple text files.
- **Blazing Performance:** The system generates a flat JSON index, meaning page routing and metadata lookups happen in milliseconds.
- **Developer-Centric:** Built with clean, vanilla PHP. No heavy frameworks, no unnecessary dependencies.
- **SEO & Clean URLs:** Built-in routing for beautiful, human-readable links.
- **Highly Portable:** Moving your site is as simple as copying a folder.

## Key Features

- **Markdown-First:** Write your posts in your favorite editor using standard Markdown.
- **Frontmatter Support:** Manage titles, dates, categories, and tags directly inside your files.
- **Taxonomy System:** Automatic indexing for categories and tags.
- **Themeable:** Simple PHP-based templating. No complex Liquid or Twig syntax to learn.
- **Build Dashboard:** A secure, web-based UI to monitor and trigger index updates.

## Directory Structure

```text
.
├── app/                # Core logic, Router, and Parsedown library
│   ├── build_index.php # CLI build trigger
│   └── config.php      # Main configuration file
├── cache/              # Generated JSON index (Must be writable)
├── content/            # Your content source
│   ├── pages/          # Static pages (e.g., about.md)
│   └── posts/          # Blog entries (e.g., my-first-post.md)
└── public/             # Document root (The only folder accessible via web)
    ├── assets/images   # Public images
    ├── themes/         # Your layout templates
    ├── index.php       # Entry point
    └── run-build.php   # Secure web-based build trigger
```

## Installation

1.  **Clone the repository:**
    ```bash
    git clone https://github.com/szalayk/MicroMark.git
    ```
2.  **Setup your Web Server:** Point your domain's document root to the `/public` directory.
3.  **Permissions:** Ensure the `/cache` directory is writable by the web server:
    ```bash
    chmod 775 cache
    ```
4.  **Configure:** Edit `app/config.php` to set your `SITE_URL` and `BUILD_KEY`.
5.  **Initial Build:** Run the indexer via CLI or browser:
      - **CLI:** `php app/build_index.php`
      - **Web:** `yourdomain.com/run-build.php?key=your_secret_key`

## Creating Content

MicroMark uses **Frontmatter** (YAML-style) at the top of Markdown files to manage metadata.

**Example Post (`content/posts/hello-world.md`):**

```markdown
---
title: Welcome to MicroMark
date: 2025-02-25
author: Your Name
category: general
tags: php, markdown, cms
---
# Hello World
This is my first post using MicroMark CMS!
```

## Theme System

Themes are located in `public/themes/[ThemeName]`.

- `layout.php`: The main wrapper for pages and posts.
- `list.php`: The template for category and tag listings.
- `partials/`: Reusable components like navigation or recent posts widgets.

## Security

- **Out-of-Root Core:** All sensitive logic and content files are located outside the `public` directory.
- **Build Protection:** The `run-build.php` script is protected by a mandatory `BUILD_KEY` parameter to prevent unauthorized server load.

## Requirements

- PHP 7.4 or higher.
- Apache (with `mod_rewrite`) or Nginx.

## Troubleshooting

### Permission Denied (index.json)
If you encounter a "Permission denied" error when running the build via browser, it's likely because the web server user doesn't have write access to the `cache/` folder.
**Fix:** Run `chmod 775 cache` (or `777` if necessary on shared hosting) or delete the manually generated `index.json` and let the web trigger create a new one with the correct owner permissions.

## License

This project is open-source and available under the MIT License.
