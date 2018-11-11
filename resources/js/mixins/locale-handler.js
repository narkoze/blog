import axios from 'axios'

export default {
  methods: {
    handleLocale (locale) {
      this.$i18n.locale = this.$root.$i18n.locale = locale

      this.$router.replace({
        params: {
          ...this.$route.params,
          locale: locale
        }
      })

      axios.defaults.baseURL = `/${locale}/api`
    }
  }
}
