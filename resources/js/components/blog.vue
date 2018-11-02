<template>
  <div>
    <navbar></navbar>

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
  import Navbar from './navbar.vue'
  import ModalEmailresend from './modals/modal-emailresend.vue'
  import ModalSignin from './modals/modal-signin.vue'
  import ModalPasswordresetemail from './modals/modal-passwordresetemail.vue'
  import ModalSignup from './modals/modal-signup.vue'

  export default {
    components: {
      Navbar,
      ModalEmailresend,
      ModalSignin,
      ModalPasswordresetemail,
      ModalSignup
    }
  }
</script>
