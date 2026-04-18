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
    <button class="btn btn--primary" type="button" id="btn-add-animal">
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
            <th class="table__th table__th--center">Actions</th>
          </tr>
        </thead>
        <tbody class="table__body" id="table-body">
          <?php foreach ($animals as $animal): ?>
            <tr class="table__row" title="Cr: <?= $animal['created_at'] ?> &#10;Up: <?= $animal['updated_at'] ?>" data-id="<?= $animal['id'] ?>" data-name="<?= $animal['name'] ?>"
              data-species="<?= $animal['species'] ?>" data-gender="<?= $animal['gender'] ?>"
              data-birth="<?= $animal['birth_date'] ?>" data-health="<?= $animal['health_status'] ?>"
              data-fee="<?= $animal['adoption_fee'] ?>" data-adoption="<?= $animal['adoption_status'] ?>"
              data-desc="<?= $animal['description'] ?>"
              data-pic="data:image/jpeg;base64,<?= base64_encode($animal['picture_data']) ?>">
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
              <td class="table__td"><?php echo $animal['gender_display'] ?></td>
              <td class="table__td"><?php echo $animal['age'] ?></td>
              <td class="table__td">
                <span class="badge badge--health-<?php echo $animal['health_class'] ?>">
                  <?php echo $animal['health_display'] ?>
                </span>
              </td>
              <td class="table__td table__td--bold">
                <?php echo $animal['adoption_fee'] ?>
              </td>
              <td class="table__td">
                <span class="badge badge--status-<?php echo $animal['adoption_class'] ?>">
                  <?php echo $animal['adoption_display'] ?>
                </span>
              </td>
              <td class="table__td">
                <div class="table__actions">
                  <button class="action-btn action-btn--edit" aria-label="Edit" data-action="edit">
                    <iconify-icon icon="material-symbols:edit-outline-rounded"></iconify-icon>
                  </button>
                  <button class="action-btn action-btn--delete" aria-label="Delete" data-action="delete">
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
  <footer class="footer">
    <p>Made with &#9825; by Farouk & Aymen</p>
    <p>&copy;2025/2026 WEB course project</p>
  </footer>

  <?php require './public/AnimalEditor.php'; ?>
  <script src="js/logic.js" defer></script>
</body>

</html>