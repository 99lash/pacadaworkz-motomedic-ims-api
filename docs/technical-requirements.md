# Features with User Stories

## Authentication
- #### Login
  - As a `user` I can sign in with username/email and password
  - As a `user` I can sign in with Google (OAuth)
  
- #### Logout
  - As a `user` I can end my session via logout button

---

## Dashboard
- As an `admin`/`superadmin` I can see:
  - **KPIs:**
    - Total Products
    - Total Sales (amount)
    - Total Revenue
    - Low Stock Alerts count
    - Out of Stock Items count
    - Total Transactions/Orders
    - Active Users count
  
  - **Analytics & Charts:**
    - Sales & Revenue Trend Chart (line/bar)
    - Top Selling Products (top 5-10)
    - Revenue by Category (pie/bar chart)
    - Inventory Value
    - Recent Activities (last 10 activities)
  
  - **Quick Actions:**
    - Create new product (button)
    - View transactions (button)
    - Generate reports (button)
    - Manage users (button)

- As a `staff` I can see:
  - **Task-oriented Widgets:**
    - Today's Sales (my sales only)
    - My Total Sales (cumulative)
    - Low Stock Alerts
    - Recent Activities (my activities)

---

## Inventory Management
- As a `user` I can view current inventory/stock levels
- As an `admin`/`superadmin` I can:
  - View all inventory with search/filter
  - See stock movement history per product
  - View low stock alerts list
  - View out of stock items list
  - Export inventory report

- #### Stock Adjustments
  - As a `user` I can record stock adjustments with reason:
    - Damaged goods
    - Expired items
    - Lost/Stolen
    - Customer returns
    - Supplier returns
    - Physical count correction
    - Other (with notes)
  - As a `user` I can view stock adjustment history
  - As an `admin`/`superadmin` I can approve/reject adjustments (optional)

---

## Product Management
- As an `admin`/`superadmin` I can:
  - Create new product with details:
    - Product name, SKU, barcode
    - Category, brand, attributes
    - Description
    - Cost price, selling price
    - Reorder point, minimum stock level
    - Supplier
    - Product image
  - View product list (grid/table view)
  - Search and filter products
  - Edit product details
  - Delete product (soft delete)
  - View product stock history
  - Import products (CSV/Excel) - optional
  - Export products (CSV/Excel)

---

## Category Management
- As an `admin`/`superadmin` I can:
  - Create product categories (e.g., Genuine Parts, Accessories, Power Products, Helmets, Apparel)
  - View list of categories
  - Edit category details
  - Delete category (with validation - no products assigned)
  - See product count per category

---

## Brand Management
- As an `admin`/`superadmin` I can:
  - Create product brands (e.g., Yamaha, Honda, Suzuki)
  - View list of brands
  - Edit brand details
  - Delete brand (with validation)
  - See product count per brand

---

## Attribute Management
- As an `admin`/`superadmin` I can:
  - Create product attributes (e.g., Size: S/M/L/XL, Color: Red/Blue/Black)
  - Create attribute values
  - View list of attributes
  - Edit attribute details
  - Delete attribute (with validation)
  - Assign attributes to products

---

## Supplier Management
- As an `admin`/`superadmin` I can:
  - Create supplier with details:
    - Company name
    - Contact person
    - Phone, email
    - Address
    - Payment terms
  - View list of suppliers
  - Edit supplier details
  - Delete supplier (with validation)
  - See purchase history per supplier

---

## User Management
- As a `superadmin` I can:
  - Create admin user or staff user
  - Assign roles to users
  
- As an `admin` I can:
  - Create staff user only
  - Assign staff role

- As an `admin`/`superadmin` I can:
  - View list of all users
  - Search and filter users
  - Edit user details
  - Deactivate/activate user account
  - Reset user password
  - View user activity history

---

## Role Management
- As an `admin`/`superadmin` I can:
  - Create new role
  - View list of roles
  - Edit role name and description
  - Delete role (with validation - no users assigned)
  - Assign permissions to roles:
    - Dashboard: view
    - Inventory: view, create, edit, delete
    - Products: view, create, edit, delete
    - Categories: view, create, edit, delete
    - Brands: view, create, edit, delete
    - Attributes: view, create, edit, delete
    - Suppliers: view, create, edit, delete
    - POS: access, create transaction
    - Purchases: view, create, edit, delete
    - Reports: view, export
    - Activity Logs: view (all/own)
    - Users: view, create, edit, delete
    - Roles: view, create, edit, delete
    - Settings: view, edit

---

## Point of Sale (POS)
- As a `user` with POS permission I can:
  - Search products (by name, SKU, barcode)
  - Scan barcode (optional)
  - Add products to cart
  - Adjust quantity
  - Remove items from cart
  - Apply discount (if authorized)
  - View cart summary
  - Select payment method:
    - Cash
    - Card
    - GCash/Digital wallet
    - Multiple payments (split)
  - Calculate change (for cash)
  - Process sale
  - Print receipt
  - Email receipt (optional)
  - Hold/Park transaction (optional)
  - Resume held transaction (optional)
  - Void transaction (with authorization)

- **System behavior:**
  - Auto-deduct stock upon successful sale
  - Create transaction record
  - Update inventory
  - Log activity

---

## Purchases (Stock In)
- As a `user` with purchase permission I can:
  - Create purchase order with:
    - Supplier
    - Expected delivery date
    - Products (search and add)
    - Quantity ordered
    - Cost price per unit
    - Total cost
    - Notes/remarks
  - View purchase order list
  - Filter by: date, supplier, status
  - Edit purchase order (if not yet received)
  - Mark as "Received" to add stock
  - Record actual received quantities
  - Handle partial deliveries
  - Note damaged/missing items
  - Generate receiving report
  - View purchase history
  - Print/export purchase orders

- **System behavior:**
  - Add stock to inventory upon marking as "Received"
  - Update product cost price (optional: weighted average)
  - Create transaction record
  - Log activity

---

## Reports
- As a `user` with report permission I can:

  - **Sales Report:**
    - View sales trends (daily/weekly/monthly/quarterly/yearly/custom)
    - See total sales amount, transaction count
    - View sales by staff/cashier
    - Visualize with line/bar charts
    - Export to CSV/Excel
  
  - **Purchase Report:**
    - View purchase trends (daily/weekly/monthly/quarterly/yearly/custom)
    - See total purchase cost, number of POs
    - View purchases by supplier
    - Visualize with line/bar charts
    - Export to CSV/Excel
  
  - **Inventory Report:**
    - View current stock levels (all products)
    - See low stock items
    - See out of stock items
    - View total inventory value
    - See stock movement history
    - Calculate turnover rate
    - Export to CSV/Excel
  
  - **Product Performance Report:**
    - View top selling products (weekly/monthly/quarterly/yearly/custom)
    - See worst performing products
    - Compare product sales
    - View revenue by category
    - View revenue by brand
    - View profit margin per product (optional)
    - Visualize with bar/pie/line charts
    - Export to CSV/Excel
  
  - **Stock Adjustment Report:**
    - View all adjustments by date range
    - Filter by adjustment type (damaged/lost/returns)
    - See adjustment by staff
    - View total value of adjustments
    - Export to CSV/Excel
  
  - **Profit & Loss Report:**
    - View total revenue
    - View cost of goods sold
    - See gross profit
    - Account for stock adjustments
    - Calculate net profit
    - By period (monthly/quarterly/yearly)
    - Export to CSV/Excel

---

## Activity Logs
- **System automatically logs:**
  - User login/logout
  - Product CRUD operations
  - Category/Brand/Attribute/Supplier CRUD
  - Stock in (purchases)
  - Stock out (sales)
  - Stock adjustments
  - User management actions
  - Role/permission changes
  - Report generation
  - Any critical system actions

- As an `admin`/`superadmin` I can:
  - View all user activity logs
  - Filter by: date range, user, action type, module
  - Search logs
  - Export logs (CSV/Excel)

- As a `staff` I can:
  - View only my activity logs
  - Filter by date range, action type
  - Export my logs

---

## Settings

- #### Profile Settings
  - As a `user` I can:
    - View my profile information
    - Edit my first name, last name
    - Change my username
    - Update my email
    - Change my password
    - Upload profile picture (optional)

- #### System Preferences (Optional - Admin only)
  - As a `user` I can:
      - Toggle between light mode and dark mode
      - System remembers my preference

  - As an `admin`/`superadmin` I can:
    - Set business name and logo
    - Manage Backup & Restore