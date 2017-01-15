import { util } from 'vue';
import store from '../store'
import extend from 'extend'

($ => {
    const vUIDroppable = {};

    if (!$().droppable) {
        util.warn('jQueryUI Droppable not installed or found globally to use `vue-ui-droppable` directive..', this);
    }

    vUIDroppable.install = function (Vue, options) {

        Vue.directive('ui-droppable', {

            bind : function (el, binding, vnode) {

            },

            update : function (el, binding, vnode) {
            },

            unbind : function (el) {
                $('.upb-add-element-message-regular', el).droppable("destroy");
            },

            componentUpdated : function (el, binding, vnode) {
            },

            inserted : function (el, binding, vnode) {

                $('.upb-add-element-message-regular', el).droppable({
                    hoverClass  : "ui-droppable-hover",
                    activeClass : "ui-droppable-active",
                    tolerance   : "pointer",
                    addClasses  : false,
                    disabled    : false,

                    drop : function (event, ui) {

                        let draggable = ui.draggable.context.__vue__;
                        let droppable = vnode.context;

                        //console.log('from ', draggable.$parent.model._upb_options._keyIndex, 'to ', droppable.model._upb_options._keyIndex)

                        if (draggable.$parent.model._upb_options._keyIndex == droppable.model._upb_options._keyIndex) {
                            //console.log(`You cannot do this :)`);
                        }
                        else {
                            let getIndex = draggable.model._upb_options._keyIndex.split('/').pop();
                            let contents = extend(true, {}, draggable.$parent.model.contents.splice(getIndex, 1).pop());
                            vnode.context.afterDrop(contents, true);
                        }
                    }
                });
            }
        });
    };

    if (typeof exports == "object") {
        module.exports = vUIDroppable
    }
    else if (typeof define == "function" && define.amd) {
        define([], function () {
            return vUIDroppable
        })
    }
    else if (window.Vue) {
        window.vUIDroppable = vUIDroppable;
        Vue.use(vUIDroppable)
    }
})(window.jQuery);