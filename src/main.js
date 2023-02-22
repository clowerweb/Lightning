import { createApp } from 'vue'
import { createPinia } from 'pinia'
import http from '@/plugins/http'

import App from '@/App.vue'
import router from '@/router'
import '@/assets/css/app.css'
import '@/assets/css/styles.css'

const app = createApp(App)

app.use(createPinia())
app.use(router)
app.use(http)

app.mount('#app')
