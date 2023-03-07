import Vue from 'vue'
import VueMeta from 'vue-meta'
import vSelect from 'vue-select'
import PortalVue from 'portal-vue'
import { App, plugin } from '@inertiajs/inertia-vue'
import { InertiaProgress } from '@inertiajs/progress/src'
import Notifications from 'vue-notification'

/* Axios */
import axios from 'axios'
import VueAxios from 'vue-axios'

/* Modal */
import vmodal from 'vue-js-modal'

import moment from 'moment'

/** Vue Select */
import 'vue-select/dist/vue-select.css';

/* Checkbox */



Vue.config.productionTip = false
Vue.mixin({ methods: { route: window.route } })
Vue.use(plugin)
Vue.use(PortalVue)
Vue.use(VueMeta)
Vue.use(vmodal)
Vue.use(Notifications)

Vue.component('v-select', vSelect)

moment.locale('ru')

InertiaProgress.init()

//global variable
Vue.prototype.$zakvaskaBlock = false

// positions
Vue.prototype.DIRECTOR = 1 // директор
Vue.prototype.TECHNICIAN = 2 // технолог
Vue.prototype.DISTRIBUTOR = 3 // реализатор
Vue.prototype.WORKER = 4 // рабочий
Vue.prototype.FACTORY_WORKER = 5 // цех
Vue.prototype.ACCOUNTANT = 6 // бухгалтер
Vue.prototype.FACTORY_MANAGER = 7 // начальник цеха

// check position of auth user
Vue.mixin({
    methods: {
        userIs(positions) {
            return this.$page.props.auth.user != null
                && positions.includes(this.$page.props.auth.user.position_id)
        },
        userIsNot(positions) {
            return !( this.$page.props.auth.user != null
                && positions.includes(this.$page.props.auth.user.position_id))
        },
    },
})

const el = document.getElementById('app')

new Vue({
    metaInfo: {
        titleTemplate: title => (title ? `${title} - Oasis CRM` : 'Oasis CRM'),
    },
    render: h =>
        h(App, {
            props: {
                initialPage: JSON.parse(el.dataset.page),
                resolveComponent: name =>
                    import (`@/Pages/${name}`).then(module => module.default),
            },
        }),
}).$mount(el)
