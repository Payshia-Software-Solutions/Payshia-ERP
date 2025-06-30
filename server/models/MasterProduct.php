<?php

class Product
{
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    // Fetch all active products
    public function getAllProducts()
    {
        $stmt = $this->pdo->prepare("SELECT * FROM master_product WHERE active_status = 1 ORDER BY display_name ASC");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Fetch a single product by ID
    public function getProductById($id)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM master_product WHERE product_id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Create a new product
    public function createProduct($data)
    {
        $stmt = $this->pdo->prepare("INSERT INTO master_product (
            product_code, display_name, name_si, name_ti, print_name,
            section_id, department_id, category_id, brand_id,
            measurement, reorder_level, lead_days,
            cost_price, selling_price, minimum_price, wholesale_price, price_2,
            item_type, item_location, image_path,
            created_by, created_at, active_status, generic_id, supplier_list,
            size_id, color_id, product_description, recipe_type, barcode,
            expiry_good, location_list, opening_stock
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

        $stmt->execute([
            $data['product_code'],
            $data['display_name'],
            $data['name_si'],
            $data['name_ti'],
            $data['print_name'],
            $data['section_id'],
            $data['department_id'],
            $data['category_id'],
            $data['brand_id'],
            $data['measurement'],
            $data['reorder_level'],
            $data['lead_days'],
            $data['cost_price'],
            $data['selling_price'],
            $data['minimum_price'],
            $data['wholesale_price'],
            $data['price_2'],
            $data['item_type'],
            $data['item_location'],
            $data['image_path'],
            $data['created_by'],
            $data['created_at'],
            $data['active_status'],
            $data['generic_id'],
            $data['supplier_list'],
            $data['size_id'],
            $data['color_id'],
            $data['product_description'],
            $data['recipe_type'],
            $data['barcode'],
            $data['expiry_good'],
            $data['location_list'],
            $data['opening_stock']
        ]);

        return $this->pdo->lastInsertId();
    }

    // Update an existing product
    public function updateProduct($id, $data)
    {
        $stmt = $this->pdo->prepare("UPDATE master_product SET
            product_code = ?, display_name = ?, name_si = ?, name_ti = ?, print_name = ?,
            section_id = ?, department_id = ?, category_id = ?, brand_id = ?,
            measurement = ?, reorder_level = ?, lead_days = ?,
            cost_price = ?, selling_price = ?, minimum_price = ?, wholesale_price = ?, price_2 = ?,
            item_type = ?, item_location = ?, image_path = ?,
            created_by = ?, active_status = ?, generic_id = ?, supplier_list = ?,
            size_id = ?, color_id = ?, product_description = ?, recipe_type = ?, barcode = ?,
            expiry_good = ?, location_list = ?, opening_stock = ?
            WHERE product_id = ?");

        $stmt->execute([
            $data['product_code'],
            $data['display_name'],
            $data['name_si'],
            $data['name_ti'],
            $data['print_name'],
            $data['section_id'],
            $data['department_id'],
            $data['category_id'],
            $data['brand_id'],
            $data['measurement'],
            $data['reorder_level'],
            $data['lead_days'],
            $data['cost_price'],
            $data['selling_price'],
            $data['minimum_price'],
            $data['wholesale_price'],
            $data['price_2'],
            $data['item_type'],
            $data['item_location'],
            $data['image_path'],
            $data['created_by'],
            $data['active_status'],
            $data['generic_id'],
            $data['supplier_list'],
            $data['size_id'],
            $data['color_id'],
            $data['product_description'],
            $data['recipe_type'],
            $data['barcode'],
            $data['expiry_good'],
            $data['location_list'],
            $data['opening_stock'],
            $id
        ]);

        return $stmt->rowCount();
    }

    // Delete a product by ID
    public function deleteProduct($id)
    {
        $stmt = $this->pdo->prepare("DELETE FROM master_product WHERE product_id = ?");
        $stmt->execute([$id]);
        return $stmt->rowCount();
    }
}
?>