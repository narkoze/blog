import Vue from 'vue'
import router from './router'
import Blog from './components/blog.vue'

window.Vue = Vue

const blog = new window.Vue({
  el: '#blog',
  router,
  components: {
    Blog
  }
})

export default blog
