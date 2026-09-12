USE g7cs;

-- These ALTER statements match the uploaded schema.
-- Run only if the foreign keys are missing.

ALTER TABLE cart
  ADD CONSTRAINT fk_cart_product
  FOREIGN KEY (product_id) REFERENCES products(proID)
  ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT fk_cart_user
  FOREIGN KEY (user_id) REFERENCES users(id)
  ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE categories
  ADD CONSTRAINT fk_categories_parent
  FOREIGN KEY (parent_id) REFERENCES categories(id)
  ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE orders
  ADD CONSTRAINT fk_orders_user
  FOREIGN KEY (user_id) REFERENCES users(id)
  ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE order_items
  ADD CONSTRAINT fk_order_items_order
  FOREIGN KEY (order_id) REFERENCES orders(id)
  ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT fk_order_items_product
  FOREIGN KEY (product_id) REFERENCES products(proID)
  ON UPDATE CASCADE;

ALTER TABLE payments
  ADD CONSTRAINT fk_payments_order
  FOREIGN KEY (order_id) REFERENCES orders(id)
  ON DELETE CASCADE ON UPDATE CASCADE;
