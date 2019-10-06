/**
 * BackOffice Library
 */
(function ($, Handlebars, window, _) {

  // Attr starts with selector
  $.fn.attrBegins = function(s) {
    const attrs = [];
    this.each(function() {
      $.each(this.attributes, function( index, attr ) {
        if (attr.name.indexOf(s) === 0) attrs.push(attr);
      });
    });
    return $(attrs);
  };

  // Widget
  function Widget(struct) {

    // Set struct
    Object.keys(struct || {}).forEach((k) => this[k] = struct[k]);

    // Set context
    this.context = this.context || {};

    // Private functions
    const getContext = function(name){
      return this[name];
    };

    // Private constants
    const items = $(this.selector || '').toArray();

    // Check items
    if (!items.length) items.push(document.createElement('div'));
    this.init = this.init || (() => {});
    this.listen = this.listen || {};
    this.context = this.context || {};

    // Template
    if (typeof this.template === 'string') {
      this.template = { default: this.template };
    }
    if (this.template) Object.keys(this.template).forEach(k => this.template[k] = Handlebars.compile(this.template[k]));

    // Call hooks
    setTimeout(() => {
      items.forEach(i => {
        // Init
        this.init(i);
        // Get item listeners
        const _listen = Object.assign({}, this.listen, $(i).data('listen'));
        // Route listen
        Object.keys(_listen).forEach(k => $(i).on(k, (e) => this[_listen[k]].call(this, e, i)));
      });
    });

  }

  // Create new widget
  Widget.Create = function(context) {
    return new Widget(context);
  };

  // Extends from EventEmitter
  Widget.prototype = new EventEmitter3();

  // Attach widget to given selector
  Widget.prototype.renderTemplate = function(context, name) {
    const _w = this;
    name = (name || 'default');
    if (!this.template.hasOwnProperty(name)) {
      throw new Error('Template ' + name + ' not found on widget ' + this.name);
    }
    context = Object.assign(this.context, context || {});
    // Create virtual dom
    const _virtualContext = {};
    const _virtualDomHolder = {};
    Object.keys(context).forEach(k => {
      if (context[k] instanceof $) {
        const holderId = _.uniqueId('holder_');
        _virtualDomHolder[holderId] = context[k];
        context[k] = `<div style="display: none;" id="${holderId}">TEST</div>`;
      }
      _virtualContext[k] = context[k];
    });
    // Render template
    const el = $(this.template[name](context));
    // Virtual -> Real Dom
    Object.keys(_virtualDomHolder).forEach(id => el.find('#' + id).replaceWith(_virtualDomHolder[id]));
    // Listen events
    el.attrBegins('on:').each(function() {
      const eventName = this.name.replace('on:', '');
      $(this.ownerElement).on(eventName, (_w.hasOwnProperty(this.value) ? _w[this.value].bind(_w) : (context[this.value] || (() => {})).bind(_w)));
    });
    return el;
  };

  // Export Widget
  window.BO_Widget = Widget;

  /**
   * Modal Widget
   * @type {Widget}
   */
  window.Modal = Widget.Create({
    name: 'Modal',
    template: `
      <div class="modal fade" style="display: none;" id="{{id}}" tabindex="-1" role="dialog" aria-labelledby="{{id}}_label" aria-hidden="true">
        <div class="modal-dialog{{size}}" role="document">
          <div class="modal-content">
            {{#if title}}
            <div class="modal-header">
              <h5 class="modal-title" id="{{id}}_label">{{title}}</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            {{/if}}
            <div class="modal-body">{{body}}</div>
            {{#if footer}}
              <div class="modal-footer">{{{footer}}}</div>
            {{/if}}
          </div>
        </div>
      </div>`,
    context: {
      title: 'test',
      body: 'asd',
      size: ''
    },
    options: {
      keyboard: true,
      focus: true,
      show: false,
      backdrop: true,
    },
    SIZE: {
      SMALL: ' modal-sm',
      DEFAULT: '',
      LARGE: ' modal-lg',
      EXTRA_LARGE: ' modal-xl'
    },
    create(context, options) {
      return this.renderTemplate(context).appendTo('body').modal(Object.assign(this.options, options || {}));
    },
    show(context) {
      return this.create(context, { show: true });
    }
  });

  /**
   * Modal confirm
   * @type {Widget}
   */
  window.Confirm = Widget.Create({
    name: 'Confirm',
    selector: "[data-confirm]",
    template: `
      <button class="btn btn-outline" on:click="onCancel">Cancel</button>
      <button class="btn btn-success" on:click="onSuccess">Accept</button>
    `,
    listen: {
      click: 'onClick'
    },
    activeModal: null,
    activeTarget: null,
    onSuccess(e) {
      e.preventDefault();
      $(e.target).attr('disabled', 'disabled');
      window.location = this.activeTarget;
    },
    onCancel(e) {
      e.preventDefault();
      this.activeModal.modal('hide');
    },
    onClick(e, el) {
      e.preventDefault();
      this.activeTarget = $(el).attr('href');
      this.activeModal = Modal.create({
        size: Modal.SIZE.SMALL,
        title: 'Are you sure?',
        body: $(el).data('confirm'),
        footer: this.renderTemplate()
      }).modal('show');
    }
  });

})($, Handlebars, window, _);
