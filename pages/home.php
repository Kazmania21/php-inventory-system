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

      <button class="btn btn-success mb-3" href="/add">
        <i class="fa-solid fa-plus"></i>
        Add Item
      </button>

      <table class="table table-striped table-bordered table-hover">
        <thead class="table-dark">
          <tr>
            <th>#</th>
            <th>Item Name</th>
            <th>Category</th>
            <th>Quantity</th>
            <th>Price</th>
          </tr>
        </thead>

        <tbody>
          <tr>
            <td>1</td>
            <td>Apple MacBook Pro</td>
            <td>Laptops</td>
            <td>5</td>
            <td>$1,699</td>
          </tr>

          <tr>
            <td>2</td>
            <td>Dell UltraSharp Monitor</td>
            <td>Monitors</td>
            <td>12</td>
            <td>$329</td>
          </tr>

          <tr>
            <td>3</td>
            <td>Logitech MX Master 3</td>
            <td>Accessories</td>
            <td>20</td>
            <td>$99</td>
          </tr>
        </tbody>
      </table>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  </body>
</html>
