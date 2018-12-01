<script>
  export default {
    props: [
      'template'
    ],
    methods: {
      previewImages (i) {
        this.$emit('previewImages', i)
      }
    },
    render: function render (h) {
      return h({
        comments: true,
        template: `<div>${this.template}</div>`
      })
    }
  }
</script>
