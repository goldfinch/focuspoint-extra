(function ($) {
  const getCoordField = function (axis) {
    const fieldName =
      axis.toUpperCase() === 'Y'
        ? this.data('yFieldName')
        : this.data('xFieldName');
    const fieldSelector = `input[name='${fieldName}']`;
    return this.closest('.image-coord-fieldgroup').find(fieldSelector);
  };

  $.entwine('ss', ($) => {
    $('.cms-edit-form').entwine({
      getCoordField(axis) {
        const fieldName =
          axis.toUpperCase() === 'Y'
            ? this.data('yFieldName')
            : this.data('xFieldName');
        const fieldSelector = `input[name='${fieldName}']`;
        return this.closest('.image-coord-fieldgroup').find(fieldSelector);
      },
      updateGrid() {
        const inGridItem = $('.ss-gridfield-item .image-coord-field');
        if (inGridItem.length) {
          inGridItem
            .closest('td')
            .addClass('imagecoord image-coord-fieldgroup');
        }

        const grid = $(this);

        // Get coordinates from text fields
        const focusX = grid.getCoordField('x').val();
        const focusY = grid.getCoordField('y').val();

        // Calculate background positions
        const backgroundWH = 11; // Width and height of grid background image
        const bgOffset = Math.floor(-backgroundWH / 2);
        const fieldW = grid.width();
        const fieldH = grid.height();
        const leftBG = this.data('cssGrid')
          ? bgOffset + focusX * fieldW
          : bgOffset + (focusX / 2 + 0.5) * fieldW;
        const topBG = this.data('cssGrid')
          ? bgOffset + focusY * fieldH
          : bgOffset + (-focusY / 2 + 0.5) * fieldH;
        // Line up crosshairs with click position
        grid.css('background-position', `${leftBG}px ${topBG}px`);

        // update fpaim
        grid.find('.fpaim').css({
          left: Math.round((Number(focusX) + 1) * 0.5 * fieldW),
          top: Math.round((Number(focusY) + 1) * 0.5 * fieldH),
          width: fieldW * 2,
          height: fieldH * 2,
        });
      },

      onadd() {
        $('.image-coord-field .grid').each(function (i, e) {
          if ($(e).closest('.ui-accordion').length) {
            $(e)
              .closest('.ui-accordion')
              .find('.ui-accordion-header')
              .click(() => {
                $(this).updateGrid();
              });
          }
        });

        this._super();
      },
      onchange() {
        $('.uploadfield-item__remove-btn')
          .off('click')
          .click((e) => {
            const imageHiddenField = $(e.currentTarget)
              .closest('.entwine-uploadfield.uploadfield')
              .find('input');

            if (imageHiddenField.length) {
              const fieldName = imageHiddenField.attr('name').split('[')[0];

              const focusPointXField = $(
                `input[name="${fieldName}-_1_-FocusPointX"]`,
              );

              if (focusPointXField.length) {
                focusPointXField
                  .closest('.CompositeField.togglecomposite')
                  .remove();
              }
            }
          });
      },
    });

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
      onmatch() {
        const $this = this; // Store a reference to the current element

        setTimeout(() => {
          if ($('.image-coord-fieldgroup').length) {
            $('.ui-tabs-anchor').click((e) => {
              setTimeout(() => {
                $this.updateGrid();
              }, 200);
            });
          }

          $this.updateGrid(); // Call updateGrid after a 1-second delay
        }, 200);
      },
      getFieldSelector(axis) {
        const fieldName =
          axis.toUpperCase() === 'Y'
            ? this.data('yFieldName')
            : this.data('xFieldName');
        return `input[name='${fieldName}']`;
      },
      getCoordField(axis) {
        const fieldSelector = this.getFieldSelector(axis);
        // console.log('LOOK FOR:', fieldName, this)
        return this.closest('.image-coord-fieldgroup').find(fieldSelector);
      },
      roundXYValues(XYval) {
        return XYval.toFixed(4);
      },
      updateGrid() {
        const inGridItem = $('.ss-gridfield-item .image-coord-field');
        if (inGridItem.length) {
          inGridItem
            .closest('td')
            .addClass('imagecoord image-coord-fieldgroup');
        }

        const grid = $(this);

        const gridImage = new Image();
        gridImage.src = grid
          .closest('.image-coord-field')
          .find('img')
          .attr('src');
        gridImage.onload = () => {
          setTimeout(() => {
            // Get coordinates from text fields
            const focusX = grid.getCoordField('x').val();
            const focusY = grid.getCoordField('y').val();

            // Calculate background positions
            const backgroundWH = 11; // Width and height of grid background image
            const bgOffset = Math.floor(-backgroundWH / 2);
            const fieldW = grid.width();
            const fieldH = grid.height();
            const leftBG = this.data('cssGrid')
              ? bgOffset + focusX * fieldW
              : bgOffset + (focusX / 2 + 0.5) * fieldW;
            const topBG = this.data('cssGrid')
              ? bgOffset + focusY * fieldH
              : bgOffset + (-focusY / 2 + 0.5) * fieldH;
            // Line up crosshairs with click position
            grid.css('background-position', `${leftBG}px ${topBG}px`);

            // update fpaim
            grid.find('.fpaim').css({
              left: Math.round((Number(focusX) + 1) * 0.5 * fieldW),
              top: Math.round((Number(focusY) + 1) * 0.5 * fieldH),
              width: fieldW * 2,
              height: fieldH * 2,
            });
          }, 200);
        };
      },
      onclick(e) {
        const grid = $(this);
        const fieldW = grid.width();
        const fieldH = grid.height();

        // Calculate ImageCoord coordinates
        const offsetX = e.pageX - grid.offset().left;
        const offsetY = e.pageY - grid.offset().top;
        const focusX = this.data('cssGrid')
          ? offsetX / fieldW
          : (offsetX / fieldW - 0.5) * 2;
        const focusY = this.data('cssGrid')
          ? offsetY / fieldH
          : (offsetY / fieldH - 0.5) * 2;

        // Pass coordinates to form fields
        this.getCoordField('x').val(focusX); // .val(this.roundXYValues(focusX));
        this.getCoordField('y').val(focusY); // .val(this.roundXYValues(focusY));
        // Update focus point grid
        this.updateGrid();
        $(this).closest('form').addClass('changed');

        // this is for grideditable field within modeladmin (to trigger change on focuspoint within the grid (image-settings.js))
        if ($(this.getFieldSelector('x')).length) {
          $(this.getFieldSelector('x')).each((key, element) => {
            if (!$(element).hasClass('editable-column-field')) {
              $(element).change();
            }
          });
        }
        if ($(this.getFieldSelector('y')).length) {
          $(this.getFieldSelector('y')).each((key, element) => {
            if (!$(element).hasClass('editable-column-field')) {
              $(element).change();
            }
          });
        }
      },
      onmousemove(e) {
        const grid = $(this);
        const fieldW = grid.width();
        const fieldH = grid.height();

        // Calculate ImageCoord coordinates based on mouse position
        const offsetX = e.pageX - grid.offset().left;
        const offsetY = e.pageY - grid.offset().top;
        const focusX = this.data('cssGrid')
          ? offsetX / fieldW
          : (offsetX / fieldW - 0.5) * 2;
        const focusY = this.data('cssGrid')
          ? offsetY / fieldH
          : (offsetY / fieldH - 0.5) * -2;

        const xysumfield = this.closest('.image-coord-fieldgroup').find(
          '.sumField',
        );
        xysumfield.html(
          `mouseX ${this.roundXYValues(focusX)} / mouseY ${this.roundXYValues(
            focusY,
          )}`,
        );
      },
    });
  });
})(jQuery);
