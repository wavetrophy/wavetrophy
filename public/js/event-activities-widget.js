String.prototype.replaceAll = function (search, replacement) {
    var target = this;
    return target.replace(new RegExp(search, 'g'), replacement);
};

var errorTemplate = `
<span class="alert alert-danger d-block" style="margin-top: 1rem;line-height: 1.6rem;">
    <span class="d-block">
        <span class="form-error-icon badge badge-danger text-uppercase">Error</span>
        <span class="form-error-message" style="margin-left: 5px;">{text}</span>
    </span>
</span>
`;
var dataTemplate = `
<div class="row" activity activity-{id} style="margin-top: 1rem" activity-id="{id}">
    <div class=" col-8 offset-2">
        <div class="row">
            <div class="col-8">
                <h5 class="d-inline">{title}</h5>
                <h6 class="d-inline" style="margin-left: 1rem;">{start} - {end}</h6>
            </div>
            <div class="col-4 row">
                <div class="col-6">
                    <button type="button" class="btn btn-primary" edit="{id}">Edit</button>
                </div>
                <div class="col-6">
                    <button type="button" class="btn btn-danger" delete="{id}">Delete</button>
                </div>
            </div>
        </div>
        <div class="row col-12" style="margin-top: .5rem">
            {description-template}
        </div>
        <input type="hidden" name="activities[{id}][id]" activity-id-{id} value="{id}">
        <input type="hidden" name="activities[{id}][title]" activity-title-{id} value="{title}">
        <input type="hidden" name="activities[{id}][start]" activity-start-{id} value="{start}">
        <input type="hidden" name="activities[{id}][end]" activity-end-{id} value="{end}">
        <input type="hidden" name="activities[{id}][description]" activity-description-{id} value="{description}">
    </div>
</div>
`;
var descriptionTemplate = '<div class="row col-12" style="margin-top: .5rem">{description}</div>';

var getId = function () {
    return $('[data-activity-id]').val();
};

var setId = function (value) {
    $('[data-activity-id]').val(value);
};

var getStart = function () {
    return $('[new-activity-start]').val();
};

var setStart = function (value) {
    $('[new-activity-start]').val(value);
};

var getEnd = function () {
    return $('[new-activity-end]').val();
};

var setEnd = function (value) {
    $('[new-activity-end]').val(value);
};

var getTitle = function () {
    return $('[new-activity-title]').val();
};

var setTitle = function (value) {
    $('[new-activity-title]').val(value);
};

var getDescription = function () {
    return $('[new-activity-description]').val();
};

var setDescription = function (value) {
    $('[new-activity-description]').val(value);
};

var load = function (id) {
    var title = $('input[activity-title-' + id + ']').val();
    var start = $('input[activity-start-' + id + ']').val();
    var end = $('input[activity-end-' + id + ']').val();
    var description = $('input[activity-description-' + id + ']').val();

    return {
        start: start,
        end: end,
        title: title,
        description: description,
    };
};

var clearAll = function () {
    setId('');
    setStart('');
    setEnd('');
    setTitle('');
    setDescription('');
};

var fill = function (id, start, end, title, description) {
    setId(id);
    setStart(start);
    setEnd(end);
    setTitle(title);
    setDescription(description);
};

var appendError = function (element, message) {
    var error = errorTemplate.replaceAll('{text}', message);
    $(element).append(error);
};

var appendActivity = function (element, id, title, start, end, description) {
    var desc = '';
    if (description) {
        desc = descriptionTemplate.replaceAll('{description}', description);
    }
    var template = dataTemplate.replaceAll('{id}', id)
        .replaceAll('{title}', title)
        .replaceAll('{start}', start)
        .replaceAll('{end}', end)
        .replaceAll('{description-template}', desc)
        .replaceAll('{description}', description || '');
    $(template).insertAfter(element);
    addListeners();
};

var addListeners = function () {
    $('[edit]').each(function (_, el) {
        if ($(el).attr('has-listener')) {
            return;
        }
        $(el).on('click', function () {
            var id = $(this).attr('edit');
            var data = load(id);
            fill(data.id, data.start, data.end, data.title, data.description);
            $('[activity-' + id + ']').remove();
        }).attr('has-listener', 'true');
    });

    $('[delete]').each(function (_, el) {
        if ($(el).attr('has-listener')) {
            return;
        }
        $(el).on('click', function () {
            var id = $(this).attr('delete');
            if (confirm('Do you really want to delete this activity?')) {
                $('[activity-' + id + ']').remove();
            }
        }).attr('has-listener', 'true');
    })
};

$(function () {
    $('[add-activity]').on('click', function () {
        var errors = $('errors');
        errors.empty();
        var activities = $('[data-activities]');
        var start = getStart();
        var end = getEnd();
        var title = getTitle();
        var description = getDescription() || null;
        var error = false;
        if (!start) {
            appendError(errors, 'Start cannot be blank');
            error = true;
        }
        if (!end) {
            appendError(errors, 'End cannot be blank');
            error = true;
        }
        if (!title) {
            appendError(errors, 'Title cannot be blank');
            error = true;
        }
        if (error) {
            return;
        }

        var id = getId() || 'new_' + activities.find('[activity]').length;
        var element = $('[data-activities]>span');
        $('[activity]').each(function (_, el) {
            var s = load($(el).attr('activity-id')).start;
            if (s < start) {
                element = el;
            }
        });
        if (element.length < 1) {
            $('[data-activities]').append('<span></span>');
            element = $('[data-activities]>span')
        }
        appendActivity(element, id, title, start, end, description);
        clearAll();
    });

    addListeners();
});