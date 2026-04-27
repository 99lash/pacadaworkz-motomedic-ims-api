# Product Attributes Unified Sync Plan

## Objective
Paganahin ang kakayahang mag-store, retrieve, update, at delete ng **Product Attributes** (gaya ng size, color) kasabay ng mismong Product entity upang maiwasan ang multiple API calls at ma-simplify ang payload.

## Key Files & Context
- `app/Http/Requests/Product/ProductRequest.php` - Idadagdag ang validation rules para sa incoming attributes.
- `app/Http/Requests/Product/ProductUpdateRequest.php` - Idadagdag rin ang validation rules para sa pag-uupdate.
- `app/Services/ProductService.php` - Dito ilalagay ang logic ng pag-sync (create/update) at eager loading (retrieve).
- `app/Http/Resources/ProductResource.php` - Para mai-format at isama ang `attribute_values` sa JSON API response.

## Implementation Steps
1. **Request Validation:** I-update ang `ProductRequest` at `ProductUpdateRequest` para tanggapin ang `attribute_values` field bilang array ng integer IDs (mula sa `attributes_values` table).
2. **Sync sa Pag-Create:** Sa `ProductService@create`, papalitan ang `// TODO: entry sa attribute table` at tatawagin ang `$product->attribute_values()->sync($data['attribute_values'])` para i-connect ang bagong product sa kanyang values.
3. **Sync sa Pag-Update:** Sa `ProductService@update`, gagamitin ulit ang `sync()` function ng Laravel para awtomatikong mag-attach ng bago at mag-detach ng mga natanggal na attribute values base sa idinikta ng payload.
4. **Eager Loading:** I-uupdate ang `ProductService@getAllProducts` at `ProductService@getProductById` upang isama (eager load) ang `attribute_values.attribute` relationship, para laging handa ang data at hindi mag-cause ng N+1 query problem.
5. **Resource Formatting:** I-uupdate ang `ProductResource` para isama ang collection mula sa `AttributesValueResource`, upang ma-expose sa API ang attribute values kasama ang kanilang pangalan.

## Verification & Testing
- Mag-simulate ng `POST` request sa `/api/v1/products` na may `attribute_values` array at asahang ang mga values ay mapupunta sa `product_attributes` pivot table.
- Subukang i-fetch ang list at specific product (`GET`) at kumpirmahin na kasama ang formated attributes.
- Mag-simulate ng `PUT` request sa isang umiiral na product upang patunayan na gumagana ang "sync" (pagbabawas at pagdaragdag ng lumang at bagong attribute values).
