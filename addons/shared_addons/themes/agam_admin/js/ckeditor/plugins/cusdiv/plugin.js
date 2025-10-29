CKEDITOR.plugins.add( 'cusdiv', {
	init: function( editor ) {
		editor.addCommand( 'cusdivDialog', new CKEDITOR.dialogCommand( 'cusdivDialog' ) );
		editor.ui.addButton( 'CusDiv', {
			label: 'Custom Div',
			command: 'cusdivDialog',
			icon: this.path + 'images/icon2.gif'
		});
		CKEDITOR.dialog.add( 'cusdivDialog', function( editor ) {
			return {
				title : 'Div Properties',
				minWidth : 500,
				minHeight : 500,
				contents :
				[
					{
						id : 'general',
						label : 'Settings',
						elements :
						[
							//{ type : 'html', html : 'This dialog window lets you create text inside simple custom div.' },
							{ type : 'text', id : 'heading', label : 'Heading', required : false, commit : function( data ) {
									data.heading = this.getValue();
								}
							},
							{ type : 'textarea', id : 'contents', label : 'Text', validate : CKEDITOR.dialog.validate.notEmpty( 'The Text field cannot be empty.' ), required : true, commit : function( data ) {
									data.contents = this.getValue();
								}
							},
							{ type : 'select', id : 'style', label : 'Style', items : 
								[
									[ '<none>', '' ],
									[ 'Bold', 'b' ],
									[ 'Underline', 'u' ],
									[ 'Italics', 'i' ]
								], commit : function( data ) {
									data.style = this.getValue();
								}
							},
							{ type : 'select', id : 'font_color', label : 'Font Color', items : 
								[
									['Default', '#000000'],
									['Black', '#000000'],
									['Blue', '#0000FF'],
									['Green', '#008000'],
									['Lime', '#00FF00'],
									['Cyan', '#00FFFF'],
									['Purple', '#800080'],
									['Gray', '#808080'],
									['Red', '#FF0000'],
									['Orange', '#FFA500'],
									['Pink', '#FFC0CB'],
									['Yellow', '#FFFF00'],
									['White', '#FFFFFF']
								], commit : function( data ) {
									data.font_color = this.getValue();
								}
							},
							{ type : 'select', id : 'bg_color', label : 'Background Color', items : 
								[
									['Default', '#D3D3D3'],
									['Black', '#000000'],
									['Blue', '#0000FF'],
									['Green', '#008000'],
									['Lime', '#00FF00'],
									['Cyan', '#00FFFF'],
									['Purple', '#800080'],
									['Gray', '#808080'],
									['Red', '#FF0000'],
									['Orange', '#FFA500'],
									['Pink', '#FFC0CB'],
									['Yellow', '#FFFF00'],
									['White', '#FFFFFF']
								], commit : function( data ) {
									data.bg_color = this.getValue();
								}
							}
						]
					}
				],
				onOk : function()
				{
					var dialog = this,
						data = {},
						html = '';
						link = editor.document.createElement( 'div' );
					this.commitContent( data );
 
					link.setAttribute( 'class', 'custom-div' );
					link.setStyle( 'background-color', data.bg_color );
					link.setStyle( 'color', data.font_color );
					link.setStyle( 'border', '1px solid #DDDDDD' );
					link.setStyle( 'border-radius', '8px 8px 8px 8px' );
					link.setStyle( 'box-shadow', '0 8px 6px -6px rgba(200, 200, 200, 0.5)' );
					link.setStyle( 'margin-bottom', '20px' );
					link.setStyle( 'padding', '10px' );
 
					switch( data.style )
					{
						case 'b' :
							link.setStyle( 'font-weight', 'bold' );
						break;
						case 'u' :
							link.setStyle( 'text-decoration', 'underline' );
						break;
						case 'i' :
							link.setStyle( 'font-style', 'italic' );
						break;
					}
					
					if(data.heading) {
						html += '<h3>' + data.heading +'</h3>';
						html += '<div>' + data.contents + '</div>';
					} else {
						html = data.contents;
					}
					
					link.setHtml( html );
 
					editor.insertElement( link );
				}
			};
		});
	}
});