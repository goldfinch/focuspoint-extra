(function ($) {
  $.entwine('ss', ($) => {
    /* counter */
    $('body').append($('<div id="AltEditorCharCounter"></div>').hide());

    $(
      '.image-settings fieldset.grid .ss-gridfield-item input, .image-settings fieldset.grid .ss-gridfield-item textarea',
    ).entwine({
      onkeydown(e) {
        if ((e.which && e.which == 13) || (e.keyCode && e.keyCode == 13)) {
          e.preventDefault();
          $(this).trigger('change');
        }
      },

      onkeyup() {
        $('#AltEditorCharCounter').html($(this).val().trim().length);
      },

      onfocusin() {
        $('.cms-edit-form').removeClass('changed');
        $('#AltEditorCharCounter').show();
        $('#AltEditorCharCounter').html($(this).val().trim().length);
      },

      onfocusout() {
        $('#AltEditorCharCounter').hide();
      },

      onchange() {
        // prevent changes to the form / popup
        $('.cms-edit-form').removeClass('changed');

        const $this = $(this);
        const id = $this.closest('tr').attr('data-id');
        const url = `${$this
          .closest('.ss-gridfield')
          .attr('data-url')}/update/${id}`;
        let data = `${encodeURIComponent(
          $this.attr('name'),
        )}=${encodeURIComponent($(this).val())}`;
        $this.closest('td').addClass('saving');

        data = data.replace('%5BGridFieldEditableColumns%5D', '');

        $.ajax({
          type: 'POST',
          url,
          data,
          success(data, textStatus) {
            $this.closest('td').attr('class', '');
            if (data.errors.length) {
              $this.closest('td').addClass('has-warning');
              data.errors.forEach((error) => {
                $this.closest('td').addClass(error);
              });
            } else {
              $this.closest('td').addClass('has-success');
            }
            $('.cms-edit-form').removeClass('changed');
          },
          error(data, textStatus) {
            $this.closest('td').attr('class', '');
            $this.closest('td').addClass('error');
            alert(data.responseText);
          },
          dataType: 'json',
        });
      },
    });
  });
})(jQuery);
