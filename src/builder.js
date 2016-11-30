import Vue from 'vue'
import store from './store'
import router from './router'

import UPBSidebar from './UPBSidebar.vue'
import UPBPreview from './UPBPreview.vue'

import VueNProgress from './plugins/vue-nprogress'

Vue.use(VueNProgress);

//const states = window._upb_states;

//const status = window._upb_status;

// window.upbBuilder

const upbBuilder = new Vue({
    router,
    el   : '#upb-sidebar',
    data : {
        store
    },

    mounted() {
        this.$nextTick(function () {
            document.getElementById('upb-pre-loader').classList.add('loaded');
        })
    },

    render : createElement => createElement(UPBSidebar)
});

document.getElementById("upb-preview-frame").addEventListener('load', function () {

    let settings = {};

    store.tabs.filter(function (content) {
        return content.id == 'settings' ? content : false;
    }).pop().contents.map(function (data) {

        if (data.metaId == 'enable' || data.metaId == 'position') {
            settings[data.metaId] = data.metaValue;
        }
    });

    // console.log(settings);

    if (settings.enable) {
        new Vue({
            data   : {
                store
            },
            render : createElement => createElement(UPBPreview)
        })
        //.$mount(window.frames['upb-preview-frame'].contentWindow.document.getElementById('upb-preview'))
            .$mount(window.frames['upb-preview-frame'].contentWindow.document.getElementById(settings.position))
    }
});

/*window.previewWindowLoaded = function (iframe) {
 upbPreview.$mount(iframe.contentWindow.document.getElementById('upb-preview'));
 };*/

/*window.frames['upb-preview-frame'].window.onload = function () {
 ////upbPreview.$mount(window.frames['upb-preview-frame'].window.document.getElementById('upb-preview'))
 // upbPreview.$mount(window.frames['upb-preview-frame'].contentWindow.document.getElementById('upb-preview'))

 console.log('onload')
 };*/

