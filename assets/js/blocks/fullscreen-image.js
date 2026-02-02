/**
 * Fullscreen Image block for Gutenberg
 */
(function (wp) {
    var registerBlockType = wp.blocks.registerBlockType;
    var el = wp.element.createElement;
    var MediaUpload = wp.blockEditor.MediaUpload;
    var MediaUploadCheck = wp.blockEditor.MediaUploadCheck;
    var MediaPlaceholder = wp.blockEditor.MediaPlaceholder;
    var MediaReplaceFlow = wp.blockEditor.MediaReplaceFlow;
    var Button = wp.components.Button;
    var TextControl = wp.components.TextControl;
    var RichText = wp.blockEditor.RichText;
    var InspectorControls = wp.blockEditor.InspectorControls;
    var PanelBody = wp.components.PanelBody;
    var useBlockProps = wp.blockEditor.useBlockProps;

    registerBlockType('okhub/fullscreen-image', {
        title: 'Fullscreen Image',
        icon: 'cover-image',
        category: 'layout',
        attributes: {
            image: { type: 'number', default: 0 },
            imageUrl: { type: 'string', default: '' },
            overlayText: { type: 'string', default: '' },
            linkUrl: { type: 'string', default: '' },
            className: { type: 'string', default: '' },
            objectFit: { type: 'string', default: 'cover' },
            alt: { type: 'string', default: '' },
            caption: { type: 'string', default: '' }
        },
        edit: function (props) {
            var attrs = props.attributes;
            var set = props.setAttributes;
            var blockProps = useBlockProps({ className: 'fullscreen-image-editor' });

            function onSelect(file) {
                var update = {};
                update['image'] = file.id || 0;
                update['imageUrl'] = file.url || '';
                // prefer attachment alt if available
                if (file.alt) update['alt'] = file.alt;
                set(update);
            }

            function onSelectURL(url) {
                set({ image: 0, imageUrl: url });
            }

            // When no image selected show the same placeholder as core Image block
            if (!attrs.imageUrl) {
                return el('div', blockProps,
                    el('div', {
                        onDragOver: function (e) { e.preventDefault(); e.stopPropagation(); },
                        onDrop: function (e) { e.preventDefault(); e.stopPropagation(); }
                    },
                        el(MediaPlaceholder, {
                            icon: 'cover-image',
                            labels: { title: 'Image', instructions: 'Drag and drop an image, upload, or choose from your library.' },
                            onSelect: onSelect,
                            onSelectURL: onSelectURL,
                            accept: 'image/*',
                            allowedTypes: ['image'],
                            multiple: false
                        })
                    )
                );
            }

            // When image exists show preview + controls (replace/remove) and Inspector
            return el('div', blockProps, [
                el(InspectorControls, {},
                    el(PanelBody, { title: 'Settings', initialOpen: true },
                        el(TextControl, { label: 'Alt text', value: attrs.alt, onChange: function (v) { set({ alt: v }); } }),
                        el(TextControl, { label: 'Overlay text', value: attrs.overlayText, onChange: function (v) { set({ overlayText: v }); } }),
                        el(TextControl, { label: 'Caption', value: attrs.caption, onChange: function (v) { set({ caption: v }); } }),
                        el(TextControl, { label: 'Link URL', value: attrs.linkUrl, onChange: function (v) { set({ linkUrl: v }); } })
                    )
                ),
                el('div', { className: 'fullscreen-image-preview' },
                    el('div', { style: { width: '100%', height: '100%', position: 'relative' } },
                        el('img', { src: attrs.imageUrl, style: { width: '100%', height: '100%', objectFit: attrs.objectFit }, alt: attrs.alt || '' }),
                        attrs.caption ? el('div', { style: { position: 'absolute', bottom: '12px', left: '12px', color: '#fff', background: 'rgba(0,0,0,0.4)', padding: '6px 10px', borderRadius: '4px' } }, attrs.caption) : null,
                        el('div', { className: 'fullscreen-image-action' },
                            el(MediaUpload, {
                                onSelect: onSelect,
                                allowedTypes: ['image'],
                                value: attrs.image,
                                render: function (obj) { return el(Button, { isPrimary: true, onClick: obj.open }, 'Replace'); }
                            }),
                            el(Button, {
                                style: {
                                    marginLeft: '8px',
                                    background: "#fff"
                                }, isSecondary: true, isDestructive: true, onClick: function () { set({ image: 0, imageUrl: '', alt: '', caption: '' }); }
                            }, 'Remove')
                        )
                    )
                )
            ]);
        },
        save: function () { return null; }
    });

})(window.wp);

