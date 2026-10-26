/**
 * Group block: "Max width" in the Dimensions panel. The value is saved in the block comment;
 * inc/group-max-width.php prints it as an inline max-width on the front end.
 */
( ( wp ) => {
	const { addFilter } = wp.hooks;
	const { createHigherOrderComponent } = wp.compose;
	const { createElement: el, Fragment } = wp.element;
	const { InspectorControls, useSettings } = wp.blockEditor;
	const {
		__experimentalToolsPanelItem: ToolsPanelItem,
		__experimentalUnitControl: UnitControl,
		__experimentalUseCustomUnits: useCustomUnits,
	} = wp.components;
	const { __ } = wp.i18n;

	addFilter( 'blocks.registerBlockType', 'fram/group-max-width/attributes', ( settings, name ) => {
		if ( 'core/group' !== name ) {
			return settings;
		}

		return {
			...settings,
			attributes: {
				...settings.attributes,
				framMaxWidth: { type: 'string' },
			},
		};
	} );

	const MaxWidthControl = ( { attributes, setAttributes, clientId } ) => {
		const [ availableUnits ] = useSettings( 'spacing.units' );
		const units = useCustomUnits( { availableUnits: availableUnits || [ '%', 'px', 'em', 'rem', 'vw' ] } );
		const label = __( 'Max width', 'fram' );

		return el(
			InspectorControls,
			{
				group: 'dimensions',
				resetAllFilter: ( nextAttributes ) => ( { ...nextAttributes, framMaxWidth: undefined } ),
			},
			el(
				ToolsPanelItem,
				{
					label,
					hasValue: () => !! attributes.framMaxWidth,
					onDeselect: () => setAttributes( { framMaxWidth: undefined } ),
					isShownByDefault: true,
					panelId: clientId,
				},
				el( UnitControl, {
					label,
					labelPosition: 'top',
					value: attributes.framMaxWidth || '',
					units,
					min: 0,
					onChange: ( next ) => setAttributes( { framMaxWidth: next || undefined } ),
					__next40pxDefaultSize: true,
				} )
			)
		);
	};

	addFilter(
		'editor.BlockEdit',
		'fram/group-max-width/controls',
		createHigherOrderComponent(
			( BlockEdit ) => ( props ) =>
				'core/group' === props.name && props.isSelected
					? el( Fragment, null, el( BlockEdit, props ), el( MaxWidthControl, props ) )
					: el( BlockEdit, props ),
			'withFramGroupMaxWidth'
		)
	);

	// Same max width on the block in the editor canvas.
	addFilter(
		'editor.BlockListBlock',
		'fram/group-max-width/preview',
		createHigherOrderComponent( ( BlockListBlock ) => ( props ) => {
			const maxWidth = 'core/group' === props.name && props.attributes.framMaxWidth;
			if ( ! maxWidth ) {
				return el( BlockListBlock, props );
			}

			const wrapperProps = props.wrapperProps || {};

			return el( BlockListBlock, {
				...props,
				wrapperProps: { ...wrapperProps, style: { ...wrapperProps.style, maxWidth } },
			} );
		}, 'withFramGroupMaxWidthPreview' )
	);
} )( window.wp );
