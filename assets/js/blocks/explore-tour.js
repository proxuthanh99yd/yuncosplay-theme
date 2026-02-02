/**
 * Explore Tour block editor script
 */
(function (wp) {
    var registerBlockType = wp.blocks.registerBlockType;
    var el = wp.element.createElement;
    var ServerSideRender = wp.serverSideRender;
    var MediaPlaceholder = wp.blockEditor.MediaPlaceholder;
    var MediaUpload = wp.blockEditor.MediaUpload;
    var MediaUploadCheck = wp.blockEditor.MediaUploadCheck;
    var TextControl = wp.components.TextControl;
    var TextareaControl = wp.components.TextareaControl;
    var PanelBody = wp.components.PanelBody;
    var InspectorControls = wp.blockEditor.InspectorControls;
    var useBlockProps = wp.blockEditor.useBlockProps;

    registerBlockType('okhub/explore-tour', {
        title: 'Explore Tour',
        icon: 'layout',
        category: 'layout',
        attributes: {
            image: { type: 'number', default: 0 },
            imageUrl: { type: 'string', default: '' },
            title: { type: 'string', default: 'Explore the tour in Cao Bang' },
            content: { type: 'string', default: 'Sign up for weekly travel inspiration straight to your inbox – all lovingly packed by our team of Travel Experts.' },
            buttonText: { type: 'string', default: 'Explore' },
            buttonUrl: { type: 'string', default: '' },
            background: { type: 'string', default: '#f3efee' },
            textColor: { type: 'string', default: '#6b0b3a' },
            alt: { type: 'string', default: '' },
            className: { type: 'string', default: '' }
        },
        edit: function (props) {
            var attrs = props.attributes;
            var set = props.setAttributes;
            var blockProps = useBlockProps({ className: 'explore-tour-editor' });

            if (!attrs.imageUrl) {
                return el('div', blockProps,
                    el('div', {
                        onDragOver: function (e) { e.preventDefault(); e.stopPropagation(); },
                        onDrop: function (e) { e.preventDefault(); e.stopPropagation(); }
                    },
                        el(MediaPlaceholder, {
                            icon: 'format-image',
                            labels: { title: 'Image', instructions: 'Drag and drop an image, upload, or choose from your library.' },
                            onSelect: function (file) { set({ image: file.id, imageUrl: file.url, alt: file.alt || '' }); },
                            onSelectURL: function (url) { set({ image: 0, imageUrl: url }); },
                            accept: 'image/*',
                            allowedTypes: ['image'],
                            multiple: false
                        })
                    )
                );
            }

            function renderImageControl(label, imageKey, imageUrlKey) {
                return el('div', { style: { marginBottom: '12px' } },
                    el('label', { style: { display: 'block', marginBottom: '6px' } }, label),
                    el(MediaUploadCheck, {},
                        el(MediaUpload, {
                            onSelect: function (file) { var u = {}; u[imageKey] = file.id; u[imageUrlKey] = file.url; set(u); },
                            allowedTypes: ['image'],
                            value: attrs[imageKey],
                            render: function (obj) {
                                return el('div', {},
                                    attrs[imageUrlKey] ?
                                        el('div', { style: { display: 'flex', gap: '8px', alignItems: 'center' } },
                                            el('img', { src: attrs[imageUrlKey], style: { width: '120px', height: '80px', objectFit: 'cover', borderRadius: '4px' } }),
                                            el('button', { className: 'components-button components-button--secondary', onClick: obj.open }, 'Replace'),
                                            el('button', { className: 'components-button components-button--link', onClick: function () { var u = {}; u[imageKey] = 0; u[imageUrlKey] = ''; set(u); } }, 'Remove')
                                        )
                                        :
                                        el('button', { className: 'components-button components-button--secondary', onClick: obj.open }, 'Select Image')
                                );
                            }
                        })
                    )
                );
            }

            return el('div', blockProps, [
                el(InspectorControls, {},
                    el(PanelBody, { title: 'Content', initialOpen: true },
                        el(TextControl, { label: 'Title', value: attrs.title, onChange: function (v) { set({ title: v }); } }),
                        el(TextareaControl, { label: 'Content', value: attrs.content, onChange: function (v) { set({ content: v }); } }),
                        el(TextControl, { label: 'Button text', value: attrs.buttonText, onChange: function (v) { set({ buttonText: v }); } }),
                        el(TextControl, { label: 'Button URL', value: attrs.buttonUrl, onChange: function (v) { set({ buttonUrl: v }); } })
                    ),
                    el(PanelBody, { title: 'Image', initialOpen: false },
                        renderImageControl('Hero image', 'image', 'imageUrl')
                    )
                ),
                // server-side preview to match front-end with quick replace/remove controls
                el('div', { style: { marginTop: '12px', position: 'relative' } },
                    attrs.imageUrl ? el('div', { className: 'explore-image-action fullscreen-image-action' },
                        el(MediaUpload, {
                            onSelect: function (file) { set({ image: file.id, imageUrl: file.url }); },
                            allowedTypes: ['image'],
                            value: attrs.image,
                            render: function (obj) { return el('button', { className: 'components-button is-primary', onClick: obj.open }, 'Replace'); }
                        }),
                        el('button', {
                            style: {
                                marginLeft: '8px',
                                background: "#fff"
                            }, className: 'components-button is-secondary is-destructive', onClick: function () { set({ image: 0, imageUrl: '', alt: '' }); }
                        }, 'Remove')
                    ) : null,
                    el(ServerSideRender, { block: 'okhub/explore-tour', attributes: attrs })
                )
            ]);
        },
        save: function () { return null; }
    });
})(window.wp);

