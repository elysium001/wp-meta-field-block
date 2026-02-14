# WP Meta Field Block

A WordPress Gutenberg block that enables dynamic meta field templating. Create custom HTML with placeholder syntax that automatically renders post meta fields, ACF fields, and custom fields at the post level.

## Features

- 🎨 **Custom HTML Templates** - Write any HTML structure with dynamic field placeholders
- 🔄 **Live Preview Toggle** - Switch between edit and preview modes in the block editor
- 🎯 **Multiple Field Sources** - Support for ACF, custom meta, and standard post fields
- 🚀 **Server-Side Rendering** - Dynamic content rendered on the frontend, not in the editor
- 🛡️ **Secure Output** - All field values are sanitized before rendering

## Placeholder Syntax

Use these placeholder formats in your HTML templates:
```html
{field_name}           <!-- Standard post meta field -->
{acf:field_name}       <!-- Advanced Custom Fields (ACF) field -->
{meta:field_name}      <!-- Custom post meta field -->
```

## Installation

1. Download or clone this repository into your WordPress plugins directory:
```bash
   cd wp-content/plugins
   git clone https://github.com/elysium001/wp-meta-field-block.git
```

2. Install dependencies and build the block:
```bash
   cd wp-meta-field-block
   npm install
   npm run build
```

3. Activate the plugin in WordPress Admin → Plugins

## Usage

### Basic Example

1. Add the "Custom Field Block" to your post or page
2. Enter your HTML template with placeholders:
```html
<div class="author-bio">
  <h3>{acf:author_name}</h3>
  <p>{acf:bio}</p>
  <a href="{meta:website_url}">{meta:website_url}</a>
</div>
```

3. Toggle preview to see how it will render with actual field values
4. Publish and view on the frontend

### Advanced Example

Create a custom product card with ACF fields:
```html
<div class="product-card">
  <img src="{acf:product_image}" alt="{acf:product_name}">
  <h2>{acf:product_name}</h2>
  <p class="price">${acf:price}</p>
  <p class="description">{acf:description}</p>
  <span class="sku">SKU: {meta:product_sku}</span>
</div>
```

## How It Works

### Architecture

The plugin uses a two-part system:

1. **Block Editor (edit.js)** - Provides the interface for editing templates and previewing content
2. **Custom Field Handler (render.php + Custom_Field class)** - Processes placeholders on the frontend

### Rendering Flow
```
User creates template → Block saves to post content → Frontend render
→ Custom_Field class processes placeholders → Output with real values
```

### Key Files

- **`render.php`** - Server-side rendering logic for the block
- **`inc/class-custom-field.php`** - Core placeholder processing engine
- **`edit.js`** - Block editor React component
- **`block.json`** - Block metadata and configuration

### The Magic: Custom_Field Class

The `Custom_Field` class handles all placeholder processing:
```php
// Pattern matching for placeholders
'/\{([a-zA-Z_][a-zA-Z0-9_:]*)\}/'

// Supports three formats:
// {field_name}      → get_post_meta()
// {acf:field_name}  → get_field() (ACF)
// {meta:field_name} → get_post_meta()
```

**Security Features:**
- Admin context detection (placeholders not processed in editor)
- `esc_html()` sanitization on all output values
- Array values converted to comma-separated strings
- Non-scalar values filtered out

## Development

### Build Commands
```bash
# Development mode with watch
npm run start

# Production build
npm run build
```

### File Structure
```
wp-meta-field-block/
├── build/                      # Compiled assets
│   └── wp-meta-field-block/
│       ├── block.json
│       ├── edit.js
│       └── ...
├── src/                        # Source files
│   └── wp-meta-field-block/
│       └── edit.js
├── inc/
│   └── class-custom-field.php  # Core field processing logic
├── gnosis-blocks.php           # Main plugin file
├── package.json
└── README.md
```

## Requirements

- WordPress 6.0 or higher
- PHP 7.4 or higher
- Node.js 14+ (for development)
- Optional: Advanced Custom Fields (ACF) plugin for `{acf:}` placeholders

## Roadmap

- [ ] Support for nested ACF fields (repeaters, groups)
- [ ] Conditional placeholder logic (`{if:field_name}`)
- [ ] Custom formatting options (`{acf:price|currency}`)
- [ ] Field validation in editor
- [ ] Export/import template library

## Contributing

Contributions are welcome! Please:

1. Fork the repository
2. Create a feature branch (`git checkout -b feature/amazing-feature`)
3. Commit your changes (`git commit -m 'Add amazing feature'`)
4. Push to the branch (`git push origin feature/amazing-feature`)
5. Open a Pull Request

## License

GPL-2.0+ - see [LICENSE](LICENSE) file for details

## Author

**WebTech Gnosis**
- Website: [webtechgnosis.com](https://webtechgnosis.com)

## Support

- 🐛 [Report bugs](https://github.com/elysium001/wp-meta-field-block/issues)
- 💡 [Request features](https://github.com/elysium001/wp-meta-field-block/issues)
- 📖 [Documentation](https://github.com/elysium001/wp-meta-field-block/wiki)

---

Made with ❤️ for the WordPress community