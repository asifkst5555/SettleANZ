# SettleANZ Enterprise SaaS Design System

This documentation manual provides frontend engineers, product designers, and full-stack developers with the instructions, token catalog, and reusable component library specifications required to build and scale admin modules on SettleANZ.

---

## 1. Global Design Tokens (CSS Variables)

All design variables are declared in the `:root` pseudo-class inside `public/admin.css` and `resources/css/site/base.css`. Always use these variables instead of hard-coding values.

### A. Semantic Color Palette
*   **Primary Accent**: `var(--sz-primary)` (`#14a394`) | Hover: `var(--sz-primary-hover)` (`#0b7a75`)
*   **Neutral Text**: `var(--sz-text)` (`#0f172a`) | Muted: `var(--sz-text-muted)` (`#64748b`)
*   **Semantic States**:
    *   *Success*: `var(--sz-success)` (`#10b981`)
    *   *Warning*: `var(--sz-warning)` (`#f59e0b`)
    *   *Danger*: `var(--sz-danger)` (`#ef4444`)
    *   *Info*: `var(--sz-info)` (`#3b82f6`)

### B. Typography
*   **Sans Font (Body)**: `var(--sz-font-sans)` ('Inter')
*   **Display Font (Headers)**: `var(--sz-font-display)` ('Plus Jakarta Sans')
*   **Scale**:
    *   `var(--sz-font-xs)` (12px)
    *   `var(--sz-font-sm)` (13px)
    *   `var(--sz-font-md)` (14px)
    *   `var(--sz-font-lg)` (16px)
    *   `var(--sz-font-xl)` (18px)
    *   `var(--sz-font-2xl)` (20px)
    *   `var(--sz-font-3xl)` (24px)
*   **Weights**:
    *   `var(--sz-weight-normal)` (400)
    *   `var(--sz-weight-medium)` (500)
    *   `var(--sz-weight-semibold)` (600)
    *   `var(--sz-weight-bold)` (700)

### C. Spacing Scale
*   `var(--sz-space-1)` (4px)
*   `var(--sz-space-2)` (8px)
*   `var(--sz-space-3)` (12px)
*   `var(--sz-space-4)` (16px)
*   `var(--sz-space-5)` (20px)
*   `var(--sz-space-6)` (24px)
*   `var(--sz-space-8)` (32px)
*   `var(--sz-space-10)` (40px)
*   `var(--sz-space-12)` (48px)
*   `var(--sz-space-16)` (64px)

### D. Elevations & Border Radii
*   **Border Radii**:
    *   `var(--sz-radius-xs)` (4px)
    *   `var(--sz-radius-sm)` (6px)
    *   `var(--sz-radius-md)` (10px)
    *   `var(--sz-radius-lg)` (14px)
    *   `var(--sz-radius-xl)` (20px)
    *   `var(--sz-radius-full)` (9999px)
*   **Shadows**:
    *   `var(--sz-shadow-xs)`
    *   `var(--sz-shadow-sm)`
    *   `var(--sz-shadow-md)` (standard card elevation)
    *   `var(--sz-shadow-lg)` (dropdowns & popovers)
    *   `var(--sz-shadow-xl)` (drawers & modals)

---

## 2. Reusable Blade Components

The Blade component files are located under `resources/views/components/`.

### 1. Button (`<x-admin-button>`)
*   **Props**: `variant` (`primary` | `secondary` | `danger` | `ghost`), `size` (`sm` | `md` | `lg`)
*   **Example**:
    ```html
    <x-admin-button variant="primary" size="md" onclick="submitForm()">
        @include('admin.partials.icon', ['name' => 'save', 'size' => 16])
        <span>Save Changes</span>
    </x-admin-button>
    ```

### 2. Icon Button (`<x-admin-icon-button>`)
*   **Props**: `icon` (Lucide icon name), `variant` (`primary` | `secondary` | `danger` | `ghost`), `size` (`sm` | `md` | `lg`)
*   **Example**:
    ```html
    <x-admin-icon-button icon="trash" variant="danger" size="sm" onclick="deleteRecord({{ $id }})" />
    ```

### 3. Text Input & Text Area (`<x-admin-input>`, `<x-admin-textarea>`)
*   **Props**: Standard input attributes (`name`, `placeholder`, `value`, `disabled`, `required`)
*   **Example**:
    ```html
    <x-admin-input name="email" type="email" placeholder="john@example.com" required />
    <x-admin-textarea name="notes" placeholder="Add internal call notes..."></x-admin-textarea>
    ```

### 4. Switch Toggle (`<x-admin-switch>`)
*   **Props**: `checked` (`true` | `false`), `name` (input name), `id` (element ID)
*   **Example**:
    ```html
    <x-admin-switch name="is_active" id="activeToggle" :checked="$user->active" />
    ```

### 5. Profile Avatar (`<x-admin-avatar>`)
*   **Props**: `name` (User Full Name), `email` (for unique color mapping), `photo` (image URL path, optional), `size` (`sm` | `md` | `lg`)
*   **Example**:
    ```html
    <x-admin-avatar :name="$lead->full_name" :email="$lead->email" size="md" />
    ```

### 6. Alert Box (`<x-admin-alert>`)
*   **Props**: `type` (`success` | `warning` | `danger` | `info`), `title` (header string), `dismissible` (`true` | `false`)
*   **Example**:
    ```html
    <x-admin-alert type="warning" title="Warning Action Required" dismissible="true">
        Please assign a staff user to complete this lead's file.
    </x-admin-alert>
    ```

### 7. Dropdown Wrapper (`<x-admin-dropdown>`)
*   **Props**: `trigger` (clickable HTML slot), `align` (`left` | `right`)
*   **Example**:
    ```html
    <x-admin-dropdown align="right">
        <x-slot:trigger>
            <x-admin-button variant="ghost" size="sm">
                @include('admin.partials.icon', ['name' => 'more-horizontal'])
            </x-admin-button>
        </x-slot:trigger>
        
        <a href="/edit" class="dropdown-item">Edit</a>
        <a href="/delete" class="dropdown-item danger">Delete</a>
    </x-admin-dropdown>
    ```

### 8. Modal Box (`<x-admin-modal>`)
*   **Props**: `id` (trigger identity element ID), `title` (header text)
*   **Example**:
    ```html
    <x-admin-modal id="confirmDeleteModal" title="Confirm Account Deletion">
        <p>Are you sure you want to permanently delete this lead? This action cannot be undone.</p>
        <div style="display: flex; gap: 0.5rem; justify-content: flex-end; margin-top: 1.5rem;">
            <x-admin-button variant="ghost" onclick="closeModal('confirmDeleteModal')">Cancel</x-admin-button>
            <x-admin-button variant="danger">Delete User</x-admin-button>
        </div>
    </x-admin-modal>
    ```

### 9. Slide Drawer (`<x-admin-drawer>`)
*   **Props**: `id` (element ID), `title` (panel header text)
*   **Example**:
    ```html
    <x-admin-drawer id="leadDetailsDrawer" title="Lead Information">
        <!-- Details markup -->
    </x-admin-drawer>
    ```

### 10. Table System (`<x-admin-table>`)
*   **Props**: `headers` (Array of column header strings)
*   **Example**:
    ```html
    <x-admin-table :headers="['Lead', 'Email', 'Source', 'Actions']">
        @foreach($leads as $lead)
            <tr>
                <td>{{ $lead->full_name }}</td>
                <td>{{ $lead->email }}</td>
                <td><x-admin-tag color="primary">{{ $lead->form_type }}</x-admin-tag></td>
                <td>
                    <x-admin-icon-button icon="eye" size="sm" />
                </td>
            </tr>
        @endforeach
    </x-admin-table>
    ```

---

## 3. Motion System & Timing Defaults

Standardize animations with our predefined duration:
*   **Timing Function**: `cubic-bezier(0.16, 1, 0.3, 1)`
*   **Speeds**: Fast (`150ms`), Normal (`250ms`), Slow (`350ms`)

Standard components support fade-in, scale, and side-slide classes (see [admin.css](file:///wsl$/Ubuntu/home/asifk/projects/SettleANZ/public/admin.css) keyframe declarations for `.sz-toast`, `.sz-modal-box`, and `.sz-drawer-box`).
