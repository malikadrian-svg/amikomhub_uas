# Creative Agency Design System Documentation

## 01. Design Philosophy: Intentional Minimalism & SaaS Utility

This design system serves as the visual and behavioral foundation for premium, SaaS-friendly digital products. Developed with a Dribbble creative agency aesthetic, this system focuses on visual polish, strict typography hierarchy, and spacious layouts without sacrificing the information density required by modern cloud platforms and dashboard environments.

- **Light Mode First:** This system is engineered specifically for daytime clarity. Light surfaces, soft grey borders, and deep text values ensure optimal legibility and maximum screen comfort over hours of continuous usage.
- **SaaS-Friendly Layout Density:** We reject the clutter of default UI libraries, yet we respect a tenant of rich software system design: data must remain visible. We achieve this by optimizing structural whitespace around data hubs rather than hiding information.
- **Whitespace as a Feature:** Whitespace is not empty space; it is the structural canvas. We prioritize breathing room over excessive content grouping.
- **No Glassmorphism, Neumorphism, or Skeuomorphism:** We design digital-first surfaces. UI elements do not mimic transparent frosted glass, clay extrusions, or real-world physics. Layers are flat, well-bounded, and separated by clear, meaningful spatial shifts.
- **Color Discipline:** Saturated primary colors are excluded from standard backgrounds. Color is restricted to brand identifiers, micro-alerts, and interactive highlights.

---

## 02. Color Palette (Light Mode Specification)

The brand visual identity is unified under a premium Violet primary scale. Neutrals are cool slates and off-whites that keep the light theme calm, elegant, and modern.

### Primary Color Scale

| Token | Hex | Usage Context |
| :--- | :--- | :--- |
| `violet-50` | `#f3ebfe` | Light background for alerts, input selections, active state highlights |
| `violet-100` | `#d9c1fb` | Selected state backgrounds, badges, secondary focus guides |
| `violet-200` | `#c6a3f9` | Accent borders, selected input margins, subtle item frames |
| `violet-300` | `#ad78f6` | Secondary interactive toggle markers, secondary icon borders |
| `violet-400` | `#9d5ef5` | Active focus indicator rings, hover outlines |
| `violet-500` | `#8436f2` | **Primary Brand Color.** All main CTAs, active links, primary fills |
| `violet-600` | `#7831dc` | **Hover State.** Shift color for primary buttons and brand focus elements |
| `violet-700` | `#5e26ac` | **Active State.** Pressed states for primary navigation and CTAs |
| `violet-800` | `#491e85` | Deep text highlights on light purple accents, badge texts |
| `violet-900` | `#371766` | Saturated icon bases, specialized dark purple cards |

### Light Mode Neutrals

| Token | Hex | Usage Context |
| :--- | :--- | :--- |
| `neutral-0` | `#ffffff` | Primary surface background (cards, panels, dropdowns, inputs) |
| `neutral-50` | `#f8fafc` | Main application shell background, viewport layout background |
| `neutral-100` | `#f1f5f9` | Low-contrast separators, tabs divider lines, inactive containers |
| `neutral-200` | `#e2e8f0` | Standard disabled states, light border limits, inactive inputs |
| `neutral-400` | `#94a3b8` | Placeholder text, secondary icons, breadcrumb paths |
| `neutral-600` | `#475569` | Secondary description copy, subtitles, dashboard counts |
| `neutral-800` | `#1e293b` | Primary text copy, labels, primary headings, input values |
| `neutral-950` | `#0f172a` | Display headings, primary buttons text, dark navigation surfaces |

---

## 03. Spacing System (4px / 4pt Grid)

We utilize a strict 4px spacing system to establish structural tension. By adhering to this grid, all layouts align with clean proportions.

- **`space-1` (4px):** Micro gaps (e.g., color dots, checkbox-to-label inline gaps, tiny badge borders).
- **`space-2` (8px):** Small layout adjustments (e.g., menu items list padding, badge horizontal padding).
- **`space-3` (12px):** Button vertical padding, small control margins.
- **`space-4` (16px):** Standard horizontal padding for inputs, list item margins, table inner cells.
- **`space-5` (20px):** Form labels to inputs separation, smaller card body paddings.
- **`space-6` (24px):** Standard card padding, outer container layout gutters.
- **`space-8` (32px):** Card grid column gap, dashboard titles spacing.
- **`space-10` (40px):** Larger form group columns, splash-page sections.
- **`space-12` (48px):** Modal internal content wrap, table top action gutters.
- **`space-16` (64px):** Main page section dividers, huge hero block margins.
- **`space-20` (80px):** Minimal splash screen offsets.
- **`space-24` (96px):** Maximum breathable offsets for elite agency showcase views.

---

## 04. Typography Scale

- **Font Family:** `Manrope` (A geometric sans-serif that balances modern identity with crisp readability).
- **Strict Weights:** Only `400` (Regular), `500` (Medium), `600` (Semi-Bold), and `700` (Bold) are allowed. Never use other weights.
- **Header Spacing:** All headings (H1–H6) must use `letter-spacing: -0.02em`. Do not apply negative letter spacing to body, captions, labels, or buttons.

| Token | size | weight | line-height | letter-spacing | Usage Recommendation |
| :--- | :--- | :--- | :--- | :--- | :--- |
| **Display** | `40px` | `700` | `1.1` | `-0.02em` | Main hero landing screens, giant analytic numbers. |
| **H1** | `32px` | `700` | `1.2` | `-0.02em` | Index page titles, onboarding header. |
| **H2** | `24px` | `600` | `1.3` | `-0.02em` | Card group titles, dynamic dashboard tabs. |
| **H3** | `20px` | `600` | `1.4` | `-0.02em` | Section headers, drawer sidebars. |
| **H4** | `18px` | `600` | `1.4` | `-0.02em` | Card titles, action control subheadings. |
| **H5** | `16px` | `600` | `1.5` | `-0.02em` | Table headers, list titles. |
| **H6** | `14px` | `600` | `1.5` | `-0.02em` | Inner panel headers, form group headers. |
| **Subtitle** | `16px` | `500` | `1.6` | `0em` | Explanatory copy directly below active page titles. |
| **Body Large**| `16px` | `400` | `1.6` | `0em` | Landing detail paragraphs, blog content. |
| **Body** | `14px` | `400` | `1.5` | `0em` | Standard text body, table descriptions, profile cards. |
| **Body Small**| `13px` | `400` | `1.5` | `0em` | Side-metadata details, form input guidance texts. |
| **Caption** | `12px` | `500` | `1.4` | `0.02em` | System tags, inactive indicators, micro dates. |
| **Label** | `13px` | `600` | `1.4` | `0em` | Active field labels, toggle switch tags. |
| **Button** | `14px` | `600` | `1.0` | `-0.01em` | Text labels inside buttons and controls. |

---

## 05. Structural Elements

To keep designs feeling modern yet structured, we adhere to soft geometry and low depth elevation.

### Border Radius Scale

We strictly avoid sharp corners, keeping components feeling refined.
- **`xs` (4px):** Form controls, checkboxes, radio selections.
- **`sm` (6px):** Dropdown rows, tag badges.
- **`md` (8px):** Small layout buttons, interactive icons list.
- **`lg` (12px):** Standard text borders, select sliders, search bars.
- **`xl` (16px):** Layout cards, banners, system toast containers.
- **`2xl` (24px):** Primary modal displays, floating side control drawers.
- **`full` (9999px):** Switches, profiles, pill trackers.

### Border Rules

We use thin borders to delineate structure without adding visual clutter.
- **Rules:** Exactly `1px` border width. Thick lines are prohibited.
- **Default color:** Neutral Slate with low opacity `neutral-200` (`#e2e8f0`) for elements, and `neutral-100` (`#f1f5f9`) for internal separators.

### Subtle Shadows (Depth Hierarchy)

We restrict shadow usage. No dark floating shapes.
- **Flat (No Shadow):** standard buttons, content list item groups, borders are sufficient.
- **Subtle Elevation (Small):** `box-shadow: 0 1px 3px 0 rgba(15, 23, 42, 0.03), 0 1px 2px -1px rgba(15, 23, 42, 0.03);`
  - Used for cards, dashboard panels.
- **Medium Elevation:** `box-shadow: 0 4px 6px -1px rgba(15, 23, 42, 0.05), ...;`
  - Used for popover options, search inputs active states.
- **High Elevation (Overlay):** `box-shadow: 0 10px 15px -3px rgba(15, 23, 42, 0.06), ...;`
  - Used only on Modals, large dashboard alerts.

---

## 06. Iconography

Iconography must match clean, outline guidelines:
- **Outline Icons Only:** Never mix outlines with solid-filled glyphs.
- **Visual Sizing:** Baseline size is `20px` for actions, `16px` for small list labels.
- **Consistency:** Round caps, round corner joints, and a fixed stroke width of `1.75px` or `2.0px`.

---

## 07. Interaction Components

All interactive states must be responsive, showing soft transitions (150ms ease-out) without jarring jumps.

### Buttons

Buttons use exact vertical sizes with balanced text tracking.

```css
/* Base Button Specs */
.btn {
  height: 40px;
  border-radius: var(--radius-md);
  font-family: 'Manrope', sans-serif;
  font-size: var(--body-small);
  font-weight: 600;
  display: inline-flex;
  align-items: center;
  justify-content: center;
  transition: all 150ms ease-out;
}
```

*   **Primary Button:**
    *   Background: `violet-500` (`#8436f2`). Text: `#ffffff`
    *   Hover: `violet-600` (`#7831dc`)
    *   Active/Pressed: `violet-700` (`#5e26ac`)
    *   Padding: `12px` vertical, `24px` horizontal (`space-3` x `space-6`)
*   **Secondary Button:**
    *   Background: `neutral-0` (`#ffffff`). Border: `1px solid neutral-200`
    *   Text color: `neutral-800`
    *   Hover: Background `neutral-50` (`#f8fafc`)
    *   Active: Background `neutral-100` (`#f1f5f9`)
*   **Ghost Button:**
    *   Background: `transparent`. Text color: `neutral-800`
    *   Hover: Background `neutral-50`
    *   Active: Background `neutral-100`
*   **Text Button:**
    *   Background: `transparent`. Text color: `violet-500`
    *   Hover: Text `violet-600`
    *   Active: Text `violet-700`
    *   Padding: Minimal inline margins, no vertical offsets.
*   **Destructive Button:**
    *   Background: `transparent`. Text color: `#e11d48` (rose-600)
    *   Hover: Background `#fff1f2` (rose-50)
    *   Active: Background `#ffe4e6` (rose-100)
*   **Disabled State:**
    *   Background: `neutral-100`. Text: `neutral-400`. Border: `neutral-200`
    *   Cursor: `not-allowed`. Transitions: disabled.
*   **Loading State:**
    *   Display a spinning circle, sizing matches text size. Maintain button height (`40px`) to prevent page shift.

### Form Inputs

Input containers are generous, maintaining clean whitespace around active inputs.

- **Text Field, Search, Password, Select, and Textarea:**
  - Height standard: `44px` (`56px` for textarea height base).
  - Padding: `12px` vertical, `16px` horizontal (`space-3` x `space-4`).
  - Border Radius: `radius-lg` (12px).
  - Typography: `Body` size (`14px`), fallback value `neutral-800`.
  - Default: Border `1px` solid `neutral-200` (`#e2e8f0`). Background `#ffffff`.
  - Hover: Border changes to `neutral-400`.
  - Focus: Border matches `violet-400` (`#9d5ef5`) with a soft glow wrapper (`box-shadow: 0 0 0 4px var(--violet-50)`).
  - Error: Border changes to `#f43f5e` (rose-500), input guidance font turns to `#e11d48`.
  - Success: Border changes to `#10b981` (emerald-500), displaying success outline markers.
  - Disabled: Background `neutral-50`, text label `neutral-400`.

- **Checkbox & Radio:**
  - Standard size: `16px` diameter/square. Checked fills use `violet-500`.

- **Switch:**
  - Track dimensions: `36px` width, `20px` height. Radius `radius-full`. Checked track is `violet-500`.

---

## 08. Cards & Container Surfaces

Cards group data points cleanly without feeling crowded.
- **Card layout:** Flat or low elevation. Keep borders thin and low-contrast.
- **Styling:** Border `1px` solid `neutral-100` (`#f1f5f9`). Background `neutral-0` (`#ffffff`).
- **Padding:** Default uses `space-6` (24px) padding. Compact grids use `space-4` (16px).
- **Design Rule:** Never separate header card dividers with solid lines. Use typographic weight shifts, spacing (`space-10`), or subtle background groupings.

---

## 09. Navigation Elements

Align with whitespace-driven hierarchy.

- **Navbar:** Height `72px`. Left logo, center links separated by `space-6`, right primary workspace. Uses single bottom separator `1px solid neutral-100`.
- **Sidebar:** Left pane layout uses list items. Highlight active states with a `violet-50` background block and active `violet-800` font labels. Avoid dark borders.
- **Tabs:** Inline styling. Active item uses `violet-500` bottom indicator border line.
- **Breadcrumb:** Path markers separated by neutral outline arrows (`>`).
- **Pagination & Menu:** Numbers in round tabs (`radius-md`). Active highlights use `violet-50` background.

---

## 10. Feedback Components

- **Alert:** Lightweight panel. Clean backgrounds (`violet-50` for information, `#fff1f2` for warn/error) with matching text shade.
- **Toast:** Slim floating blocks, fixed to top-right screen space. Radius `radius-xl`. Subtle bottom shadow.
- **Badge:** Symmetrical tags. Horizontal padding `space-2` (8px), vertical `space-1` (4px). Typography `Caption` size.
- **Progress:** A thin bar line: tracker background is `neutral-100`, actual loader is `violet-500`. Height `4px`.
- **Tooltip:** Small overlay tag with a `neutral-950` background and white `12px` font text.
- **Modal:** Center screen overlays. High depth shadow. Wrap backdrop with `rgba(15, 23, 42, 0.3)` dark blur.
- **Skeleton & Loading:** Pulse animations must remain calm (`duration: 1.5s`). Use a flat slate color fill (`neutral-100`).
- **Empty State:** Large illustration or subtle icon header centering, combined with main action button and subtitle details.

---

## 11. Layout, Motion, and Accessibility

### Whitespace & Layout Gutters
- **Visual Rhythm:** Avoid cramped views. Group related elements within margins of `space-8` or `space-10`.
- **Asymmetric Tension:** When suitable, use asymmetrical splits (e.g. 1/3 card summary, 2/3 main activity chart) to give page design a dynamic Dribbble look.

### Motion Principles
Animations are silent, brief, and purposeful.
- **Base Interactive Transition:** `150ms` (hover states, toggle switches).
- **Component Transitions:** `200ms` (dropdown list expanding, input highlight transitions).
- **Page Transitions:** `250ms` (slidings, large side sheets).
- **Easing:** Rely on ease-out-expo (`cubic-bezier(0.16, 1, 0.3, 1)`) for snappy layouts.

### Accessibility Standards
- **Contrast Check:** Verify all typography colors against neutral backgrounds pass WCAG AA ratings (minimum 4.5:1 ratio).
- **Visual Focus:** Keyboards focus modes are explicit (`outline: 2px solid violet-400`).
- **Semantic Structure:** Keep standard tags intact. Navbars use `<nav>`, labels are bound to `<input>` fields.
