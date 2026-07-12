(function () {
    'use strict';

    var PDF_CDN = 'https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/';
    var PDF_JS_URL = PDF_CDN + 'pdf.min.js';
    var PDF_WORKER_URL = PDF_CDN + 'pdf.worker.min.js';

    function loadPdfJs(callback) {
        if (typeof pdfjsLib !== 'undefined') {
            callback();
            return;
        }
        var s = document.createElement('script');
        s.src = PDF_JS_URL;
        s.onload = function () {
            pdfjsLib.GlobalWorkerOptions.workerSrc = PDF_WORKER_URL;
            if (typeof callback === 'function') callback();
        };
        s.onerror = function () {
            console.error('[PDF Viewer] Failed to load PDF.js from CDN:', PDF_JS_URL);
            var loader = document.getElementById('pv-loading');
            var errEl = document.getElementById('pv-error');
            if (loader) loader.style.display = 'none';
            if (errEl) {
                errEl.style.display = 'flex';
                document.getElementById('pv-error-desc').textContent = 'Failed to load the PDF viewer library. Check your internet connection and try again.';
            }
        };
        document.head.appendChild(s);
    }

    function matrixTransform(m1, m2) {
        return [
            m1[0] * m2[0] + m1[2] * m2[1],
            m1[1] * m2[0] + m1[3] * m2[1],
            m1[0] * m2[2] + m1[2] * m2[3],
            m1[1] * m2[2] + m1[3] * m2[3],
            m1[0] * m2[4] + m1[2] * m2[5] + m1[4],
            m1[1] * m2[4] + m1[3] * m2[5] + m1[5],
        ];
    }

    function safeTransform(transform, itemTransform) {
        try {
            if (typeof pdfjsLib !== 'undefined' && pdfjsLib.Util && typeof pdfjsLib.Util.transform === 'function') {
                return pdfjsLib.Util.transform(transform, itemTransform);
            }
        } catch (e) {}
        return matrixTransform(transform, itemTransform);
    }

    function formatBytes(bytes) {
        if (!bytes || bytes === 0) return '0 B';
        var units = ['B', 'KB', 'MB', 'GB'];
        var i = Math.floor(Math.log(bytes) / Math.log(1024));
        return (bytes / Math.pow(1024, i)).toFixed(1) + ' ' + units[i];
    }

    function formatDate(str) {
        if (!str) return '—';
        try { return new Date(str).toLocaleDateString('en-US', { year: 'numeric', month: 'short', day: 'numeric' }); }
        catch (e) { return str; }
    }

    function escapeHtml(str) {
        var div = document.createElement('div');
        div.appendChild(document.createTextNode(str));
        return div.innerHTML;
    }

    function PdfViewer() {
        this.modal = null;
        this.pdfDoc = null;
        this.currentPage = 1;
        this.totalPages = 0;
        this.zoom = 1;
        this.rotation = 0;
        this.sidebarOpen = true;
        this.ebook = null;
        this.renderedPages = {};
        this.searchResults = [];
        this.currentMatchIdx = -1;
        this.isLoading = false;
        this.pageTextContent = {};
        this.textLayers = {};
        this.isContinuous = true;
        this.pageGap = 8;

        this._els = {};
        this._init();
    }

    PdfViewer.prototype._init = function () {
        var self = this;

        var style = document.createElement('link');
        style.rel = 'stylesheet';
        style.href = '/css/pdf-viewer.css';
        document.head.appendChild(style);

        var html = '' +
            '<div id="pdf-viewer-overlay" class="pv-overlay">' +
            '  <div class="pv-backdrop"></div>' +
            '  <div class="pv-container">' +

            '    <div class="pv-toolbar">' +
            '      <div class="pv-toolbar-left">' +
            '        <button type="button" class="pv-btn pv-btn-close" title="Close (Esc)" data-action="close">' +
            '          <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 6L6 18M6 6l12 12"/></svg>' +
            '        </button>' +
            '        <div class="pv-divider-v"></div>' +
            '        <div class="pv-file-info">' +
            '          <div class="pv-filename" id="pv-filename">Document</div>' +
            '          <div class="pv-file-meta" id="pv-file-meta"></div>' +
            '        </div>' +
            '      </div>' +
            '      <div class="pv-toolbar-center">' +
            '        <button type="button" class="pv-btn" title="Toggle Sidebar" data-action="sidebar">' +
            '          <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="18" height="18" rx="2"/><line x1="9" y1="3" x2="9" y2="21"/></svg>' +
            '        </button>' +
            '        <div class="pv-divider-v"></div>' +
            '        <button type="button" class="pv-btn" title="Zoom Out" data-action="zoom-out">' +
            '          <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/><line x1="8" y1="11" x2="14" y2="11"/></svg>' +
            '        </button>' +
            '        <select class="pv-zoom-select" id="pv-zoom-select" title="Zoom">' +
            '          <option value="0.5">50%</option>' +
            '          <option value="0.75">75%</option>' +
            '          <option value="1" selected>100%</option>' +
            '          <option value="1.25">125%</option>' +
            '          <option value="1.5">150%</option>' +
            '          <option value="2">200%</option>' +
            '          <option value="3">300%</option>' +
            '          <option value="fit-width">Fit Width</option>' +
            '          <option value="fit-page">Fit Page</option>' +
            '        </select>' +
            '        <button type="button" class="pv-btn" title="Zoom In" data-action="zoom-in">' +
            '          <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/><line x1="8" y1="11" x2="14" y2="11"/><line x1="11" y1="8" x2="11" y2="14"/></svg>' +
            '        </button>' +
            '        <div class="pv-divider-v"></div>' +
            '        <button type="button" class="pv-btn" title="Toggle Continuous Scroll" data-action="toggle-scroll">' +
            '          <span class="pv-scroll-icon" style="font-size:13px;font-weight:600">1</span>' +
            '        </button>' +
            '        <div class="pv-divider-v"></div>' +
            '        <button type="button" class="pv-btn" title="Rotate Left" data-action="rotate-left">' +
            '          <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M1 4v6h6"/><path d="M3.51 15a9 9 0 102.13-9.36L1 10"/></svg>' +
            '        </button>' +
            '        <button type="button" class="pv-btn" title="Rotate Right" data-action="rotate-right">' +
            '          <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M23 4v6h-6"/><path d="M20.49 15a9 9 0 11-2.12-9.36L23 10"/></svg>' +
            '        </button>' +
            '        <div class="pv-divider-v"></div>' +
            '        <button type="button" class="pv-btn" title="Search" data-action="search">' +
            '          <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>' +
            '        </button>' +
            '      </div>' +
            '      <div class="pv-toolbar-right">' +
            '        <button type="button" class="pv-btn" title="Print" data-action="print">' +
            '          <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M6 9V2h12v7"/><path d="M6 18H4a2 2 0 01-2-2v-5a2 2 0 012-2h16a2 2 0 012 2v5a2 2 0 01-2 2h-2"/><path d="M6 14h12v8H6z"/></svg>' +
            '        </button>' +
            '        <button type="button" class="pv-btn" title="Download PDF" data-action="download">' +
            '          <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15v4a2 2 0 01-2 2H5a2 2 0 01-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>' +
            '        </button>' +
            '        <button type="button" class="pv-btn" title="Toggle Fullscreen" data-action="fullscreen">' +
            '          <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M8 3H5a2 2 0 00-2 2v3m18 0V5a2 2 0 00-2-2h-3m0 18h3a2 2 0 002-2v-3M3 16v3a2 2 0 002 2h3"/></svg>' +
            '        </button>' +
            '      </div>' +
            '    </div>' +

            '    <div class="pv-body">' +

            '      <div class="pv-sidebar" id="pv-sidebar">' +
            '        <div class="pv-sidebar-tabs">' +
            '          <button type="button" class="pv-sidebar-tab is-active" data-tab="thumbnails">Thumbnails</button>' +
            '          <button type="button" class="pv-sidebar-tab" data-tab="info">Info</button>' +
            '        </div>' +
            '        <div class="pv-sidebar-content">' +
            '          <div class="pv-tab-panel is-active" id="pv-tab-thumbnails">' +
            '            <div class="pv-thumbnails" id="pv-thumbnails"></div>' +
            '          </div>' +
            '          <div class="pv-tab-panel" id="pv-tab-info">' +
            '            <div class="pv-meta" id="pv-meta"></div>' +
            '          </div>' +
            '        </div>' +
            '      </div>' +

            '      <div class="pv-canvas-area" id="pv-canvas-area">' +
            '        <div class="pv-loading" id="pv-loading">' +
            '          <div class="pv-spinner"></div>' +
            '          <div class="pv-loading-text">Loading document...</div>' +
            '        </div>' +
            '        <div class="pv-error" id="pv-error" style="display:none">' +
            '          <div class="pv-error-icon">' +
            '            <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="#ef4444" stroke-width="1.5"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>' +
            '          </div>' +
            '          <div class="pv-error-title">Unable to load PDF</div>' +
            '          <div class="pv-error-desc" id="pv-error-desc">The document may be corrupted or inaccessible.</div>' +
            '          <button type="button" class="pv-btn pv-btn-retry" data-action="retry">Retry</button>' +
            '        </div>' +
            '        <div class="pv-pages" id="pv-pages"></div>' +
            '      </div>' +

            '      <div class="pv-search-panel" id="pv-search-panel" style="display:none">' +
            '        <div class="pv-search-input-wrap">' +
            '          <input type="text" class="pv-search-input" id="pv-search-input" placeholder="Search document..." autocomplete="off">' +
            '          <span class="pv-search-count" id="pv-search-count">0 results</span>' +
            '          <button type="button" class="pv-btn pv-btn-search-nav" title="Previous Match" data-action="search-prev">' +
            '            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="15 18 9 12 15 6"/></svg>' +
            '          </button>' +
            '          <button type="button" class="pv-btn pv-btn-search-nav" title="Next Match" data-action="search-next">' +
            '            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="9 18 15 12 9 6"/></svg>' +
            '          </button>' +
            '          <button type="button" class="pv-btn pv-btn-search-close" title="Close Search" data-action="search-close">' +
            '            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>' +
            '          </button>' +
            '        </div>' +
            '      </div>' +
            '    </div>' +

            '    <div class="pv-footer">' +
            '      <div class="pv-footer-left">' +
            '        <button type="button" class="pv-btn pv-btn-nav" title="Previous Page" data-action="prev-page">' +
            '          <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="15 18 9 12 15 6"/></svg>' +
            '          <span>Prev</span>' +
            '        </button>' +
            '      </div>' +
            '      <div class="pv-footer-center">' +
            '        <span class="pv-page-label">Page</span>' +
            '        <input type="number" class="pv-page-input" id="pv-page-input" value="1" min="1" max="9999">' +
            '        <span class="pv-page-label">of <span id="pv-total-pages">0</span></span>' +
            '      </div>' +
            '      <div class="pv-footer-right">' +
            '        <button type="button" class="pv-btn pv-btn-nav" title="Next Page" data-action="next-page">' +
            '          <span>Next</span>' +
            '          <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="9 18 15 12 9 6"/></svg>' +
            '        </button>' +
            '      </div>' +
            '    </div>' +

            '  </div>' +
            '</div>';

        var div = document.createElement('div');
        div.innerHTML = html;
        document.body.appendChild(div);

        this.modal = div.querySelector('#pdf-viewer-overlay');

        this._els = {
            backdrop: this.modal.querySelector('.pv-backdrop'),
            close: this.modal.querySelector('[data-action="close"]'),
            sidebarBtn: this.modal.querySelector('[data-action="sidebar"]'),
            zoomIn: this.modal.querySelector('[data-action="zoom-in"]'),
            zoomOut: this.modal.querySelector('[data-action="zoom-out"]'),
            zoomSelect: this.modal.querySelector('#pv-zoom-select'),
            rotateLeft: this.modal.querySelector('[data-action="rotate-left"]'),
            rotateRight: this.modal.querySelector('[data-action="rotate-right"]'),
            searchBtn: this.modal.querySelector('[data-action="search"]'),
            printBtn: this.modal.querySelector('[data-action="print"]'),
            downloadBtn: this.modal.querySelector('[data-action="download"]'),
            fullscreenBtn: this.modal.querySelector('[data-action="fullscreen"]'),
            prevPage: this.modal.querySelector('[data-action="prev-page"]'),
            nextPage: this.modal.querySelector('[data-action="next-page"]'),
            pageInput: this.modal.querySelector('#pv-page-input'),
            totalPagesEl: this.modal.querySelector('#pv-total-pages'),
            filenameEl: this.modal.querySelector('#pv-filename'),
            fileMetaEl: this.modal.querySelector('#pv-file-meta'),
            sidebar: this.modal.querySelector('#pv-sidebar'),
            sidebarTabs: this.modal.querySelectorAll('.pv-sidebar-tab'),
            thumbnails: this.modal.querySelector('#pv-thumbnails'),
            metaInfo: this.modal.querySelector('#pv-meta'),
            canvasArea: this.modal.querySelector('#pv-canvas-area'),
            pages: this.modal.querySelector('#pv-pages'),
            loading: this.modal.querySelector('#pv-loading'),
            error: this.modal.querySelector('#pv-error'),
            errorDesc: this.modal.querySelector('#pv-error-desc'),
            retryBtn: this.modal.querySelector('[data-action="retry"]'),
            searchPanel: this.modal.querySelector('#pv-search-panel'),
            searchInput: this.modal.querySelector('#pv-search-input'),
            searchCount: this.modal.querySelector('#pv-search-count'),
            searchPrev: this.modal.querySelector('[data-action="search-prev"]'),
            searchNext: this.modal.querySelector('[data-action="search-next"]'),
            searchClose: this.modal.querySelector('[data-action="search-close"]'),
        };

        var self = this;
        this._els.backdrop.addEventListener('click', function () { self.close(); });
        this._els.close.addEventListener('click', function () { self.close(); });

        this._els.sidebarBtn.addEventListener('click', function () { self.toggleSidebar(); });
        this._els.zoomIn.addEventListener('click', function () { self.zoomIn(); });
        this._els.zoomOut.addEventListener('click', function () { self.zoomOut(); });
        this._els.zoomSelect.addEventListener('change', function () { self._handleZoomChange(this.value); });
        this._els.rotateLeft.addEventListener('click', function () { self.rotate(-90); });
        this._els.rotateRight.addEventListener('click', function () { self.rotate(90); });
        var scrollToggle = self.modal.querySelector('[data-action="toggle-scroll"]');
        scrollToggle.addEventListener('click', function () { self.toggleScrollMode(); });
        scrollToggle.querySelector('.pv-scroll-icon').textContent = '\u2261';
        scrollToggle.title = 'Switch to Single Page';
        scrollToggle.classList.add('is-active');
        this._els.searchBtn.addEventListener('click', function () { self.toggleSearch(); });
        this._els.printBtn.addEventListener('click', function () { self.print(); });
        this._els.downloadBtn.addEventListener('click', function () { self.download(); });
        this._els.fullscreenBtn.addEventListener('click', function () { self.toggleFullscreen(); });
        this._els.prevPage.addEventListener('click', function () { self.prevPage(); });
        this._els.nextPage.addEventListener('click', function () { self.nextPage(); });

        this._els.pageInput.addEventListener('change', function () {
            var p = parseInt(this.value, 10);
            if (p >= 1 && p <= self.totalPages) self.goToPage(p);
        });

        this._els.sidebarTabs.forEach(function (tab) {
            tab.addEventListener('click', function () {
                self._switchSidebarTab(this.dataset.tab);
            });
        });

        this._els.searchInput.addEventListener('input', function () {
            self._performSearch(this.value);
        });
        this._els.searchInput.addEventListener('keydown', function (e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                if (e.shiftKey) self._els.searchPrev.click();
                else self._els.searchNext.click();
            }
            if (e.key === 'Escape') self.closeSearch();
        });
        this._els.searchPrev.addEventListener('click', function () { self.prevMatch(); });
        this._els.searchNext.addEventListener('click', function () { self.nextMatch(); });
        this._els.searchClose.addEventListener('click', function () { self.closeSearch(); });
        this._els.retryBtn.addEventListener('click', function () { self._loadWithRetry(); });

        document.addEventListener('keydown', function (e) {
            if (!self.isOpen()) return;
            if (e.key === 'Escape') { self.close(); return; }
            if (e.key === 'ArrowLeft' || e.key === 'PageUp') { e.preventDefault(); self.prevPage(); return; }
            if (e.key === 'ArrowRight' || e.key === 'PageDown') { e.preventDefault(); self.nextPage(); return; }
            if ((e.ctrlKey || e.metaKey) && e.key === 'f') { e.preventDefault(); self.openSearch(); return; }
        });

        this.modal.querySelector('#pv-canvas-area').addEventListener('wheel', function (e) {
            if (e.ctrlKey || e.metaKey) {
                e.preventDefault();
                if (e.deltaY < 0) self.zoomIn();
                else self.zoomOut();
            }
        }, { passive: false });
    };

    PdfViewer.prototype.isOpen = function () {
        return this.modal && this.modal.classList.contains('pv-is-open');
    };

    PdfViewer.prototype.open = function (ebook) {
        var self = this;
        this.ebook = ebook;
        this.currentPage = 1;
        this.rotation = 0;
        this.zoom = 1;
        this.sidebarOpen = true;
        this.searchResults = [];
        this.currentMatchIdx = -1;
        this.pageTextContent = {};
        this.textLayers = {};
        this.renderedPages = {};
        this.isLoading = false;

        this._els.pages.innerHTML = '';
        this._els.thumbnails.innerHTML = '';
        this._els.metaInfo.innerHTML = '';
        this._els.loading.style.display = 'flex';
        this._els.error.style.display = 'none';
        this._els.searchPanel.style.display = 'none';
        this._els.searchInput.value = '';

        this._els.filenameEl.textContent = ebook.file_name || ebook.title || 'Document';
        var metaParts = [];
        if (ebook.file_size) metaParts.push(formatBytes(ebook.file_size));
        if (ebook.page_count) metaParts.push(ebook.page_count + ' pages');
        if (ebook.created_at) metaParts.push('Uploaded ' + formatDate(ebook.created_at));
        if (ebook.uploaded_by) metaParts.push('by ' + ebook.uploaded_by);
        this._els.fileMetaEl.textContent = metaParts.join(' \u00b7 ');

        this._renderMeta(ebook);

        this._els.sidebar.classList.remove('pv-sidebar-closed');
        this._els.sidebarBtn.classList.remove('is-active');
        this._switchSidebarTab('thumbnails');

        this.modal.classList.add('pv-is-open');
        document.body.style.overflow = 'hidden';

        loadPdfJs(function () {
            self._loadDocument(ebook.preview_url);
        });
    };

    PdfViewer.prototype.close = function () {
        this.modal.classList.remove('pv-is-open');
        document.body.style.overflow = '';
        if (this.pdfDoc) {
            try { this.pdfDoc.destroy(); } catch (e) {}
            this.pdfDoc = null;
        }
        this._els.pages.innerHTML = '';
        this._els.thumbnails.innerHTML = '';
        this.renderedPages = {};
        this.pageTextContent = {};
        if (document.fullscreenElement) {
            document.exitFullscreen().catch(function () {});
        }
    };

    PdfViewer.prototype._loadDocument = function (url) {
        var self = this;
        this.isLoading = true;

        var loadingTask = pdfjsLib.getDocument({
            url: url,
            withCredentials: true,
            stopAtErrors: false,
        });

        loadingTask.promise.then(function (doc) {
            self.pdfDoc = doc;
            self.totalPages = doc.numPages;
            self._els.totalPagesEl.textContent = doc.numPages;
            self._els.pageInput.max = doc.numPages;
            self._els.pageInput.value = 1;
            self._els.loading.style.display = 'none';
            self._els.error.style.display = 'none';
            self.isLoading = false;
            self._renderCurrentView();
            self._generateThumbnails();
        }).catch(function (err) {
            self.isLoading = false;
            self._els.loading.style.display = 'none';
            self._els.error.style.display = 'flex';
            self._els.errorDesc.textContent = err.message || 'The document may be corrupted or inaccessible.';
        });
    };

    PdfViewer.prototype._loadWithRetry = function () {
        this._els.error.style.display = 'none';
        this._els.loading.style.display = 'flex';
        if (this.ebook && this.ebook.preview_url) {
            this._loadDocument(this.ebook.preview_url);
        }
    };

    PdfViewer.prototype._renderCurrentView = function () {
        var self = this;

        if (this.isContinuous) {
            this._renderAllPages();
        } else {
            this._renderPage(this.currentPage);
        }

        this._updatePageInfo();
    };

    PdfViewer.prototype._renderPage = function (pageNum) {
        if (!this.pdfDoc || pageNum < 1 || pageNum > this.totalPages) return;
        if (this.renderedPages[pageNum]) return;

        var self = this;

        this.pdfDoc.getPage(pageNum).then(function (page) {
            var viewport = page.getViewport({ scale: self.zoom, rotation: self.rotation });

            var wrapper = document.createElement('div');
            wrapper.className = 'pv-page-wrapper';
            wrapper.dataset.page = pageNum;

            var canvas = document.createElement('canvas');
            canvas.className = 'pv-page-canvas';
            canvas.width = viewport.width * (window.devicePixelRatio || 1);
            canvas.height = viewport.height * (window.devicePixelRatio || 1);
            canvas.style.width = viewport.width + 'px';
            canvas.style.height = viewport.height + 'px';

            var textLayer = document.createElement('div');
            textLayer.className = 'pv-text-layer';

            wrapper.appendChild(canvas);
            wrapper.appendChild(textLayer);

            if (self.isContinuous) {
                self._els.pages.appendChild(wrapper);
            } else {
                self._els.pages.innerHTML = '';
                self._els.pages.appendChild(wrapper);
            }

            var ctx = canvas.getContext('2d');
            ctx.scale(window.devicePixelRatio || 1, window.devicePixelRatio || 1);

            page.render({ canvasContext: ctx, viewport: viewport }).promise.then(function () {
                self.renderedPages[pageNum] = true;
                try { self._renderTextLayer(page, textLayer, viewport); } catch (e) { console.warn('[PDF Viewer] Text layer error:', e); }
                try { self._applyHighlights(pageNum); } catch (e) {}
            }).catch(function (err) {
                console.error('[PDF Viewer] Page render error:', err);
            });

            self.currentPage = pageNum;
            self._els.pageInput.value = pageNum;
            self._updatePageNav();
        }).catch(function (err) {
            console.error('[PDF Viewer] Failed to get page', pageNum, ':', err);
            self._els.loading.style.display = 'none';
            self._els.error.style.display = 'flex';
            self._els.errorDesc.textContent = 'Failed to render page ' + pageNum + '. ' + (err.message || '');
        });
    };

    PdfViewer.prototype._renderAllPages = function () {
        var self = this;
        this._els.pages.innerHTML = '';

        var fragment = document.createDocumentFragment();

        function renderBatch(start, end) {
            var promises = [];
            for (var i = start; i <= end && i <= self.totalPages; i++) {
                promises.push(self._renderPageInBatch(i, fragment));
            }
            return Promise.all(promises).then(function () {
                self._els.pages.appendChild(fragment);
                self._updatePageNav();
            });
        }

        renderBatch(1, Math.min(10, this.totalPages)).then(function () {
            if (self.totalPages > 10) {
                renderBatch(11, self.totalPages);
            }
        });
    };

    PdfViewer.prototype._renderPageInBatch = function (pageNum, fragment) {
        var self = this;
        return this.pdfDoc.getPage(pageNum).then(function (page) {
            var viewport = page.getViewport({ scale: self.zoom, rotation: self.rotation });

            var wrapper = document.createElement('div');
            wrapper.className = 'pv-page-wrapper';
            wrapper.dataset.page = pageNum;

            var canvas = document.createElement('canvas');
            canvas.className = 'pv-page-canvas';
            canvas.width = viewport.width * (window.devicePixelRatio || 1);
            canvas.height = viewport.height * (window.devicePixelRatio || 1);
            canvas.style.width = viewport.width + 'px';
            canvas.style.height = viewport.height + 'px';

            var textLayer = document.createElement('div');
            textLayer.className = 'pv-text-layer';

            wrapper.appendChild(canvas);
            wrapper.appendChild(textLayer);
            fragment.appendChild(wrapper);

            var ctx = canvas.getContext('2d');
            ctx.scale(window.devicePixelRatio || 1, window.devicePixelRatio || 1);

            return page.render({ canvasContext: ctx, viewport: viewport }).promise.then(function () {
                self.renderedPages[pageNum] = true;
                self._renderTextLayer(page, textLayer, viewport);
                self._applyHighlights(pageNum);
            });
        });
    };

    PdfViewer.prototype._renderTextLayer = function (page, textLayerEl, viewport) {
        var self = this;
        page.getTextContent().then(function (textContent) {
            try {
                self.pageTextContent[page.pageNumber] = textContent;
                textLayerEl.innerHTML = '';
                textContent.items.forEach(function (item) {
                    var tx = safeTransform(viewport.transform, item.transform);
                    var span = document.createElement('span');
                    span.className = 'pv-text-item';
                    span.textContent = item.str;
                    var fontSize = Math.sqrt((tx[0] * tx[0]) + (tx[1] * tx[1]));
                    var angle = Math.atan2(tx[1], tx[0]);
                    span.style.left = Math.round(tx[4]) + 'px';
                    span.style.top = Math.round(tx[5] - fontSize) + 'px';
                    span.style.fontSize = Math.round(fontSize) + 'px';
                    if (Math.abs(angle) > 0.001) {
                        span.style.transform = 'rotate(' + angle + 'rad)';
                        span.style.transformOrigin = '0 100%';
                    }
                    span.style.fontFamily = item.fontName || 'sans-serif';
                    textLayerEl.appendChild(span);
                });
            } catch (e) {
                console.warn('[PDF Viewer] Text layer error:', e);
            }
        }).catch(function (err) {
            console.warn('[PDF Viewer] Failed to get text content:', err);
        });
    };

    PdfViewer.prototype._applyHighlights = function (pageNum) {
        var self = this;
        if (!this.searchResults.length) return;

        var pageResults = this.searchResults.filter(function (r) { return r.page === pageNum; });
        if (!pageResults.length) return;

        var textLayer = this._els.pages.querySelector('.pv-page-wrapper[data-page="' + pageNum + '"] .pv-text-layer');
        if (!textLayer) return;

        var spans = textLayer.querySelectorAll('.pv-text-item');
        pageResults.forEach(function (result) {
            spans.forEach(function (span) {
                if (span.textContent.toLowerCase().includes(result.query.toLowerCase())) {
                    span.classList.add('pv-highlight');
                }
                if (result.isCurrent) {
                    span.classList.add('pv-highlight-current');
                }
            });
        });
    };

    PdfViewer.prototype._generateThumbnails = function () {
        var self = this;
        if (!this.pdfDoc) return;

        this._els.thumbnails.innerHTML = '';

        for (var i = 1; i <= this.totalPages; i++) {
            this._renderThumbnail(i);
        }
    };

    PdfViewer.prototype._renderThumbnail = function (pageNum) {
        var self = this;
        this.pdfDoc.getPage(pageNum).then(function (page) {
            var scale = 0.2;
            var viewport = page.getViewport({ scale: scale });

            var wrapper = document.createElement('div');
            wrapper.className = 'pv-thumb-wrapper';
            wrapper.dataset.page = pageNum;
            wrapper.title = 'Page ' + pageNum;

            var canvas = document.createElement('canvas');
            canvas.className = 'pv-thumb-canvas';
            canvas.width = viewport.width;
            canvas.height = viewport.height;

            var label = document.createElement('div');
            label.className = 'pv-thumb-label';
            label.textContent = pageNum;

            wrapper.appendChild(canvas);
            wrapper.appendChild(label);

            wrapper.addEventListener('click', function () {
                self.goToPage(pageNum);
            });

            self._els.thumbnails.appendChild(wrapper);

            var ctx = canvas.getContext('2d');
            page.render({ canvasContext: ctx, viewport: viewport }).promise.catch(function () {});
        });
    };

    PdfViewer.prototype._renderMeta = function (ebook) {
        var self = this;
        var fields = [
            { label: 'Title', value: ebook.title },
            { label: 'Category', value: ebook.category },
            { label: 'Description', value: ebook.description },
            { label: 'Language', value: ebook.language },
            { label: 'File Size', value: ebook.file_size ? formatBytes(ebook.file_size) : null },
            { label: 'Total Pages', value: ebook.page_count ? String(ebook.page_count) : null },
            { label: 'Downloads', value: ebook.download_count != null ? String(ebook.download_count) : null },
            { label: 'Uploaded By', value: ebook.uploaded_by },
            { label: 'Created', value: ebook.created_at ? formatDate(ebook.created_at) : null },
            { label: 'Last Updated', value: ebook.updated_at ? formatDate(ebook.updated_at) : null },
            { label: 'Author', value: ebook.author },
        ];

        var html = '';
        fields.forEach(function (f) {
            if (!f.value) return;
            html += '<div class="pv-meta-row">' +
                '<div class="pv-meta-label">' + escapeHtml(f.label) + '</div>' +
                '<div class="pv-meta-value">' + escapeHtml(f.value) + '</div>' +
                '</div>';
        });

        this._els.metaInfo.innerHTML = html;
    };

    PdfViewer.prototype.goToPage = function (pageNum) {
        if (pageNum < 1 || pageNum > this.totalPages || !this.pdfDoc) return;

        this.currentPage = pageNum;
        this._els.pageInput.value = pageNum;

        if (this.isContinuous) {
            var wrapper = this._els.pages.querySelector('.pv-page-wrapper[data-page="' + pageNum + '"]');
            if (wrapper) {
                wrapper.scrollIntoView({ behavior: 'smooth', block: 'start' });
            }
        } else {
            this.renderedPages = {};
            this._els.pages.innerHTML = '';
            this._renderPage(pageNum);
        }

        this._updatePageNav();
        this._updateThumbnailActive();
    };

    PdfViewer.prototype.nextPage = function () {
        if (this.currentPage < this.totalPages) {
            this.goToPage(this.currentPage + 1);
        }
    };

    PdfViewer.prototype.prevPage = function () {
        if (this.currentPage > 1) {
            this.goToPage(this.currentPage - 1);
        }
    };

    PdfViewer.prototype.zoomIn = function () {
        var levels = [0.5, 0.75, 1, 1.25, 1.5, 2, 3];
        var self = this;
        var next = levels.find(function (l) { return l > self.zoom; });
        if (next) this.setZoom(next);
    };

    PdfViewer.prototype.zoomOut = function () {
        var levels = [0.5, 0.75, 1, 1.25, 1.5, 2, 3];
        var self = this;
        var prev = levels.slice().reverse().find(function (l) { return l < self.zoom; });
        if (prev) this.setZoom(prev);
    };

    PdfViewer.prototype.setZoom = function (level) {
        this.zoom = level;
        this._els.zoomSelect.value = String(level);
        this.renderedPages = {};
        this._els.pages.innerHTML = '';
        if (this.pdfDoc) {
            this._renderCurrentView();
        }
    };

    PdfViewer.prototype._handleZoomChange = function (value) {
        if (value === 'fit-width') {
            this.fitWidth();
        } else if (value === 'fit-page') {
            this.fitPage();
        } else {
            this.setZoom(parseFloat(value));
        }
    };

    PdfViewer.prototype.fitWidth = function () {
        if (!this.pdfDoc) return;
        var self = this;
        var containerWidth = this._els.canvasArea.clientWidth - 40;
        this.pdfDoc.getPage(this.currentPage).then(function (page) {
            var vp = page.getViewport({ scale: 1 });
            var scale = containerWidth / vp.width;
            self.setZoom(scale);
            self._els.zoomSelect.value = 'fit-width';
        });
    };

    PdfViewer.prototype.fitPage = function () {
        if (!this.pdfDoc) return;
        var self = this;
        var containerWidth = this._els.canvasArea.clientWidth - 40;
        var containerHeight = this._els.canvasArea.clientHeight - 40;
        this.pdfDoc.getPage(this.currentPage).then(function (page) {
            var vp = page.getViewport({ scale: 1 });
            var scale = Math.min(containerWidth / vp.width, containerHeight / vp.height);
            self.setZoom(scale);
            self._els.zoomSelect.value = 'fit-page';
        });
    };

    PdfViewer.prototype.rotate = function (degrees) {
        this.rotation = (this.rotation + degrees) % 360;
        this.renderedPages = {};
        this._els.pages.innerHTML = '';
        if (this.pdfDoc) {
            this._renderCurrentView();
        }
    };

    PdfViewer.prototype.toggleSidebar = function () {
        this.sidebarOpen = !this.sidebarOpen;
        this._els.sidebar.classList.toggle('pv-sidebar-closed');
        this._els.sidebarBtn.classList.toggle('is-active');
    };

    PdfViewer.prototype._switchSidebarTab = function (tab) {
        this._els.sidebarTabs.forEach(function (t) {
            t.classList.toggle('is-active', t.dataset.tab === tab);
        });
        document.querySelectorAll('#pv-sidebar .pv-tab-panel').forEach(function (p) {
            p.classList.toggle('is-active', p.id === 'pv-tab-' + tab);
        });
    };

    PdfViewer.prototype.toggleSearch = function () {
        if (this._els.searchPanel.style.display !== 'none') {
            this.closeSearch();
        } else {
            this.openSearch();
        }
    };

    PdfViewer.prototype.openSearch = function () {
        this._els.searchPanel.style.display = 'block';
        this._els.searchInput.focus();
        this._els.searchInput.select();
    };

    PdfViewer.prototype.closeSearch = function () {
        this._els.searchPanel.style.display = 'none';
        this._els.searchInput.value = '';
        this.searchResults = [];
        this.currentMatchIdx = -1;
        this._els.searchCount.textContent = '0 results';
        this._clearHighlights();
    };

    PdfViewer.prototype._performSearch = function (query) {
        var self = this;
        this._clearHighlights();

        if (!query || query.length < 1) {
            this.searchResults = [];
            this.currentMatchIdx = -1;
            this._els.searchCount.textContent = '0 results';
            return;
        }

        this.searchResults = [];
        this.currentMatchIdx = -1;
        var lowerQuery = query.toLowerCase();

        var pagePromises = [];
        for (var i = 1; i <= this.totalPages; i++) {
            pagePromises.push(
                this.pdfDoc.getPage(i).then(function (page) {
                    return page.getTextContent().then(function (textContent) {
                        self.pageTextContent[page.pageNumber] = textContent;
                        var pageText = textContent.items.map(function (item) { return item.str; }).join(' ');
                        var idx = pageText.toLowerCase().indexOf(lowerQuery);
                        while (idx !== -1) {
                            self.searchResults.push({
                                page: page.pageNumber,
                                index: idx,
                                query: query,
                                isCurrent: false,
                            });
                            idx = pageText.toLowerCase().indexOf(lowerQuery, idx + 1);
                        }
                    });
                })
            );
        }

        Promise.all(pagePromises).then(function () {
            self._els.searchCount.textContent = self.searchResults.length + ' results';
            if (self.searchResults.length > 0) {
                self.currentMatchIdx = 0;
                self.searchResults[0].isCurrent = true;
                self.goToPage(self.searchResults[0].page);
                setTimeout(function () {
                    self._applyHighlights(self.searchResults[0].page);
                }, 300);
            }
        });
    };

    PdfViewer.prototype.nextMatch = function () {
        if (!this.searchResults.length) return;
        var prev = this.searchResults[this.currentMatchIdx];
        if (prev) prev.isCurrent = false;

        this.currentMatchIdx = (this.currentMatchIdx + 1) % this.searchResults.length;
        var match = this.searchResults[this.currentMatchIdx];
        match.isCurrent = true;

        this._clearHighlights();
        this.goToPage(match.page);
        var self = this;
        setTimeout(function () { self._applyHighlights(match.page); }, 300);

        this._els.searchCount.textContent =
            (this.currentMatchIdx + 1) + ' of ' + this.searchResults.length + ' results';
    };

    PdfViewer.prototype.prevMatch = function () {
        if (!this.searchResults.length) return;
        var prev = this.searchResults[this.currentMatchIdx];
        if (prev) prev.isCurrent = false;

        this.currentMatchIdx = (this.currentMatchIdx - 1 + this.searchResults.length) % this.searchResults.length;
        var match = this.searchResults[this.currentMatchIdx];
        match.isCurrent = true;

        this._clearHighlights();
        this.goToPage(match.page);
        var self = this;
        setTimeout(function () { self._applyHighlights(match.page); }, 300);

        this._els.searchCount.textContent =
            (this.currentMatchIdx + 1) + ' of ' + this.searchResults.length + ' results';
    };

    PdfViewer.prototype._clearHighlights = function () {
        this._els.pages.querySelectorAll('.pv-text-item.pv-highlight, .pv-text-item.pv-highlight-current')
            .forEach(function (el) {
                el.classList.remove('pv-highlight', 'pv-highlight-current');
            });
    };

    PdfViewer.prototype._updatePageInfo = function () {
        this._els.pageInput.value = this.currentPage;
    };

    PdfViewer.prototype._updatePageNav = function () {
        this._els.prevPage.disabled = this.currentPage <= 1;
        this._els.nextPage.disabled = this.currentPage >= this.totalPages;
        this._els.prevPage.classList.toggle('is-disabled', this.currentPage <= 1);
        this._els.nextPage.classList.toggle('is-disabled', this.currentPage >= this.totalPages);
    };

    PdfViewer.prototype._updateThumbnailActive = function () {
        this._els.thumbnails.querySelectorAll('.pv-thumb-wrapper').forEach(function (w) {
            w.classList.toggle('is-active', parseInt(w.dataset.page, 10) === self.currentPage);
        });
    };

    PdfViewer.prototype.download = function () {
        if (this.ebook && this.ebook.preview_url) {
            var a = document.createElement('a');
            a.href = this.ebook.preview_url;
            a.download = this.ebook.file_name || 'document.pdf';
            document.body.appendChild(a);
            a.click();
            document.body.removeChild(a);
        }
    };

    PdfViewer.prototype.print = function () {
        if (this.ebook && this.ebook.preview_url) {
            var iframe = document.createElement('iframe');
            iframe.style.cssText = 'position:fixed;top:-9999px;left:-9999px;width:1px;height:1px;';
            iframe.src = this.ebook.preview_url + '#toolbar=0';
            document.body.appendChild(iframe);
            iframe.onload = function () {
                setTimeout(function () {
                    try {
                        if (iframe.contentWindow) iframe.contentWindow.print();
                    } catch (e) {
                        window.open(self.ebook.preview_url, '_blank');
                    }
                }, 500);
            };
            setTimeout(function () {
                document.body.removeChild(iframe);
            }, 60000);
        }
    };

    PdfViewer.prototype.toggleFullscreen = function () {
        var container = this.modal.querySelector('.pv-container');
        if (!document.fullscreenElement) {
            if (container.requestFullscreen) {
                container.requestFullscreen();
            } else if (container.webkitRequestFullscreen) {
                container.webkitRequestFullscreen();
            } else if (container.msRequestFullscreen) {
                container.msRequestFullscreen();
            }
        } else {
            if (document.exitFullscreen) {
                document.exitFullscreen();
            } else if (document.webkitExitFullscreen) {
                document.webkitExitFullscreen();
            } else if (document.msExitFullscreen) {
                document.msExitFullscreen();
            }
        }
    };

    PdfViewer.prototype.toggleScrollMode = function () {
        this.isContinuous = !this.isContinuous;
        var btn = this.modal.querySelector('[data-action="toggle-scroll"]');
        if (btn) {
            btn.querySelector('.pv-scroll-icon').textContent = this.isContinuous ? '\u2261' : '1';
            btn.title = this.isContinuous ? 'Switch to Single Page' : 'Switch to Continuous Scroll';
            btn.classList.toggle('is-active', this.isContinuous);
        }
        this.renderedPages = {};
        this._els.pages.innerHTML = '';
        this._renderCurrentView();
        if (this.isContinuous && this.currentPage > 1) {
            var wrapper = this._els.pages.querySelector('.pv-page-wrapper[data-page="' + this.currentPage + '"]');
            if (wrapper) wrapper.scrollIntoView({ block: 'start' });
        }
    };

    PdfViewer.prototype.getPageText = function (pageNum) {
        var self = this;
        return new Promise(function (resolve) {
            if (self.pageTextContent[pageNum]) {
                resolve(self.pageTextContent[pageNum]);
                return;
            }
            self.pdfDoc.getPage(pageNum).then(function (page) {
                page.getTextContent().then(function (tc) {
                    self.pageTextContent[pageNum] = tc;
                    resolve(tc);
                });
            });
        });
    };

    var viewerInstance = null;

    window.openPdfViewer = function (button) {
        try {
            var data = JSON.parse(button.getAttribute('data-ebook'));
            if (!viewerInstance) {
                viewerInstance = new PdfViewer();
            }
            viewerInstance.open(data);
        } catch (e) {
            console.error('PDF Viewer error:', e);
            if (typeof adminModal !== 'undefined' && adminModal) {
                adminModal.alert({ title: 'Error', message: 'Failed to open PDF viewer. Please try again.' });
            }
        }
    };
})();
