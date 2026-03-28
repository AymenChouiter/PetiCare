<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>PetiCare</title>
  <link rel="stylesheet" href="css/style.css" />
  <!-- Font -->
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link
    href="https://fonts.googleapis.com/css2?family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&display=swap"
    rel="stylesheet" />
  <!-- Icons -->
  <script src="https://code.iconify.design/iconify-icon/1.0.7/iconify-icon.min.js"></script>
</head>

<body>
  <header class="header">
    <div class="container">
      <div class="header__brand">
        <img src="/assets/logo.png" alt="Logo" class="header__logo" />
        <h1 class="header__title">Admin Dashboard</h1>
      </div>
    </div>
  </header>
  <div class="page-head container">
    <div class="page-head__meta">
      <h2 class="page-head__title">Animals</h2>
      <p class="page-head__desc">
        Manage all animals in the PetiCare adoption center
      </p>
    </div>
    <button class="btn btn--primary" type="button" data-btn-open="add">
      <iconify-icon icon="material-symbols:add-rounded"></iconify-icon>
      <span>Add Animal</span>
    </button>
  </div>
  <div class="toolbar-container container">
    <div class="toolbar">
      <div class="toolbar__search">
        <iconify-icon icon="mdi:magnify" aria-hidden="true" class="toolbar__search-icon"></iconify-icon>

        <input type="text" name="search" placeholder="Search animals by name..." class="toolbar__search-input"
          autocomplete="off" />
      </div>
      <select name="species" class="toolbar__select">
        <option value="">All Species</option>
        <option value="dog">Dog</option>
        <option value="cat">Cat</option>
        <option value="bird">Bird</option>
        <option value="rabbit">Rabbit</option>
        <option value="other">Other</option>
      </select>
      <select name="adoption_status" class="toolbar__select">
        <option value="">All Adoption Status</option>
        <option value="AVAILABLE">Available</option>
        <option value="RESERVED">Reserved</option>
        <option value="ADOPTED">Adopted</option>
      </select>
    </div>
  </div>
  <div class="container table-container">
    <div class="table-wrapper">
      <table class="table">
        <thead class="table__head">
          <tr class="table__row">
            <th class="table__th">Animal</th>
            <th class="table__th">Species</th>
            <th class="table__th">Gender</th>
            <th class="table__th">Age</th>
            <th class="table__th">Health</th>
            <th class="table__th">Fee</th>
            <th class="table__th">Status</th>
            <th class="table__th">Timeline</th>
            <th class="table__th table__th--center">Actions</th>
          </tr>
        </thead>
        <tbody class="table__body">
          <?php foreach ($animals as $animal): ?>
            <tr class="table__row">
              <td class="table__td">
                <div class="table__animal-info">
                  <div class="table__avatar">
                    <?php if ($animal['picture_data']): ?>
                      <img src="data:image/jpeg;base64,<?php echo base64_encode($animal['picture_data']); ?>"
                        alt="Animal picture" />
                    <?php else: ?>
                      <iconify-icon icon="lucide:paw-print"></iconify-icon>
                    <?php endif; ?>
                  </div>
                  <div class="table__animal-meta">
                    <span class="table__animal-name"><?php echo $animal['name'] ?></span>
                    <span class="table__animal-desc"><?php echo $animal['description'] ?></span>
                  </div>
                </div>
              </td>
              <td class="table__td"><?php echo $animal['species'] ?></td>
              <td class="table__td"><?php echo $animal['gender'] ?></td>
              <td class="table__td"><?php echo $animal['age'] ?></td>
              <td class="table__td">
                <span class="badge badge--health-<?php echo $animal['health_status'] ?>">
                  <?php echo strtolower(str_replace('_', ' ', $animal['health_status'])); ?>
                </span>
              </td>
              <td class="table__td table__td--bold">
                <?php echo $animal['adoption_fee'] ?>
              </td>
              <td class="table__td">
                <span class="badge badge--status-<?php echo strtolower($animal['adoption_status']) ?>">
                  <?php echo $animal['adoption_status'] ?>
                </span>
              </td>
              <td class="table__td">
                <div class="table__timeline">
                  <span>Cr: <?php echo $animal['created_at'] ?> </span>
                  <span>Up: <?php echo $animal['updated_at'] ?> </span>
                </div>
              </td>
              <td class="table__td">
                <div class="table__actions">
                  <button class="action-btn action-btn--edit"
                          aria-label="Edit" 
                          data-btn-open="update"
                          data-pic="<?php echo base64_encode($animal['picture_data']) ?>"
                          data-id="<?php echo $animal['id'] ?>" 
                          data-name="<?php echo $animal['name'] ?>" 
                          data-species="<?php echo $animal['species'] ?>" 
                          data-gender="<?php echo $animal['gender'] ?>" 
                          data-birth="<?php echo $animal['birth_date'] ?>" 
                          data-health="<?php echo $animal['health_status'] ?>" 
                          data-adopation="<?php echo $animal['adoption_status'] ?>" 
                          data-fee="<?php echo $animal['adoption_fee'] ?>" 
                          data-Description="<?php echo $animal['description'] ?>"
                  >
                    <iconify-icon icon="material-symbols:edit-outline-rounded"></iconify-icon>
                  </button>
                  <button class="action-btn action-btn--delete" aria-label="Delete" value="<?php echo $animal['id'] ?>" onclick="deleteThis(this)">
                    <iconify-icon icon="material-symbols:delete-outline"></iconify-icon>
                  </button>
                </div>
              </td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </div>
  <?php 
    require './public/tableTool.php';
  ?>
  <footer class="footer">
    <p>Made with &#9825; by Farouk & Aymen</p>
    <p>&copy;2025/2026 WEB course project</p>
  </footer>
  <script src="js/logic.js" defer></script>
</body>

</html>