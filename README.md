# Sistema de Gestió de Reserves d'Aules

Aquest projecte és una aplicació web per gestionar la reserva d'aules al centre educatiu **Institut Sa Palomera**. Està desenvolupat seguint el patró **Model-Vista-Controlador (MVC)** per garantir una estructura clara, modular i escalable.

## Tecnologies Utilitzades

- **Frontend**:
  - HTML5, CSS3 i JavaScript.
  - Llibreria **FullCalendar** per a la gestió visual de reserves.
  - Notificacions visuals amb **Toastr**.

- **Backend**:
  - **PHP** natiu per a la lògica del sistema.
  - Autenticació amb **OAuth**.
  - Persistència de dades amb **MySQL**.

- **Altres**:
  - Ús de variables d'entorn amb `.env` per a configuracions sensibles.
  - Estructura modular basada en MVC.

## Estructura del Projecte

- **Model**: Gestiona l'accés a la base de dades i les operacions relacionades amb les entitats principals (usuaris, reserves, aules).
  - Exemple: `model/aules.php`, `model/usuari.php`.

- **Vista**: Conté les interfícies d'usuari i els components visuals.
  - Exemple: `view/parts/header.php`, `view/parts/modalCrearReserva.php`.

- **Controlador**: Gestiona la lògica de negoci i la comunicació entre el model i la vista.
  - Exemple: `controlador/crearReserva.php`, `controlador/getReservesPropies.php`.

## Funcionalitats Principals

1. **Gestió d'Usuaris**:
   - Autenticació segura amb OAuth.
   - Rols diferenciats: Administrador i Professor.

2. **Gestió de Reserves**:
   - Crear, consultar, modificar i eliminar reserves.
   - Validacions estrictes per evitar conflictes horaris o d'aula.
   - Gestió de repeticions setmanals o mensuals.

3. **Interfície Visual**:
   - Calendari interactiu amb **FullCalendar**.
   - Codificació cromàtica per identificar aules.
   - Feedback immediat amb notificacions visuals.

## Configuració

1. **Base de Dades**:
   - Importa el fitxer `reservesaules.sql` per crear la taula necessària.

2. **Variables d'Entorn**:
   - Crea un fitxer `.env` amb les credencials de la base de dades i configuracions OAuth.

3. **Servidor**:
   - Executa el projecte en un servidor local com **XAMPP**.

## Estructura de Carpetes

- `app/`: Conté els controladors, models i vistes.
- `config/`: Configuració de connexió a la base de dades.
- `public/`: Arxius públics com CSS, JS i imatges.
- `vendor/`: Llibreries instal·lades amb Composer.

---

Aquest projecte està pensat per ser fàcilment extensible i adaptable a les necessitats del centre educatiu.