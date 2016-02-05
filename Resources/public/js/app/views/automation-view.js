define([
    'underscore',
    'tpl!diamanteautomation/js/app/templates/automation-template.ejs',
    'oroui/js/app/views/base/view'
],function (_, AutomationTemplate, BaseView) {
    'use strict';

    var AutomationView = BaseView.extend({
        autoRender: true,
        className: 'container-fluid',
        template : AutomationTemplate,

        events: {
            'change > .control-group :input' : 'change'
        },

        regions: {
            'automation-conditions': '#automation-conditions',
            'automation-actions': '#automation-actions'
        },

        initialize: function(options){
            this.options = _.omit(options, 'el', 'model');
        },

        getTemplateData: function() {
            var data = BaseView.prototype.getTemplateData.call(this);
            return _.extend(data, this.options);
        },

        change: function(e) {
            var input = this.$(e.target);
            this.model.set( input.data('attr'), input.val(), { silent: true });
        }
    });

    return AutomationView;
});