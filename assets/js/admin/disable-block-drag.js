// Disable block drag & drop inside Gutenberg canvas while allowing media drop areas.
(function () {
    'use strict';

    function isMediaTarget(el) {
        if (!el) return false;
        return !!el.closest('.components-drop-zone, .block-editor-media-placeholder, .components-drop-zone__content, .block-editor-media-placeholder__button, .components-form-file-upload, input[type="file"]');
    }

    // Only block drag for specific custom blocks
    // Blocks may not have predictable classes; prefer matching by data-type attribute (block name)
    var blockedBlockClassnames = [
        'wp-block-okhub-explore-tour',
        'wp-block-okhub-fullscreen-image',
        'wp-block-okhub-highlight-section'
    ];
    var blockedBlockNames = [
        'okhub/explore-tour',
        'okhub/fullscreen-image',
        'okhub/highlight-section'
    ];

    function isCustomBlock(el) {
        if (!el) return false;
        var blockEl = el.closest('.wp-block, .block-editor-block-list__block');
        if (!blockEl) return false;
        // check data-type attribute (preferred)
        try {
            var dataType = blockEl.getAttribute && blockEl.getAttribute('data-type');
            if (dataType && blockedBlockNames.indexOf(dataType) !== -1) {
                return true;
            }
        } catch (e) { }
        for (var i = 0; i < blockedBlockClassnames.length; i++) {
            if (blockEl.classList && blockEl.classList.contains(blockedBlockClassnames[i])) {
                return true;
            }
        }
        return false;
    }

    function stopIfBlockDrag(e) {
        try {
            var t = e.target;
            // if target is inside a media drop area, allow
            if (isMediaTarget(t)) return;
            // only prevent if it's a drag involving our custom blocks
            if (isCustomBlock(t)) {
                e.preventDefault();
                e.stopPropagation();
                return false;
            }
        } catch (err) {
            // noop
        }
    }

    // Use capture so it runs before Gutenberg's handlers.
    document.addEventListener('dragstart', stopIfBlockDrag, true);
    document.addEventListener('dragover', stopIfBlockDrag, true);
    document.addEventListener('drop', stopIfBlockDrag, true);
    // Also proactively remove draggable attribute and pointer handlers from matching blocks
    function disableDraggableOnNode(node) {
        if (!node || !node.classList) return;
        for (var i = 0; i < blockedBlockClassnames.length; i++) {
            if (node.classList.contains(blockedBlockClassnames[i])) {
                try {
                    node.setAttribute('draggable', 'false');
                    node.style.touchAction = 'none';
                    node.addEventListener('dragstart', function (e) { e.preventDefault(); e.stopPropagation(); }, true);
                } catch (err) { }
                break;
            }
        }
    }

    function scanAndDisable(root) {
        var rootNode = root || document;
        blockedBlockClassnames.forEach(function (cls) {
            var els = rootNode.querySelectorAll('.' + cls);
            for (var i = 0; i < els.length; i++) {
                disableDraggableOnNode(els[i]);
            }
        });
    }

    // initial scan
    scanAndDisable(document);

    // observe future added blocks
    var mo = new MutationObserver(function (mutations) {
        mutations.forEach(function (m) {
            if (m.addedNodes && m.addedNodes.length) {
                m.addedNodes.forEach(function (n) {
                    if (n.nodeType === 1) {
                        scanAndDisable(n);
                    }
                });
            }
        });
    });
    mo.observe(document.body, { childList: true, subtree: true });
})();

