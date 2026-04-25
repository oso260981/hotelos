# Manual de Usuario: Expediente de Identidad y Firma Digital
## HotelOS - Sistema de Recepción Inteligente

Este manual describe el funcionamiento del nuevo módulo de **Expediente de Identidad** con inteligencia artificial y el sistema de **Sincronización de Firma** con tablet exterior.

---

### 1. El Módulo de Identificación (Recepción)
En la pantalla principal de "Trabajo", al abrir el modal de registro, encontrarás la sección de **Identificación**.

#### Opciones de Captura:
*   **Botón ON**: Activa la webcam de la PC. Úsala para capturar el documento en vivo.
    *   *Tip*: Asegúrate de que el documento esté bien iluminado y derecho.
*   **Botón Subir**: Permite seleccionar una foto o PDF guardado en la computadora.
*   **Botón QR**: Genera un puente para que el huésped use su propio celular para tomar la foto (ideal para documentos con mucho reflejo o cámaras de PC de baja calidad).

#### Procesamiento con IA:
Una vez capturada la imagen, aparecerá el botón **"Procesar con IA"**. Al presionarlo:
1. El sistema leerá automáticamente el nombre, apellidos, número de identificación y dirección.
2. Si el documento está al revés o rotado, usa las herramientas de rotación que aparecerán en la vista previa.

---

### 2. Sincronización de Firma (PC a Tablet)
Una vez que los datos del huésped están en el formulario, puedes proceder a la firma digital.

#### Pasos para la Recepción:
1. Haz clic en el botón **"Enviar a Tablet"**.
2. Verás una confirmación: "Firma activada en la tablet exterior".
3. No es necesario cerrar el modal, puedes esperar a que el huésped firme.

#### Modo Manual (Actualizaciones):
Si el huésped ya está registrado y solo quieres actualizar su firma:
1. Escribe su **Nombre** y **Apellidos** en los campos de texto.
2. Presiona **"Enviar a Tablet"**. El sistema detectará que no hay un escaneo nuevo y usará los datos que escribiste para identificar al huésped en la tablet.

---

### 3. Interfaz del Huésped (Tablet Exterior)
La tablet debe estar siempre encendida en la dirección: `[tu-dominio]/tablet`.

1.  **Pantalla de Espera**: La tablet mostrará una bienvenida elegante y el logo del hotel mientras no haya solicitudes.
2.  **Pantalla de Firma**: En cuanto la recepción presione "Enviar", la tablet cambiará automáticamente.
    *   Mostrará el nombre del huésped: *"Hola, [Nombre del Huésped]"*.
    *   Habilitará un recuadro blanco para firmar con el dedo o stylus.
3.  **Finalización**: El huésped debe presionar **"Finalizar Registro"**.
    *   La firma se guardará automáticamente en el expediente de la PC.
    *   La tablet volverá a la pantalla de espera sola después de 3 segundos.

---

### 4. Consejos para un Escaneo Perfecto
*   **Orientación**: Si la IA no lee bien los datos, verifica que la imagen esté horizontal y derecha en la vista previa. Usa los botones de rotación si es necesario.
*   **Reflejos**: Evita que el flash de la cámara o luces directas den sobre el plástico del documento.
*   **Modo Manual**: Si el nombre en la tablet aparece vacío, asegúrate de haber escrito al menos el nombre en el formulario de la PC antes de enviar.

---

### 5. Resolución de Problemas Comunes
*   **"Error al conectar con la base de datos"**: Hemos optimizado el sistema para evitar esto, pero si sucede, espera 10 segundos y vuelve a intentar. El sistema se recuperará solo.
*   **La tablet no cambia de pantalla**: Verifica que la tablet tenga conexión a internet y que la URL sea la correcta. La tablet revisa nuevas solicitudes cada 5 segundos.
*   **Firma no aparece en la PC**: Asegúrate de que el huésped haya presionado el botón "Finalizar Registro" en la tablet.

---
*Manual generado por Antigravity AI - HotelOS 2026*
