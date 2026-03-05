# Cardgust – Estado Técnico Actual
## Módulo: Warhammer Quest ACG (2026)

---

# 1. Stack Tecnológico

Backend:
- Laravel 12
- Livewire 4 (Single File Components)
- MariaDB

Frontend:
- Tailwind CSS
- Vite
- textFit.js (integración manual sin npm)

Entorno:
- Linux (dev)
- Deploy previsto: Dinahosting (Apache + PHP)

---

# 2. Arquitectura General

Arquitectura modular por juego.

Cada juego es autónomo y encapsulado:

- Rutas propias
- Controladores propios
- Tablas propias
- Assets propios
- Lógica de render independiente

Juego activo:
- whquestacg

Objetivo estructural:
Permitir múltiples juegos coexistiendo sin acoplamiento entre dominios.

---

# 3. Motor de Render de Carta (Calidad Imprenta)

Resolución real de trabajo:
- 300 ppp (print real)

Conversión física exacta:
px = (mm / 25.4) * 300

Función base:
protected function mmToPx(float $mm): int

Todo el sistema visual se basa en medidas físicas reales, no aproximaciones CSS.

---

# 4. Estructura del Visor

Jerarquía DOM:

Wrapper
 └ Spacer dinámico (altura real escalada)
     └ Zoom Layer (transform: scale)
         └ Total Print Area
             └ Bleed
                 └ Carta real

Zoom visual:
transform: scale()
transform-origin: top center

Altura dinámica calculada:
#[Computed]
public function scaledHeight()

El sistema mantiene proporciones exactas sin deformación.

---

# 5. Sistema de Imprenta

Implementado:

- Cut Margin (3mm)
- Bleed (3mm)
- Safe Area opcional
- Marcas de corte SVG precisas
- Toggle dinámico de zonas

Todo calculado en píxeles reales derivados de mm.
No hay valores mágicos.

---

# 6. Sistema de Ajuste Tipográfico (textFit)

Integración manual sin Alpine ni npm.

Ubicación:
public/scripts/textFit.min.js

Activación:
- DOMContentLoaded
- MutationObserver (compatibilidad Livewire 4)

Configuración estable actual:

- multiLine
- reProcess
- alignVert configurable
- minFontSize = 8
- maxFontSize proporcional a altura contenedor

Garantiza:
- Ajuste dinámico en tiempo real
- Recalculo tras re-render Livewire
- Compatibilidad con zoom

---

# 7. Sistema de Acciones

Características:

- 1 a 3 acciones
- Distribución proporcional automática
- Centrado vertical y horizontal
- Estructura tabular estable (table-fixed)

No depende del número de acciones a nivel estructural.
La presentación se adapta dinámicamente.

---

# 8. Sistema de Skins (Arquitectura Avanzada)

Objetivo:
Separar completamente diseño de datos.

Permite variar:

- Fondos
- Colores
- Tipografía
- Layout
- Distribución estructural

Configuración central:
config/skins.php

Ejemplo conceptual:

'classic' => [
    'font_title' => 'CelestiaAntiquaSB',
    'font_body'  => 'Garamond',
    'layout' => [...],
    'background' => 'skins/classic/bg.png',
]

Acceso desde componente:

public string $skin = 'classic';

public function getSkinConfigProperty()
{
    return config("skins.{$this->skin}");
}

Arquitectura preparada para:
- Motor de layouts configurable
- Resolución dinámica de diseño
- Evolución hacia theming avanzado

---

# 9. Sistema Multi-Idioma (Independiente de la App)

La app puede cambiar idioma global.
Las cartas mantienen idioma propio.

Estructura:

Tabla principal:
whq_enemigos
- original_locale

Tabla traducciones:
whq_enemigo_translations
- enemigo_id
- locale
- titulo
- acciones
- efectos
- nemesis
- flavor

Restricción:
UNIQUE(enemigo_id, locale)

Modelo desacoplado del sistema de localización de Laravel.

---

# 10. Sistema de Fallback

Comportamiento:

- Si existe traducción → mostrar
- Si no existe → fallback al original
- Indicador visual si está en fallback

Método central:

public function getTranslationOrFallback(string $locale)

Retorna:

[
    'data' => TranslationModel,
    'is_fallback' => bool
]

El render no necesita lógica adicional.

---

# 11. Creación Inline de Traducciones

Desde la vista de carta:

- Detección automática si no existe traducción
- Botón "Crear traducción"
- Formulario inline
- Guardado vía Livewire
- Re-render automático

Propiedades clave:

public string $cardLocale;
public bool $creatingTranslation;
public array $newTranslation;

Método:
public function storeTranslation()

Sistema totalmente reactivo.

---

# 12. Estado Actual

✔ Motor de render 300ppp funcional  
✔ Sistema de zoom estable  
✔ textFit estable con Livewire 4  
✔ Acciones dinámicas proporcionales  
✔ Arquitectura preparada para skins  
✔ Sistema multi-idioma desacoplado  
✔ Fallback automático con aviso  
✔ Creación inline de traducciones  

---

# 13. Próxima Evolución Natural

1. Normalizar acciones y efectos en tablas separadas.
2. Resolver sistema completo de skins dinámicos.
3. Exportación PDF (evaluar Chromium headless vs DomPDF).
4. Sistema de roles para traducciones.
5. Panel de revisión y traducciones pendientes.
6. Motor de layouts 100% declarativo por skin.

---

# Dirección del Proyecto

Cardgust está evolucionando hacia:

Motor modular de cartas imprimibles multi-idioma,
con render físico real,
arquitectura desacoplada,
y sistema de skins configurable.
