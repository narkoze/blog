import Vue from 'vue'
import VueRouter from 'vue-router'
import routes from './routes'
import VueI18n from 'vue-i18n'
import lang from './vue-i18n-locales.generated'
import Blog from './components/blog.vue'

Vue.use(VueI18n)
const i18n = new VueI18n({
  locale: window.Laravel.locale,
  messages: lang
})

routes.forEach(route => {
  route.path = `/:lang/${route.path}`
})
routes.push({ path: '/', redirect: `/${i18n.locale}` })

Vue.use(VueRouter)
const router = new VueRouter({
  routes
})

router.beforeEach((to, from, next) => {
  let lang = to.params.lang || i18n.locale
  if (Object.keys(i18n.messages).includes(lang)) {
    i18n.locale = lang
  }
  next()
})

const blog = new Vue({
  i18n,
  router,
  components: {
    Blog
  }
}).$mount('#blog')

export default blog
