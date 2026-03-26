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