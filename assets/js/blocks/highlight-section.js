/**
 * Highlight Section Block for Gutenberg Editor (with ServerSideRender + improved media UI)
 */
(function (wp) {
    var registerBlockType = wp.blocks.registerBlockType;
    var el = wp.element.createElement;
    var __ = wp.i18n.__;
    var InspectorControls = wp.blockEditor.InspectorControls;
    var PanelBody = wp.components.PanelBody;
    var TextControl = wp.components.TextControl;
    var TextareaControl = wp.components.TextareaControl;
    var SelectControl = wp.components.SelectControl;
    var ColorPalette = wp.components.ColorPalette;
    var useBlockProps = wp.blockEditor.useBlockProps;
    var ServerSideRender = wp.serverSideRender;
    var MediaUpload = wp.blockEditor.MediaUpload;
    var MediaUploadCheck = wp.blockEditor.MediaUploadCheck;
    var Button = wp.components.Button;

    registerBlockType('okhub/highlight-section', {
        title: __('Highlight Section', 'okhub-theme'),
        icon: { src: 'format-image' },
        category: 'layout',
        keywords: [
            __('highlight', 'okhub-theme'),
            __('section', 'okhub-theme'),
            __('featured', 'okhub-theme'),
        ],
        supports: {
            align: true,
            customClassName: true
        },

        attributes: {
            title: { type: 'string', default: 'Tiêu đề nổi bật' },
            subtitle: { type: 'string', default: '' },
            content: { type: 'string', default: '' },
            background: { type: 'string', default: '#f8f9fa' },
            textColor: { type: 'string', default: '#333' },
            linkTitle: { type: 'string', default: '' },
            linkUrl: { type: 'string', default: '' },
            linkColor: { type: 'string', default: '#007cba' },
            layout: { type: 'string', default: 'center' },
            className: { type: 'string', default: '' },
            image1: { type: 'number', default: 0 },
            image1Url: { type: 'string', default: '' },
            image2: { type: 'number', default: 0 },
            image2Url: { type: 'string', default: '' },
            image3: { type: 'number', default: 0 },
            image3Url: { type: 'string', default: '' }
        },

        edit: function (props) {
            var attributes = props.attributes;
            var setAttributes = props.setAttributes;

            var blockProps = useBlockProps({
                className: 'highlight-section-editor ' + (attributes.layout || '')
            });

            function renderImageControl(label, imageKey, imageUrlKey) {
                return el('div', { style: { marginBottom: '12px' } },
                    el('label', { style: { display: 'block', marginBottom: '6px' } }, label),
                    el(MediaUploadCheck, {},
                        el(MediaUpload, {
                            onSelect: function (file) {
                                var update = {};
                                update[imageKey] = file.id;
                                update[imageUrlKey] = file.url;
                                setAttributes(update);
                            },
                            allowedTypes: ['image'],
                            value: attributes[imageKey],
                            render: function (obj) {
                                return el('div', {},
                                    attributes[imageUrlKey] ?
                                        el('div', { style: { display: 'flex', gap: '8px', alignItems: 'center' } },
                                            el('img', { src: attributes[imageUrlKey], style: { width: '80px', height: '60px', objectFit: 'cover', borderRadius: '4px' } }),
                                            el(Button, { isSecondary: true, onClick: obj.open }, __('Replace', 'okhub-theme')),
                                            el(Button, { isLink: true, onClick: function () { var u = {}; u[imageKey] = 0; u[imageUrlKey] = ''; setAttributes(u); } }, __('Remove', 'okhub-theme'))
                                        )
                                        :
                                        el(Button, { isSecondary: true, onClick: obj.open }, __('Select Image', 'okhub-theme'))
                                );
                            }
                        })
                    )
                );
            }

            return el('div', blockProps, [
                // Inspector controls (content + images + style)
                el(InspectorControls, { key: 'controls' },
                    el(PanelBody, { title: __('Content Settings', 'okhub-theme'), initialOpen: true },
                        el(TextControl, { label: __('Title', 'okhub-theme'), value: attributes.title, onChange: function (v) { setAttributes({ title: v }); } }),
                        el(TextareaControl, { label: __('Content', 'okhub-theme'), value: attributes.content, onChange: function (v) { setAttributes({ content: v }); } }),
                        el(TextControl, { label: __('Link title', 'okhub-theme'), value: attributes.linkTitle, onChange: function (v) { setAttributes({ linkTitle: v }); } }),
                        el(TextControl, { label: __('Link URL', 'okhub-theme'), value: attributes.linkUrl, onChange: function (v) { setAttributes({ linkUrl: v }); } })
                    ),
                    el(PanelBody, { title: __('Images', 'okhub-theme'), initialOpen: false },
                        renderImageControl(__('Left image', 'okhub-theme'), 'image1', 'image1Url'),
                        renderImageControl(__('Middle image', 'okhub-theme'), 'image2', 'image2Url'),
                        renderImageControl(__('Right image', 'okhub-theme'), 'image3', 'image3Url')
                    ),
                    el(PanelBody, { title: __('Style Settings', 'okhub-theme'), initialOpen: false },
                        el('div', { style: { marginBottom: '10px' } },
                            el('label', { style: { display: 'block', marginBottom: '5px' } }, __('Background Color', 'okhub-theme')),
                            el(ColorPalette, { value: attributes.background, onChange: function (v) { setAttributes({ background: v }); }, colors: [{ name: 'Light Gray', color: '#f8f9fa' }, { name: 'White', color: '#ffffff' }] })
                        ),
                        el(SelectControl, { label: __('Layout', 'okhub-theme'), value: attributes.layout, options: [{ value: 'center', label: __('Center', 'okhub-theme') }, { value: 'left', label: __('Left', 'okhub-theme') }, { value: 'right', label: __('Right', 'okhub-theme') }], onChange: function (v) { setAttributes({ layout: v }); } })
                    )
                ),

                // Server-side render preview (matches front-end)
                el('div', { className: 'highlight-ssr-preview' },
                    el(ServerSideRender, { block: 'okhub/highlight-section', attributes: attributes })
                )
            ]);
        },

        save: function () {
            return null; // render_callback handles output
        }
    });
})(window.wp);