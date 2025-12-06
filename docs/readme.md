# Inventory Management API Documentation

This document provides detailed documentation for the Inventory Management feature, including the service, controller, API endpoints, sample requests, and database migration.

## Table of Contents

- [Inventory Service (`InventoryService.php`)](#inventory-service-inventoryservicephp)
- [Inventory Controller (`InventoryController.php`)](#inventory-controller-inventorycontrollerphp)
- [API Endpoints](#api-endpoints)
- [Sample Requests](#sample-requests)
  - [Store (Create) Inventory](#store-create-inventory)
  - [Update (Patch) Inventory](#update-patch-inventory)
- [API Endpoints and Responses](#api-endpoints-and-responses)

## Seeder
The `InventorySeeder` is used to populate the `inventory` table with initial data. This is useful for development and testing purposes.

### Running the Seeder
To run the seeder and populate the `inventory` table, use the following Artisan command:
```bash
php artisan db:seed 
```

---

## API Endpoints and Responses

All endpoints are prefixed with `/api/v1/inventory`.

### GET /
Retrieves a paginated list of inventory items.

**Sample Response:**
```json
{
    "success": true,
    "data": [
        {
            "id": 1,
            "sku": "SKU001",
            "product_name": "Product A",
            "category": "Category 1",
            "brand": "Brand X",
            "quantity": 100,
            "last_stock_in": "2025-12-01 09:00:00"
        },
        {
            "id": 2,
            "sku": "SKU002",
            "product_name": "Product B",
            "category": "Category 2",
            "brand": "Brand Y",
            "quantity": 50,
            "last_stock_in": "2025-12-05 14:20:00"
        }
    ],
    "meta": {
        "current_page": 1,
        "per_page": 10,
        "total": 2,
        "last_page": 1
    }
}
```

### POST /
Creates a new inventory item.

**Sample Request:**
```json
{
    "product_id": 1,
    "supplier_id": 5,
    "quantity": 100,
    "last_stock_in": "2025-12-01 09:00:00"
}
```

**Sample Response (201 Created):**
```json
{
    "success": true,
    "data": {
        "id": 3,
        "sku": "SKU003",
        "product_name": "Product C",
        "category": "Category 1",
        "brand": "Brand Z",
        "quantity": 100,
        "last_stock_in": "2025-12-01 09:00:00"
    }
}
```

### GET /{id}
Retrieves a single inventory item by its ID.

**Sample Response:**
```json
{
    "success": true,
    "data": {
        "id": 1,
        "sku": "SKU001",
        "product_name": "Product A",
        "category": "Category 1",
        "brand": "Brand X",
        "quantity": 100,
        "last_stock_in": "2025-12-01 09:00:00"
    }
}
```

### PATCH /{id}
Updates an existing inventory item.

**Sample Request:**
```json
{
    "quantity": 150,
    "last_stock_in": "2025-12-06 10:30:00"
}
```

**Sample Response:**
```json
{
    "success": true,
    "data": {
        "id": 1,
        "sku": "SKU001",
        "product_name": "Product A",
        "category": "Category 1",
        "brand": "Brand X",
        "quantity": 150,
        "last_stock_in": "2025-12-06 10:30:00"
    }
}
```

### DELETE /{id}
Deletes an inventory item.

**Sample Response:**
```json
{
    "success": true,
    "message": "Inventory item deleted successfully"
}
```
