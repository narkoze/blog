<template>
  <transition-group
    class="notifications"
    name="notification"
    tag="div"
    appear
  >
    <div
      @click="destroy(i)"
      v-for="(notification, i) in notifications"
      :key="notification.id"
      :class="['notification', {
        'is-primary': notification.status === 'success',
        'is-danger': notification.status === 'error'
      }]"
    >
      <button
        @click="destroy(i)"
        class="delete"
      >
      </button>

      {{ notification.message }}
    </div>
  </transition-group>
</template>

<script>
  export default {
    data: () => ({
      timeout: null
    }),
    computed: {
      notifications () {
        if (this.$root.notifications.length) {
          this.stopDestroy()
          this.timeout = setInterval(this.destroySuccess, 7000)
        }

        return this.$root.notifications
      }
    },
    methods: {
      destroySuccess () {
        let i = this.$root.notifications.findIndex(notification => notification.status === 'success')
        if (i !== -1) {
          this.destroy(i)
        } else {
          this.stopDestroy()
        }
      },
      stopDestroy () {
        clearInterval(this.timeout)
      },
      destroy (i) {
        this.$root.notifications.splice(i, 1)
      }
    },
    beforeDestroy () {
      this.stopDestroy()
    }
  }
</script>

<style lang="scss">
  .notification-enter-active, .notification-leave-active {
    transition: all .3s;
  }
  .notification-enter, .notification-leave-to {
    opacity: 0;
    height: 0;
    line-height: 0px;
    button {
      display: none;
    }
  }
</style>
