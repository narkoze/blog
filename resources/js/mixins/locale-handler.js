import axios from 'axios'

export default {
  methods: {
    handleLocale (locale) {
      this.$i18n.locale = this.$root.$i18n.locale = locale

      this.$router.replace({
        params: {
          locale: locale
        }
      })

      axios.defaults.baseURL = `/${locale}/api`

      this.$events.$emit('locale-changed')
    }
  }
}
