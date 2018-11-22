<template>
  <div>
    <notifications></notifications>
    <navbar></navbar>

    <section
      v-if="!$route.meta.requiresAdmin && !$route.meta.requiresAuth"
      class="hero is-medium"
    >
      <div class="hero-body">
        <div class="container has-text-centered">
          <h1 class="title is-size-2 has-text-white-ter">
            {{ $t('title') }}
          </h1>
          <h2 class="subtitle has-text-grey-lighter">
            {{ $t('subtitle') }}
          </h2>
        </div>
      </div>
    </section>

    <router-view></router-view>

    <modal-emailresend
      v-if="$root.showModalEmailresend"
      @close="$root.showModalEmailresend = false"
    >
    </modal-emailresend>

    <modal-signin
      v-if="$root.showModalSignin"
      @close="$root.showModalSignin = false"
    >
    </modal-signin>

    <modal-passwordresetemail
      v-if="$root.showModalPasswordresetemail"
      @close="$root.showModalPasswordresetemail = false"
    >
    </modal-passwordresetemail>

    <modal-signup
      v-if="$root.showModalSignup"
      @close="$root.showModalSignup = false"
    >
    </modal-signup>
  </div>
</template>

<script>
  import Notifications from './notifications.vue'
  import Navbar from './navbar.vue'
  import ModalEmailresend from './modals/modal-emailresend.vue'
  import ModalSignin from './modals/modal-signin.vue'
  import ModalPasswordresetemail from './modals/modal-passwordresetemail.vue'
  import ModalSignup from './modals/modal-signup.vue'

  export default {
    components: {
      Notifications,
      Navbar,
      ModalEmailresend,
      ModalSignin,
      ModalPasswordresetemail,
      ModalSignup
    }
  }
</script>

<i18n>
  {
    "en": {
      "title": "BLOG",
      "subtitle": "Simply created with Laravel, Vue and Bulma"
    },
    "lv": {
      "title": "EMUĀRS",
      "subtitle": "Vienkārši uztaisīts ar Laravel, Vue un Bulma"
    }
  }
</i18n>
