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
            <th>Birth Date</th>
            <th>Description</th>
            <th>Health Status</th>
            <th>Adoption Fee</th>
            <th>Adoption Status</th>
            <th>Picture</th>
            <th>Created At</th>
            <th>Updated At</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($animals as $animal): ?>
            <tr>
              <td><?php echo htmlspecialchars($animal['id'] ?? ''); ?></td>
              <td><?php echo htmlspecialchars($animal['name'] ?? ''); ?></td>
              <td><?php echo htmlspecialchars($animal['species'] ?? ''); ?></td>
              <td><?php echo htmlspecialchars($animal['gender'] ?? ''); ?></td>
              <td><?php echo htmlspecialchars($animal['birth_date'] ?? ''); ?></td>
              <td><?php echo htmlspecialchars($animal['description'] ?? ''); ?></td>
              <td><?php echo htmlspecialchars($animal['health_status'] ?? ''); ?></td>
              <td><?php echo htmlspecialchars($animal['adoption_fee'] ?? ''); ?></td>
              <td><?php echo htmlspecialchars($animal['adoption_status'] ?? ''); ?></td>
              <td>
                <?php if (!empty($animal['picture_data'])): ?>
                  <img
                    src="data:image/jpeg;base64,<?php echo base64_encode($animal['picture_data']); ?>"
                    alt="Animal picture"
                    width="80"
                  />
                <?php else: ?>
                  No image
                <?php endif; ?>
              </td>
              <td><?php echo htmlspecialchars($animal['created_at'] ?? ''); ?></td>
              <td><?php echo htmlspecialchars($animal['updated_at'] ?? ''); ?></td>
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
      <label for="add-name">Animal Name:</label>
      <input type="text" id="add-name" name="name" />
      <br />

      <label for="add-species">Species:</label>
      <input type="text" id="add-species" name="species" />
      <br />

      <label for="add-gender">Gender:</label>
      <select id="add-gender" name="gender">
        <option value="MALE">MALE</option>
        <option value="FEMALE">FEMALE</option>
      </select>
      <br />

      <label for="add-birth-date">Birth Date:</label>
      <input type="date" id="add-birth-date" name="birth_date" />
      <br />

      <label for="add-description">Description:</label>
      <input type="text" id="add-description" name="description" />
      <br />

      <label for="add-health-status">Health Status:</label>
      <select id="add-health-status" name="health_status">
        <option value="HEALTHY">HEALTHY</option>
        <option value="UNDER_TREATMENT">UNDER_TREATMENT</option>
      </select>
      <br />

      <label for="add-adoption-fee">Adoption Fee:</label>
      <input type="number" step="0.01" id="add-adoption-fee" name="adoption_fee" />
      <br />

      <label for="add-adoption-status">Adoption Status:</label>
      <select id="add-adoption-status" name="adoption_status">
        <option value="AVAILABLE">AVAILABLE</option>
        <option value="RESERVED">RESERVED</option>
        <option value="ADOPTED">ADOPTED</option>
      </select>
      <br />

      <label for="add-picture">Picture:</label>
      <input type="file" id="add-picture" name="picture" />
      <br />

      <button type="submit">Add Animal</button>
    </form>

    <hr />

    <h2>Update Animal (POST /update)</h2>
    <form action="/update" method="post" enctype="multipart/form-data">
      <label for="update-id">Animal ID (required):</label>
      <input type="number" id="update-id" name="id" />
      <br />

      <p>You can fill only the fields you want to update.</p>

      <label for="update-name">Animal Name:</label>
      <input type="text" id="update-name" name="name" />
      <br />

      <label for="update-species">Species:</label>
      <input type="text" id="update-species" name="species" />
      <br />

      <label for="update-gender">Gender:</label>
      <select id="update-gender" name="gender">
        <option value="">-- unchanged --</option>
        <option value="MALE">MALE</option>
        <option value="FEMALE">FEMALE</option>
      </select>
      <br />

      <label for="update-birth-date">Birth Date:</label>
      <input type="date" id="update-birth-date" name="birth_date" />
      <br />

      <label for="update-description">Description:</label>
      <input type="text" id="update-description" name="description" />
      <br />

      <label for="update-health-status">Health Status:</label>
      <select id="update-health-status" name="health_status">
        <option value="">-- unchanged --</option>
        <option value="HEALTHY">HEALTHY</option>
        <option value="UNDER_TREATMENT">UNDER_TREATMENT</option>
      </select>
      <br />

      <label for="update-adoption-fee">Adoption Fee:</label>
      <input type="number" step="0.01" id="update-adoption-fee" name="adoption_fee" />
      <br />

      <label for="update-adoption-status">Adoption Status:</label>
      <select id="update-adoption-status" name="adoption_status">
        <option value="">-- unchanged --</option>
        <option value="AVAILABLE">AVAILABLE</option>
        <option value="RESERVED">RESERVED</option>
        <option value="ADOPTED">ADOPTED</option>
      </select>
      <br />

      <label for="update-picture">Picture:</label>
      <input type="file" id="update-picture" name="picture" />
      <br />

      <button type="submit">Update Animal</button>
    </form>

    <hr />

    <h2>Delete Animal (POST /delete)</h2>
    <form action="/delete" method="post">
      <label for="delete-id">Animal ID:</label>
      <input type="number" id="delete-id" name="id" />
      <br />
      <button type="submit">Delete Animal</button>
    </form>

    <hr />

    <p>
      After submitting a form, the JSON response from the API will be shown by the browser.
    </p>
  </body>
</html>
