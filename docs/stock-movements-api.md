# API Endpoint: `GET /api/v1/stock-movements`

This endpoint retrieves a paginated list of stock movements.

## Authentication

- **Type:** Bearer Token
- **Required:** Yes

## Authorization

- **Roles:** `superadmin`, `admin`

## Request

### URL
`{{base_url}}/api/v1/stock-movements`

### Method
`GET`

### Query Parameters

| Parameter       | Type    | Description                                             |
| --------------- | ------- | ------------------------------------------------------- |
| `product_id`    | integer | Filter by product ID.                                   |
| `user_id`       | integer | Filter by user ID.                                      |
| `product_name`  | string  | Filter by product name (supports partial matching).     |
| `user_name`     | string  | Filter by user name (supports partial matching).        |
| `brand_name`    | string  | Filter by brand name (supports partial matching).       |
| `movement_type` | string  | Filter by the type of movement (e.g., 'sale', 'adjustment'). |
| `start_date`    | date    | Filter by the start date (format: `YYYY-MM-DD`).        |
| `end_date`      | date    | Filter by the end date (format: `YYYY-MM-DD`).          |
| `page`          | integer | The page number for pagination.                         |
| `per_page`      | integer | The number of items per page for pagination.            |

### Example Request

```http
GET {{base_url}}/api/v1/stock-movements?product_name=Sample&page=1&per_page=10
Authorization: Bearer <your_token>
```

## Response

### Success Response (200 OK)

A paginated JSON response containing a list of stock movements.

```json
{
    "data": [
        {
            "product": "Product Name",
            "brand": "Brand Name",
            "category": "Category Name",
            "user": "User Name",
            "quantity": 10,
            "reference_type": "sale",
            "reference_id": 123,
            "notes": "Sale transaction"
        }
    ],
    "links": {
        "first": "{{base_url}}/api/v1/stock-movements?page=1",
        "last": "{{base_url}}/api/v1/stock-movements?page=5",
        "prev": null,
        "next": "{{base_url}}/api/v1/stock-movements?page=2"
    },
    "meta": {
        "current_page": 1,
        "from": 1,
        "last_page": 5,
        "path": "{{base_url}}/api/v1/stock-movements",
        "per_page": 1,
        "to": 1,
        "total": 5
    }
}
```

### Response Body Structure (for each item)

| Field            | Type    | Description                              |
| ---------------- | ------- | ---------------------------------------- |
| `product`        | string  | The name of the product.                 |
| `brand`          | string  | The brand of the product.                |
| `category`       | string  | The category of the product.             |
| `user`           | string  | The name of the user who made the movement. |
| `quantity`       | integer | The quantity of the movement.            |
| `reference_type` | string  | The type of reference for the movement.  |
| `reference_id`   | integer | The ID of the reference.                 |
| `notes`          | string  | Additional notes for the movement.       |


### Error Responses

| Code | Reason            | Description                                                     |
| ---- | ----------------- | --------------------------------------------------------------- |
| 401  | Unauthorized      | If the request is not authenticated (missing or invalid token). |
| 403  | Forbidden         | If the authenticated user does not have the required role.      |
| 500  | Internal Server Error | If an unexpected server error occurs.                         |

```json
{
    "message": "An unexpected error occurred."
}
```
