/* global buildCourseUrl, displaySuccessMessage, displayErrorMessage, csrfToken */
/* exported updateSolutionTaNotes, detectSolutionChange */
function updateSolutionTaNotes(gradeable_id, component_id, itempool_item) {
    const data = {
        solution_text: $(`#textbox-solution-${component_id}`).val().trim(),
        component_id,
        itempool_item,
        csrf_token: csrfToken,
    };
    $.ajax({
        url: buildCourseUrl(['gradeable', gradeable_id, 'solution_ta_notes']),
        type: 'POST',
        data,
        success: function (res) {
            res = JSON.parse(res);
            if (res.status === 'success') {
                displaySuccessMessage('Solution has been updated successfully...');
                // Dom manipulation after the Updating/adding the solution note
                $(`#solution-box-${component_id}`).attr('data-first-edit', 0);

                // Updating the last edit info
                $(`#solution-box-${component_id} .last-edit`).removeClass('hide');
                $(`#solution-box-${component_id} .last-edit i.last-edit-time`).text(res.data.edited_at);
                $(`#solution-box-${component_id} .last-edit i.last-edit-author`).text(
                    res.data.current_user_id === res.data.author ? `${res.data.author} (You)` : res.data.author,
                );
                // Updating the saved notes with the latest solution
                $(`#sol-textbox-cont-${component_id}-saved .solution-notes-text`).text(res.data.solution_text);
            }
            else {
                displayErrorMessage('Something went wrong while updating the solution...');
            }
        },
        error: function(err) {
            console.log(err);
        },
    });
}

//set Save button class depending on if the solution has been altered from the previous solution
function detectSolutionChange() {
    const textarea = $(this);
    const solution_div = textarea.closest('.solution-cont');
    const save_button = solution_div.find('.solution-save-btn');
    if (textarea.val() !== solution_div.attr('data-original-solution')) {
        save_button.removeClass('btn-default');
        save_button.addClass('btn-primary');
    }
    else {
        save_button.removeClass('btn-primary');
        save_button.addClass('btn-default');
    }
}
