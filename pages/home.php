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

      <a class="btn btn-success mb-3" href="/add">
        <i class="fa-solid fa-plus"></i>
        Add Item
      </a>

      <table class="table table-striped table-bordered table-hover">
        <thead class="table-dark">
          <tr>
            <th>#</th>
            <th>Item Name</th>
            <th>Category</th>
            <th>Quantity</th>
            <th>Price</th>
            <th>Actions</th>
          </tr>
        </thead>

        <tbody>
          <?php foreach ($inventoryItems->i as $index => $item): ?>
            <tr id="row-<?= $item['Id'] ?>">
              <td><?= $item['Id'] ?></td>
              <td><?= htmlspecialchars($item['Name']) ?></td>
              <td><?= htmlspecialchars($item->c['CategoryName']) ?></td>
              <td><?= $item['Quantity'] ?></td>
              <td>$<?= number_format((float) $item['Price'], 2) ?></td>
              <td>
                <button class="btn btn-danger" onclick="deleteItem(<?= $item['Id'] ?>)">Delete</button>
              </td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>

    <script src="../assets/js/delete.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  </body>
</html>
