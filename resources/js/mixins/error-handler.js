export default {
  data: () => ({
    errors: {},
    disabled: false
  }),
  methods: {
    handleError (error) {
      this.disabled = false

      switch (error.response.status) {
        case 401:
          this.errors = error.response.data

          this.$root.auth.token = this.$root.auth.user = null
          localStorage.removeItem('access_token')
          localStorage.removeItem('user')

          this.$root.showModalSignin = true
          break
        case 422:
          this.errors = error.response.data.errors
          break
        default:
          console.log('There was an error and yes - this is a notification')
      }
    }
  }
}
