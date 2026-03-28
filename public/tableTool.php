<dialog class="modal" data-modal="add">
    <div class="modal__header">
      <div class="modal_info">
        <h2 class="modal__title">Add New Animal</h2>
        <p class="modal__desc">
          Fill in the details to add a new animal to the adoption center
        </p>
      </div>
      <button type="button" class="modal__close" aria-label="Close modal" data-btn-close="add">
        <iconify-icon icon="material-symbols:close-rounded" aria-hidden="true"></iconify-icon>
      </button>
    </div>

    <form method="POST" action="/add" enctype="multipart/form-data" class="modal__form">

        <div class="form-group">
          <label for="animal-picture">Profile Picture</label>
          <input type="file" name="picture" id="animal-picture" accept="image/*" />
        </div>

        <div class="form-row">
          <div class="form-group">
            <label for="animal-name">Name</label>
            <input type="text" name="name" id="animal-name" placeholder="e.g. Luna" required />
          </div>
          <div class="form-group">
            <label for="animal-species">Species</label>
            <input type="text" name="species" id="animal-species" placeholder="e.g. Dog" required />
          </div>
        </div>

        <div class="form-row">
          <div class="form-group">
            <label for="animal-gender">Gender</label>
            <select name="gender" id="animal-gender" required>
              <option value="MALE">Male</option>
              <option value="FEMALE">Female</option>
            </select>
          </div>
          <div class="form-group">
            <label for="animal-birth">Birth Date</label>
            <input type="date" name="birth_date" id="animal-birth" required />
          </div>
        </div>

        <div class="form-row">
          <div class="form-group">
            <label for="animal-health">Health Status</label>
            <select name="health_status" id="animal-health" required>
              <option value="HEALTHY">Healthy</option>
              <option value="UNDER_TREATMENT">Under Treatment</option>
            </select>
          </div>
          <div class="form-group">
            <label for="animal-adoption">Adoption Status</label>
            <select name="adoption_status" id="animal-adoption">
              <option value="AVAILABLE">Available</option>
              <option value="RESERVED">Reserved</option>
              <option value="ADOPTED">Adopted</option>
            </select>
          </div>
        </div>

        <div class="form-group">
          <label for="animal-fee">Adoption Fee (DA)</label>
          <input type="number" step="100" name="adoption_fee" id="animal-fee" placeholder="0" required />
        </div>

        <div class="form-group">
          <label for="animal-desc">Description</label>
          <textarea name="description" id="animal-desc" rows="3" placeholder="Describe this animal..."></textarea>
        </div>

        <div class="modal__footer">
          <button type="button" class="btn btn--secondary" data-btn-close="add">Cancel</button>
          <button type="submit" class="btn btn--primary">Add Animal</button>
        </div>
    </form>
</dialog>

<dialog class="modal" data-modal="update">
  <div class="modal__header">
    <div class="modal_info">
      <h2 class="modal__title">Update the Animal Data</h2>
    </div>
    <button type="button" class="modal__close" aria-label="Close modal" data-btn-close="update">
      <iconify-icon icon="material-symbols:close-rounded" aria-hidden="true"></iconify-icon>
    </button>
  </div>
  <form action="/update" method="post" enctype="multipart/form-data" class="modal__form" >
    <div class="form-group" style="display: none;">
      <label for="update-animalId">Animal ID (required):</label>
      <input type="number" id="update-animalId" name="id" readonly />
    </div>
    
    <div class="table__avatar">
      <img src="" alt="" id="update-preview"> 
    </div>
    <div class="form-group">
      <label for="animal-picture">Profile Picture</label>
      <input type="file" name="picture" id="animal-picture" accept="image/*" />
    </div>
                  
    <div class="form-row">
      <div class="form-group">
        <label for="animal-name">Name</label>
        <input type="text" name="name" id="animal-name" placeholder="e.g. Luna" required />
      </div>
      <div class="form-group">
        <label for="animal-species">Species</label>
        <input type="text" name="species" id="animal-species" placeholder="e.g. Dog" required />
      </div>
    </div>
                  
    <div class="form-row">
      <div class="form-group">
        <label for="animal-gender">Gender</label>
        <select name="gender" id="animal-gender" required>
          <option value="MALE">Male</option>
          <option value="FEMALE">Female</option>
        </select>
      </div>
      <div class="form-group">
        <label for="animal-birth">Birth Date</label>
        <input type="date" name="birth_date" id="animal-birth" required />
      </div>
    </div>
                  
    <div class="form-row">
      <div class="form-group">
        <label for="animal-health">Health Status</label>
        <select name="health_status" id="animal-health" required>
          <option value="HEALTHY">Healthy</option>
          <option value="UNDER_TREATMENT">Under Treatment</option>
        </select>
      </div>
      <div class="form-group">
        <label for="animal-adoption">Adoption Status</label>
        <select name="adoption_status" id="animal-adoption">
          <option value="AVAILABLE">Available</option>
          <option value="RESERVED">Reserved</option>
          <option value="ADOPTED">Adopted</option>
        </select>
      </div>
    </div>
                  
    <div class="form-group">
      <label for="animal-fee">Adoption Fee (DA)</label>
      <input type="number" step="100" name="adoption_fee" id="animal-fee" placeholder="0" required />
    </div>
                  
    <div class="form-group">
      <label for="animal-desc">Description</label>
      <textarea name="description" id="animal-desc" rows="3" placeholder="Describe this animal..."></textarea>
    </div>
                  
    <div class="modal__footer">
      <button type="submit" class="btn btn--primary">Update</button>
    </div>
  </form>
</dialog>

<form action="/delete" method="post" style="display: none;">
  <div class="form-group">
    <label for="delete-animalId">Animal ID:</label>
    <input type="number" id="delete-animalId" name="id" />
  </div>
  <div class="modal__footer">
    <button type="submit" class="btn btn--primary" id="del">Delete</button>
  </div>
</form>