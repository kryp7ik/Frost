# Playwright E2E Test Coverage

> Updated 2026-04-06 | Frost POS — Laravel 13 + Inertia v2 + Vue 3 + Vuetify

---

## Coverage Summary

| Metric | Count |
|---|---|
| Vue page components | 44 |
| HTTP routes | 150+ |
| Interactive actions (forms, buttons, AJAX) | 80+ |
| E2E test files | 12 (incl. auth setup) |
| E2E test cases | 66 |
| Pages with smoke coverage | 29/44 |
| Pages with interaction tests | 9/44 |
| Actions with E2E coverage | ~30/80+ |

**Overall interaction coverage: ~38%**

---

## Existing Test Files

| File | Tests | What It Covers |
|---|---|---|
| `auth.setup.ts` | 1 | Login flow, session persistence |
| `smoke.spec.ts` | 29 | Page-load + testid visibility for 29 routes |
| `dashboard.spec.ts` | 2 | Dashboard renders announcements-card & shifts-card |
| `admin/users.spec.ts` | 3 | Index lists users, create form renders, full create flow |
| `admin/roles.spec.ts` | 2 | Index lists roles, create form renders |
| `admin/ingredients.spec.ts` | 2 | Index lists data, dialog create flow |
| `admin/discounts.spec.ts` | 2 | Index lists data, dialog create flow |
| `orders/index.spec.ts` | 5 | Table renders, date filter, new order, status chips, view button |
| `orders/create-flow.spec.ts` | 6 | New order redirects to ShowOpen, all cards, empty state, add product/liquid forms visible |
| `orders/manage-order.spec.ts` | 6 | Set customer, no customer state, add/remove discount, delete/dismiss |
| `orders/payment.spec.ts` | 4 | Cash payment, overpay change, new order link, empty order rejection |
| `orders/closed-order.spec.ts` | 4 | Closed card renders, items+payments, heading, back button |

---

## Bugs Found & Fixed During E2E Testing

| Bug | File | Fix |
|---|---|---|
| Order creation crashes with "Undefined array key 'liquids'" when no liquids passed | `EloquentLiquidProductRepository.php:194` | `$data['liquids']` → `$data['liquids'] ?? []` |
| Order creation crashes with "Undefined array key 'products'" when no products passed | `EloquentShopOrderRepository.php:142` | `$data['products']` → `$data['products'] ?? []` |
| Order show page crashes with "Attempt to read property 'id' on array" | `ShopOrderController.php:114` | `getAll(true)` returned nested arrays; changed to `getAll()` for flat model collection |

### Broken Actions Fixed (UI rebuilt from Blade→Vue migration)

| Action | Backend Route | Status |
|---|---|---|
| **Add product to order** | `POST /orders/{id}/add-product` | FIXED — autocomplete + qty form in ShowOpen |
| **Add liquid to order** | `POST /orders/{id}/add-liquid` | FIXED — recipe/size/nicotine/VG/menthol/salt/extra form in ShowOpen |
| **Quantity update** | `GET /orders/{id}/quantity-update` | FIXED — +/- buttons per product |
| **Duplicate liquid** | `GET /orders/duplicate-liquid/{id}` | FIXED — copy button per liquid |
| **Email receipt** | `POST /orders/email-receipt` | FIXED — email button on Receipt page |
| **Delete payment** | `GET /orders/payment/{id}/delete` | FIXED — manager-only delete per payment |
| **Last liquid (reorder)** | `GET /orders/{id}/last-liquid` | NOT BUILT — customer's previous liquid quick-reorder |

### Other Fixes & Changes

| Change | Detail |
|---|---|
| Removed `Orders/Create.vue` page | "New Order" nav/button now directly creates an order and redirects to ShowOpen (no intermediate page) |
| Removed `POST /orders/create` route | No longer needed since `GET /orders/create` handles creation |
| Added pending orders sidebar | Collapsible drawer in AppLayout showing incomplete orders for the store |
| Enriched `suspendedOrders` shared data | Added `customer_name` and `created_at` fields |
| Integrated FullCalendar on Schedule page | Weekly time-grid with drag-and-drop, shift create/delete dialogs |

---

## Known Issues

| Issue | Page | Detail |
|---|---|---|
| Sales report returns 500 | `/admin/store/report/sales` | Pre-existing server error, not related to migration |

---

## Page-by-Page Coverage

Legend:
- [x] Tested (has interaction-level E2E test)
- [~] Smoke only (page loads, testid visible — no interactions)
- [ ] No coverage

---

### Auth

#### Login (`/users/login`) — `Auth/Login.vue`
- [x] Email + password form submission (auth.setup.ts)
- [ ] Validation errors (wrong password, missing fields)
- [ ] Remember-me checkbox behavior
- [ ] Redirect after login

---

### Dashboard (`/`) — `Dashboard.vue`
- [x] Announcements card renders (dashboard.spec.ts)
- [x] Shifts card renders (dashboard.spec.ts)
- [ ] Announcement content displays correctly
- [ ] Shifts show correct employee data

---

### Account

#### Edit (`/account/edit`) — `Account/Edit.vue`
- [~] Page loads
- [ ] Fill name, email, save → success
- [ ] Change password (new + confirm)
- [ ] Validation errors (mismatched password, invalid email)
- [ ] Cancel navigates back to dashboard

#### Two-Factor (`/account/two-factor`) — `Profile/TwoFactor.vue`
- [~] Page loads (no testid assertion)
- [ ] Enable 2FA → QR code displayed
- [ ] Confirm code → success
- [ ] Show recovery codes
- [ ] Regenerate recovery codes
- [ ] Disable 2FA

---

### Announcements

#### Index (`/announcements`) — `Announcements/Index.vue`
- [~] Page loads
- [ ] List renders seeded announcements
- [ ] Sticky announcements appear first
- [ ] "New Announcement" link navigates to create
- [ ] Edit link navigates to edit page
- [ ] Delete button with confirmation removes announcement

#### Create (`/announcements/create`) — `Announcements/Create.vue`
- [~] Page loads
- [ ] Fill title, type, content, sticky → submit → redirect to index
- [ ] Validation errors (missing title)
- [ ] Cancel navigates back

#### Edit (`/announcements/{id}/edit`) — `Announcements/Edit.vue`
- [ ] No coverage (no smoke test for dynamic route)
- [ ] Pre-populated form loads
- [ ] Update fields → submit → redirect
- [ ] Validation errors

---

### Customers

#### Index (`/customers`) — `Customers/Index.vue`
- [~] Page loads
- [ ] Data table lists seeded customers
- [ ] "New Customer" opens dialog
- [ ] Fill name, phone, email → submit → appears in table
- [ ] Validation errors (missing phone)
- [ ] View button navigates to show page

#### Show (`/customers/{id}/show`) — `Customers/Show.vue`
- [ ] No coverage (dynamic route)
- [ ] Customer details render
- [ ] Order history table renders
- [ ] Back button navigates to index

---

### Orders

#### Index (`/orders`) — `Orders/Index.vue` — `index.spec.ts`
- [x] Data table renders with seeded data
- [x] Date filter input present and pre-filled with today
- [x] "New Order" button creates order and opens ShowOpen
- [x] Status chips show Complete or Open
- [x] View button navigates to order detail (ShowOpen or ShowClosed)

#### New Order (`/orders/create`) — no page (redirects directly)
- [x] `GET /orders/create` creates order → redirects to ShowOpen (create-flow.spec.ts)
- [x] ShowOpen renders all management cards (items, discounts, customer, total, payment, delete)
- [x] New order shows "No items yet" empty state
- [x] New order shows $0.00 total
- [x] Add product form (autocomplete + qty) is visible
- [x] Add liquid form (recipe, size, nicotine, VG, menthol, salt, extra) is visible

#### ShowOpen (`/orders/{id}/show`) — `Orders/ShowOpen.vue` — `manage-order.spec.ts`
- [x] **Set customer** by phone → customer info with points displays
- [x] Fresh order shows "No customer attached"
- [x] **Add discount** from dropdown → discount appears in list
- [x] **Remove discount** → discount disappears from list
- [x] **Delete order** with confirmation → redirects to new order
- [x] Dismiss delete confirmation → stays on order page
- [ ] **Add product** via UI form (autocomplete + qty + submit)
- [ ] **Add liquid** via UI form (recipe + options + submit)
- [ ] **Quantity update** (+/- buttons)
- [ ] **Duplicate liquid** (copy button)
- [ ] **Delete payment** (manager-only)

#### Payment & Receipt — `payment.spec.ts`
- [x] Cash payment on order with product → redirects to receipt
- [x] Overpayment shows change due alert on receipt
- [x] Receipt "New Order" link creates order and opens ShowOpen
- [x] Payment on empty order stays on ShowOpen (does not redirect)
- [ ] Email receipt button

#### ShowClosed (`/orders/{id}/show`) — `Orders/ShowClosed.vue` — `closed-order.spec.ts`
- [x] Completed order renders ShowClosed with order-closed-card
- [x] Closed order displays items, Total, and Payments
- [x] Heading shows "(Closed)" label
- [x] Back button navigates to orders index

---

### Pending Orders Sidebar — `AppLayout.vue`
- [ ] "Pending" badge appears in navbar when incomplete orders exist
- [ ] Clicking badge opens right-side drawer
- [ ] Drawer lists orders with ID, total, customer, relative time
- [ ] Clicking an order navigates to ShowOpen and closes drawer
- [ ] Backdrop click closes drawer

---

### Schedule

#### Home (`/schedule`) — `Schedule/Home.vue` — FullCalendar integration
- [~] Page loads (smoke test)
- [ ] Calendar renders in weekly time-grid view
- [ ] Seeded shifts display as colored blocks
- [ ] Drag-and-drop moves a shift (updates via `PUT /shift/{id}`)
- [ ] Resize changes shift duration
- [ ] Click+drag on empty space opens create dialog
- [ ] Create dialog: select employee → creates shift
- [ ] Click existing shift opens delete dialog
- [ ] Delete dialog: confirm → removes shift
- [ ] Employee legend renders with colors
- [ ] View toggle: week / day / month

#### Warning (`/warning`) — `Schedule/Warning.vue`
- [ ] No coverage
- [ ] Page renders warning content

---

### Admin Home (`/admin`) — `Admin/Home.vue`
- [~] Page loads
- [ ] All 11 navigation cards render and link correctly

---

### Admin — Users

#### Index (`/admin/users`) — `Admin/Users/Index.vue`
- [x] Table lists seeded users (users.spec.ts)
- [ ] "Trashed" toggle filters soft-deleted users
- [ ] Delete button with confirmation soft-deletes user
- [ ] Restore button restores trashed user
- [ ] Edit button navigates to edit page

#### Create (`/admin/users/create`) — `Admin/Users/Create.vue`
- [x] Full create flow tested (users.spec.ts)
- [ ] Validation errors (duplicate email, weak password)
- [ ] Multiple role assignment

#### Edit (`/admin/users/{id}/edit`) — `Admin/Users/Edit.vue`
- [ ] No coverage (dynamic route)
- [ ] Pre-populated form loads
- [ ] Update name, email, store, roles → submit → redirect
- [ ] Password change (optional)
- [ ] Validation errors

---

### Admin — Roles

#### Index (`/admin/roles`) — `Admin/Roles/Index.vue`
- [x] Table lists seeded roles (roles.spec.ts)
- [ ] "New Role" link navigates to create

#### Create (`/admin/roles/create`) — `Admin/Roles/Create.vue`
- [~] Form renders (roles.spec.ts — visibility only)
- [ ] Full create flow (fill name, display name, description → submit)
- [ ] Validation errors
- [ ] Cancel navigates back

---

### Admin — Products

#### Home (`/admin/store/products`) — `Admin/Store/Products/Home.vue`
- [ ] No smoke test for this route
- [ ] Three navigation cards render and link correctly

#### Index (`/admin/store/products/index`) — `Admin/Store/Products/Index.vue`
- [~] Page loads
- [ ] Data table lists seeded products
- [ ] "New Product" link navigates to create
- [ ] View button navigates to show
- [ ] Edit button navigates to edit
- [ ] Cost displays as currency

#### Create (`/admin/store/products/create`) — `Admin/Store/Products/Create.vue`
- [~] Page loads
- [ ] Fill name, SKU, category, cost → submit → redirect
- [ ] Validation errors
- [ ] Cancel navigates back

#### Edit (`/admin/store/products/{id}/edit`) — `Admin/Store/Products/Edit.vue`
- [ ] No coverage (dynamic route)
- [ ] Pre-populated form loads
- [ ] Update fields → submit → redirect
- [ ] Validation errors

#### Show (`/admin/store/products/{id}/show`) — `Admin/Store/Products/Show.vue`
- [ ] No coverage (dynamic route)
- [ ] Product details render
- [ ] Instances table with store-specific pricing
- [ ] Edit instance link navigates correctly
- [ ] Edit product link navigates correctly
- [ ] Back button works

#### EditInstance (`/admin/store/products/instance/{id}/edit`) — `Admin/Store/Products/EditInstance.vue`
- [ ] No coverage (dynamic route)
- [ ] Pre-populated form (price, stock, redline, active)
- [ ] Update fields → submit → redirect
- [ ] Validation errors

#### Redline (`/admin/store/products/redline`) — `Admin/Store/Products/Redline.vue`
- [~] Page loads
- [ ] Low-stock products display in table
- [ ] Back button navigates to products index

---

### Admin — Ingredients

#### Index (`/admin/store/ingredients`) — `Admin/Store/Ingredients/Index.vue`
- [x] Table lists data (ingredients.spec.ts)
- [x] Dialog create flow (ingredients.spec.ts)
- [ ] Edit button navigates to edit page
- [ ] Validation errors on create (missing name/vendor)

#### Edit (`/admin/store/ingredients/{id}/edit`) — `Admin/Store/Ingredients/Edit.vue`
- [ ] No coverage (dynamic route)
- [ ] Pre-populated form loads
- [ ] Update fields → submit → redirect
- [ ] Cancel navigates back

---

### Admin — Recipes

#### Index (`/admin/store/recipes`) — `Admin/Store/Recipes/Index.vue`
- [~] Page loads
- [ ] Data table lists seeded recipes
- [ ] Active status icon displays correctly
- [ ] View button navigates to show
- [ ] "New Recipe" link navigates to create

#### Create (`/admin/store/recipes/create`) — `Admin/Store/Recipes/Create.vue`
- [~] Page loads
- [ ] Fill name, SKU → submit → redirect
- [ ] Validation errors
- [ ] Cancel navigates back

#### Show (`/admin/store/recipes/{id}/show`) — `Admin/Store/Recipes/Show.vue`
- [ ] No coverage (dynamic route)
- [ ] Recipe details render
- [ ] Ingredients list renders
- [ ] **Add ingredient** (select + amount + add button)
- [ ] **Remove ingredient** (delete button per row)
- [ ] Back button works

---

### Admin — Discounts

#### Index (`/admin/store/discounts`) — `Admin/Store/Discounts/Index.vue`
- [x] Table lists data (discounts.spec.ts)
- [x] Dialog create flow (discounts.spec.ts)
- [ ] Edit button navigates to edit page
- [ ] Type/filter/approval/redeemable display correctly
- [ ] Conditional point value field (redeemable toggle)

#### Edit (`/admin/store/discounts/{id}/edit`) — `Admin/Store/Discounts/Edit.vue`
- [ ] No coverage (dynamic route)
- [ ] Pre-populated form loads
- [ ] Update fields → submit → redirect
- [ ] Redeemable toggle shows/hides point value field
- [ ] Cancel navigates back

---

### Admin — Shipments

#### Index (`/admin/store/shipments`) — `Admin/Store/Shipments/Index.vue`
- [~] Page loads
- [ ] Data table lists seeded shipments
- [ ] "New Shipment" link navigates to create
- [ ] View button navigates to show

#### Create (`/admin/store/shipments/create`) — `Admin/Store/Shipments/Create.vue`
- [~] Page loads
- [ ] **Add line** button adds dynamic row
- [ ] Select product instance + quantity per line
- [ ] **Remove line** button removes row
- [ ] Submit with lines → redirect to index
- [ ] Submit disabled when no lines
- [ ] Cancel navigates back

#### Show (`/admin/store/shipments/{id}/show`) — `Admin/Store/Shipments/Show.vue`
- [ ] No coverage (dynamic route)
- [ ] Shipment details and contents render
- [ ] Back button works

---

### Admin — Transfers

#### Index (`/admin/store/transfers`) — `Admin/Store/Transfers/Index.vue`
- [~] Page loads
- [ ] Data table lists transfers with status chips
- [ ] "New Transfer" link navigates to create
- [ ] View button navigates to show
- [ ] **Receive button** with confirmation marks transfer received

#### Create (`/admin/store/transfers/create`) — `Admin/Store/Transfers/Create.vue`
- [~] Page loads
- [ ] Select from/to stores
- [ ] **Add line** button adds dynamic row
- [ ] Select product instance + quantity per line
- [ ] **Remove line** button removes row
- [ ] Submit → redirect to index
- [ ] Cancel navigates back

#### Show (`/admin/store/transfers/{id}/show`) — `Admin/Store/Transfers/Show.vue`
- [ ] No coverage (dynamic route)
- [ ] Transfer details and status render
- [ ] Contents list renders
- [ ] Back button works

---

### Admin — Inventory

#### Create (`/admin/store/inventory/create`) — `Admin/Store/Inventory/Create.vue`
- [~] Page loads
- [ ] Product groups render with count inputs
- [ ] Fill counts → submit → processes inventory reconciliation
- [ ] Back button works

---

### Admin — Touch

#### Touch (`/admin/store/touch`) — `Admin/Store/Touch/Touch.vue`
- [~] Page loads
- [ ] Pending liquids list loads (polling)
- [ ] Select liquids via checkboxes
- [ ] **Complete** button marks selected as done
- [ ] Recently completed list renders
- [ ] **Undo** button reverts completion
- [ ] 15-second auto-refresh polling works

---

### Admin — Reports

#### Sales (`/admin/store/report/sales`) — `Admin/Store/Report/Sales.vue`
- **BROKEN** — returns 500 (pre-existing server error)
- [ ] Set start/end dates, store, report type
- [ ] **Run Report** button fetches results
- [ ] Results card renders report data

#### Inventory (`/admin/store/report/inventory`) — `Admin/Store/Report/Inventory.vue`
- [~] Page loads
- [ ] Select store filter
- [ ] **Run Report** button fetches results
- [ ] Results card renders report data

---

## Pages With No Coverage At All

These pages have no smoke test and no interaction test (dynamic routes not in smoke suite):

| Page | Route Pattern |
|---|---|
| Products Home | `/admin/store/products` |
| Product Show | `/admin/store/products/{id}/show` |
| Product Edit | `/admin/store/products/{id}/edit` |
| Product EditInstance | `/admin/store/products/instance/{id}/edit` |
| Ingredient Edit | `/admin/store/ingredients/{id}/edit` |
| Recipe Show | `/admin/store/recipes/{id}/show` |
| Discount Edit | `/admin/store/discounts/{id}/edit` |
| Shipment Show | `/admin/store/shipments/{id}/show` |
| Transfer Show | `/admin/store/transfers/{id}/show` |
| Customer Show | `/customers/{id}/show` |
| Announcement Edit | `/announcements/{id}/edit` |
| User Edit | `/admin/users/{id}/edit` |
| Schedule Warning | `/warning` |

---

## Priority Actions — Untested Interactions

### P1 — CRUD Completeness
1. **Products** — create, edit, show, edit instance (price/stock/redline)
2. **Recipes** — create, show, add/remove ingredients
3. **Customers** — create via dialog, view show page
4. **Announcements** — create, edit, delete with confirmation
5. **Users** — edit, soft-delete, restore, trashed toggle
6. **Roles** — full create flow (currently visibility-only)

### P2 — Order UI Interactions (UI built, tests needed)
7. **Add product** via ShowOpen form → product appears in items list
8. **Add liquid** via ShowOpen form → liquid appears in items list
9. **Quantity +/-** buttons update quantity
10. **Duplicate liquid** button creates copy
11. **Delete payment** (manager-only)
12. **Email receipt** button
13. **Pending orders sidebar** — toggle, list, navigate

### P3 — Admin Operations
14. **Shipments** — create with dynamic lines, view show page
15. **Transfers** — create with dynamic lines, receive confirmation
16. **Inventory** — fill counts and submit reconciliation
17. **Touch** — select liquids, complete, undo
18. **Reports** — fix sales report 500 error, test filters
19. **Schedule** — calendar interactions (drag-and-drop, create/delete shifts)

### P4 — Account & Auth
20. **Account edit** — update name/email/password
21. **Two-factor auth** — enable, confirm, recovery codes, disable
22. **Login validation** — wrong credentials, missing fields

### P5 — Edge Cases & Validation
23. Form validation errors across all forms
24. Authorization (manager-only pages accessed by non-manager)
25. Data table pagination and sorting
26. Confirmation dialogs (delete actions)

---

## Recommended Test File Structure

```
tests/e2e/
  auth.setup.ts                (exists)
  smoke.spec.ts                (exists)
  dashboard.spec.ts            (exists)
  login.spec.ts                (new — validation, redirect)
  account.spec.ts              (new — edit profile, 2FA)
  announcements.spec.ts        (new — CRUD)
  customers.spec.ts            (new — create, show)
  schedule.spec.ts             (new — calendar interactions, create/delete shifts)
  pending-orders.spec.ts       (new — sidebar toggle, list, navigate)
  orders/
    index.spec.ts              (exists)
    create-flow.spec.ts        (exists)
    manage-order.spec.ts       (exists — expand with add product/liquid via UI)
    payment.spec.ts            (exists — expand with email receipt)
    closed-order.spec.ts       (exists)
  admin/
    users.spec.ts              (exists — expand with edit/delete/restore)
    roles.spec.ts              (exists — expand with create flow)
    ingredients.spec.ts        (exists — expand with edit)
    discounts.spec.ts          (exists — expand with edit)
    products/
      crud.spec.ts             (new — create, edit, show)
      instances.spec.ts        (new — edit instance pricing)
      redline.spec.ts          (new — low stock display)
    recipes.spec.ts            (new — create, show, add/remove ingredients)
    shipments.spec.ts          (new — create with lines, show)
    transfers.spec.ts          (new — create with lines, show, receive)
    inventory.spec.ts          (new — count submission)
    touch.spec.ts              (new — select, complete, undo)
    reports.spec.ts            (new — fix 500, sales + inventory with filters)
```
