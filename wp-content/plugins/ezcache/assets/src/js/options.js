import Vue from 'vue';
import UniqueId from 'vue-unique-id';
import VueRouter from 'vue-router'
import Fragment from 'vue-fragment';
import VueSweetalert2 from 'vue-sweetalert2';

import OptionsScreen from './Pages/OptionsScreen';
import Stats from './Pages/Stats';
import Settings from './Pages/Settings';
import AdvancedSettings from './Pages/AdvancedSettings';
import License from './Pages/License';

Vue.use(UniqueId);
Vue.use(VueRouter);
Vue.use(Fragment.Plugin);
Vue.use(VueSweetalert2);

let {trans, ...ezcache} = window.ezcache;

Vue.prototype.$window = window;
Vue.prototype.$eventBus = new Vue();
Vue.prototype.$wp = {
    ...ezcache,
    trans(key) {
        return trans[key] ? trans[key] : `__${key}__`;
    }
};

Vue.component('ezc-options', OptionsScreen);

Vue.mixin({
    methods: {
        toast(text, type='success') {
            return this.$swal({ position: 'bottom-start', type: type, text: text, timer: 2000, toast: true, showCancelButton: false, showCloseButton: false, showConfirmButton: false });
        }
    }
});

const routes = [
    { path: '/', component: Stats },
    { path: '/settings', component: Settings },
    { path: '/advanced', component: AdvancedSettings },
    { path: '/license', component: License },
];

const router = new VueRouter({ routes });

new Vue({ router }).$mount('#ezcache-options');
