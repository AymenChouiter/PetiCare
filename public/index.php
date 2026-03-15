<!doctype html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="./css/style.css">
    <title>Animal Care Center - Admin</title>
  </head>
  <body>
    <h1>Animal Care Center - Admin Panel</h1>

    <h2>Animals in Database</h2>
    <?php if (isset($animals) && is_array($animals) && count($animals) > 0): ?>
      <table border="1" cellpadding="4" cellspacing="0">
        <thead>
          <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Species</th>
            <th>Gender</th>
            <th>Age</th>
            <th>Description</th>
            <th>Illnesses</th>
            <th>Price</th>
            <th>Status</th>
            <th>Picture</th>
            <th>Created At</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($animals as $animal): ?>
            <tr>
              <td><?php echo htmlspecialchars($animal['animalId'] ?? ''); ?></td>
              <td><?php echo htmlspecialchars($animal['animalName'] ?? ''); ?></td>
              <td><?php echo htmlspecialchars($animal['species'] ?? ''); ?></td>
              <td><?php echo htmlspecialchars($animal['gender'] ?? ''); ?></td>
              <td><?php echo htmlspecialchars($animal['animalAge'] ?? ''); ?></td>
              <td><?php echo htmlspecialchars($animal['description'] ?? ''); ?></td>
              <td><?php echo htmlspecialchars($animal['illnesses'] ?? ''); ?></td>
              <td><?php echo htmlspecialchars($animal['animalPrice'] ?? ''); ?></td>
              <td><?php echo htmlspecialchars($animal['status'] ?? ''); ?></td>
              <td>
                <?php if (!empty($animal['picture'])): ?>
                  <img
                    src="data:image/jpeg;base64,<?php echo base64_encode($animal['picture']); ?>"
                    alt="Animal picture"
                    width="80"
                  />
                <?php else: ?>
                  No image
                <?php endif; ?>
              </td>
              <td><?php echo htmlspecialchars($animal['createdAt'] ?? ''); ?></td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    <?php else: ?>
      <p>No animals found.</p>
    <?php endif; ?>

    <hr />

    <h2>Add New Animal (POST /add)</h2>
    <form action="/add" method="post" enctype="multipart/form-data">
      <label for="add-animalName">Animal Name:</label>
      <input type="text" id="add-animalName" name="animalName" />
      <br />

      <label for="add-species">Species:</label>
      <input type="text" id="add-species" name="species" />
      <br />

      <label for="add-gender">Gender:</label>
      <input type="text" id="add-gender" name="gender" />
      <br />

      <label for="add-animalAge">Age:</label>
      <input type="text" id="add-animalAge" name="animalAge" />
      <br />

      <label for="add-description">Description:</label>
      <input type="text" id="add-description" name="description" />
      <br />

      <label for="add-illnesses">Illnesses:</label>
      <input type="text" id="add-illnesses" name="illnesses" />
      <br />

      <label for="add-animalPrice">Price:</label>
      <input type="text" id="add-animalPrice" name="animalPrice" />
      <br />

      <label for="add-status">Status:</label>
      <input type="text" id="add-status" name="status" />
      <br />

      <label for="add-picture">Picture:</label>
      <input type="file" id="add-picture" name="picture" />
      <br />

      <button type="submit">Add Animal</button>
    </form>

    <hr />

    <h2>Update Animal (POST /update)</h2>
    <form action="/update" method="post" enctype="multipart/form-data">
      <label for="update-animalId">Animal ID (required):</label>
      <input type="number" id="update-animalId" name="animalId" />
      <br />

      <p>You can fill only the fields you want to update.</p>

      <label for="update-animalName">Animal Name:</label>
      <input type="text" id="update-animalName" name="animalName" />
      <br />

      <label for="update-species">Species:</label>
      <input type="text" id="update-species" name="species" />
      <br />

      <label for="update-gender">Gender:</label>
      <input type="text" id="update-gender" name="gender" />
      <br />

      <label for="update-animalAge">Age:</label>
      <input type="text" id="update-animalAge" name="animalAge" />
      <br />

      <label for="update-description">Description:</label>
      <input type="text" id="update-description" name="description" />
      <br />

      <label for="update-illnesses">Illnesses:</label>
      <input type="text" id="update-illnesses" name="illnesses" />
      <br />

      <label for="update-animalPrice">Price:</label>
      <input type="text" id="update-animalPrice" name="animalPrice" />
      <br />

      <label for="update-status">Status:</label>
      <input type="text" id="update-status" name="status" />
      <br />

      <label for="update-picture">Picture:</label>
      <input type="file" id="update-picture" name="picture" />
      <br />

      <button type="submit">Update Animal</button>
    </form>

    <hr />

    <h2>Delete Animal (POST /delete)</h2>
    <form action="/delete" method="post">
      <label for="delete-animalId">Animal ID:</label>
      <input type="number" id="delete-animalId" name="animalId" />
      <br />
      <button type="submit">Delete Animal</button>
    </form>

    <hr />

    <p>
      After submitting a form, the JSON response from the API will be shown by the browser.
    </p>
  </body>
</html>
