"use strict";

const tableBody = document.getElementById("table-body");
const btnAddAnimal = document.getElementById("btn-add-animal");

const animalEditor = document.getElementById("animal-editor");
const editorTitle = document.getElementById("editor-title");
const btnCloseEditor = document.getElementById("btn-close-editor");
const previewImg = document.getElementById("edit-preview-image");
const btnRemoveImg = document.getElementById("btn-remove-img");
const animalForm = document.getElementById("animal-form");
const btnCancel = document.getElementById("btn-cancel-submit");
const skeleton = document.getElementById('skeleton');
const animalPictureInput = document.getElementById("animal-picture");
const searchInput = document.getElementById("search-input");
const speciesSelect = document.getElementById("species-filter");
const adoptionStatusSelect = document.getElementById("adoption-filter");
const pictureContainer = previewImg?.parentElement;

const setPreviewState = ({ src = "", showContainer = true }) => {
  if (!previewImg || !skeleton || !btnRemoveImg || !pictureContainer) return;

  const hasImage = Boolean(src);
  previewImg.src = src;
  pictureContainer.style.display = showContainer ? "block" : "none";
  previewImg.style.display = hasImage ? "block" : "none";
  skeleton.style.display = hasImage ? "none" : "block";
  btnRemoveImg.style.display = hasImage ? "inline-flex" : "none";
};

const fillForm = (animalData = {}) => {
  const { name, species, gender, birth, health, adoption, fee, desc, pic } =
    animalData;

  animalForm.querySelector('[name="name"]').value = name ?? "";
  animalForm.querySelector('[name="species"]').value = species ?? "";
  animalForm.querySelector('[name="gender"]').value = gender ?? "";
  animalForm.querySelector('[name="birth_date"]').value = birth ?? "";
  animalForm.querySelector('[name="health_status"]').value = health ?? "";
  animalForm.querySelector('[name="adoption_status"]').value = adoption ?? "";
  animalForm.querySelector('[name="adoption_fee"]').value =
    (fee ?? "").replace(" DA", "").replace(",", "") ?? "";
  animalForm.querySelector('[name="description"]').value = desc ?? "";
  setPreviewState({ src: pic ?? "", showContainer: true });
};

const handlePictureInputChange = (inp) => {
  if (inp.files && inp.files[0]) {
    const reader = new FileReader();

    reader.onload = function (e) {
      setPreviewState({ src: e.target.result, showContainer: true });
    };
    reader.readAsDataURL(inp.files[0]);
  } else {
    setPreviewState({ src: "", showContainer: true });
  }
};

// >>>>>>>>>>>>>>>>>>>>>>>>> Editor
const openEditor = (mode, animalData) => {
  const resolvedMode = mode === "edit" ? "edit" : "add";
  if (!animalEditor) return;

  animalEditor.showModal();
  document.body.classList.add("no-scroll");
  animalForm.reset();
  setPreviewState({ src: "", showContainer: true });

  if (resolvedMode === "edit" && animalData) {
    editorTitle.textContent = "Edit Animal";
    animalForm.dataset.mode = "edit";
    animalForm.dataset.id = animalData.id;
    fillForm(animalData);
  } else {
    editorTitle.textContent = "Add New Animal";
    animalForm.dataset.mode = "add";
    delete animalForm.dataset.id;
  }
};

const closeEditor = () => {
  if (!animalEditor) return;

  setPreviewState({ src: "", showContainer: false });
  animalForm.reset();
  animalEditor.close();
  document.body.classList.remove("no-scroll");
};

// >>>>>>>>>>>>>>>>>>>>>>>>> Search and Filter
const applyFilters = () => {
  const searchTerm = searchInput.value.toLowerCase();
  const selectedSpecies = speciesSelect.value.toLowerCase();
  const selectedAdoption = adoptionStatusSelect.value;

  const rows = tableBody.querySelectorAll("tr");

  rows.forEach((row) => {
    const name = row.dataset.name?.toLowerCase() || "";
    const species = row.dataset.species?.toLowerCase() || "";
    const adoption = row.dataset.adoption || "";

    const matchesSearch = name.includes(searchTerm);
    const matchesSpecies = !selectedSpecies || species === selectedSpecies;
    const matchesAdoption = !selectedAdoption || adoption === selectedAdoption;

    row.style.display =
      matchesSearch && matchesSpecies && matchesAdoption ? "" : "none";
  });
};

// >>>>>>>>>>>>>>>>>>>>>>>>> Animal operations
const handleAnimalFormSubmit = async (e) => {
  e.preventDefault();

  const animalData = new FormData(e.target);
  const mode = animalForm.dataset.mode;
  const id = animalForm.dataset.id;

  const hasNoImage =
    !previewImg.getAttribute("src") && !animalData.get("picture").size;

  if (mode === "edit" && hasNoImage) animalData.set("delete_picture", "1");

  const requiredFields = [
    "name",
    "species",
    "gender",
    "birth_date",
    "health_status",
    "adoption_status",
    "adoption_fee",
  ];
  const missing = requiredFields.some((field) => !animalData.get(field));

  if (missing) {
    alert("Please fill in all required fields!");
    return;
  }

  const url = mode === "edit" ? `/update?id=${id}` : "/add";

  try {
    const res = await fetch(url, { method: "POST", body: animalData });

    if (!res.ok) {
      throw new Error("Request failed");
    }

    closeEditor();
    window.location.reload();
  } catch (err) {
    console.error(err);
    alert("Something went wrong. Please try again!");
  }
};

const handleDeleteAnimal = async (id) => {
  try {
    const res = await fetch(`/delete?id=${id}`, { method: "DELETE" });
    const json = await res.json();

    window.location.reload();
  } catch (err) {
    console.error(err);
    alert("Something went wrong. Please try again!");
  }
};

const handleTableAction = (e) => {
  const btn = e.target.closest("[data-action]");
  if (!btn) return;

  const action = btn.dataset.action;
  const row = btn.closest("tr");
  const animalData = row.dataset;

  if (action === "edit") openEditor("edit", animalData);
  if (action === "delete") handleDeleteAnimal(animalData.id);
};

if (searchInput) searchInput.addEventListener("input", applyFilters);
if (speciesSelect) speciesSelect.addEventListener("change", applyFilters);
if (adoptionStatusSelect) adoptionStatusSelect.addEventListener("change", applyFilters);
if (btnAddAnimal) btnAddAnimal.addEventListener("click", () => openEditor("add"));
if (btnCloseEditor) btnCloseEditor.addEventListener("click", closeEditor);
if (btnCancel) btnCancel.addEventListener("click", closeEditor);
if (animalForm) animalForm.addEventListener("submit", handleAnimalFormSubmit);
if (tableBody) tableBody.addEventListener("click", handleTableAction);
if (animalPictureInput) {
  animalPictureInput.addEventListener("change", (event) =>
    handlePictureInputChange(event.target),
  );
}
if (btnRemoveImg) {
  btnRemoveImg.addEventListener("click", () => {
    if (animalPictureInput) animalPictureInput.value = "";
    setPreviewState({ src: "", showContainer: true });
  });
}
