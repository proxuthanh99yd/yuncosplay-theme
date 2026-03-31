# Plan Figma-to-Code

## Overview

Analyze a Figma design and produce a detailed implementation plan for user review. The plan lists sections, components, typography specs, assets to download, fonts, and file structure. The user reviews and approves before building.

## Usage

```
/figma-plan [figma-url]
```

## Instructions

### Step 1 — Parse URL

Extract `fileKey` and `nodeId` (convert `-` to `:` in node-id param).

### Step 2 — Fetch Figma Data

Call the Figma MCP in parallel:

1. `get_design_context({ fileKey, nodeId })` — full structure + screenshot
2. `get_variable_defs({ fileKey, nodeId })` — design tokens
3. `get_code_connect_map({ fileKey, nodeId })` — existing mappings
4. `get_metadata({ fileKey, nodeId })` — structure overview

**CRITICAL: Only fetch data for the provided nodeId. NEVER navigate to parent frames, sibling frames, or the page root. Plan ONLY what the user gives you.**

If the metadata shows many direct children (indicating a large frame), also call `get_design_context` on key children to get detailed data.

### Step 3 — Determine Scope

Examine the fetched node to determine its scope:

**A. Full Page** — the node has many direct children at different Y positions, forming distinct visual sections (e.g., navbar, hero, footer). Typically height > 3000px with 5+ direct child frames.

**B. Single Section** — the node is one self-contained visual block (e.g., a hero banner, a product list, a footer). Typically one logical area.

**C. Single Component** — the node is a small reusable element (e.g., a button, a card, a nav item). Typically < 500px in both dimensions.

This scope determines the plan format (see Step 5).

### Step 4 — Analyze & Build Plan

From the Figma data, determine:

**A. Frame info:**
- Name (kebab-case slug)
- Design frame dimensions (width × height)
- rem divisor (typically `16` for default browser font-size, check `_variables.css` or `_reset.css` for custom base)
- **Viewport target:** `desktop` or `mobile` — determined by design width:
  - ≥ 1024px → `desktop` (common: 1440px, 1600px, 1920px)
  - < 1024px → `mobile` (common: 375px, 390px, 414px)
- **Layout strategy** (for full-page scope):
  - Identify which sections are **full-bleed** vs **contained**
  - Record the **content max-width**

**B. Components breakdown:**

For **Full Page** scope:
- List sections (direct children of root, ordered top-to-bottom by Y)
- For each section: name, nodeId, Figma URL, layout type, complexity, heading level, sub-components

For **Single Section** scope:
- List sub-components within the section
- For each: name, nodeId, what it does, whether it's repeated

For **Single Component** scope:
- List props/variants visible in the design
- Note interactive states if visible

**C. Common components** (reused across 2+ places):
- Buttons, cards, badges, etc. that appear multiple times
- Existing shared components from `template-parts/components/` to reuse

**D. Typography reference:**
Walk every text node, extract unique styles. For each unique style, list:
- Figma px size, font weight, line-height, letter-spacing, text-transform
- The equivalent CSS declarations
- Example:
  ```css
  font-size: 2.5rem;
  font-weight: 800;
  line-height: 1.5;
  letter-spacing: 0.0625rem;
  text-transform: uppercase;
  font-family: 'Phudu', sans-serif;
  ```

**E. Assets to download:**
- Images → `assets/images/{pageName}/` with naming (`d-` prefix for desktop, `m-` for mobile)
- Icons → `assets/images/{pageName}/ic-{name}.svg` or `assets/icons/`
- Logos → `assets/images/{pageName}/logo-{name}.svg`

**F. Fonts (must match Figma exactly):**
For each font family found in the design:
1. Check if already loaded in theme (`assets/fonts/stylesheet.css` or `import-css-js.php`)
2. Search Google Fonts: `https://fonts.google.com/?query={fontName}`
3. Search for downloadable .woff2 files
4. If custom/proprietary font:
   - Note the exact font to acquire from the design team
   - Find closest Google Font alternative
5. Record: family name, source (existing/google/local/custom), weights needed, loading method (`wp_enqueue_style` or `@font-face`)

**G. Asset Enqueue Plan:**
- Which CSS/JS files need to be registered in `import-css-js.php`
- Conditional loading (e.g., only on specific page templates)
- Dependencies (e.g., Swiper CSS/JS needed for carousel sections)

### Step 5 — Save Plan to File

Write the plan as a structured markdown file at:
- Full Page: `template-parts/{pageName}/PLAN.md`
- Single Section: `template-parts/{pageName}/PLAN.md`
- Single Component: `template-parts/components/PLAN.md`

### Step 6 — Present Plan to User

Use the appropriate format based on scope:

---

#### Format A: Full Page

```markdown
## Plan: {Page Name}

**Figma:** {url}
**Scope:** Full Page
**Design:** {width}×{height}px | rem divisor: {divisor} | Viewport: {desktop/mobile}
**Layout:** Content max-width: {contentWidth}px | Full-bleed sections: {list}
**Screenshot:** [embedded]

---

### Sections ({count})

| # | Section | Node ID | Layout | Complexity | Heading | Sub-templates |
|---|---------|---------|--------|------------|---------|---------------|
| 1 | Header | 18:20 | full-bleed | simple | — | mega-menu |
| 2 | Hero | 18:35 | full-bleed | medium | **h1** | hero-slider |
| ... | ... | ... | ... | ... | ... | ... |

### Common Components (shared across sections)

| Component | Used in | Source |
|-----------|---------|--------|
| animated-button | Hero, CTA | `template-parts/components/animated-button/` (existing) |
| product-card | Products, Featured | new — `template-parts/components/product-card/` |

### Typography ({count} unique styles)

| # | Figma | CSS | Used in |
|---|-------|-----|---------|
| 1 | 40px / 800 / 1.2 | `font-size: 2.5rem; font-weight: 800; line-height: 1.2; text-transform: uppercase;` | Hero |
| ... | ... | ... | ... |

### Assets to Download ({count})

| Type | Filename | Section | Figma Node |
|------|----------|---------|------------|
| image | d-hero-banner.webp | Hero | 341:100 |
| ... | ... | ... | ... |

### Fonts

| Family | Source | Weights | Load Method |
|--------|--------|---------|-------------|
| Phudu | Google Fonts (existing) | 300-900 | wp_enqueue_style |
| ... | ... | ... | ... |

### Asset Enqueue

| Handle | Type | File | Condition |
|--------|------|------|-----------|
| {pageName} | style | template-parts/{pageName}/assets/styles.css | is_page_template('{template}.php') |
| {pageName} | script | template-parts/{pageName}/assets/scripts.js | is_page_template('{template}.php') |

### Files to Create

```
template-parts/{pageName}/
├── index.php                          # Section orchestrator
├── assets/
│   ├── styles.css                     # Page-level styles (imports)
│   └── scripts.js                     # Page-level scripts
├── section-hero/
│   ├── index.php
│   └── assets/
│       ├── styles.css
│       └── scripts.js
├── section-about/
│   ├── index.php
│   └── assets/
│       └── styles.css
└── ...
```

**Total: ~{N} template files**

---

Ready to build? Run: `/figma-build`
```

---

#### Format B: Single Section

```markdown
## Plan: {Section Name}

**Figma:** {url}
**Scope:** Single Section
**Design:** {width}×{height}px | rem divisor: {divisor} | Viewport: {desktop/mobile}
**Layout type:** {full-bleed / contained}
**Screenshot:** [embedded]

---

### Component Breakdown

| # | Component | Node ID | Description | Repeated |
|---|-----------|---------|-------------|----------|
| 1 | section-hero (root) | 21:66 | Main section wrapper | — |
| 2 | hero-arrow | 2703:18737 | Left/right navigation arrows | ×2 |
| 3 | hero-indicator | 341:3812 | Dot indicators for slides | — |
| ... | ... | ... | ... | ... |

### Typography

| # | Figma | CSS | Element |
|---|-------|-----|---------|
| 1 | 40px / 800 / 1.5 | `font-size: 2.5rem; font-weight: 800; line-height: 1.5; letter-spacing: 0.0625rem; text-transform: uppercase;` | Main heading |
| ... | ... | ... | ... |

### Assets to Download

| Type | Filename | Figma Node |
|------|----------|------------|
| image | d-hero-banner.webp | 341:3648 |
| ... | ... | ... |

### Fonts

| Family | Source | Weights | Load Method |
|--------|--------|---------|-------------|
| ... | ... | ... | ... |

### Files to Create

```
template-parts/{pageName}/
  section-{name}/
    index.php                       # Main section template
    {sub-component}.php             # Sub-template (if complex)
    assets/
        styles.css
        scripts.js                  # Only if interactive
```

**Total: ~{N} files**

---

Ready to build? Run: `/figma-build`
```

---

#### Format C: Single Component

```markdown
## Plan: {Component Name}

**Figma:** {url}
**Scope:** Single Component
**Design:** {width}×{height}px | rem divisor: {divisor}
**Screenshot:** [embedded]

---

### Variants / States

| State | Description |
|-------|-------------|
| default | Normal state |
| hover | On mouse hover |
| active | On click |
| ... | ... |

### Typography

| # | Figma | CSS |
|---|-------|-----|
| ... | ... | ... |

### Assets

| Type | Filename | Figma Node |
|------|----------|------------|
| ... | ... | ... |

### Files to Create

```
template-parts/components/{component-name}/
    index.php
    assets/
        styles.css
        scripts.js                  # Only if interactive
```
```

---

## Rules

- **Do NOT generate any code** — this command only produces the plan.
- **Save the plan** to the appropriate `PLAN.md` location so `/figma-build` can read it.
- **NEVER navigate to parent frames.** Plan ONLY the exact node the user provides.
- Use actual Figma node IDs from the fetched data — never invent.
- Follow Smart Decomposition: only extract sub-components for repeated patterns (3+), complex interactive elements, or templates > 300 lines.
- Include Figma URL per component so each can be fetched individually during build.
- The plan is the contract — `/figma-build` follows it exactly.
- **Check existing components** in `template-parts/components/` before planning new ones.
- **Plan asset enqueue** — every new CSS/JS file needs registration in `import-css-js.php`.
