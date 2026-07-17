# Changelog

### 2026-05-18 — Fix Organization postal code schema

- **Files:** `inc/schema.php`, `docs/vault/project-snapshot.md`, `docs/vault/changelog.md`, WordPress ACF option data
- **Type:** fix
- **Note:** Add `postalCode` to Organization/LocalBusiness `PostalAddress` output and verify rendered JSON-LD.

### 2026-05-18 — Fix Organization image schema

- **Files:** `inc/schema.php`, `docs/vault/project-snapshot.md`, `docs/vault/changelog.md`, WordPress ACF option data
- **Type:** fix
- **Note:** Add business image ACF field and use it as Organization/LocalBusiness `image` and `logo` fallback.

### 2026-05-18 — Fix production empty schema type node

- **Files:** `inc/schema.php`, `docs/vault/project-snapshot.md`, `docs/vault/changelog.md`
- **Type:** fix
- **Note:** Remove Rank Math JSON-LD graph nodes with missing or empty `@type` to fix production validator error `#schema-4281`.

### 2026-05-18 — Validate rendered JSON-LD with Playwright

- **Files:** `inc/schema.php`, `docs/vault/project-snapshot.md`, `docs/vault/changelog.md`
- **Type:** fix
- **Note:** Browser-tested rendered JSON-LD, removed Rank Math duplicate/mismatched nodes, fixed FAQPage output, and confirmed no parse errors/duplicates on key URLs.

### 2026-05-18 — Create Site Business Schema ACF options

- **Files:** `inc/schema.php`, `docs/vault/project-snapshot.md`, `docs/vault/changelog.md`, WordPress ACF option data
- **Type:** feat
- **Note:** Add MCP-created ACF options page/field group for structured business schema data and refactor LocalBusiness schema to read option fields.

### 2026-05-18 — Fix remaining schema edge cases

- **Files:** `inc/schema.php`, `docs/vault/project-snapshot.md`, `docs/vault/changelog.md`
- **Type:** fix
- **Note:** Omit empty AggregateOffer nodes and normalize missing WooCommerce shop page IDs before building schema URLs.

### 2026-05-18 — Fix product and local SEO schema

- **Files:** `inc/schema.php`, `docs/vault/project-snapshot.md`, `docs/vault/changelog.md`
- **Type:** fix
- **Note:** Align Product offers with rental pricing, add variable product AggregateOffer, improve LocalBusiness data, and canonicalize Search schema URLs.

### 2026-05-18 — Implement page-level Rank Math schema

- **Files:** `inc/schema.php`, `docs/vault/project-snapshot.md`, `docs/vault/changelog.md`
- **Type:** feat
- **Note:** Add JSON-LD coverage for Home, About, Contact, FAQs, Blog listing, Product archive/category, and Search via Rank Math graph override.

### 2026-05-18 — Fix schema breadcrumb page targets

- **Files:** `inc/schema.php`, `rank-math.php`, `docs/vault/project-snapshot.md`, `docs/vault/changelog.md`
- **Type:** fix
- **Note:** Use real blog page `/blogs` and WooCommerce shop page for schema and Rank Math breadcrumbs instead of stale hardcoded IDs.

### 2026-05-18 — Implement Rank Math schema override

- **Files:** `inc/schema.php`, `functions.php`, `rank-math.php`, `SCHEMA-SEO-PLAN.md`
- **Type:** feat
- **Note:** Override Rank Math JSON-LD graph for blog Article/Breadcrumb and product Product/Offer/Breadcrumb schema.
