<dialog class="editor" id="animal-editor">
  <div class="editor__header">
    <h2 class="editor__title" id="editor-title">Add New Animal</h2>
    <button type="button" class="editor__close" aria-label="Close editor" id="btn-close-editor">
      <iconify-icon icon="material-symbols:close-rounded" aria-hidden="true"></iconify-icon>
    </button>
  </div>
  <div class="animal-picture">
    <div id="skeleton" class="skeleton-loader"></div>
    <img src="" alt="" id="edit-preview-image" style="display: none;">
  </div>
  <form enctype="multipart/form-data" class="editor__form" id="animal-form">
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
      <input type="number" min="0" name="adoption_fee" id="animal-fee" placeholder="0" required />
    </div>
    <div class="form-group">
      <label for="animal-desc">Description</label>
      <textarea name="description" id="animal-desc" rows="3" placeholder="Describe this animal..."></textarea>
    </div>
    <div class="editor__footer">
      <button type="button" class="btn btn--secondary" id="btn-cancel-submit">
        Cancel
      </button>
      <button type="submit" class="btn btn--primary">Save</button>
    </div>
  </form>
</dialog>