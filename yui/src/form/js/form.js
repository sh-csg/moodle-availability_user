// eslint-disable-next-line camelcase
M.availability_user = M.availability_user || {};

M.availability_user.form = Y.Object(M.core_availability.plugin);

M.availability_user.form.initInner = function(param) {
    this.params = param;
};

M.availability_user.form.getNode = function(json) {
    var html = '<label><span class="col-form-label pr-3">' + M.util.get_string('title', 'availability_user') +
        '</span><span class="availability-group form-group"><select class="custom-select">';

    this.params.forEach(
        function(val) {
            html += '<option value="' + val.id + '">' + val.fullname + '</option>';
        }
    );

    html += '</select></span></label>';
    var node = Y.Node.create('<span>' + html + '</span>');

    if (json.userid) {
        if (node.one('option[value=' + json.userid + ']') === null) {
            node.one('select').appendChild(
                Y.Node.create('<option value="' + json.userid + '">(' +
                M.util.get_string('missing_user', 'availability_user') +
                ')')
                );
        }
        node.one('option[value=' + json.userid + ']').set('selected', true);
    }

    if (!M.availability_user.form.addedEvents) {
        M.availability_user.form.addedEvents = true;
        var root = Y.one('#fitem_id_availabilityconditionsjson');
        root.delegate('click', function() {
            M.core_availability.form.update();
        }, '.availability_user select');
    }

    return node;
};

M.availability_user.form.fillValue = function(value, node) {
    var userSelect = node.one('select');
    value.userid = userSelect.get('options').item(userSelect.get('selectedIndex')).get('value');
};