@php
    $categoryOptions = ['Housing', 'Banking', 'Migration', 'Settlement', 'Visa', 'Employment', 'Healthcare', 'Moving', 'Working', 'Lifestyle'];
    $currentCategory = old('category', $post->category ?? null);
    $initialFaqItems = old('faq_items');
    if (is_string($initialFaqItems) && $initialFaqItems !== '') {
        $initialFaqItems = json_decode($initialFaqItems, true);
    }
    if (!is_array($initialFaqItems)) {
        $initialFaqItems = is_array($post->faq_items ?? null) ? $post->faq_items : [];
    }

    $currentImage = \App\Support\BlogMedia::normalizeFilename(old('image', $post->image ?? ''));
    $currentImageUrl = !empty($currentImage) ? \App\Support\BlogMedia::url($currentImage) : '';
    $hasImage = !empty($currentImage) && !empty($currentImageUrl);
@endphp

<style>
    .post-editor {
        display: grid;
        grid-template-columns: minmax(0, 1fr) 340px;
        gap: 1.5rem;
        width: 100%;
        margin-top: 1rem;
    }
    .post-editor *, .post-editor *::before, .post-editor *::after { box-sizing: border-box; }
    @@media (max-width: 1024px) {
        .post-editor { grid-template-columns: 1fr; }
    }
    .post-main {
        background: #fff;
        border: 1px solid #e3e8ee;
        border-radius: 12px;
        padding: 1.75rem 1.85rem 2rem;
        box-shadow: 0 6px 18px rgba(15, 23, 42, 0.05);
        min-width: 0;
    }
    .post-side {
        display: flex;
        flex-direction: column;
        gap: 1rem;
    }
    .post-tabs {
        display: flex;
        align-items: center;
        justify-content: flex-start;
        gap: 0.9rem;
        margin-bottom: 1.35rem;
        padding-bottom: 1rem;
        border-bottom: 1px solid #e7edf3;
    }
    .post-tabs__group {
        display: inline-flex;
        align-items: center;
        gap: 0.45rem;
        padding: 0.35rem;
        background: linear-gradient(180deg, #f6f9fc 0%, #eef4f8 100%);
        border: 1px solid #dbe5ec;
        border-radius: 999px;
        box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.95);
    }
    .post-tab-btn {
        appearance: none;
        border: 0;
        background: transparent;
        color: #5f7282;
        font-size: 0.88rem;
        font-weight: 700;
        letter-spacing: 0.01em;
        padding: 0.7rem 1.1rem;
        border-radius: 999px;
        cursor: pointer;
        transition: background 0.18s ease, color 0.18s ease, box-shadow 0.18s ease, transform 0.18s ease;
    }
    .post-tab-btn:hover {
        color: #12384f;
        background: rgba(255, 255, 255, 0.8);
    }
    .post-tab-btn.is-active {
        background: linear-gradient(135deg, #0b7a75 0%, #0e8b84 100%);
        color: #fff;
        box-shadow: 0 10px 22px rgba(11, 122, 117, 0.22);
    }
    .post-tabs__meta {
        font-size: 0.78rem;
        color: #6f8090;
        letter-spacing: 0.04em;
        text-transform: uppercase;
        font-weight: 700;
        margin-left: auto;
    }
    .post-tabs__actions {
        display: inline-flex;
        flex-wrap: wrap;
        gap: 0.6rem;
        align-items: center;
    }
    .panel-toolbar {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 1rem;
        margin-bottom: 1rem;
        padding: 1rem 1.05rem;
        border: 1px solid #dce7ee;
        border-radius: 14px;
        background:
            linear-gradient(180deg, rgba(248, 251, 253, 0.95) 0%, rgba(255,255,255,0.98) 100%);
        box-shadow: inset 0 1px 0 rgba(255,255,255,0.9);
    }
    .panel-toolbar__copy strong {
        display: block;
        margin: 0 0 0.25rem;
        color: #12384f;
        font-size: 0.95rem;
        letter-spacing: -0.01em;
    }
    .panel-toolbar__copy span {
        display: block;
        color: #6b7f8f;
        font-size: 0.81rem;
        line-height: 1.5;
    }
    .panel-toolbar__actions {
        display: inline-flex;
        gap: 0.65rem;
        align-items: center;
        flex-shrink: 0;
    }
    .ai-btn {
        background: #FFF0EC;
        padding: 0.85rem 1.4rem;
        margin: 0;
        display: inline-flex;
        align-items: center;
        gap: 0.7rem;
        transform: translate(0%, 0%);
        overflow: hidden;
        color: #D17453;
        font-size: 0.9rem;
        font-weight: 700;
        letter-spacing: 0.04em;
        text-align: center;
        text-transform: none;
        text-decoration: none;
        border-radius: 10px;
        border: none;
        cursor: pointer;
        position: relative;
        transition: all 0.3s ease;
    }
    .ai-btn::before {
        content: '';
        position: absolute;
        top: 0px;
        left: 0px;
        width: 100%;
        height: 100%;
        background-color: #ff8c42;
        opacity: 0;
        transition: .2s opacity ease-in-out;
    }
    .ai-btn:hover::before {
        opacity: 0.1;
    }
    .ai-btn:hover {
        transform: translateY(-3px);
        color: #ff6b35;
    }
    .ai-btn span {
        position: absolute;
    }
    .ai-btn span:nth-child(1) {
        top: 0px;
        left: 0px;
        width: 100%;
        height: 2px;
        background: linear-gradient(to left, rgba(255, 140, 66, 0), #ff8c42);
        animation: 2s animateTop linear infinite;
    }
    .ai-btn span:nth-child(2) {
        top: 0px;
        right: 0px;
        height: 100%;
        width: 2px;
        background: linear-gradient(to top, rgba(255, 140, 66, 0), #ff8c42);
        animation: 2s animateRight linear -1s infinite;
    }
    .ai-btn span:nth-child(3) {
        bottom: 0px;
        left: 0px;
        width: 100%;
        height: 2px;
        background: linear-gradient(to right, rgba(255, 140, 66, 0), #ff8c42);
        animation: 2s animateBottom linear infinite;
    }
    .ai-btn span:nth-child(4) {
        top: 0px;
        left: 0px;
        height: 100%;
        width: 2px;
        background: linear-gradient(to bottom, rgba(255, 140, 66, 0), #ff8c42);
        animation: 2s animateLeft linear -1s infinite;
    }
    @keyframes animateTop {
        0% { transform: translateX(100%); }
        100% { transform: translateX(-100%); }
    }
    @keyframes animateRight {
        0% { transform: translateY(100%); }
        100% { transform: translateY(-100%); }
    }
    @keyframes animateBottom {
        0% { transform: translateX(-100%); }
        100% { transform: translateX(100%); }
    }
    @keyframes animateLeft {
        0% { transform: translateY(-100%); }
        100% { transform: translateY(100%); }
    }
    .ai-btn__icon {
        font-size: 1.2rem;
    }
    .ai-btn[disabled] {
        opacity: 0.6;
        cursor: not-allowed;
    }
    .ai-btn[disabled] .ai-btn__icon {
        animation: icon-spin 1s linear infinite;
    }
    @keyframes icon-spin {
        from { transform: rotate(0deg); }
        to { transform: rotate(360deg); }
    }
    .ai-status {
        margin-bottom: 1rem;
        padding: 0.8rem 0.95rem;
        border-radius: 10px;
        font-size: 0.83rem;
        line-height: 1.5;
        display: none;
    }
    .ai-status.is-info {
        display: block;
        background: #eef8f7;
        border: 1px solid #c7e8e2;
        color: #0b6c68;
    }
    .ai-status.is-error {
        display: block;
        background: #fff0ec;
        border: 1px solid #f1c8bf;
        color: #b6402a;
    }
    .post-tab-panel[hidden] {
        display: none !important;
    }
    @media (max-width: 760px) {
        .post-tabs {
            flex-direction: column;
            align-items: stretch;
        }
        .post-tabs__group {
            width: 100%;
            justify-content: stretch;
        }
        .post-tab-btn {
            flex: 1 1 0;
            text-align: center;
        }
        .post-tabs__meta {
            text-align: center;
            margin-left: 0;
        }
        .panel-toolbar {
            flex-direction: column;
            align-items: stretch;
        }
        .panel-toolbar__actions {
            width: 100%;
        }
        .panel-toolbar__actions .ai-btn {
            flex: 1 1 0;
            text-align: center;
        }
    }
    .post-card {
        background: #fff;
        border: 1px solid #e3e8ee;
        border-radius: 12px;
        padding: 1.15rem 1.25rem 1.25rem;
        box-shadow: 0 6px 18px rgba(15, 23, 42, 0.05);
    }
    .post-card h4 {
        margin: 0 0 0.85rem;
        font-size: 0.78rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.06em;
        color: #12384f;
        border-bottom: 1px solid #e3e8ee;
        padding-bottom: 0.55rem;
    }
    .admin-edit-form .post-main input.post-title-input {
        display: block !important;
        width: 100% !important;
        max-width: 100% !important;
        padding: 0.95rem 1.1rem !important;
        font-size: 1.65rem !important;
        font-weight: 700 !important;
        line-height: 1.25 !important;
        color: #12384f !important;
        outline: 0 !important;
        background: #f4f7fb !important;
        border: 1px solid #e3e8ee !important;
        border-radius: 8px !important;
        transition: border-color 0.2s, box-shadow 0.2s, background 0.2s;
    }
    .admin-edit-form .post-main input.post-title-input:hover { border-color: #d0d8e2 !important; }
    .admin-edit-form .post-main input.post-title-input:focus {
        border-color: #0b7a75 !important;
        background: #ffffff !important;
        box-shadow: 0 0 0 3px rgba(11, 122, 117, 0.12) !important;
    }
    .admin-edit-form .post-main input.post-title-input::placeholder { color: #b0bcc7; font-weight: 600; }

    .post-permalink {
        display: flex;
        align-items: center;
        flex-wrap: wrap;
        gap: 0.35rem;
        margin: 0.55rem 0 1.4rem;
        padding: 0.55rem 0.8rem;
        background: #f4f7fb;
        border: 1px solid #e3e8ee;
        border-radius: 8px;
        font-size: 0.85rem;
        color: #667788;
    }
    .post-permalink strong { color: #12384f; font-weight: 600; }
    .post-permalink input {
        flex: 1 1 auto;
        min-width: 0;
        border: 0;
        padding: 0.2rem 0;
        background: transparent;
        font-size: 0.85rem;
        color: #12384f;
        outline: 0;
        font-family: 'Monaco', 'Menlo', monospace;
    }

    .editor-bar {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 0.65rem;
    }
    .editor-bar-actions {
        display: flex;
        gap: 0.5rem;
    }
    .editor-bar-actions button {
        padding: 0.4rem 0.85rem;
        background: #f4f7fb;
        border: 1px solid #e3e8ee;
        border-radius: 6px;
        font-size: 0.82rem;
        color: #12384f;
        cursor: pointer;
        font-weight: 500;
    }
    .editor-bar-actions button:hover { background: #e3e8ee; }
    .editor-bar-actions button.is-active {
        background: #0b7a75;
        color: #fff;
        border-color: #0b7a75;
    }

    #editor-quill {
        background: #fff;
        font-size: 1rem;
    }
    .ql-toolbar.ql-snow {
        border: 1px solid #d6dde6 !important;
        border-bottom: 0 !important;
        border-radius: 8px 8px 0 0;
        background: #fafbfc;
        padding: 0.55rem 0.65rem !important;
    }
    .ql-container.ql-snow {
        border: 1px solid #d6dde6 !important;
        border-radius: 0 0 8px 8px;
        min-height: 420px;
        background: #fff;
    }
    .ql-editor {
        min-height: 420px;
        font-size: 1rem;
        line-height: 1.7;
        padding: 1.25rem 1.4rem;
        color: #22313d;
    }
    .ql-editor h1, .ql-editor h2, .ql-editor h3 {
        color: #12384f;
        font-weight: 700;
        margin-top: 1.2em;
        margin-bottom: 0.5em;
    }
    .ql-editor h2 { font-size: 1.5rem; }
    .ql-editor h3 { font-size: 1.2rem; }
    .ql-editor p { margin: 0 0 0.85em; }
    .ql-editor ul, .ql-editor ol {
        padding-left: 1.6em;
        margin: 0 0 1em;
    }
    .ql-editor ul > li, .ql-editor ol > li {
        list-style-position: outside;
        margin-bottom: 0.35em;
        line-height: 1.65;
    }
    .ql-editor ul > li { list-style-type: disc; }
    .ql-editor ol > li { list-style-type: decimal; }
    .ql-editor blockquote {
        border-left: 4px solid #0b7a75;
        padding: 0.4rem 0.95rem;
        margin: 0 0 1em;
        background: #f4f7fb;
        color: #2c3a47;
        border-radius: 0 6px 6px 0;
    }
    .ql-editor a { color: #0b7a75; text-decoration: underline; }
    .ql-editor strong { color: #12384f; }

    #editor-html {
        width: 100%;
        min-height: 560px;
        padding: 1.1rem 1.25rem;
        border: 1px solid #d6dde6;
        border-radius: 6px;
        font-family: 'Monaco', 'Menlo', 'Courier New', monospace;
        font-size: 0.9rem;
        line-height: 1.55;
        resize: vertical;
        display: none;
    }

    .field-row { display: flex; flex-direction: column; gap: 0.35rem; }
    .field-row + .field-row { margin-top: 0.95rem; }
    .field-row label {
        font-size: 0.78rem;
        font-weight: 600;
        color: #12384f;
        text-transform: uppercase;
        letter-spacing: 0.04em;
    }
    .field-row input,
    .field-row select,
    .field-row textarea {
        width: 100%;
        padding: 0.65rem 0.85rem;
        border: 2px solid #e3e8ee;
        border-radius: 8px;
        font-size: 0.9rem;
        color: #22313d;
        font-family: inherit;
        background: linear-gradient(135deg, #fafbfc 0%, #f4f7fb 100%);
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        box-shadow: 0 2px 6px rgba(15, 23, 42, 0.05);
    }
    .field-row select {
        appearance: none;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='8' viewBox='0 0 12 8'%3E%3Cpath fill='%230b7a75' d='M1 1l5 5 5-5'/%3E%3C/svg%3E");
        background-repeat: no-repeat;
        background-position: right 0.85rem center;
        padding-right: 2.5rem;
        cursor: pointer;
    }
    .field-row select option {
        padding: 0.75rem 0.85rem;
        background: #ffffff;
        color: #22313d;
        font-size: 0.9rem;
        font-weight: 500;
        line-height: 1.6;
    }
    .field-row select option:checked {
        background: linear-gradient(135deg, #0b7a75 0%, #095e5a 100%);
        color: #ffffff;
        font-weight: 600;
    }
    .field-row select option:hover {
        background: #f0f5f4;
        color: #0b7a75;
    }
    .field-row input:hover,
    .field-row select:hover,
    .field-row textarea:hover {
        border-color: #d0d8e2;
        box-shadow: 0 4px 12px rgba(15, 23, 42, 0.08);
        background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%);
    }
    .field-row input:focus,
    .field-row select:focus,
    .field-row textarea:focus {
        outline: 0;
        border-color: #0b7a75;
        box-shadow: 0 0 0 3px rgba(11, 122, 117, 0.15), 0 4px 12px rgba(11, 122, 117, 0.1);
        background: #fff;
    }
    .field-row small { font-size: 0.72rem; color: #667788; margin-top: 0.2rem; }
    .field-row textarea { resize: vertical; min-height: 80px; }

    .toggle-stack {
        display: flex;
        flex-direction: column;
        gap: 0.85rem;
    }
    .toggle-row {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 1rem;
        padding: 0.95rem 1rem;
        border: 1px solid #dde7ee;
        border-radius: 14px;
        background: #fbfdfe;
    }
    .toggle-row__copy {
        min-width: 0;
    }
    .toggle-row__copy strong {
        display: block;
        color: #12384f;
        font-size: 0.96rem;
        letter-spacing: -0.01em;
    }
    .toggle-row__copy span {
        display: block;
        margin-top: 0.2rem;
        color: #6f8090;
        font-size: 0.77rem;
        line-height: 1.45;
    }
    .toggle-control {
        position: relative;
        display: inline-flex;
        align-items: center;
        flex-shrink: 0;
        cursor: pointer;
    }
    .toggle-control input {
        position: absolute;
        opacity: 0;
        pointer-events: none;
    }
    .toggle-control__track {
        position: relative;
        width: 58px;
        height: 32px;
        border-radius: 999px;
        background: #d7e1e8;
        box-shadow: inset 0 1px 2px rgba(15, 23, 42, 0.12);
        transition: background 0.2s ease, box-shadow 0.2s ease;
    }
    .toggle-control__track::after {
        content: '';
        position: absolute;
        top: 4px;
        left: 4px;
        width: 24px;
        height: 24px;
        border-radius: 50%;
        background: #fff;
        box-shadow: 0 3px 8px rgba(15, 23, 42, 0.18);
        transition: transform 0.2s ease;
    }
    .toggle-control input:checked + .toggle-control__track {
        background: linear-gradient(135deg, #0b7a75 0%, #127a88 100%);
        box-shadow: inset 0 1px 2px rgba(6, 78, 90, 0.22), 0 8px 18px rgba(11, 122, 117, 0.2);
    }
    .toggle-control input:checked + .toggle-control__track::after {
        transform: translateX(26px);
    }
    .toggle-control input:focus-visible + .toggle-control__track {
        outline: none;
        box-shadow: 0 0 0 4px rgba(11, 122, 117, 0.14);
    }

    .cat-chips { display: flex; flex-wrap: wrap; gap: 0.3rem; margin-top: 0.4rem; }
    .cat-chip {
        padding: 0.25rem 0.65rem;
        background: #f4f7fb;
        border: 1px solid #d6dde6;
        border-radius: 999px;
        font-size: 0.78rem;
        color: #12384f;
        cursor: pointer;
        transition: all 0.15s;
    }
    .cat-chip:hover { background: #0b7a75; color: #fff; border-color: #0b7a75; }

    .submit-row {
        display: flex;
        flex-direction: column;
        gap: 0.5rem;
    }
    .btn-primary, .btn-secondary {
        padding: 0.7rem 1rem;
        border: 0;
        border-radius: 6px;
        font-weight: 600;
        cursor: pointer;
        font-size: 0.92rem;
    }
    .btn-primary { background: #0b7a75; color: #fff; }
    .btn-primary:hover { background: #095e5a; }
    .btn-secondary { background: #f4f7fb; color: #12384f; border: 1px solid #d6dde6; }
    .btn-secondary:hover { background: #e3e8ee; }

    .img-preview {
        margin-top: 0.55rem;
        padding: 0.5rem;
        background: #f4f7fb;
        border-radius: 6px;
        text-align: center;
    }
    .img-preview img { max-width: 100%; border-radius: 4px; max-height: 140px; }

    /* Featured image dropzone */
    .img-dropzone {
        position: relative;
        display: flex;
        align-items: center;
        justify-content: center;
        min-height: 160px;
        padding: 0.85rem;
        border: 2px dashed #cdd6e0;
        border-radius: 10px;
        background: #f9fbfd;
        cursor: pointer;
        transition: border-color 0.2s, background 0.2s;
        overflow: hidden;
    }
    .img-dropzone:hover,
    .img-dropzone:focus-visible,
    .img-dropzone.is-drag {
        border-color: #0b7a75;
        background: #f0faf8;
        outline: 0;
    }
    .img-dropzone.is-drag { box-shadow: 0 0 0 4px rgba(11,122,117,0.12); }
    .img-dropzone.is-uploading { pointer-events: none; opacity: 0.8; }

    .img-dropzone__preview { width: 100%; }
    .img-dropzone__preview img {
        display: block;
        width: 100%;
        max-height: 220px;
        object-fit: cover;
        border-radius: 6px;
    }
    .img-dropzone__placeholder {
        text-align: center;
        color: #667788;
    }
    .img-dropzone__placeholder p {
        margin: 0.55rem 0 0.25rem;
        font-size: 0.92rem;
        color: #12384f;
    }
    .img-dropzone__placeholder small { font-size: 0.75rem; }

    .img-actions {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 0.55rem;
        margin-top: 0.65rem;
        padding: 0.5rem 0.7rem;
        background: #f4f7fb;
        border: 1px solid #e3e8ee;
        border-radius: 8px;
        font-size: 0.82rem;
    }
    .img-filename {
        flex: 1;
        min-width: 0;
        color: #12384f;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
        font-family: 'Monaco', 'Menlo', monospace;
        font-size: 0.78rem;
    }
    .img-btn {
        padding: 0.32rem 0.65rem;
        background: #fff;
        border: 1px solid #d6dde6;
        border-radius: 5px;
        font-size: 0.78rem;
        font-weight: 600;
        color: #12384f;
        cursor: pointer;
    }
    .img-btn:hover { background: #f4f7fb; border-color: #0b7a75; color: #0b7a75; }
    .img-btn--danger:hover { border-color: #c95d37; color: #c95d37; }
    .img-btn + .img-btn { margin-left: 0.35rem; }

    .img-upload-status {
        margin-top: 0.5rem;
        padding: 0.5rem 0.7rem;
        border-radius: 6px;
        font-size: 0.82rem;
    }
    .img-upload-status.is-info { background: #eaf6f5; color: #0b6c68; border: 1px solid #b8e3df; }
    .img-upload-status.is-error { background: #fbecec; color: #b6402a; border: 1px solid #f1c0b6; }

    /* Import from document card */
    .import-card {
        margin-bottom: 1.4rem;
        padding: 1rem 1.15rem 1.15rem;
        background: linear-gradient(180deg, #ffffff 0%, #f9fbfd 100%);
        border: 1px solid #d6e1ea;
        border-radius: 12px;
    }
    .import-card__head {
        display: flex;
        gap: 1rem;
        align-items: flex-start;
        justify-content: space-between;
    }
    .import-card__head strong { color: #12384f; font-size: 0.98rem; font-weight: 700; }
    .import-card__head p {
        margin: 0.2rem 0 0;
        color: #667788;
        font-size: 0.82rem;
        line-height: 1.5;
    }
    .import-card__head code {
        background: #f4f7fb;
        padding: 0.05rem 0.3rem;
        border-radius: 4px;
        font-size: 0.78rem;
        color: #12384f;
    }
    .import-card__pick {
        flex-shrink: 0;
        padding: 0.55rem 1rem;
        background: #0b7a75;
        color: #fff;
        border: 0;
        border-radius: 6px;
        font-size: 0.85rem;
        font-weight: 600;
        cursor: pointer;
    }
    .import-card__pick:hover { background: #095e5a; }
    .import-card__zone {
        margin-top: 0.85rem;
        padding: 0.85rem;
        text-align: center;
        background: #ffffff;
        border: 2px dashed #cdd6e0;
        border-radius: 8px;
        color: #667788;
        font-size: 0.88rem;
        cursor: pointer;
        transition: border-color 0.2s, background 0.2s;
    }
    .import-card__zone:hover,
    .import-card__zone:focus-visible,
    .import-card__zone.is-drag {
        border-color: #0b7a75;
        background: #f0faf8;
        color: #0b7a75;
        outline: 0;
    }
    .import-card.is-loading .import-card__zone { opacity: 0.6; pointer-events: none; }
    .import-card__status {
        margin-top: 0.65rem;
        padding: 0.55rem 0.75rem;
        border-radius: 6px;
        font-size: 0.85rem;
    }
    .import-card__status.is-info { background: #eaf6f5; color: #0b6c68; border: 1px solid #b8e3df; }
    .import-card__status.is-error { background: #fbecec; color: #b6402a; border: 1px solid #f1c0b6; }

    .seo-panel {
        margin-top: 1.5rem;
        padding: 1.25rem;
        background: linear-gradient(180deg, #ffffff 0%, #f8fbfd 100%);
        border: 1px solid #d6e1ea;
        border-radius: 12px;
    }
    .seo-panel__header {
        margin-bottom: 1rem;
    }
    .seo-panel__header h3 {
        margin: 0;
        font-size: 1rem;
        color: #12384f;
    }
    .seo-panel__header p {
        margin: 0.25rem 0 0;
        font-size: 0.84rem;
        line-height: 1.55;
        color: #667788;
    }
    .seo-grid {
        display: grid;
        grid-template-columns: minmax(0, 1fr) minmax(280px, 340px);
        gap: 1rem 1.25rem;
        align-items: start;
    }
    @media (max-width: 980px) {
        .seo-grid { grid-template-columns: 1fr; }
    }
    .seo-fields {
        display: grid;
        gap: 0.95rem;
    }
    .seo-fieldset {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 0.95rem;
    }
    @media (max-width: 760px) {
        .seo-fieldset { grid-template-columns: 1fr; }
    }
    .seo-field {
        display: flex;
        flex-direction: column;
        gap: 0.35rem;
    }
    .seo-field label {
        font-size: 0.78rem;
        font-weight: 700;
        color: #12384f;
        text-transform: uppercase;
        letter-spacing: 0.04em;
    }
    .seo-field input,
    .seo-field textarea,
    .seo-field select {
        width: 100%;
        padding: 0.75rem 0.9rem;
        border: 2px solid #e3e8ee;
        border-radius: 10px;
        font-size: 0.92rem;
        color: #22313d;
        background: linear-gradient(135deg, #fafbfc 0%, #f4f7fb 100%);
        font-family: inherit;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        box-shadow: 0 2px 8px rgba(15, 23, 42, 0.05);
    }
    .seo-field select {
        appearance: none;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='8' viewBox='0 0 12 8'%3E%3Cpath fill='%230b7a75' d='M1 1l5 5 5-5'/%3E%3C/svg%3E");
        background-repeat: no-repeat;
        background-position: right 1rem center;
        padding-right: 2.8rem;
        cursor: pointer;
    }
    .seo-field select option {
        padding: 0.85rem 0.9rem;
        background: #ffffff;
        color: #22313d;
        font-size: 0.92rem;
        font-weight: 500;
        line-height: 1.8;
    }
    .seo-field select option:checked {
        background: linear-gradient(135deg, #0b7a75 0%, #095e5a 100%);
        color: #ffffff;
        font-weight: 600;
        box-shadow: 0 0 10px rgba(11, 122, 117, 0.5);
    }
    .seo-field select option:hover {
        background: #f0f5f4;
        color: #0b7a75;
    }
    .seo-field textarea {
        min-height: 96px;
        resize: vertical;
    }
    .seo-field input:hover,
    .seo-field textarea:hover,
    .seo-field select:hover {
        border-color: #d0d8e2;
        box-shadow: 0 4px 14px rgba(15, 23, 42, 0.1);
        background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%);
    }
    .seo-field input:focus,
    .seo-field textarea:focus,
    .seo-field select:focus {
        outline: 0;
        border-color: #0b7a75;
        box-shadow: 0 0 0 4px rgba(11, 122, 117, 0.15), 0 4px 14px rgba(11, 122, 117, 0.1);
        background: #fff;
    }
    .seo-field small {
        font-size: 0.74rem;
        color: #667788;
    }
    .seo-counter {
        font-size: 0.74rem;
        font-weight: 700;
    }
    .seo-counter.is-good { color: #0b7a75; }
    .seo-counter.is-warn { color: #c27a10; }
    .seo-counter.is-bad { color: #c95d37; }
    .seo-toggle {
        display: flex;
        align-items: center;
        gap: 0.6rem;
        padding: 0.8rem 0.9rem;
        background: #fff;
        border: 1px solid #d6dde6;
        border-radius: 8px;
    }
    .seo-toggle span {
        font-size: 0.9rem;
        color: #22313d;
    }
    .seo-preview-stack {
        display: grid;
        gap: 1rem;
        position: sticky;
        top: 1rem;
    }
    .seo-preview-card {
        background: #fff;
        border: 1px solid #dce7ee;
        border-radius: 12px;
        padding: 1rem;
        box-shadow: 0 8px 20px rgba(15, 23, 42, 0.04);
    }
    .seo-preview-card h4 {
        margin: 0 0 0.85rem;
        font-size: 0.76rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.08em;
        color: #667788;
    }
    .google-preview__url {
        font-size: 0.8rem;
        line-height: 1.4;
        color: #3c4043;
        margin-bottom: 0.2rem;
        word-break: break-all;
    }
    .google-preview__title {
        font-size: 1.12rem;
        line-height: 1.3;
        color: #1a0dab;
        margin-bottom: 0.28rem;
    }
    .google-preview__desc {
        font-size: 0.86rem;
        line-height: 1.55;
        color: #4d5156;
    }
    .og-preview {
        border: 1px solid #dfe5ea;
        border-radius: 10px;
        overflow: hidden;
        background: #f8fafc;
    }
    .og-preview__media {
        aspect-ratio: 1.91 / 1;
        background: linear-gradient(135deg, rgba(11, 122, 117, 0.14), rgba(242, 125, 45, 0.12));
        display: flex;
        align-items: center;
        justify-content: center;
        color: #6b7280;
        font-size: 0.82rem;
        text-align: center;
        padding: 1rem;
    }
    .og-preview__media img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        display: block;
    }
    .og-preview__body {
        padding: 0.8rem 0.9rem;
        background: #fff;
    }
    .og-preview__site {
        font-size: 0.7rem;
        color: #667788;
        text-transform: uppercase;
        margin-bottom: 0.25rem;
    }
    .og-preview__title {
        font-size: 0.92rem;
        font-weight: 700;
        line-height: 1.35;
        color: #12384f;
        margin-bottom: 0.2rem;
    }
    .og-preview__desc {
        font-size: 0.79rem;
        line-height: 1.5;
        color: #667788;
    }
    .seo-note {
        padding: 0.85rem 0.95rem;
        border-radius: 10px;
        background: #f7f9fb;
        border: 1px solid #e1e8ee;
        font-size: 0.82rem;
        line-height: 1.55;
        color: #5f6f7c;
    }
    .seo-note strong {
        color: #12384f;
    }
    .seo-checklist {
        display: grid;
        gap: 0.65rem;
    }
    .seo-check-item {
        display: flex;
        align-items: flex-start;
        gap: 0.7rem;
        padding: 0.75rem 0.8rem;
        border: 1px solid #e5ebf0;
        border-radius: 10px;
        background: #fbfdff;
    }
    .seo-check-item.is-pass {
        border-color: #bde3d8;
        background: #f3fbf8;
    }
    .seo-check-item.is-warn {
        border-color: #f1dcc0;
        background: #fffaf2;
    }
    .seo-check-icon {
        width: 1.35rem;
        height: 1.35rem;
        border-radius: 999px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 0.78rem;
        font-weight: 800;
        flex-shrink: 0;
        background: #e6edf3;
        color: #637384;
        margin-top: 0.05rem;
    }
    .seo-check-item.is-pass .seo-check-icon {
        background: #0b7a75;
        color: #fff;
    }
    .seo-check-item.is-warn .seo-check-icon {
        background: #d79b2d;
        color: #fff;
    }
    .seo-check-copy strong {
        display: block;
        margin-bottom: 0.15rem;
        font-size: 0.84rem;
        color: #12384f;
    }
    .seo-check-copy span {
        display: block;
        font-size: 0.78rem;
        line-height: 1.5;
        color: #667788;
    }
    .seo-score {
        display: grid;
        gap: 0.9rem;
    }
    .seo-score__top {
        display: flex;
        align-items: center;
        gap: 1rem;
    }
    .seo-score__ring {
        width: 84px;
        height: 84px;
        border-radius: 50%;
        margin: 0 auto;
        display: grid;
        place-items: center;
        background: conic-gradient(#0b7a75 0deg, #0b7a75 var(--score-angle, 0deg), #e7eef3 var(--score-angle, 0deg), #e7eef3 360deg);
        position: relative;
    }
    .seo-score__ring::before {
        content: '';
        position: absolute;
        inset: 8px;
        border-radius: 50%;
        background: #fff;
    }
    .seo-score__value {
        position: relative;
        z-index: 1;
        font-size: 1.15rem;
        font-weight: 800;
        color: #12384f;
    }
    .seo-score__label {
        text-align: left;
        font-size: 0.8rem;
        color: #667788;
        line-height: 1.5;
    }
    .seo-score__summary {
        display: grid;
        gap: 0.3rem;
        flex: 1;
    }
    .seo-score__status {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: fit-content;
        padding: 0.34rem 0.7rem;
        border-radius: 999px;
        font-size: 0.72rem;
        font-weight: 800;
        letter-spacing: 0.08em;
        text-transform: uppercase;
        background: #edf2f7;
        color: #607080;
    }
    .seo-score__status.is-strong {
        background: #e9f8f3;
        color: #0b7a75;
    }
    .seo-score__status.is-fair {
        background: #fff6e9;
        color: #b87812;
    }
    .seo-score__status.is-weak {
        background: #fff0ec;
        color: #c95d37;
    }
    .seo-score__breakdown {
        display: grid;
        gap: 0.55rem;
        margin-top: 0.2rem;
    }
    .seo-score__item {
        display: grid;
        grid-template-columns: 1fr auto;
        gap: 0.7rem;
        align-items: center;
        padding: 0.55rem 0.7rem;
        border-radius: 10px;
        border: 1px solid #e7edf2;
        background: #fbfdff;
    }
    .seo-score__item.is-pass {
        border-color: #cbe8dd;
        background: #f4fbf8;
    }
    .seo-score__item.is-warn {
        border-color: #f0dfc8;
        background: #fffaf3;
    }
    .seo-score__item.is-optional {
        border-style: dashed;
    }
    .seo-score__item-title {
        font-size: 0.79rem;
        font-weight: 700;
        color: #12384f;
    }
    .seo-score__item-hint {
        margin-top: 0.15rem;
        font-size: 0.74rem;
        line-height: 1.45;
        color: #6a7c89;
    }
    .seo-score__item-points {
        font-size: 0.76rem;
        font-weight: 800;
        color: #0b7a75;
        white-space: nowrap;
    }
    .faq-builder {
        display: grid;
        gap: 0.9rem;
    }
    .faq-list {
        display: grid;
        gap: 0.8rem;
    }
    .faq-item {
        padding: 0.9rem;
        border: 1px solid #dce6ed;
        border-radius: 12px;
        background: #fbfdff;
        box-shadow: inset 0 1px 0 rgba(255,255,255,0.8);
    }
    .faq-item__top {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 0.75rem;
        margin-bottom: 0.75rem;
    }
    .faq-item__label {
        font-size: 0.76rem;
        font-weight: 800;
        letter-spacing: 0.08em;
        text-transform: uppercase;
        color: #6a7c89;
    }
    .faq-item__remove {
        border: 0;
        background: transparent;
        color: #ba5a3e;
        font-size: 0.8rem;
        font-weight: 700;
        cursor: pointer;
    }
    .faq-item__fields {
        display: grid;
        gap: 0.75rem;
    }
    .faq-item__fields input,
    .faq-item__fields textarea {
        width: 100%;
        padding: 0.7rem 0.8rem;
        border: 1px solid #d6dde6;
        border-radius: 8px;
        font-size: 0.9rem;
        background: #fff;
        font-family: inherit;
    }
    .faq-item__fields textarea {
        min-height: 110px;
        resize: vertical;
    }
    .faq-item__fields input:focus,
    .faq-item__fields textarea:focus {
        outline: 0;
        border-color: #0b7a75;
        box-shadow: 0 0 0 3px rgba(11, 122, 117, 0.12);
    }
    .faq-add-btn {
        justify-self: start;
        padding: 0.7rem 1rem;
        border: 1px solid #cfe0e7;
        border-radius: 999px;
        background: #f3f8fb;
        color: #12384f;
        font-size: 0.84rem;
        font-weight: 700;
        cursor: pointer;
    }
    .faq-add-btn:hover {
        border-color: #0b7a75;
        color: #0b7a75;
        background: #eef8f7;
    }
</style>

<form class="admin-edit-form" method="POST" action="{{ $action }}" id="blogForm">
    @csrf
    <input type="hidden" name="active_tab" id="activeTabInput" value="{{ old('active_tab', 'editor') }}">
    @if ($method !== 'POST')
        @method($method)
    @endif

    @if ($errors->any())
        <div style="background:#fff0ec;border:1px solid #f1c8bf;border-radius:12px;padding:1rem 1.25rem;margin-bottom:1.25rem;">
            <strong style="color:#b6402a;display:block;margin-bottom:0.5rem;">Please fix the following errors:</strong>
            <ul style="margin:0;padding-left:1.25rem;color:#b6402a;font-size:0.9rem;">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @if (session('status'))
        <div style="background:#e8f5e9;border:1px solid #66bb6a;border-radius:12px;padding:1rem 1.25rem;margin-bottom:1.25rem;color:#2e7d32;font-weight:600;">
            {{ session('status') }}
        </div>
    @endif

    <div class="post-editor">
        {{-- MAIN COLUMN --}}
        <div class="post-main">
            <div class="post-tabs" role="tablist" aria-label="Blog editor sections">
                <div class="post-tabs__group">
                    <button type="button" class="post-tab-btn is-active" id="tabButtonEditor" data-tab-target="editor" role="tab" aria-selected="true" aria-controls="tabPanelEditor">Editor</button>
                    <button type="button" class="post-tab-btn" id="tabButtonSeo" data-tab-target="seo" role="tab" aria-selected="false" aria-controls="tabPanelSeo">SEO</button>
                </div>
                <div class="post-tabs__meta">Content Workspace</div>
            </div>

            <div class="ai-status" id="blogAiStatus"></div>

            <section class="post-tab-panel" id="tabPanelEditor" data-tab-panel="editor" role="tabpanel" aria-labelledby="tabButtonEditor">
                <div class="panel-toolbar">
                    <div class="panel-toolbar__copy">
                        <strong>Writing Workspace</strong>
                        <span>Generate or improve the article body, then review and refine the content before saving.</span>
                    </div>
                    <div class="panel-toolbar__actions">
                        <button type="button" class="ai-btn" id="aiDraftBtn">
                            AI Write Draft
                            <span></span>
                            <span></span>
                            <span></span>
                            <span></span>
                        </button>
                    </div>
                </div>

                {{-- Import from PDF / DOCX --}}
                <div class="import-card" id="importCard">
                    <div class="import-card__head">
                        <div>
                            <strong>Import from PDF or Word</strong>
                            <p>Upload a <code>.pdf</code> or <code>.docx</code> — we'll auto-fill the title, excerpt and content. You can edit everything before saving.</p>
                        </div>
                        <button type="button" class="import-card__pick" id="importPickBtn">Choose file</button>
                    </div>
                    <div class="import-card__zone" id="importZone" tabindex="0" role="button" aria-label="Drop PDF or DOCX here">
                        <span>📄 Drop file here, or click <strong>Choose file</strong></span>
                    </div>
                    <div class="import-card__status" id="importStatus" style="display:none;"></div>
                    <input type="file" id="importFileInput" accept=".pdf,.docx,.doc,application/pdf,application/vnd.openxmlformats-officedocument.wordprocessingml.document,application/msword" hidden>
                </div>

                {{-- Title --}}
                <input type="text" name="title" class="post-title-input"
                       value="{{ old('title', $post->title) }}" required
                       placeholder="Enter title here">

                {{-- Permalink --}}
                <div class="post-permalink">
                    <strong>Permalink:</strong>
                    <span>/blog/</span>
                    <input type="text" name="slug" id="slugInput"
                           value="{{ old('slug', $post->slug) }}" placeholder="auto-generated-from-title">
                </div>

                {{-- Editor label --}}
                <div class="editor-bar">
                    <span style="font-size:0.82rem;color:#667788;font-weight:600;text-transform:uppercase;letter-spacing:0.05em;">Content</span>
                    <span style="font-size:0.78rem;color:#94a3b8;">Use the toolbar below to format. Click <strong>Source</strong> to edit raw HTML.</span>
                </div>

                {{-- TinyMCE will replace this textarea --}}
                <textarea name="body_html" id="editor-tinymce">{{ old('body_html', $post->body_html) }}</textarea>
            </section>

            <section class="seo-panel post-tab-panel" id="tabPanelSeo" data-tab-panel="seo" role="tabpanel" aria-labelledby="tabButtonSeo" hidden>
                <div class="panel-toolbar">
                    <div class="panel-toolbar__copy">
                        <strong>SEO Workspace</strong>
                        <span>Generate search-ready metadata, keyword targeting, and FAQ support from the current article content.</span>
                    </div>
                    <div class="panel-toolbar__actions">
                        <button type="button" class="ai-btn" id="aiSeoBtn">
                            AI Fill SEO
                            <span></span>
                            <span></span>
                            <span></span>
                            <span></span>
                        </button>
                    </div>
                </div>

                <div class="seo-panel__header">
                    <h3>SEO Settings</h3>
                    <p>Control how this article appears in Google and social previews. Empty fields automatically fall back to the post title, excerpt, slug, and featured image.</p>
                </div>

                <div class="seo-grid">
                    <div class="seo-fields">
                        <div class="seo-field">
                            <label for="metaTitleInput">SEO Title</label>
                            <input type="text" name="meta_title" id="metaTitleInput"
                                   value="{{ old('meta_title', $post->meta_title) }}"
                                   maxlength="60"
                                   placeholder="Defaults to post title + SettleANZ Blog">
                            <small>Best around 50 to 60 characters.</small>
                            <span class="seo-counter" data-counter-for="metaTitleInput" data-good-min="50" data-good-max="60" data-max="60"></span>
                        </div>

                        <div class="seo-field">
                            <label for="metaDescriptionInput">Meta Description</label>
                            <textarea name="meta_description" id="metaDescriptionInput"
                                      maxlength="160"
                                      placeholder="Defaults to the excerpt and should explain the article value quickly.">{{ old('meta_description', $post->meta_description) }}</textarea>
                            <small>Best around 140 to 160 characters.</small>
                            <span class="seo-counter" data-counter-for="metaDescriptionInput" data-good-min="140" data-good-max="160" data-max="160"></span>
                        </div>

                        <div class="seo-fieldset">
                            <div class="seo-field">
                                <label for="focusKeywordInput">Focus Keyword</label>
                                <input type="text" name="focus_keyword" id="focusKeywordInput"
                                       value="{{ old('focus_keyword', $post->focus_keyword) }}"
                                       maxlength="120"
                                       placeholder="Example: rent in Australia as a new immigrant">
                                <small>Main target phrase for the article. This is for editorial guidance, not a meta keywords tag.</small>
                            </div>

                            <div class="seo-field">
                                <label for="secondaryKeywordsInput">Secondary Keywords</label>
                                <input type="text" name="secondary_keywords" id="secondaryKeywordsInput"
                                       value="{{ old('secondary_keywords', $post->secondary_keywords) }}"
                                       maxlength="1000"
                                       placeholder="Comma-separated related phrases">
                                <small>Optional related phrases and variants.</small>
                            </div>
                        </div>

                        <div class="seo-fieldset">
                            <div class="seo-field">
                                <label for="authorUrlInput">Author Profile URL</label>
                                <input type="url" name="author_url" id="authorUrlInput"
                                       value="{{ old('author_url', $post->author_url) }}"
                                       placeholder="{{ route('about') }}">
                                <small>Recommended for article structured data. Use an About or author bio page.</small>
                            </div>

                            <div class="seo-field">
                                <label for="ogTitleInput">Open Graph Title</label>
                                <input type="text" name="og_title" id="ogTitleInput"
                                       value="{{ old('og_title', $post->og_title) }}"
                                       maxlength="120"
                                       placeholder="Defaults to SEO title">
                                <small>Used for social sharing cards.</small>
                                <span class="seo-counter" data-counter-for="ogTitleInput" data-good-min="40" data-good-max="95" data-max="120"></span>
                            </div>

                            <div class="seo-field">
                                <label for="schemaTypeInput">Schema Type</label>
                                @php($schemaType = old('schema_type', $post->schema_type ?: 'Article'))
                                <select class="pro-select" name="schema_type" id="schemaTypeInput">
                                    @foreach (['Article', 'BlogPosting', 'NewsArticle', 'WebPage'] as $schemaOption)
                                        <option value="{{ $schemaOption }}" {{ $schemaType === $schemaOption ? 'selected' : '' }}>{{ $schemaOption }}</option>
                                    @endforeach
                                </select>
                                <small>`Article` is the safest default.</small>
                            </div>
                        </div>

                        <div class="seo-field">
                            <label for="ogDescriptionInput">Open Graph Description</label>
                            <textarea name="og_description" id="ogDescriptionInput"
                                      maxlength="200"
                                      placeholder="Defaults to the meta description.">{{ old('og_description', $post->og_description) }}</textarea>
                            <small>Keep this concise and share-friendly.</small>
                            <span class="seo-counter" data-counter-for="ogDescriptionInput" data-good-min="90" data-good-max="160" data-max="200"></span>
                        </div>

                        <div class="seo-fieldset">
                            <div class="seo-field">
                                <label for="canonicalUrlInput">Canonical URL</label>
                                <input type="url" name="canonical_url" id="canonicalUrlInput"
                                       value="{{ old('canonical_url', $post->canonical_url) }}"
                                       placeholder="{{ url('/blog/' . ($post->slug ?: 'post-slug')) }}">
                                <small>Optional. Use this only if another URL should be treated as the primary version.</small>
                            </div>

                            <div class="seo-field">
                                <label for="ogImageInput">Open Graph Image</label>
                                <input type="text" name="og_image" id="ogImageInput"
                                       value="{{ old('og_image', $post->og_image) }}"
                                       placeholder="Optional absolute URL or /media/... path">
                                <small>Leave blank to use the featured image.</small>
                            </div>
                        </div>

                        <label class="toggle-row seo-toggle">
                            <input type="hidden" name="no_index" value="0">
                            <span class="toggle-row__copy">
                                <strong>No-index this post</strong>
                                <span>Hide this article from search engine indexing when you do not want it to appear in search results.</span>
                            </span>
                            <span class="toggle-control">
                                <input type="checkbox" name="no_index" id="noIndexInput" value="1" {{ old('no_index', $post->no_index) ? 'checked' : '' }}>
                                <span class="toggle-control__track"></span>
                            </span>
                        </label>

                        <div class="seo-field">
                            <label>FAQ Content</label>
                            <small>Valid FAQ markup requires the questions and answers to be visible on the page. Google currently shows FAQ rich results mostly for government and health sites, but this can still help search engines understand the page.</small>
                            <div class="faq-builder">
                                <input type="hidden" name="faq_items" id="faqItemsInput" value="{{ old('faq_items', json_encode($initialFaqItems, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES)) }}">
                                <div class="faq-list" id="faqList"></div>
                                <button type="button" class="faq-add-btn" id="faqAddBtn">Add FAQ Item</button>
                            </div>
                        </div>
                    </div>

                    <div class="seo-preview-stack">
                        <div class="seo-preview-card">
                            <h4>SEO Score</h4>
                            <div class="seo-score">
                                <div class="seo-score__top">
                                    <div class="seo-score__ring" id="seoScoreRing" style="--score-angle: 0deg;">
                                        <div class="seo-score__value" id="seoScoreValue">0</div>
                                    </div>
                                    <div class="seo-score__summary">
                                        <div class="seo-score__status" id="seoScoreStatus">Needs Work</div>
                                        <div class="seo-score__label" id="seoScoreLabel">Add the core SEO fields to improve this post’s search readiness.</div>
                                    </div>
                                </div>
                                <div class="seo-score__breakdown" id="seoScoreBreakdown"></div>
                            </div>
                        </div>

                        <div class="seo-preview-card">
                            <h4>SEO Notes</h4>
                            <div class="seo-note">
                                <strong>Meta keywords are not being added.</strong> Google ignores the `keywords` meta tag, so this panel uses a focus keyword and on-page checklist instead.
                            </div>
                        </div>

                        <div class="seo-preview-card">
                            <h4>On-Page Checklist</h4>
                            <div class="seo-checklist" id="seoChecklist">
                                <div class="seo-check-item" data-check="keyword">
                                    <div class="seo-check-icon">!</div>
                                    <div class="seo-check-copy">
                                        <strong>Set a focus keyword</strong>
                                        <span>Choose the main phrase this article should target.</span>
                                    </div>
                                </div>
                                <div class="seo-check-item" data-check="title">
                                    <div class="seo-check-icon">!</div>
                                    <div class="seo-check-copy">
                                        <strong>Use it in the SEO title</strong>
                                        <span>Helps align the title with the search intent.</span>
                                    </div>
                                </div>
                                <div class="seo-check-item" data-check="description">
                                    <div class="seo-check-icon">!</div>
                                    <div class="seo-check-copy">
                                        <strong>Use it in the meta description</strong>
                                        <span>Improves topical clarity for search snippets.</span>
                                    </div>
                                </div>
                                <div class="seo-check-item" data-check="slug">
                                    <div class="seo-check-icon">!</div>
                                    <div class="seo-check-copy">
                                        <strong>Use it in the slug</strong>
                                        <span>Keep the URL descriptive and clean.</span>
                                    </div>
                                </div>
                                <div class="seo-check-item" data-check="body">
                                    <div class="seo-check-icon">!</div>
                                    <div class="seo-check-copy">
                                        <strong>Use it in the article body</strong>
                                        <span>Make sure the phrase appears naturally in the content.</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="seo-preview-card">
                            <h4>Google Preview</h4>
                            <div class="google-preview">
                                <div class="google-preview__url" id="seoPreviewUrl"></div>
                                <div class="google-preview__title" id="seoPreviewTitle"></div>
                                <div class="google-preview__desc" id="seoPreviewDesc"></div>
                            </div>
                        </div>

                        <div class="seo-preview-card">
                            <h4>Social Preview</h4>
                            <div class="og-preview">
                                <div class="og-preview__media" id="ogPreviewMedia">
                                    <img id="ogPreviewImg" src="" alt="Open Graph preview" style="display:none;">
                                    <span id="ogPreviewPlaceholder">Featured image will be used if no custom OG image is set.</span>
                                </div>
                                <div class="og-preview__body">
                                    <div class="og-preview__site">{{ parse_url(config('app.url'), PHP_URL_HOST) ?: 'settleanz.com' }}</div>
                                    <div class="og-preview__title" id="ogPreviewTitle"></div>
                                    <div class="og-preview__desc" id="ogPreviewDesc"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>

        {{-- SIDEBAR --}}
        <aside class="post-side">
            {{-- Publish --}}
            <div class="post-card">
                <h4>Publish</h4>
                <div class="toggle-stack">
                    <label class="toggle-row">
                        <input type="hidden" name="is_published" value="0">
                        <span class="toggle-row__copy">
                            <strong>Published</strong>
                            <span>Turn this on when the article should be live on the public blog.</span>
                        </span>
                        <span class="toggle-control">
                            <input type="checkbox" name="is_published" value="1" @checked((bool) old('is_published', $post->is_published))>
                            <span class="toggle-control__track"></span>
                        </span>
                    </label>
                    <label class="toggle-row">
                        <input type="hidden" name="is_featured_home" value="0">
                        <span class="toggle-row__copy">
                            <strong>Featured on homepage</strong>
                            <span>Highlight this post in the homepage content area for extra visibility.</span>
                        </span>
                        <span class="toggle-control">
                            <input type="checkbox" name="is_featured_home" value="1" {{ old('is_featured_home', $post->is_featured_home) ? 'checked' : '' }}>
                            <span class="toggle-control__track"></span>
                        </span>
                    </label>
                </div>
                <div class="field-row">
                    <label>Publish Date</label>
                    <input type="datetime-local" name="published_at"
                           value="{{ old('published_at', optional($post->published_at)->format('Y-m-d\TH:i')) }}">
                    <small>Leave blank for draft.</small>
                </div>
                <div class="submit-row" style="margin-top:1rem;">
                    <button type="submit" class="btn-primary" name="status_action" value="draft" style="background:#f59e0b;">Save Draft</button>
                    <button type="submit" class="btn-primary" name="status_action" value="publish">Save & Publish</button>
                </div>
            </div>

            {{-- Category --}}
            <div class="post-card">
                <h4>Category</h4>
                <div class="field-row">
                    <select class="pro-select" name="category" id="categoryInput" required>
                        <option value="" disabled {{ empty($currentCategory) ? 'selected' : '' }}>Select a category...</option>
                        @foreach ($categoryOptions as $c)
                            <option value="{{ $c }}" {{ $currentCategory === $c ? 'selected' : '' }}>{{ $c }}</option>
                        @endforeach
                        @if ($currentCategory && !in_array($currentCategory, $categoryOptions, true))
                            <option value="{{ $currentCategory }}" selected>{{ $currentCategory }}</option>
                        @endif
                    </select>
                </div>
            </div>

            {{-- Featured Image --}}
            <div class="post-card">
                <h4>Featured Image</h4>

                <input type="hidden" name="image" id="imageInput" value="{{ $currentImage ?? '' }}">

                <div class="img-dropzone" id="imageDropzone" tabindex="0" role="button" aria-label="Upload featured image">
                    <div class="img-dropzone__preview" id="imagePreviewWrap" style="{{ empty($currentImageUrl) ? 'display: none;' : '' }}">
                        <img id="imagePreviewImg"
                             src="{{ !empty($currentImageUrl) ? $currentImageUrl : '' }}"
                             alt="Featured image preview">
                    </div>
                    <div class="img-dropzone__placeholder" id="imagePlaceholder" style="{{ !empty($currentImageUrl) ? 'display: none;' : '' }}">
                        <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round" style="color:#0b7a75;">
                            <rect x="3" y="3" width="18" height="18" rx="2"></rect>
                            <circle cx="8.5" cy="8.5" r="1.5"></circle>
                            <path d="m21 15-5-5L5 21"></path>
                        </svg>
                        <p><strong>Click to upload</strong> or drag & drop</p>
                        <small>PNG, JPG, WEBP, AVIF, GIF · max 5 MB</small>
                    </div>
                    <input type="file" id="imageFileInput" accept="image/*" hidden>
                </div>

                <div class="img-actions" style="{{ empty($currentImageUrl) ? 'display: none;' : '' }}" id="imageActions">
                    <span class="img-filename" id="imageFilenameLabel">{{ $currentImage ?? '' }}</span>
                    <div>
                        <button type="button" class="img-btn" id="imageReplaceBtn">Replace</button>
                        <button type="button" class="img-btn img-btn--danger" id="imageRemoveBtn">Remove</button>
                    </div>
                </div>

                <div class="img-upload-status" id="imageStatus" style="display:none;"></div>

                <div class="field-row" style="margin-top: 0.85rem;">
                    <label>Fallback CSS class</label>
                    <input type="text" name="image_class"
                           value="{{ old('image_class', $post->image_class) }}"
                           placeholder="guide-feature-card__image--teal">
                </div>
            </div>

            {{-- Excerpt --}}
            <div class="post-card">
                <h4>Excerpt</h4>
                <div class="field-row">
                    <textarea name="excerpt" rows="4" required
                              placeholder="Short summary shown on listings...">{{ old('excerpt', $post->excerpt) }}</textarea>
                    <small><span id="excerptCount">{{ strlen(old('excerpt', $post->excerpt ?? '')) }}</span> / 500 chars</small>
                </div>
            </div>

            {{-- Author & Reading time --}}
            <div class="post-card">
                <h4>Author</h4>
                <div class="field-row">
                    <label>Author name</label>
                    <input type="text" name="author_name"
                           value="{{ old('author_name', $post->author_name) }}" required>
                </div>
                <div class="field-row">
                    <label>Reading time</label>
                    <input type="text" name="reading_time"
                           value="{{ old('reading_time', $post->reading_time) }}"
                           placeholder="e.g. 5 min read">
                </div>
            </div>
        </aside>
    </div>
</form>

<script src="https://cdn.jsdelivr.net/npm/tinymce@7.6.1/tinymce.min.js" referrerpolicy="origin"></script>
<script>
(function(){
    if (window.tinymce) {
        window.tinymce.baseURL = 'https://cdn.jsdelivr.net/npm/tinymce@7.6.1';
        window.tinymce.suffix = '.min';
    }

    // ── TOP-LEVEL DOM DECLARATIONS (PREVENTS TEMPORAL DEAD ZONE REFERENCEERRORS) ──
    const titleInput          = document.querySelector('input[name="title"]');
    const slugInput           = document.getElementById('slugInput');
    const excerpt             = document.querySelector('textarea[name="excerpt"]');
    const dropzone            = document.getElementById('imageDropzone');
    const fileInput           = document.getElementById('imageFileInput');
    const imageInput          = document.getElementById('imageInput');
    const previewWrap         = document.getElementById('imagePreviewWrap');
    const previewImg          = document.getElementById('imagePreviewImg');
    const placeholder         = document.getElementById('imagePlaceholder');
    const actions             = document.getElementById('imageActions');
    const filenameLabel       = document.getElementById('imageFilenameLabel');
    const replaceBtn          = document.getElementById('imageReplaceBtn');
    const removeBtn           = document.getElementById('imageRemoveBtn');
    const statusBox           = document.getElementById('imageStatus');
    const csrfToken           = document.querySelector('input[name="_token"]')?.value || '';
    const uploadUrl           = "{{ route('admin.blog-posts.upload-image') }}";
    const tabButtons          = Array.from(document.querySelectorAll('[data-tab-target]'));
    const tabPanels           = Array.from(document.querySelectorAll('[data-tab-panel]'));
    const activeTabInput      = document.getElementById('activeTabInput');
    const aiDraftBtn          = document.getElementById('aiDraftBtn');
    const aiSeoBtn            = document.getElementById('aiSeoBtn');
    const blogAiStatus        = document.getElementById('blogAiStatus');
    const faqItemsInput       = document.getElementById('faqItemsInput');
    const faqList             = document.getElementById('faqList');
    const faqAddBtn           = document.getElementById('faqAddBtn');
    const importCard          = document.getElementById('importCard');
    const importZone          = document.getElementById('importZone');
    const importInput         = document.getElementById('importFileInput');
    const importPickBtn       = document.getElementById('importPickBtn');
    const importStatus        = document.getElementById('importStatus');
    const importUrl           = "{{ route('admin.blog-posts.import-file') }}";

    function initBlogEditor() {
        var ta = document.getElementById('editor-tinymce');
        if (!ta) return;

        if (window.tinymce && typeof tinymce.init === 'function') {
            if (tinymce.get('editor-tinymce')) {
                return;
            }

            tinymce.init({
                selector: '#editor-tinymce',
                base_url: 'https://cdn.jsdelivr.net/npm/tinymce@7.6.1',
                suffix: '.min',
                license_key: 'gpl',
                height: 700,
                min_height: 400,
                menubar: 'edit view insert format tools',
                plugins: 'autolink lists link image charmap anchor searchreplace visualblocks code fullscreen media table wordcount',
                toolbar: 'undo redo | blocks | bold italic underline strikethrough | forecolor backcolor | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image media table | blockquote code | removeformat | fullscreen',
                block_formats: 'Paragraph=p; Heading 2=h2; Heading 3=h3; Heading 4=h4; Quote=blockquote; Code=pre',
                content_style: 'body { font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif; font-size: 16px; line-height: 1.7; color: #22313d; padding: 1rem 1.25rem; } h2,h3,h4 { color:#12384f; font-weight:700; } a { color:#0b7a75; } blockquote { border-left:4px solid #0b7a75; padding:0.4rem 0.95rem; background:#f4f7fb; border-radius:0 6px 6px 0; }',
                branding: false,
                promotion: false,
                relative_urls: false,
                convert_urls: false,
                paste_data_images: true,
                image_caption: true,
                setup: function(editor) {
                    editor.on('input change keyup setcontent', function() {
                        if (typeof updateSeoPreview === 'function') {
                            updateSeoPreview();
                        }
                    });
                },
                init_instance_callback: function(editor) {
                    if (typeof updateSeoPreview === 'function') {
                        updateSeoPreview();
                    }
                }
            });
        } else {
            ta.style.display = 'block';
            ta.style.minHeight = '480px';
            ta.style.width = '100%';
            ta.style.padding = '1.1rem';
            ta.style.border = '1px solid #d6dde6';
            ta.style.borderRadius = '8px';
            ta.style.fontFamily = 'Monaco, Menlo, Consolas, monospace';
            ta.style.fontSize = '0.9rem';
            ta.style.lineHeight = '1.55';
            ta.addEventListener('input', function() {
                if (typeof updateSeoPreview === 'function') {
                    updateSeoPreview();
                }
            });
        }
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initBlogEditor);
    } else {
        initBlogEditor();
    }



    function safeParseFaqItems() {
        if (!faqItemsInput || !faqItemsInput.value) return [];
        try {
            const parsed = JSON.parse(faqItemsInput.value);
            return Array.isArray(parsed) ? parsed : [];
        } catch (error) {
            return [];
        }
    }

    function syncFaqItems() {
        if (!faqItemsInput || !faqList) return;
        const items = Array.from(faqList.querySelectorAll('.faq-item')).map((item) => ({
            question: item.querySelector('[data-faq-question]')?.value?.trim() || '',
            answer: item.querySelector('[data-faq-answer]')?.value?.trim() || '',
        })).filter((item) => item.question || item.answer);

        faqItemsInput.value = JSON.stringify(items);
        updateSeoPreview();
    }

    function setBlogAiStatus(message, type) {
        if (!blogAiStatus) return;
        if (!message) {
            blogAiStatus.className = 'ai-status';
            blogAiStatus.textContent = '';
            blogAiStatus.style.display = 'none';
            return;
        }
        blogAiStatus.className = 'ai-status is-' + (type || 'info');
        blogAiStatus.textContent = message;
        blogAiStatus.style.display = 'block';
    }

    function setAiBusy(button, busy, label) {
        if (!button) return;
        if (busy) {
            button.dataset.originalLabel = button.textContent;
            button.textContent = label;
            button.disabled = true;
            return;
        }
        button.textContent = button.dataset.originalLabel || button.textContent;
        button.disabled = false;
    }

    function normalizeAiErrorMessage(payload, fallbackMessage) {
        const userMessage = String(payload?.user_message || '').trim();
        const rawMessage = String(payload?.message || '').trim();

        if (userMessage) {
            return userMessage;
        }

        const combined = `${rawMessage} ${String(payload?.error || '')}`.toLowerCase();
        if (
            combined.includes('json') ||
            combined.includes('api request failed') ||
            combined.includes('did not include a valid json object') ||
            combined.includes('model_not_found') ||
            combined.includes('invalid_request_error') ||
            combined.includes('ai request failed')
        ) {
            return 'The AI API is not working right now. Please try again. If it keeps failing, check the API key, model, or provider settings.';
        }

        return fallbackMessage;
    }

    function showAiFailurePopup(message) {
        if (!message) return;
        adminModal.error({ title: 'Error', message: message });
    }

    function applyBlogSeoData(data) {
        const map = {
            meta_title: 'metaTitleInput',
            meta_description: 'metaDescriptionInput',
            focus_keyword: 'focusKeywordInput',
            secondary_keywords: 'secondaryKeywordsInput',
            og_title: 'ogTitleInput',
            og_description: 'ogDescriptionInput',
            schema_type: 'schemaTypeInput',
        };

        Object.entries(map).forEach(([key, id]) => {
            const field = document.getElementById(id);
            if (field && typeof data[key] === 'string' && data[key].trim() !== '') {
                field.value = data[key];
                field.dispatchEvent(new Event('input', { bubbles: true }));
            }
        });

        if (faqItemsInput && Array.isArray(data.faq_items) && data.faq_items.length) {
            faqItemsInput.value = JSON.stringify(data.faq_items);
            renderFaqItems();
            syncFaqItems();
        }
    }

    function applyBlogDraftData(data) {
        if (typeof data.title === 'string' && data.title.trim() !== '') {
            titleInput.value = data.title.trim();
            titleInput.dispatchEvent(new Event('input', { bubbles: true }));
        }
        if (typeof data.excerpt === 'string') {
            excerpt.value = data.excerpt;
            excerpt.dispatchEvent(new Event('input', { bubbles: true }));
        }
        if (typeof data.category === 'string' && data.category.trim() !== '') {
            const categoryInput = document.getElementById('categoryInput');
            if (categoryInput) {
                const normalized = data.category.trim();
                let option = Array.from(categoryInput.options).find((item) => item.value === normalized);
                if (!option) {
                    option = document.createElement('option');
                    option.value = normalized;
                    option.textContent = normalized;
                    categoryInput.appendChild(option);
                }
                categoryInput.value = normalized;
            }
        }
        if (typeof data.reading_time === 'string' && data.reading_time.trim() !== '') {
            const readingTimeInput = document.querySelector('input[name="reading_time"]');
            if (readingTimeInput) readingTimeInput.value = data.reading_time.trim();
        }
        if (typeof data.body_html === 'string' && data.body_html.trim() !== '') {
            if (window.tinymce && tinymce.get('editor-tinymce')) {
                tinymce.get('editor-tinymce').setContent(data.body_html);
            } else {
                const editor = document.getElementById('editor-tinymce');
                if (editor) {
                    editor.value = data.body_html;
                    editor.dispatchEvent(new Event('input', { bubbles: true }));
                }
            }
        }
        applyBlogSeoData(data);

        const meta = data && typeof data === 'object' ? data._meta || {} : {};
        if (meta && meta.source === 'fallback') {
            const warning = String(meta.warning || 'The AI API failed, so a basic fallback draft was created instead.').trim();
            setBlogAiStatus(warning, 'error');
            showAiFailurePopup(warning);
        }
    }

    function faqItemTemplate(index, item = {}) {
        const wrapper = document.createElement('div');
        wrapper.className = 'faq-item';
        const escapeHtml = (value) => String(value || '')
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;')
            .replace(/'/g, '&#039;');
        wrapper.innerHTML = `
            <div class="faq-item__top">
                <div class="faq-item__label">FAQ Item ${index + 1}</div>
                <button type="button" class="faq-item__remove" data-faq-remove>Remove</button>
            </div>
            <div class="faq-item__fields">
                <input type="text" data-faq-question maxlength="300" placeholder="Question" value="${escapeHtml(item.question || '')}">
                <textarea data-faq-answer maxlength="3000" placeholder="Answer">${escapeHtml(item.answer || '')}</textarea>
            </div>
        `;
        return wrapper;
    }

    function renderFaqItems() {
        if (!faqList) return;
        const items = safeParseFaqItems();
        faqList.innerHTML = '';
        items.forEach((item, index) => {
            faqList.appendChild(faqItemTemplate(index, item));
        });
        if (!items.length) {
            faqList.appendChild(faqItemTemplate(0, {}));
        }
    }



    function setActiveTab(name) {
        const targetTab = (name === 'seo' || name === 'editor') ? name : 'editor';

        tabButtons.forEach((button) => {
            const isActive = button.dataset.tabTarget === targetTab;
            button.classList.toggle('is-active', isActive);
            button.setAttribute('aria-selected', isActive ? 'true' : 'false');
        });

        tabPanels.forEach((panel) => {
            const isMatch = panel.dataset.tabPanel === targetTab;
            panel.hidden = !isMatch;
            if (!isMatch) {
                panel.style.display = 'none';
            } else {
                panel.style.display = '';
            }
        });

        if (targetTab === 'editor' && window.tinymce && tinymce.get('editor-tinymce')) {
            try {
                tinymce.get('editor-tinymce').show();
            } catch (e) {}
        }

        if (activeTabInput) {
            activeTabInput.value = targetTab;
        }
    }

    tabButtons.forEach((button) => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            setActiveTab(this.dataset.tabTarget);
        });
    });

    const initialTab = activeTabInput?.value || 'editor';
    setActiveTab(initialTab === 'seo' ? 'seo' : 'editor');

    titleInput.addEventListener('input', function(){
        if (!slugInput.dataset.userEdited) {
            slugInput.value = this.value.toLowerCase()
                .replace(/[^\w\s-]/g, '')
                .replace(/\s+/g, '-')
                .replace(/-+/g, '-');
        }
    });
    slugInput.addEventListener('input', function(){ slugInput.dataset.userEdited = '1'; });

    // Excerpt counter
    const counter = document.getElementById('excerptCount');
    if (excerpt && counter) {
        excerpt.addEventListener('input', function(){
            counter.textContent = this.value.length;
        });
    }

    function absoluteUrl(value) {
        const raw = (value || '').trim();
        if (!raw) return '';
        if (/^https?:\/\//i.test(raw)) return raw;
        if (raw.startsWith('/')) return window.location.origin + raw;
        return window.location.origin + '/' + raw.replace(/^\/+/, '');
    }

    function truncate(value, max) {
        const text = (value || '').trim();
        if (text.length <= max) return text;
        return text.slice(0, max - 1).trimEnd() + '…';
    }

    function updateSeoCounter(el) {
        const input = document.getElementById(el.dataset.counterFor);
        if (!input) return;

        const length = (input.value || '').trim().length;
        const goodMin = Number(el.dataset.goodMin || 0);
        const goodMax = Number(el.dataset.goodMax || 0);
        const max = Number(el.dataset.max || 0);

        el.textContent = length + (max ? ' / ' + max + ' chars' : ' chars');
        el.classList.remove('is-good', 'is-warn', 'is-bad');

        if (!length) return;
        if ((goodMax && length > goodMax) || (max && length > max)) {
            el.classList.add('is-bad');
            return;
        }
        if (goodMin && length < goodMin) {
            el.classList.add('is-warn');
            return;
        }

        el.classList.add('is-good');
    }

    function updateSeoPreview() {
        const metaTitleInput = document.getElementById('metaTitleInput');
        const metaDescriptionInput = document.getElementById('metaDescriptionInput');
        const focusKeywordInput = document.getElementById('focusKeywordInput');
        const authorUrlInput = document.getElementById('authorUrlInput');
        const ogTitleInput = document.getElementById('ogTitleInput');
        const ogDescriptionInput = document.getElementById('ogDescriptionInput');
        const canonicalUrlInput = document.getElementById('canonicalUrlInput');
        const ogImageInput = document.getElementById('ogImageInput');
        const featuredImageInput = document.getElementById('imageInput');
        const seoScoreRing = document.getElementById('seoScoreRing');
        const seoScoreValue = document.getElementById('seoScoreValue');
        const seoScoreLabel = document.getElementById('seoScoreLabel');
        const seoScoreStatus = document.getElementById('seoScoreStatus');
        const seoScoreBreakdown = document.getElementById('seoScoreBreakdown');
        const previewUrl = document.getElementById('seoPreviewUrl');
        const previewTitle = document.getElementById('seoPreviewTitle');
        const previewDesc = document.getElementById('seoPreviewDesc');
        const ogPreviewTitle = document.getElementById('ogPreviewTitle');
        const ogPreviewDesc = document.getElementById('ogPreviewDesc');
        const ogPreviewImg = document.getElementById('ogPreviewImg');
        const ogPreviewPlaceholder = document.getElementById('ogPreviewPlaceholder');

        const slug = (slugInput?.value || '').trim() || 'post-slug';
        const fallbackUrl = window.location.origin + '/blog/' + slug.replace(/^\/+/, '');
        const seoTitle = (metaTitleInput?.value || '').trim()
            || (((titleInput?.value || '').trim()) ? (titleInput.value.trim() + ' | SettleANZ Blog') : 'Blog post title | SettleANZ Blog');
        const seoDescription = (metaDescriptionInput?.value || '').trim()
            || ((excerpt?.value || '').trim())
            || 'Write a clear excerpt so search engines and visitors understand the article quickly.';
        const socialTitle = (ogTitleInput?.value || '').trim() || seoTitle;
        const socialDescription = (ogDescriptionInput?.value || '').trim() || seoDescription;
        const canonicalUrl = (canonicalUrlInput?.value || '').trim() || fallbackUrl;
        const authorUrl = (authorUrlInput?.value || '').trim();
        const faqItems = safeParseFaqItems().filter((item) => (item.question || '').trim() && (item.answer || '').trim());
        const focusKeyword = (focusKeywordInput?.value || '').trim().toLowerCase();
        const bodyText = ((window.tinymce && tinymce.get('editor-tinymce'))
            ? tinymce.get('editor-tinymce').getContent({ format: 'text' })
            : (document.getElementById('editor-tinymce')?.value || '')).toLowerCase();
        const titleLength = ((metaTitleInput?.value || '').trim() || seoTitle).length;
        const descriptionLength = ((metaDescriptionInput?.value || '').trim() || seoDescription).length;
        const hasCustomCanonical = !!(canonicalUrlInput?.value || '').trim();
        const hasKeywordInTitle = !!focusKeyword && seoTitle.toLowerCase().includes(focusKeyword);
        const hasKeywordInDescription = !!focusKeyword && seoDescription.toLowerCase().includes(focusKeyword);
        const hasKeywordInSlug = !!focusKeyword && slug.toLowerCase().includes(focusKeyword.replace(/\s+/g, '-'));
        const hasKeywordInBody = !!focusKeyword && bodyText.includes(focusKeyword);
        const titleLengthGood = titleLength >= 50 && titleLength <= 60;
        const descriptionLengthGood = descriptionLength >= 140 && descriptionLength <= 160;
        const hasImage = !!(ogImageInput?.value || '').trim() || !!featuredImageInput?.value;
        const hasSchemaType = !!document.getElementById('schemaTypeInput')?.value;
        const hasReadingTime = !!document.querySelector('input[name="reading_time"]')?.value?.trim();

        previewUrl.textContent = truncate(canonicalUrl.replace(/^https?:\/\//i, ''), 80);
        previewTitle.textContent = truncate(seoTitle, 60);
        previewDesc.textContent = truncate(seoDescription, 160);
        ogPreviewTitle.textContent = truncate(socialTitle, 100);
        ogPreviewDesc.textContent = truncate(socialDescription, 160);

        const fallbackImage = featuredImageInput?.value
            ? ((previewImg?.src || document.getElementById('imagePreviewImg')?.src) || (window.location.origin + '/storage/blog/' + featuredImageInput.value.replace(/^\/+/, '')))
            : '';
        const socialImage = absoluteUrl(ogImageInput?.value || '') || fallbackImage;

        if (socialImage) {
            ogPreviewImg.src = socialImage;
            ogPreviewImg.style.display = '';
            ogPreviewPlaceholder.style.display = 'none';
        } else {
            ogPreviewImg.removeAttribute('src');
            ogPreviewImg.style.display = 'none';
            ogPreviewPlaceholder.style.display = '';
        }

        const checks = {
            keyword: !!focusKeyword,
            title: hasKeywordInTitle,
            description: hasKeywordInDescription,
            slug: hasKeywordInSlug,
            body: hasKeywordInBody,
        };

        document.querySelectorAll('[data-check]').forEach((item) => {
            const passed = !!checks[item.dataset.check];
            item.classList.toggle('is-pass', passed);
            item.classList.toggle('is-warn', !passed);
            const icon = item.querySelector('.seo-check-icon');
            if (icon) {
                icon.textContent = passed ? '✓' : '!';
            }
        });

        const scoreItems = [
            { key: 'focusKeyword', title: 'Focus keyword selected', hint: 'Set one primary phrase for this article.', passed: !!focusKeyword, points: 10, optional: false },
            { key: 'keywordTitle', title: 'Keyword appears in SEO title', hint: 'Match the title closely to search intent.', passed: hasKeywordInTitle, points: 12, optional: false },
            { key: 'keywordDescription', title: 'Keyword appears in meta description', hint: 'Helps reinforce relevance in snippets.', passed: hasKeywordInDescription, points: 10, optional: false },
            { key: 'keywordSlug', title: 'Keyword appears in slug', hint: 'Short, readable URLs are best.', passed: hasKeywordInSlug, points: 8, optional: false },
            { key: 'keywordBody', title: 'Keyword appears in body content', hint: 'Use the phrase naturally in the article.', passed: hasKeywordInBody, points: 12, optional: false },
            { key: 'titleLength', title: 'SEO title length is in range', hint: `Current length: ${titleLength} characters. Aim for 50 to 60.`, passed: titleLengthGood, points: 12, optional: false },
            { key: 'descriptionLength', title: 'Meta description length is in range', hint: `Current length: ${descriptionLength} characters. Aim for about 140 to 160.`, passed: descriptionLengthGood, points: 10, optional: false },
            { key: 'image', title: 'Social image is available', hint: 'Use a featured image or custom OG image.', passed: hasImage, points: 8, optional: false },
            { key: 'authorUrl', title: 'Author profile URL is set', hint: 'Supports stronger article schema and trust signals.', passed: !!authorUrl, points: 8, optional: false },
            { key: 'schemaType', title: 'Article schema type is set', hint: 'Keep this as Article or BlogPosting for blog posts.', passed: hasSchemaType, points: 6, optional: false },
            { key: 'readingTime', title: 'Reading time is filled in', hint: 'Helpful for users and content completeness.', passed: hasReadingTime, points: 4, optional: true },
            { key: 'canonical', title: 'Custom canonical URL configured', hint: hasCustomCanonical ? 'Custom canonical is set.' : 'Optional. Only use if this page should point to another preferred URL.', passed: hasCustomCanonical, points: 2, optional: true },
            { key: 'faq', title: 'Three relevant FAQ items added', hint: `Current FAQ count: ${faqItems.length}. Add 3 content-based FAQs for the strongest setup.`, passed: faqItems.length === 3, points: 9, optional: true },
        ];

        const requiredItems = scoreItems.filter((item) => !item.optional);
        const optionalItems = scoreItems.filter((item) => item.optional);
        const earnedRequired = requiredItems.filter((item) => item.passed).reduce((sum, item) => sum + item.points, 0);
        const totalRequired = requiredItems.reduce((sum, item) => sum + item.points, 0);
        const earnedOptional = optionalItems.filter((item) => item.passed).reduce((sum, item) => sum + item.points, 0);
        const totalOptional = optionalItems.reduce((sum, item) => sum + item.points, 0);
        const requiredScore = totalRequired === 0 ? 0 : (earnedRequired / totalRequired) * 85;
        const optionalScore = totalOptional === 0 ? 0 : (earnedOptional / totalOptional) * 15;
        const allRequiredPassed = requiredItems.every((item) => item.passed);
        const rawScore = requiredScore + optionalScore;
        const score = Math.max(0, Math.min(100, Math.round(rawScore)));

        if (seoScoreRing) {
            seoScoreRing.style.setProperty('--score-angle', `${Math.round((score / 100) * 360)}deg`);
        }
        if (seoScoreValue) {
            seoScoreValue.textContent = String(score);
        }
        if (seoScoreStatus) {
            seoScoreStatus.classList.remove('is-strong', 'is-fair', 'is-weak');
            if (allRequiredPassed && score >= 85) {
                seoScoreStatus.textContent = 'Strong';
                seoScoreStatus.classList.add('is-strong');
            } else if (score >= 55) {
                seoScoreStatus.textContent = 'Fair';
                seoScoreStatus.classList.add('is-fair');
            } else {
                seoScoreStatus.textContent = 'Needs Work';
                seoScoreStatus.classList.add('is-weak');
            }
        }
        if (seoScoreLabel) {
            seoScoreLabel.textContent = allRequiredPassed && score >= 85
                ? 'All required SEO checks are passing, and the optional enhancements are in good shape too.'
                : score >= 55
                    ? 'The basics are partly in place, but one or more required SEO items still need attention.'
                    : 'Start with the required title, description, keyword, image, and author signals first.';
        }
        if (seoScoreBreakdown) {
            seoScoreBreakdown.innerHTML = '';
            scoreItems.forEach((item) => {
                const row = document.createElement('div');
                row.className = `seo-score__item ${item.passed ? 'is-pass' : 'is-warn'} ${item.optional ? 'is-optional' : ''}`;
                row.innerHTML = `
                    <div>
                        <div class="seo-score__item-title">${item.title}${item.optional ? ' (Optional)' : ''}</div>
                        <div class="seo-score__item-hint">${item.hint}</div>
                    </div>
                    <div class="seo-score__item-points">${item.passed ? '+' : ''}${item.passed ? item.points : 0} pts</div>
                `;
                seoScoreBreakdown.appendChild(row);
            });
        }
    }

    document.querySelectorAll('.seo-counter').forEach((el) => {
        updateSeoCounter(el);
        const input = document.getElementById(el.dataset.counterFor);
        if (input) {
            input.addEventListener('input', function() {
                updateSeoCounter(el);
                updateSeoPreview();
            });
        }
    });

    [titleInput, slugInput, excerpt, document.getElementById('canonicalUrlInput'), document.getElementById('ogImageInput'), document.getElementById('focusKeywordInput'), document.getElementById('secondaryKeywordsInput'), document.getElementById('authorUrlInput')]
        .filter(Boolean)
        .forEach((el) => el.addEventListener('input', updateSeoPreview));

    renderFaqItems();

    if (aiDraftBtn) {
        aiDraftBtn.addEventListener('click', async function() {
            setBlogAiStatus('Generating a blog draft with AI…', 'info');
            setAiBusy(aiDraftBtn, true, 'Writing…');
            try {
                const response = await fetch("{{ route('admin.ai.blog-draft') }}", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('input[name="_token"]')?.value || '',
                    },
                    credentials: 'same-origin',
                    body: JSON.stringify({
                        title: titleInput?.value || '',
                        category: document.getElementById('categoryInput')?.value || '',
                        excerpt: excerpt?.value || '',
                        body_html: window.tinymce && tinymce.get('editor-tinymce') ? tinymce.get('editor-tinymce').getContent() : (document.getElementById('editor-tinymce')?.value || ''),
                        author_name: document.querySelector('input[name="author_name"]')?.value || '',
                        reading_time: document.querySelector('input[name="reading_time"]')?.value || '',
                    }),
                });
                const payload = await response.json().catch(() => ({}));
                if (!response.ok) {
                    throw new Error(normalizeAiErrorMessage(payload, 'The AI draft could not be generated right now.'));
                }
                applyBlogDraftData(payload.data || {});
                setActiveTab('editor');
                const meta = payload?.data?._meta || {};
                if (meta.source !== 'fallback') {
                    setBlogAiStatus('AI draft applied. Review the content carefully before saving.', 'info');
                }
            } catch (error) {
                const message = error.message || 'The AI draft could not be generated right now.';
                setBlogAiStatus(message, 'error');
                showAiFailurePopup(message);
            } finally {
                setAiBusy(aiDraftBtn, false, '');
            }
        });
    }

    if (aiSeoBtn) {
        aiSeoBtn.addEventListener('click', async function() {
            setBlogAiStatus('Generating SEO fields with AI…', 'info');
            setAiBusy(aiSeoBtn, true, 'Thinking…');
            try {
                const response = await fetch("{{ route('admin.ai.blog-seo') }}", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('input[name="_token"]')?.value || '',
                    },
                    credentials: 'same-origin',
                    body: JSON.stringify({
                        title: titleInput?.value || '',
                        slug: slugInput?.value || '',
                        category: document.getElementById('categoryInput')?.value || '',
                        excerpt: excerpt?.value || '',
                        body_html: window.tinymce && tinymce.get('editor-tinymce') ? tinymce.get('editor-tinymce').getContent() : (document.getElementById('editor-tinymce')?.value || ''),
                    }),
                });
                const payload = await response.json().catch(() => ({}));
                if (!response.ok) {
                    throw new Error(normalizeAiErrorMessage(payload, 'The AI SEO fields could not be generated right now.'));
                }
                applyBlogSeoData(payload.data || {});
                setActiveTab('seo');
                setBlogAiStatus('AI SEO suggestions applied. Review them before saving.', 'info');
            } catch (error) {
                const message = error.message || 'The AI SEO fields could not be generated right now.';
                setBlogAiStatus(message, 'error');
                showAiFailurePopup(message);
            } finally {
                setAiBusy(aiSeoBtn, false, '');
            }
        });
    }

    if (faqAddBtn && faqList) {
        faqAddBtn.addEventListener('click', function() {
            const nextIndex = faqList.querySelectorAll('.faq-item').length;
            faqList.appendChild(faqItemTemplate(nextIndex, {}));
            syncFaqItems();
        });

        faqList.addEventListener('click', function(event) {
            const removeBtn = event.target.closest('[data-faq-remove]');
            if (!removeBtn) return;
            const item = removeBtn.closest('.faq-item');
            if (item) item.remove();
            if (!faqList.querySelector('.faq-item')) {
                faqList.appendChild(faqItemTemplate(0, {}));
            }
            Array.from(faqList.querySelectorAll('.faq-item__label')).forEach((label, index) => {
                label.textContent = `FAQ Item ${index + 1}`;
            });
            syncFaqItems();
        });

        faqList.addEventListener('input', function(event) {
            if (event.target.matches('[data-faq-question], [data-faq-answer]')) {
                syncFaqItems();
            }
        });
    }

    updateSeoPreview();

    // ── Featured image upload ─────────────────────────────────────────

    function showStatus(message, type){
        if (!statusBox) return;
        if (!message) { statusBox.style.display = 'none'; statusBox.textContent = ''; return; }
        statusBox.textContent = message;
        statusBox.className = 'img-upload-status is-' + (type || 'info');
        statusBox.style.display = '';
    }

    function setImage(filename, url){
        const cleanName = (filename || '').replace(/^.*[\\\/]/, '').replace(/^(storage\/blog\/|storage\/|media\/blog\/|public\/media\/blog\/)/, '');
        if (imageInput) imageInput.value = cleanName;
        if (previewImg) {
            previewImg.src = url + (url.includes('?') ? '&' : '?') + 't=' + Date.now();
            previewImg.style.display = '';
        }
        if (previewWrap) previewWrap.style.display = '';
        if (placeholder) placeholder.style.display = 'none';
        if (actions) actions.style.display = '';
        if (filenameLabel) filenameLabel.textContent = cleanName;
        showStatus('', null);
        updateSeoPreview();
    }

    function clearImage(){
        if (imageInput) imageInput.value = '';
        if (previewImg) {
            previewImg.src = '';
            previewImg.removeAttribute('src');
            previewImg.style.display = 'none';
        }
        if (previewWrap) previewWrap.style.display = 'none';
        if (placeholder) placeholder.style.display = '';
        if (actions) actions.style.display = 'none';
        if (filenameLabel) filenameLabel.textContent = '';
        showStatus('', null);
        updateSeoPreview();
    }

    async function uploadFile(file){
        if (!file) return;
        if (!file.type.startsWith('image/')) {
            showStatus('Please choose an image file.', 'error');
            return;
        }
        if (file.size > 5 * 1024 * 1024) {
            showStatus('File is larger than 5 MB.', 'error');
            return;
        }

        showStatus('Uploading ' + file.name + '…', 'info');
        dropzone.classList.add('is-uploading');

        const fd = new FormData();
        fd.append('image', file);
        fd.append('_token', csrfToken);

        try {
            const res = await fetch(uploadUrl, {
                method: 'POST',
                body: fd,
                headers: { 'X-CSRF-TOKEN': csrfToken, 'Accept': 'application/json' },
                credentials: 'same-origin'
            });
            const data = await res.json().catch(() => ({}));
            if (!res.ok) {
                const msg = (data && (data.message || (data.errors && data.errors.image && data.errors.image[0]))) || 'Upload failed.';
                showStatus(msg, 'error');
                return;
            }
            setImage(data.filename, data.url);
            showStatus('Uploaded successfully.', 'info');
            setTimeout(() => showStatus('', null), 2500);
        } catch (e) {
            showStatus('Upload failed. ' + (e.message || ''), 'error');
        } finally {
            dropzone.classList.remove('is-uploading');
            if (fileInput) fileInput.value = '';
        }
    }

    if (dropzone && fileInput) {
        dropzone.addEventListener('click', (e) => {
            if (e.target.closest('#imageActions') || e.target.closest('button')) {
                return;
            }
            fileInput.value = '';
            fileInput.click();
        });
        dropzone.addEventListener('keydown', (e) => {
            if (e.key === 'Enter' || e.key === ' ') {
                e.preventDefault();
                fileInput.value = '';
                fileInput.click();
            }
        });
        fileInput.addEventListener('change', () => {
            if (fileInput.files && fileInput.files[0]) {
                uploadFile(fileInput.files[0]);
            }
        });

        ['dragenter', 'dragover'].forEach(evt =>
            dropzone.addEventListener(evt, (e) => { e.preventDefault(); e.stopPropagation(); dropzone.classList.add('is-drag'); })
        );
        ['dragleave', 'drop'].forEach(evt =>
            dropzone.addEventListener(evt, (e) => { e.preventDefault(); e.stopPropagation(); dropzone.classList.remove('is-drag'); })
        );
        dropzone.addEventListener('drop', (e) => {
            const file = e.dataTransfer?.files?.[0];
            if (file) uploadFile(file);
        });
    }

    if (replaceBtn) {
        replaceBtn.addEventListener('click', (e) => {
            e.preventDefault();
            e.stopPropagation();
            if (fileInput) {
                fileInput.value = '';
                fileInput.click();
            }
        });
    }

    if (removeBtn) {
        removeBtn.addEventListener('click', (e) => {
            e.preventDefault();
            e.stopPropagation();
            clearImage();
        });
    }

    // ── Import from PDF / DOCX ─────────────────────────────────────────

    function showImportStatus(message, type){
        if (!importStatus) return;
        if (!message) { importStatus.style.display = 'none'; importStatus.textContent = ''; return; }
        importStatus.textContent = message;
        importStatus.className = 'import-card__status is-' + (type || 'info');
        importStatus.style.display = '';
    }

    function applyImported(data){
        // Title
        if (data.title) {
            const t = document.querySelector('input[name="title"]');
            if (t) {
                t.value = data.title;
                t.dispatchEvent(new Event('input', { bubbles: true })); // triggers slug auto-gen
            }
        }
        // Excerpt
        if (data.excerpt) {
            const ex = document.querySelector('textarea[name="excerpt"]');
            if (ex) {
                ex.value = data.excerpt;
                ex.dispatchEvent(new Event('input', { bubbles: true }));
            }
        }
        // Reading time
        if (data.reading_time) {
            const rt = document.querySelector('input[name="reading_time"]');
            if (rt) rt.value = data.reading_time;
        }
        // Body HTML → TinyMCE
        if (data.body_html) {
            if (window.tinymce && tinymce.get('editor-tinymce')) {
                tinymce.get('editor-tinymce').setContent(data.body_html);
            } else {
                const ta = document.getElementById('editor-tinymce');
                if (ta) ta.value = data.body_html;
            }
        }

        updateSeoPreview();
    }

    async function importDocument(file){
        if (!file) return;

        const ext = file.name.toLowerCase().split('.').pop();
        if (!['pdf', 'docx', 'doc'].includes(ext)) {
            showImportStatus('Please choose a .pdf, .docx, or .doc file.', 'error');
            return;
        }
        if (file.size > 10 * 1024 * 1024) {
            showImportStatus('File is larger than 10 MB.', 'error');
            return;
        }

        showImportStatus('Parsing ' + file.name + '… this can take a few seconds.', 'info');
        importCard.classList.add('is-loading');

        const fd = new FormData();
        fd.append('document', file);
        fd.append('_token', csrfToken);

        try {
            const res = await fetch(importUrl, {
                method: 'POST',
                body: fd,
                headers: { 'X-CSRF-TOKEN': csrfToken, 'Accept': 'application/json' },
                credentials: 'same-origin'
            });
            const data = await res.json().catch(() => ({}));
            if (!res.ok) {
                const msg = (data && (data.message || (data.errors && data.errors.document && data.errors.document[0]))) || 'Import failed.';
                showImportStatus(msg, 'error');
                return;
            }
            applyImported(data);
            const wc = data.word_count ? (' · ' + data.word_count + ' words') : '';
            showImportStatus('Imported successfully' + wc + '. Review and edit before saving.', 'info');
        } catch (e) {
            showImportStatus('Import failed. ' + (e.message || ''), 'error');
        } finally {
            importCard.classList.remove('is-loading');
            importInput.value = '';
        }
    }

    if (importInput) {
        importInput.addEventListener('change', () => importDocument(importInput.files[0]));
    }
    if (importPickBtn) {
        importPickBtn.addEventListener('click', (e) => { e.preventDefault(); importInput.click(); });
    }
    if (importZone) {
        importZone.addEventListener('click', () => importInput.click());
        importZone.addEventListener('keydown', (e) => {
            if (e.key === 'Enter' || e.key === ' ') { e.preventDefault(); importInput.click(); }
        });
        ['dragenter', 'dragover'].forEach(evt =>
            importZone.addEventListener(evt, (e) => { e.preventDefault(); e.stopPropagation(); importZone.classList.add('is-drag'); })
        );
        ['dragleave', 'drop'].forEach(evt =>
            importZone.addEventListener(evt, (e) => { e.preventDefault(); e.stopPropagation(); importZone.classList.remove('is-drag'); })
        );
        importZone.addEventListener('drop', (e) => {
            const file = e.dataTransfer?.files?.[0];
            if (file) importDocument(file);
        });
    }

    // Ensure TinyMCE content is synced to textarea before form submit
    const blogForm = document.getElementById('blogForm');
    if (blogForm) {
        blogForm.addEventListener('submit', function() {
            if (window.tinymce && tinymce.get('editor-tinymce')) {
                tinymce.get('editor-tinymce').save();
            }
        });
    }
})();
</script>
