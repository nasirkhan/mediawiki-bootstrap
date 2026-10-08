# Changelog

## v2.0.0 — The Complete Rewrite

**Released:** October 8, 2026 · **23 commits** · `3f4cb31…be87646`

---

### New Features

- **Complete skin foundation** — Bootstrap-based skin rebuilt from the ground up with a modern, componentised template structure (`3f4cb31`)
- **Page actions dropdown** — Collapsible dropdown for page-level actions with updated template data bindings (`8dd9a1e`)
- **Main page display title** — Administrators can set a custom display title for the main page via template (`b63d27f`)
- **Notification and user menu templates** — Dedicated template files for the notification bell and user account menu; navbar and main menu formatting updated to match (`467283a`)
- **Custom footer text** — Administrators can configure footer content via the `MediaWiki:` namespace without touching skin files (`4761f37`)
- **Category page support** — Category pages now render correctly with updated templates and category-specific styles (`5139f61`)
- **Anonymous user state** — Notification bell and user menu are conditionally hidden for logged-out visitors (`1d3968c`)
- **Login link in footer for anonymous visitors** — Logged-out users see a clear path to sign in from the footer (`6155ae5`)
- **Google Fonts preconnect links** — Preconnect hints added to `<head>` for faster font loading; `--font-family` variable updated (`be87646`)

### Changed

- **Code structure refactored** — Across-the-board cleanup for readability and long-term maintainability (`4d85aa7`)
- **Sidebar portlet checkbox hidden** — Removed the visible `<input type="checkbox">` from sidebar portlets for a cleaner UI (`8c3874b`)
- **Navbar layout reworked** — Three rounds of alignment and container class work; final structure uses correct Bootstrap container for responsive breakpoints (`6a05af0`, `886b48b`, `c4b05a6`)
- **Nav links set in small-caps** — Navbar links now use `font-variant: small-caps`; navbar container class adjusted (`73093fb`)
- **Bengali language switcher enhanced** — Language links now generated dynamically; switcher styling updated for legibility across viewport sizes (`c413a1d`)
- **Link style: small-caps, no underline** — Body links use `font-variant: small-caps` and rely on color alone for differentiation (`3ded728`)
- **Content heading and language links refactored** — Improved layout and landmark structure for the content header area (`8447818`)

### Removed

- **Bootstrap JavaScript bundle dropped** — `mediawikibootstrap.js` and the Bootstrap JS layer have been removed. Components are now handled natively or via MediaWiki's own module system (`e0474ef`)

### Documentation

- **Navigation menu and main page title** — README covers how to configure the navigation menu and override the main page display title (`f7cd2d6`)
- **Footer copyright notice** — README covers how to set a custom footer copyright message via the `MediaWiki:` namespace (`a35f75d`)

---

*Previous release: [v1.4.0](https://github.com/your-org/MediaWikiBootstrap/releases/tag/v1.4.0)*
