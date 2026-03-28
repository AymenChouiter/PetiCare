const modalAddAnimal = document.querySelector('[data-modal="add"]');
const btnOpenAddModal = document.querySelector('[data-btn-open="add"]');
const btnCloseAddModal = document.querySelector('[data-btn-close="add"]');

const openAddAnimalModal = () => {
  modalAddAnimal.showModal();
  document.body.classList.add("no-scroll");
};

const closeAddAnimalModal = () => {
  modalAddAnimal.close();
  document.body.classList.remove("no-scroll");
};

btnOpenAddModal.addEventListener("click", openAddAnimalModal);
btnCloseAddModal.addEventListener("click", closeAddAnimalModal);

const modalsUpdate = document.querySelectorAll('[data-modal="update"]');
const btnsOpenUpdate = document.querySelectorAll('[data-btn-open="update"]');
const btnsCloseUpdate = document.querySelectorAll('[data-btn-close="update"]');

const modalUpdateAnimal = document.querySelector('[data-modal="update"]');

const openUpdateAnimalModal = () => {
  const button = event.currentTarget;

  const pic = button.getAttribute('data-pic');
  const id = button.getAttribute('data-id');
  const name = button.getAttribute('data-name');
  const species = button.getAttribute('data-species');
  const gender = button.getAttribute('data-gender');
  const birth = button.getAttribute('data-birth');
  const health = button.getAttribute('data-health');
  const adoption = button.getAttribute('data-adopation');
  const fee = button.getAttribute('data-fee');
  const description = button.getAttribute('data-description');

  document.getElementById("update-animalId").value = id;
  document.getElementById('update-preview').src = "data:image/png;base64," + pic;

  modalUpdateAnimal.querySelector('[name="name"]').value = name;
  modalUpdateAnimal.querySelector('[name="species"]').value = species;
  modalUpdateAnimal.querySelector('[name="gender"]').value = gender;
  modalUpdateAnimal.querySelector('[name="birth_date"]').value = birth;
  modalUpdateAnimal.querySelector('[name="health_status"]').value = health;
  modalUpdateAnimal.querySelector('[name="adoption_status"]').value = adoption;
  modalUpdateAnimal.querySelector('[name="adoption_fee"]').value = fee;
  modalUpdateAnimal.querySelector('[name="description"]').value = description;

  modalUpdateAnimal.showModal();
  document.body.classList.add("no-scroll");
};

const closeUpdateAnimalModal = () => {
  modalUpdateAnimal.close();
  document.body.classList.remove("no-scroll");
};

btnsOpenUpdate.forEach(btn => {
  btn.addEventListener("click", openUpdateAnimalModal);
});

btnsCloseUpdate.forEach(btn => {
  btn.addEventListener("click", closeUpdateAnimalModal);
});

const deleteThis = async (reco) => {
  let input = document.getElementById('delete-animalId');
  let submitButton = document.getElementById('del');
  input.value = reco.value;
  await submitButton.click();
};