import Vue from 'vue'
import VueRouter from 'vue-router'
import routes from './routes'
import Blog from './components/blog.vue'

window.Vue = Vue

Vue.use(VueRouter)
const router = new VueRouter({
  routes
})

const blog = new window.Vue({
  el: '#blog',
  router,
  components: {
    Blog
  }
})

export default blog
