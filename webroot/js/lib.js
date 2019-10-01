/**
 * BackOffice Library
 */
(function ($, Handlebars, window) {

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
    this.template = this.template ? Handlebars.compile(this.template) : (() => {});

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
  Widget.prototype.renderTemplate = function(context) {
    context = Object.assign(this.context, context || {});
    const el = $(this.template(context));
    el.find("[name^='news']").toArray().filter(i => $(i).attr('on:'))
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
            {{#footer}}
              <div class="modal-footer">{{{footer}}}</div>
            {{/footer}}
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
    selector: "[data-confirm]",
    template: `
      <button class="btn btn-success" >Tamam</button>
    `,
    listen: {
      click: 'onClick'
    },
    onClick(e, el) {
      e.preventDefault();
      Modal.create({
        size: Modal.SIZE.SMALL,
        title: 'Emin misiniz?',
        body: $(el).data('confirm')
      }).modal('show');
      console.log('Confirm requested', el);
    }
  });

})($, Handlebars, window);
