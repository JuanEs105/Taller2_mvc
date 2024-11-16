const tareasEliminarModal = document.getElementById("tareaEliminarModal");
const closeBtnModal =
  tareasEliminarModal.getElementsByClassName("closeBtn")[0];
const notBtnModal = contactoEliminarModal.getElementsByClassName("notBtn")[0];
const contactoForm = document.forms["tareaForm"];

const eliminarTarea = (id) => {
  const codInput = contactoForm["cod"];
  codInput.value = id;
  contactoEliminarModal.classList.remove("ocultarModal");
};

const cerrarModal = () => {
  contactoEliminarModal.classList.add("ocultarModal");
};

closeBtnModal.addEventListener("click", () => cerrarModal());
notBtnModal.addEventListener("click", () => cerrarModal());

// FILTRAR
function mostrarVentana() {
  document.getElementById("fondoVentana").style.display = "flex";
}

function cerrarVentana() {
  document.getElementById("fondoVentana").style.display = "none";
}

// AGRUPAR
function mostrarVentanaAgrupar() {
  document.getElementById("fondoVentanaAgrupar").style.display = "flex";
}

function cerrarVentanaAgrupar() {
  document.getElementById("fondoVentanaAgrupar").style.display = "none";
}
