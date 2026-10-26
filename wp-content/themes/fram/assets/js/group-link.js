/**
 * Group block: "Link" toolbar button. The URL is saved in the block comment;
 * inc/group-link.php prints a link over the whole box on the front end.
 */
( ( wp ) => {
	const { addFilter } = wp.hooks;
	const { createHigherOrderComponent } = wp.compose;
	const { createElement: el, Fragment, useState } = wp.element;
	const { BlockControls, LinkControl } = wp.blockEditor;
	const { ToolbarButton, Popover } = wp.components;
	const { SVG, Path } = wp.primitives;
	const { __ } = wp.i18n;

	// Core "link" icon (@wordpress/icons is not available as a global).
	const linkIcon = el(
		SVG,
		{ xmlns: 'http://www.w3.org/2000/svg', viewBox: '0 0 24 24' },
		el( Path, {
			d: 'M10 17.389H8.444A5.194 5.194 0 1 1 8.444 7H10v1.5H8.444a3.694 3.694 0 0 0 0 7.389H10v1.5ZM14 7h1.556a5.194 5.194 0 0 1 0 10.39H14v-1.5h1.556a3.694 3.694 0 0 0 0-7.39H14V7Zm-4.5 6h5v-1.5h-5V13Z',
		} )
	);

	addFilter( 'blocks.registerBlockType', 'fram/group-link/attributes', ( settings, name ) => {
		if ( 'core/group' !== name ) {
			return settings;
		}

		return {
			...settings,
			attributes: {
				...settings.attributes,
				framLinkUrl: { type: 'string' },
				framLinkNewTab: { type: 'boolean', default: false },
			},
		};
	} );

	const GroupLinkControls = ( { attributes, setAttributes } ) => {
		const [ isEditing, setIsEditing ] = useState( false );
		const [ anchor, setAnchor ] = useState( null );
		const { framLinkUrl: url, framLinkNewTab: opensInNewTab } = attributes;

		return el(
			Fragment,
			null,
			el(
				BlockControls,
				{ group: 'block' },
				el( ToolbarButton, {
					ref: setAnchor,
					icon: linkIcon,
					label: __( 'Link', 'fram' ),
					isActive: !! url,
					onClick: () => setIsEditing( ! isEditing ),
				} )
			),
			isEditing &&
				el(
					Popover,
					{
						anchor,
						placement: 'bottom',
						shift: true,
						focusOnMount: 'firstElement',
						onClose: () => setIsEditing( false ),
					},
					el( LinkControl, {
						value: { url, opensInNewTab },
						forceIsEditingLink: ! url,
						onChange: ( next ) =>
							setAttributes( {
								framLinkUrl: next.url || undefined,
								framLinkNewTab: !! next.opensInNewTab,
							} ),
						onRemove: () => {
							setAttributes( { framLinkUrl: undefined, framLinkNewTab: false } );
							setIsEditing( false );
						},
					} )
				)
		);
	};

	addFilter(
		'editor.BlockEdit',
		'fram/group-link/controls',
		createHigherOrderComponent(
			( BlockEdit ) => ( props ) =>
				'core/group' === props.name && props.isSelected
					? el( Fragment, null, el( BlockEdit, props ), el( GroupLinkControls, props ) )
					: el( BlockEdit, props ),
			'withFramGroupLink'
		)
	);
} )( window.wp );
