/**
 * Created by susannazanatta on 30/08/2016.
 */


/* ****** POST DRAG AND DROP ***** */

var dragSrcEl = null;

//start the drag
function handleDragStart(e) {
    this.style.opacity = '0.4';  // this / e.target is the source node.

    dragSrcEl = this;
    e.dataTransfer.effectAllowed = 'move';
    e.dataTransfer.setData('text/html', this.innerHTML);
}

function handleDragOver(e) {
    if (e.preventDefault) {
        e.preventDefault(); // Necessary. Allows us to drop.
    }

    return false;
}

function handleDragEnter(e) {
    // this / e.target is the current hover target.
    this.classList.add('over');
}

function handleDragLeave(e) {
    this.classList.remove('over');  // this / e.target is previous target element.
}

function handleDrop(e) {
    // this / e.target is current target element.
    if (e.stopPropagation) {
        e.stopPropagation(); // stops the browser from redirecting.
    }
    e.dataTransfer.dropEffect = 'move';  // See the section on the DataTransfer object.

    if (e.stopPropagation) {
        e.stopPropagation();
    }

    if (dragSrcEl != this) {
        dragSrcEl.innerHTML = this.innerHTML;
        this.innerHTML = e.dataTransfer.getData('text/html');
    }

    // See the section on the DataTransfer object.
    return false;
}

function handleDragEnd(e) {
    // this/e.target is the source node.
    this.style.opacity = '';
    [].forEach.call(rows, function (row) {
        row.classList.remove('over');
    });
}

var rows = document.querySelectorAll('#the-list .draggable-tr');
[].forEach.call(rows, function (row) {
    row.addEventListener('dragstart', handleDragStart, false);
    row.addEventListener('dragenter', handleDragEnter, false);
    row.addEventListener('dragover', handleDragOver, false);
    row.addEventListener('dragleave', handleDragLeave, false);
    row.addEventListener('drop', handleDrop, false);
    row.addEventListener('dragend', handleDragEnd, false);
});


/* ****** SAVE DRAG AND DROP ***** */

var dataArray = [];
var postId;

jQuery('#save-post-order').on('click', function () {
    dataArray.splice(0, dataArray.length);
    jQuery('.draggable-tr').each(function () {
        postId = jQuery(this).children('.ID').html();
        dataArray.push(postId);
    });
    save_order(dataArray);
});


function save_order(dataArray) {

    var data = {action: 'save_order', id_array: dataArray};
    var table = jQuery('.wp-list-table');

    jQuery.ajax({
        data: data,
        type: 'POST',
        dataType: 'json',
        url: ajaxurl,
        success: function (response) {
            table.before('<div class="notice notice-success"><p>Order has been saved</p></div>');

        },
        error: function (error) {
            table.before('<div class="notice notice-error"><p>Oooops, something went wrong. Please try again</p></div>');
        }
    })
}


/* ****** SAVE ON OFF OPTION ***** */

var dataOnOff;

jQuery('.save_custom_order_off').on('click', function () {
    dataOnOff = 'off';
    save_custom_order_on_off(dataOnOff);
});

jQuery('.save_custom_order_on').on('click', function () {
    dataOnOff = 'on';
    save_custom_order_on_off(dataOnOff);
});


function save_custom_order_on_off(dataOnOff) {

    var data = {action: 'save_custom_order_on_off', custom_order_on_of: dataOnOff};
    var outputPosition = jQuery('#poststuff');

    jQuery.ajax({
        data: data,
        type: 'POST',
        dataType: 'json',
        url: ajaxurl,
        success: function (response) {
            outputPosition.before('<div class="notice notice-success"><p>All good! Your setting was saved!</p></div>');
            document.location.reload(true);

        },
        error: function (error) {
            outputPosition.before('<div class="notice notice-error"><p>Oooops, something went wrong. Please try again</p></div>');
        }
    })
}


/* ****** SAVE CAPABILITIES ***** */

var roles = [];
var role;


jQuery('#dd-custom-order-save-capability_settings').on('click', function () {
    jQuery('#users_can_custom_post_order input:checkbox:checked').each(function () {
        role = jQuery(this).val();
        roles.push(role);
    });
    update_custom_order_capability_settings(roles);
});


function update_custom_order_capability_settings(roles) {
    var data = {action: 'update_custom_order_capability', custom_post_order_capabilities: roles};
    var outputPosition = jQuery('#poststuff');
    jQuery.ajax({
        data: data,
        type: 'POST',
        dataType: 'json',
        url: ajaxurl,
        success: function (response) {
            outputPosition.before('<div class="notice notice-success"><p>All good! Your setting was saved!</p></div>');
            document.location.reload(true);

        },
        error: function (error) {
            outputPosition.before('<div class="notice notice-error"><p>Oooops, something went wrong. Please try again</p></div>');
        }
    })
}







