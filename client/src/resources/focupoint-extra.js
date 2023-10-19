(function ($) {

	$.entwine('ss', function ($) {

    console.log('init')

    // $('.col-FocusPointX').remove();
    // $('.col-FocusPointY').remove();

		function handleResize() {
			// Call the updateGrid function when the window is resized
			$('.image-coord-field .grid').each(function () {
				$(this).updateGrid();
			});
		}

        // Bind the handleResize function to the window's resize event
        $(window).on('resize', handleResize);

		$('.image-coord-field .grid').entwine({

			onmatch: function () {
				var $this = this; // Store a reference to the current element
    			setTimeout(function () {

        			$this.updateGrid(); // Call updateGrid after a 1-second delay
    			}, 1200);
			},
			getCoordField: function (axis) {
				var fieldName = (axis.toUpperCase() === 'Y') ? this.data('yFieldName') : this.data('xFieldName');
				var fieldSelector = "input[name='" + fieldName + "']";
        console.log('LOOK FOR:', fieldName)
				return this.closest('.image-coord-fieldgroup').find(fieldSelector);
			},
			roundXYValues: function (XYval) {
				return XYval.toFixed(4);
			},
			updateGrid: function () {

        let inGridItem = $('.ss-gridfield-item .image-coord-field');

        if (inGridItem.length) {
          inGridItem.closest('td').addClass('imagecoord image-coord-fieldgroup')
        }

				var grid = $(this);

        // Get coordinates from text fields
				var focusX = grid.getCoordField('x').val();
				var focusY = grid.getCoordField('y').val();
        console.log('grid', focusX, focusY, grid)

				// Calculate background positions
				var backgroundWH = 11; // Width and height of grid background image
				var bgOffset = Math.floor(-backgroundWH / 2);
				var fieldW = grid.width();
				var fieldH = grid.height();
				var leftBG = this.data('cssGrid') ? bgOffset + (focusX * fieldW) : bgOffset + ((focusX / 2 + .5) * fieldW);
				var topBG = this.data('cssGrid') ? bgOffset + (focusY * fieldH) : bgOffset + ((-focusY / 2 + .5) * fieldH);
        console.log(leftBG,  topBG)
				// Line up crosshairs with click position
				grid.css('background-position', leftBG + 'px ' + topBG + 'px');
			},
			onclick: function (e) {
				var grid = $(this);
				var fieldW = grid.width();
				var fieldH = grid.height();

				// Calculate ImageCoord coordinates
				var offsetX = e.pageX - grid.offset().left;
				var offsetY = e.pageY - grid.offset().top;
				var focusX = this.data('cssGrid') ? offsetX / fieldW : (offsetX / fieldW - .5) * 2;
				var focusY = this.data('cssGrid') ? offsetY / fieldH : (offsetY / fieldH - .5) * -2;

				// Pass coordinates to form fields
        console.log('click', this.roundXYValues(focusX) , this.roundXYValues(focusY))
				this.getCoordField('x').val(this.roundXYValues(focusX));
				this.getCoordField('y').val(this.roundXYValues(focusY));
        console.log('check', this.getCoordField('x').val(), this.getCoordField('y').val())
				// Update focus point grid
				this.updateGrid();
				$(this).closest('form').addClass('changed');
			},
            onmousemove: function (e) {
                var grid = $(this);
                var fieldW = grid.width();
                var fieldH = grid.height();

                // Calculate ImageCoord coordinates based on mouse position
                var offsetX = e.pageX - grid.offset().left;
                var offsetY = e.pageY - grid.offset().top;
                var focusX = this.data('cssGrid') ? offsetX / fieldW : (offsetX / fieldW - .5) * 2;
                var focusY = this.data('cssGrid') ? offsetY / fieldH : (offsetY / fieldH - .5) * -2;

				var xysumfield = this.closest('.image-coord-fieldgroup').find(".sumField");
				xysumfield.html('mouseX ' + this.roundXYValues(focusX) + ' / mouseY ' + this.roundXYValues(focusY));
            }

		});
	});
}(jQuery));
