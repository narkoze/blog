import Vue from 'vue'
import VueRouter from 'vue-router'
import routes from './routes'
import VueI18n from 'vue-i18n'
import lang from './vue-i18n-locales.generated'
import Blog from './components/blog.vue'
import axios from 'axios'

Vue.use(VueI18n)
const i18n = new VueI18n({
  locale: document.querySelector('html').getAttribute('lang'),
  messages: lang
})

axios.defaults.baseURL = `/${i18n.locale}/api`
axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest'
axios.defaults.headers.common['X-CSRF-TOKEN'] = document.querySelector('meta[name="csrf-token"]').getAttribute('content')
axios.defaults.headers.common['Authorization'] = localStorage.getItem('access_token')

routes.forEach(route => {
  route.path = `/:locale/${route.path}`
})
routes.push({ path: '/', redirect: `/${i18n.locale}` })

Vue.use(VueRouter)
const router = new VueRouter({
  mode: 'history',
  routes
})

router.beforeEach((to, from, next) => {
  let lang = i18n.locale
  if (Object.keys(i18n.messages).includes(lang)) {
    i18n.locale = lang
  }
  next()
})

export default new Vue({
  el: '#blog',
  i18n,
  router,
  components: {
    Blog
  },
  data: () => ({
    auth: {
      token: null || axios.defaults.headers.common['Authorization'],
      user: null || JSON.parse(localStorage.getItem('user'))
    },
    showModalSignin: false,
    showModalSignup: false,
    showModalEmailresend: false,
    showModalPasswordresetemail: false
  })
})
