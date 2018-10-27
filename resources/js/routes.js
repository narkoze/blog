import Start from './components/start.vue'
import End from './components/end.vue'

const routes = [
  { name: 'home', path: '/', component: Start },
  { name: 'start', path: '/start', component: Start },
  { name: 'end', path: '/end', component: End },
]

export default routes
