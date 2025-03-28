# Projecte Final - Gestor de Reserves de Classes

## 1. Introducció
Aquest projecte té com a objectiu desenvolupar un sistema de gestió de reserves d'aules per al professorat de la intranet del centre de l'institut Sa Palomera. El sistema permetrà als professors reservar una aula per a la seva classe durant un temps determinat. Aquest sistema facilitarà l'organització de les aules, millorarà l'eficiència en l'assignació de recursos i ajudarà a evitar conflictes d'horaris amb el professorat.

## 2. Requeriments Funcional
Les funcionalitats principals del sistema inclouen:
- **Registre de professors (usuaris):** Els professors es poden registrar al sistema.
- **Reserva d'aules:** Els professors poden reservar aules per a classes, especificant la data i l'hora.
- **Gestió de reserves:** Els usuaris poden modificar o eliminar les seves reserves.
- **Veure disponibilitat de les aules:** Els professors poden consultar la disponibilitat de les aules.
- **Accés diferenciat:** Hi haurà dos tipus d'accés:
  - **Administrador:** Gestiona usuaris i visualitza totes les reserves per modificar-les o eliminar-les.
  - **Professor (Usuari):** Reserva i gestiona les seves aules.

## 3. Requeriments Tècnics
Tecnologies utilitzades:
- **Backend:** PHP.
- **Frontend:** HTML5, CSS3, TypeScript/JavaScript.
- **Base de dades:** MySQL.
- **Servidor Web:** XAMPP (usant Apache).
- **Framework CSS:** Bootstrap (per al disseny de les interfícies).

### 3.1 FrontEnd
El frontend serà desenvolupat utilitzant:
- **HTML5** per a l'estructura del contingut.
- **CSS3** amb **Bootstrap** per als estils visuals.
- **JavaScript/TypeScript** per a la interactivitat dinàmica de la interfície.

El disseny serà senzill, elegant i intuïtiu, utilitzant components bàsics de Bootstrap per facilitar l'objectiu del projecte.

### 3.2 BackEnd
El backend serà desenvolupat en **PHP**. Els aspectes principals inclouen:
- **Autenticació d'usuaris**: Els professors s'autenticaran amb el seu nom d'usuari i contrasenya del centre.
- **Controladors** per gestionar les reserves (crear, modificar, eliminar).
- **Models** per interactuar amb la base de dades (aules, reserves, usuaris).

## 4. Diagrames
### 4.1 Diagrama de Casos d'Ús
El diagrama de casos d'ús representarà les interaccions entre els usuaris i el sistema, mostrant com cada tipus d'usuari interactua amb el sistema:
- Registrar usuari.
- Reservar aula.
- Veure reserves.
- Administrar usuaris (per a l'administrador).

<img src="./Images/Diagrama_Casosdus.jpg" alt="Diagrama de Casos d'Ús" width="700"/>


### 4.2 Diagrama d'Activitats
Aquest diagrama mostrarà el flux de treball de les funcionalitats principals, com el procés de reserva d'una aula per part d'un professor.
<img src="./Images/Diagrama_Activitats.jpg" alt="Diagrama d'Activitats" width="500"/>

### 4.3 Diagrama de Classes
El diagrama de classes representarà les entitats del sistema i les seves relacions:
- **Classe Usuari**: Representa els professors.
- **Classe Reserva**: Representa les reserves de les aules.
- **Classe Aula**: Representa les aules disponibles.
<img src="./Images/Diagrama_Classes.jpg" alt="Diagrama de Classes" width="700"/>

### 4.4 Diagrama BD (Base de Dades)
El diagrama de base de dades mostrarà les taules i les seves relacions:
- **Usuaris**: ID, nom, correu, contrasenya, tipus d'usuari.
- **Aules**: ID, nom de l'aula, capacitat.
- **Reserves**: ID, usuari_id, aula_id, data_hora_inici, data_hora_fi.
<img src="./Images/Diagrama_BD.jpg" alt="Diagrama BD" width="700"/>

## 5. Disseny d'Interfícies
El disseny de les interfícies estarà dividit en dos vistes:
- **Vista User (Professor):** Permet gestionar les reserves d'aules.
- **Vista Administrador:** Permet gestionar els usuaris i revisar les reserves.









## 11. Implementació
La implementació del projecte es farà en dues parts principals:
- **Backend**: Programació en PHP natiu per gestionar la lògica de les reserves, autenticar usuaris i gestionar les interaccions amb la base de dades.
- **Frontend**: Desenvolupament de les interfícies en HTML, CSS (Bootstrap) i TypeScript/JavaScript per a la interactivitat de l'usuari.

## 12. Proves Unitàries
Detallar les proves realitzades per garantir que totes les funcionalitats crítiques funcionen correctament, com el login, la creació i eliminació de reserves.

## 13. Desplegament del Projecte
El projecte serà desplegat en un entorn real utilitzant **XAMPP** com a servidor local per a PHP i **MySQL** per a la base de dades. El codi es desarà en un repositori a **GitHub**.

## 14. Sistema (XAMPP, PHP, MySQL, Hosting)
El sistema estarà basat en **XAMPP** per a la gestió del servidor, **PHP** per al backend, **MySQL** per a la base de dades, i el **hosting** serà gestionat a través de Don Dominio.

## 15. Enllaços al Projecte
Enllaços rellevants:
- [Repositori a GitHub](#)
- [Aplicació Web](#)
- [Documentació addicional](#)

## 16. Propostes de Millora
Reflexions sobre possibles millores:
- **Millores de rendiment**.
- **Notificacions per correu electrònic** quan es realitzin o modifiquin reserves.
- **Integració amb calendaris** per a una millor visualització de les reserves.

## 17. Conclusions
Aquest projecte té el potencial de millorar l'organització i gestió de les reserves d'aules al centre, facilitant la tasca tant per als professors com per als administradors.

## 18. Webgrafia
- [Documentació oficial de PHP](https://www.php.net)
- [Bootstrap Documentation](https://getbootstrap.com/docs/)
- [MySQL Documentation](https://dev.mysql.com/doc/)
- [Figma](https://www.figma.com/)

## 19. Documentació i Ajuda
Enllaços i recursos per ajudar en el desenvolupament del projecte.

## 20. Eines
- **XAMPP** per al servidor local.
- **PHPMyAdmin** per gestionar la base de dades.