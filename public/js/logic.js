"use strict";

const tableBody = document.getElementById("table-body");
const animalEditor = document.getElementById("animal-editor");
const editorTitle = document.getElementById("editor-title");
const btnAddAnimal = document.getElementById("btn-add-animal");
const btnCloseEditor = document.getElementById("btn-close-editor");
const animalForm = document.getElementById("animal-form");
const btnCancel = document.getElementById("btn-cancel-submit");
const previewImg = document.getElementById("edit-preview-image");
const skeleton = document.getElementById('skeleton');
const animalPictureInput = document.getElementById("animal-picture");

const setPreviewState = (mode, src = "") => {
  previewImg.src = src;
  if (mode === "image") {
    previewImg.style.display = "block";
    skeleton.style.display = "none";
    return;
  }

  previewImg.style.display = "none";
  skeleton.style.display = "block";
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
  if (pic) {
    setPreviewState("image", pic);
  } else {
    setPreviewState("skeleton");
  }
};

const fillimg = (inp) => {
  if (inp.files && inp.files[0]) {
    const reader = new FileReader();

    reader.onload = function(e) {
      setPreviewState("image", e.target.result);
    };
    reader.readAsDataURL(inp.files[0]);
  }else {
    setPreviewState("skeleton");
  }
};

// >>>>>>>>>>>>>>>>>>>>>>>>> Editor
const openEditor = (mode, animalData) => {
  const resolvedMode = mode === "edit" ? "edit" : "add";
  animalEditor.showModal();
  document.body.classList.add("no-scroll");
  previewImg.parentElement.style.display = "block";
  animalForm.reset();
  setPreviewState("skeleton");

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
  setPreviewState("skeleton");
  previewImg.parentElement.style.display = "none";
  animalForm.reset();
  animalEditor.close();
  document.body.classList.remove("no-scroll");
};

// >>>>>>>>>>>>>>>>>>>>>>>>> Animal operations
const handleAnimalFormSubmit = async (e) => {
  e.preventDefault();

  const animalData = new FormData(e.target);
  const mode = animalForm.dataset.mode;
  const id = animalForm.dataset.id;

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

btnAddAnimal.addEventListener("click", () => openEditor("add"));
btnCloseEditor.addEventListener("click", closeEditor);
btnCancel.addEventListener("click", closeEditor);
animalForm.addEventListener("submit", handleAnimalFormSubmit);
tableBody.addEventListener("click", handleTableAction);
animalPictureInput.addEventListener("change", (event) => fillimg(event.target));
