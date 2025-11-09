<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Inventory</title>

    <link
      href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
      rel="stylesheet"
    />

    <link
      rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"
      integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA=="
      crossorigin="anonymous"
      referrerpolicy="no-referrer"
    />
  </head>

  <body>
    <h1 class="text-center mt-5"><?= $title ?></h1>

    <div class="container mt-5">
      <h2 class="mb-4">Inventory Table</h2>

      <form action="api/inventory" method="POST" class="p-4 border rounded">
        <div class="mb-3">
          <label class="form-label">Item Name</label>
          <input type="text" name="itemName" class="form-control" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Category</label>
            <select name="categoryId" class="form-control" required>
                <option value="">-- Select a Category --</option>

                <?php foreach ($itemCategories->c as $index => $category): ?>
                    <option value="<?= htmlspecialchars($category['Id']) ?>">
                        <?= htmlspecialchars($category['Name']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="mb-3">
          <label class="form-label">Quantity</label>
          <input type="number" name="quantity" class="form-control" required>
        </div>

        <div class="mb-3">
          <label class="form-label">Price</label>
          <input type="number" step="0.01" name="price" class="form-control" required>
        </div>

        <button type="submit" class="btn btn-primary">
          <i class="fa-solid fa-plus"></i>
          Add Item
        </button>
      </form>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  </body>
</html>
