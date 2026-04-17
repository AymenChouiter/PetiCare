"use strict";

const tableBody = document.getElementById("table-body");
const btnAddAnimal = document.getElementById("btn-add-animal");

const animalEditor = document.getElementById("animal-editor");
const editorTitle = document.getElementById("editor-title");
const btnCloseEditor = document.getElementById("btn-close-editor");
const previewImg = document.getElementById("edit-preview-image");
const btnRemoveImg = document.getElementById("btn-remove-img");
const animalForm = document.getElementById("animal-form");
const pictureInput = document.getElementById("animal-picture");
const btnCancel = document.getElementById("btn-cancel-submit");

const searchInput = document.getElementById("search-input");
const speciesSelect = document.getElementById("species-filter");
const adoptionStatusSelect = document.getElementById("adoption-filter");

const fillForm = (animalData) => {
  const { name, species, gender, birth, health, adoption, fee, desc, pic } =
    animalData;

  animalForm.querySelector('[name="name"]').value = name ?? "";
  animalForm.querySelector('[name="species"]').value = species ?? "";
  animalForm.querySelector('[name="gender"]').value = gender ?? "";
  animalForm.querySelector('[name="birth_date"]').value = birth ?? "";
  animalForm.querySelector('[name="health_status"]').value = health ?? "";
  animalForm.querySelector('[name="adoption_status"]').value = adoption ?? "";
  animalForm.querySelector('[name="adoption_fee"]').value =
    fee?.replace("$", "") ?? "";
  animalForm.querySelector('[name="description"]').value = desc ?? "";

  if (pic) {
    previewImg.src = pic;
    previewImg.parentElement.style.display = "block";
  } else {
    previewImg.src = "";
    previewImg.parentElement.style.display = "none";
  }
};

// >>>>>>>>>>>>>>>>>>>>>>>>> Editor
const openEditor = (mode, animalData) => {
  animalEditor.showModal();
  document.body.classList.add("no-scroll");

  if (mode === "edit" && animalData) {
    editorTitle.textContent = "Edit Animal";
    fillForm(animalData);
    if (previewImg.getAttribute("src"))
      previewImg.parentElement.style.display = "block";

    animalForm.dataset.mode = "edit";
    animalForm.dataset.id = animalData.id;
  } else {
    editorTitle.textContent = "Add New Animal";

    animalForm.dataset.mode = "add";
    delete animalForm.dataset.id;
  }
};

const closeEditor = () => {
  previewImg.src = "";
  previewImg.parentElement.style.display = "none";
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

searchInput.addEventListener("input", applyFilters);
speciesSelect.addEventListener("change", applyFilters);
adoptionStatusSelect.addEventListener("change", applyFilters);
btnAddAnimal.addEventListener("click", openEditor);
btnCloseEditor.addEventListener("click", closeEditor);
btnCancel.addEventListener("click", closeEditor);
animalForm.addEventListener("submit", handleAnimalFormSubmit);
tableBody.addEventListener("click", handleTableAction);
btnRemoveImg.addEventListener("click", () => {
  previewImg.src = "";
  previewImg.parentElement.style.display = "none";
});
pictureInput.addEventListener("change", () => {
  const file = pictureInput.files[0];
  if (!file) {
    return;
  }

  const reader = new FileReader();
  reader.onload = (e) => {
    previewImg.src = e.target.result;
    previewImg.parentElement.style.display = "block";
  };
  reader.readAsDataURL(file);
});
