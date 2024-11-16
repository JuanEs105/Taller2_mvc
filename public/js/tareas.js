// Función genérica para mostrar un modal o ventana
const mostrarElemento = (selector) => {
    const elemento = document.querySelector(selector);
    if (elemento) elemento.style.display = "flex";
  };
  
  // Función genérica para ocultar un modal o ventana
  const ocultarElemento = (selector) => {
    const elemento = document.querySelector(selector);
    if (elemento) elemento.style.display = "none";
  };
  
  // Función para configurar el modal de eliminación
  const configurarEliminarTarea = () => {
    const tareaEliminarModal = document.getElementById("tareaEliminarModal");
    const tareaForm = document.forms["tareaForm"];
  
    document.addEventListener("click", (event) => {
      const target = event.target;
  
      // Mostrar modal de eliminación
      if (target.matches(".btn-eliminar")) {
        const tareaId = target.dataset.id; // Asegúrate de que el botón tenga el atributo `data-id`
        const codInput = tareaForm["cod"];
        codInput.value = tareaId;
        mostrarElemento("#tareaEliminarModal");
      }
  
      // Cerrar modal (closeBtn o notBtn)
      if (
        target.matches("#tareaEliminarModal .closeBtn") ||
        target.matches("#tareaEliminarModal .notBtn")
      ) {
        ocultarElemento("#tareaEliminarModal");
      }
    });
  };
  
  // Configurar el manejo de las ventanas emergentes (filtrar/agrupar)
  const configurarVentanasEmergentes = () => {
    document.addEventListener("click", (event) => {
      const target = event.target;
  
      // Mostrar ventana de filtrar
      if (target.matches(".btn-filtrar")) {
        mostrarElemento("#fondoVentana");
      }
  
      // Cerrar ventana de filtrar
      if (target.matches("#fondoVentana .cerrarVentana")) {
        ocultarElemento("#fondoVentana");
      }
  
      // Mostrar ventana de agrupar
      if (target.matches(".btn-agrupar")) {
        mostrarElemento("#fondoVentanaAgrupar");
      }
  
      // Cerrar ventana de agrupar
      if (target.matches("#fondoVentanaAgrupar .cerrarVentanaAgrupar")) {
        ocultarElemento("#fondoVentanaAgrupar");
      }
    });
  };
  
  // Inicializar funcionalidades
  document.addEventListener("DOMContentLoaded", () => {
    configurarEliminarTarea();
    configurarVentanasEmergentes();
  });
  