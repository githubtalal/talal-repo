import { createApp } from 'vue'
import { createPinia } from 'pinia'

import App from './App.vue'
import router from './router'
import ElementPlus from 'element-plus'
import Ar from 'element-plus/es/locale/lang/ar'
import 'element-plus/dist/index.css'
import axios from 'axios';


import './assets/main.css'

/* import the fontawesome core */
import { library } from '@fortawesome/fontawesome-svg-core'

/* import font awesome icon component */
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome'

/* import specific icons */
import { faUserSecret } from '@fortawesome/free-solid-svg-icons'

/* add icons to the library */
library.add(faUserSecret)

const app = createApp(App)

app.use(createPinia())
app.config.globalProperties.$http = axios;
app.use(router)
app.use(ElementPlus,{locale: Ar})
app.component('font-awesome-icon', FontAwesomeIcon)

app.mount('#app')
