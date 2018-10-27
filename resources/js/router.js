import Vue from 'vue'
import VueRouter from 'vue-router'
import Start from './components/start.vue'
import End from './components/end.vue'

Vue.use(VueRouter)

const routes = [
  { name: 'home', path: '/', component: Start },
  { name: 'start', path: '/start', component: Start },
  { name: 'end', path: '/end', component: End },
]

const router = new VueRouter({
  routes
})

export default router
