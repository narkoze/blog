<script>
  export default {
    props: [
      'template'
    ],
    methods: {
      to () {
        this.$emit('to')
      }
    },
    render: function render (h) {
      return h({ template: `<div>${this.template}</div>` })
    }
  }
</script>
